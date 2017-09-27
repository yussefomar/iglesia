<?php
namespace EMM\UserBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use EMM\UserBundle\Entity\User;
use EMM\UserBundle\Form\UserType;
use EMM\USerBundle\Models\Document;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EMM\UserBundle\Entity\Archivo;
use EMM\UserBundle\Entity\Comentario;
use EMM\UserBundle\Form\ComentarioType;

class UserController extends Controller
{
    public function subirAction(Request $request){
        
        
        
          
        
        if ($request->getMethod() == 'POST') {
            $image = $request->files->get('archivito');
            $url =$request->request->get('url');
            $titulo =$request->request->get('titulo');  
             $descripcion =$request->request->get('descripcion'); 
            if (($image instanceof UploadedFile) && ($image->getError() == '0'))/* image es un instancia de uploadfile  y si no hay la crea   y el get error es igual a cero osea estatus recicibiod bien, pero todavia no se  guarda */ {
                if (($image->getSize() < 20000000000000)) {
                    $originalName = $image->getClientOriginalName(); /* retorna el nombre del archivo original */

                    $name_array = explode('.', $originalName); /* separa los elemento que tengan un punto */
                    $file_type = $name_array[sizeof($name_array) - 1]; /* obtiene la extension del largo menos uno, me duevle la extension ya que empieza de cero el arreglo y termina en sizeof menos uno */
                    $valid_filetypes = array('jpg', 'jpeg', 'mp3'); /* crea un arreglo con todas las extenciones que vas a aceptar, valida que estos suban esos tipos de archivos */
                    if (in_array(strtolower($file_type), $valid_filetypes))/* valida si tiene alguna de esas extensiones, in_array es para de php, sirve para buscarelementos denro de un arreglo, y strlower para pasar a mayuscula, filetyep para recorrelo , si retorna verdadero entonces cumple */ {
                        $document = new Document();
                        $document->setFile($image); /* recibe como parametro el arreglo y con es hace el upload delarchivo */
                        $document->setSubDirectory('archivos'); /* dentro dela caparpeta upload puedo llamar una carpeta con esenombre,podrias tener carpeta para los usuarios,arcvhiso/ y algo siqueires mas */
                        $document->processFile(); /**/
                        
                        $usuarios= $this->get('security.token_storage')->getToken()->getUser();
                        
                       
                        
                        $archivo = new Archivo();
                        $archivo->setArchivo($image->getBasename()); /*  getbasenameese metodo se almancena el nombre con que symfony lo guardo en la carpeta .tmp */
                        $archivo->setTitle($titulo);
                        
                         //relate this product to the category
                        
                        $archivo->setUser($usuarios);
                      
                        $archivo->setStatus(1);
                        $archivo->setDescription($descripcion);
                        $archivo->setUrl($url);
                        
                       
                        
                         
                       
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($usuarios);
                        $em->persist($archivo);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add(
                                'mensaje', 'el archivo se ha subido correctamente'
                        );
                        
                        
                         
                       return $this->redirectToRoute('emm_user_aboutas');
                      //return $this->render('EMMUserBundle:User:aboutAs.html.twig');
                        
                        
                        
                        
                         
                    } else {
                        $this->get('session')->getFlashBag()->add(
                                'mensaje', 'la extensión del archivo no es la correcta'
                        );
                        //redirecciono        
                        return $this->redirect($this->generateUrl('emm_user_subir'));
                    }
                }
            } else {
                $this->get('session')->getFlashBag()->add(
                        'mensaje', 'no entró o se produjo algún error inesoperado'
                );
                //redirecciono        
                return $this->redirect($this->generateUrl('emm_user_subir'));
                //die("no entró o se produjo algún error inesoperado");
            }
        }
         return $this->render('EMMUserBundle:User:subir.html.twig');
    }
    
    
    
    public function sermonesAction(){
         return $this->render('EMMUserBundle:User:home.html.twig');
    }
    
    public function homeAction(){
        return $this->render('EMMUserBundle:User:home.html.twig');
    }
    public function indexAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        // $users = $em->getRepository('EMMUserBundle:User')->findAll();
        
        /*
        
        $res = 'Lista de usuarios: <br />';
        
        foreach($users as $user)
        {
            $res .= 'Usuario: ' . $user->getUsername() . ' - Email: ' . $user->getEmail() . '<br />';
        }
        
        return new Response($res);
        */
        
        $dql = "SELECT u FROM EMMUserBundle:User u ORDER BY u.id DESC";
        $users = $em->createQuery($dql);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $users, $request->query->getInt('page', 1),
            10
        );
        
        $deleteFormAjax = $this->createCustomForm(':USER_ID', 'DELETE', 'emm_user_delete');
        
        return $this->render('EMMUserBundle:User:index.html.twig', array('pagination' => $pagination, 'delete_form_ajax' => $deleteFormAjax->createView()));
    }
    
    public function addAction()
    {
        $user = new User();
        $form = $this->createCreateForm($user);
        
        return $this->render('EMMUserBundle:User:add.html.twig', array('form' => $form->createView()));
    }
    
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
                'action' => $this->generateUrl('emm_user_create'),
                'method' => 'POST'
            ));
        
        return $form;
    }
    
    public function createAction(Request $request)
    {   
        $user = new User();
        $form = $this->createCreateForm($user);
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $password = $form->get('password')->getData();
            
            $passwordConstraint = new Assert\NotBlank();
            $errorList = $this->get('validator')->validate($password, $passwordConstraint);
            
            if(count($errorList) == 0)
            {
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $password);
                
                $user->setPassword($encoded);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                
                $successMessage = $this->get('translator')->trans('The user has been created.');
                $this->addFlash('mensaje', $successMessage);
                
                return $this->redirectToRoute('emm_user_index');                
            }
            else
            {
                $errorMessage = new FormError($errorList[0]->getMessage());
                $form->get('password')->addError($errorMessage);
            }
        }
        
        return $this->render('EMMUserBundle:User:add.html.twig', array('form' => $form->createView()));
    }
    
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('EMMUserBundle:User')->find($id);
        
        if(!$user)
        {
            $messageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException($messageException);
        }
        
        $form = $this->createEditForm($user);
        
        return $this->render('EMMUserBundle:User:edit.html.twig', array('user' => $user, 'form' => $form->createView()));
        
    }
    
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array('action' => $this->generateUrl('emm_user_update', array('id' => $entity->getId())), 'method' => 'PUT'));
        
        return $form;
    }
    
    public function updateAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('EMMUserBundle:User')->find($id);
        if(!$user)
        {
            $messageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException($messageException);
        }
        
        $form = $this->createEditForm($user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $password = $form->get('password')->getData();
            if(!empty($password))
            {
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $password);
                $user->setPassword($encoded);
            }
            else
            {
                $recoverPass = $this->recoverPass($id);
                $user->setPassword($recoverPass[0]['password']);                
            }
            
            if($form->get('role')->getData() == 'ROLE_ADMIN')
            {
                $user->setIsActive(1);
            }
            $em->flush();
            
            $successMessage = $this->get('translator')->trans('The user has been modified.');
            $this->addFlash('mensaje', $successMessage);
            return $this->redirectToRoute('emm_user_edit', array('id' => $user->getId()));
        }
        return $this->render('EMMUserBundle:User:edit.html.twig', array('user' => $user, 'form' => $form->createView()));
    }
    
    private function recoverPass($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT u.password
            FROM EMMUserBundle:User u
            WHERE u.id = :id'    
        )->setParameter('id', $id);
        
        $currentPass = $query->getResult();
        
        return $currentPass;
    }
    
    public function viewAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('EMMUserBundle:User');
        
        $user = $repository->find($id);
        
        if(!$user)
        {
            $messageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException($messageException);
        }
        
        $deleteForm = $this->createCustomForm($user->getId(), 'DELETE', 'emm_user_delete');
        
        return $this->render('EMMUserBundle:User:view.html.twig', array('user' => $user, 'delete_form' => $deleteForm->createView()));
    }
    
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('EMMUserBundle:User')->find($id);
        
        if(!$user)
        {
            $messageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException($messageException);
        }
        
        $allUsers = $em->getRepository('EMMUserBundle:User')->findAll();
        $countUsers = count($allUsers);
        
        // $form = $this->createDeleteForm($user);
        $form = $this->createCustomForm($user->getId(), 'DELETE', 'emm_user_delete');
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            if($request->isXMLHttpRequest())
            {
                $res = $this->deleteUser($user->getRole(), $em, $user);
                
                return new Response(
                    json_encode(array('removed' => $res['removed'], 'message' => $res['message'], 'countUsers' => $countUsers)),
                    200,
                    array('Content-Type' => 'application/json')
                );
            }
            
            $res = $this->deleteUser($user->getRole(), $em, $user);
            $this->addFlash($res['alert'], $res['message']);
            return $this->redirectToRoute('emm_user_index');            
        }
    }
    
    private function deleteUser($role, $em, $user)
    {
        if($role == 'ROLE_USER')
        {
            $em->remove($user);
            $em->flush();
            
            $message = $this->get('translator')->trans('The user has been deleted.');
            $removed = 1;
            $alert = 'mensaje';
        }
        elseif($role == 'ROLE_ADMIN')
        {
            $message = $this->get('translator')->trans('The user could not be deleted.');
            $removed = 0;
            $alert = 'error';
        }
        
        return array('removed' => $removed, 'message' => $message, 'alert' => $alert);
    }
    
    private function createCustomForm($id, $method, $route)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod($method)
            ->getForm();
    }
    
    
    
    public function plantillasVentaAction(){
        
        $repositorio=$this->getDoctrine()->getRepository('EMMUserBundle:Archivo');
      $query=$repositorio->createQueryBuilder('a')->orderBy('a.createdAt','DESC')->setMaxResults( 8)->getQuery();
              
      $archivos=$query->getResult();
        
        return $this->render('EMMUserBundle:User:plantillasVenta.html.twig',array('archivos'=>$archivos));
    }
    
     public function aboutAsAction()
            
    {
      $repositorio=$this->getDoctrine()->getRepository('EMMUserBundle:Archivo');
      $query=$repositorio->createQueryBuilder('a')->orderBy('a.createdAt','DESC')->setMaxResults( 8)->getQuery();
              
      $archivos=$query->getResult();
        
        return $this->render('EMMUserBundle:User:aboutAs.html.twig',array('archivos'=>$archivos));
    }
    
      public function entradasAction($entradasurl,Request $request)
            
    {
          
      /* aca esta lo de mostrar la entrada buscada por la url*/  
 
     $repository = $this->getDoctrine()
    ->getRepository('EMMUserBundle:Archivo');
       
      
                 $unArchivo = $repository->findOneByUrl($entradasurl);

       if (!$unArchivo) {
        throw $this->createNotFoundException(
            'No product found for id '.$entradasurl
        );
    }
       

      /* aca esta el formulario comentario pasado al twig*/
    
    
    $comentario=new Comentario();
    $form=$this->createCreateFormComentario($comentario,$unArchivo);
    /*$form->handleRequest($request);
    /*
    if(/*$form->isSubmitted() &&$form->isValid())
    {
        $em=$this->getDoctrine->getManager();
        $em->persist($comentario);
        $em->flush();
         return $this->redirect($this->generateUrl($entradasurl,'emm_user_entradas' ));
         
     
    }
        */
        return $this->render('EMMUserBundle:User:entradas.html.twig',array( 'unArchivo'=>$unArchivo,'formcomentario' => $form->createView()));
          
    }
    
     private function createCreateFormComentario(Comentario $entity ,Archivo $unArchivo)
    {
        $form = $this->createForm(new ComentarioType(), $entity, array(
                'action' => $this->generateUrl('emm_user_comentar', array( 'entradasurl' =>  $unArchivo->getUrl() ) ),
                'method' => 'POST'
            ));
          return $form;
    }
    
    public function comentarAction($entradasurl,Request $request){
          $repository = $this->getDoctrine()
    ->getRepository('EMMUserBundle:Archivo');
       
      
                 $unEntrada = $repository->findOneByUrl($entradasurl);

       if (!$unEntrada) {
        throw $this->createNotFoundException(
            'No product found for id '.$entradasurl
        );
    }
        
        
        
         $comentario=new Comentario();
         $form=$this->createCreateFormComentario($comentario ,$unEntrada);
         $form->handleRequest($request);
          /* if ($request->isMethod('POST')) {
        $form->get('user')->submit(3);*/

      /* if( $form->isValid())
    {
        $em=$this->getDoctrine->getManager();
        $em->persist($comentario);
        $em->flush();
         return $this->redirect($this->generateUrl('emm_user_entradas' ));
         
     $this->get('session')->getFlashBag()->add(
                                'mensaje', 'el archivo se ha subido correctamente');
    /*}*/
    
    
   /* }*/
         
           
              
             
         
    
    if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
        
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            
            
            $file = $comentario->getHeadshot();
            
             
            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $comentario->setHeadshot($fileName);
            $comentario->setUser($usuario);
            $comentario->setEntradaid($unEntrada);
            
            // ... persist the $product variable or any other work
$em = $this->getDoctrine()->getManager();
            
           $em->persist($comentario);
           $em->persist($usuario);
           $em->persist($unEntrada);
           $em->flush();
            return $this->redirect($this->generateUrl('emm_user_entradas', array( 'entradasurl' =>  $unEntrada->getUrl() ) ));
         
        $this->get('session')->getFlashBag()->add(
                                'mensaje', 'el archivo se ha subido correctamente');
        }
    
        
        
       
    
    $this->get('session')->getFlashBag()->add(
                        'mensaje', 'no entró o se produjo algún error inesoperado');
     return $this->render('EMMUserBundle:User:entradas.html.twig',array( /*'unArchivo'=>$unArchivo,*/'formcomentario' => $form->createView()));
    }
   
}

