<?php

namespace EMM\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
namespace EMM\UserBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use EMM\UserBundle\Entity\User;
use EMM\UserBundle\Form\ArchivoType;
use EMM\USerBundle\Models\Document;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EMM\UserBundle\Entity\Archivo;

class ArchivoController extends Controller
{
    public function indexAction()
    {
        return $this->render('EMMUserBundle:Default:index.html.twig');
    }
    
     public function addAction()
    {
         $archivo=new Archivo();
         $form=$this->createCreateForm($archivo);
          
 
        return $this->render('EMMUserBundle:Archivo:add.html.twig',array('form'=>$form->createView()) );
    }
    
    private function createCreateForm(Archivo $entity){
        
        $form=$this->createForm(new ArchivoType(),$entity,array( 'action'=>$this->generateUrl('emm_archivo_create'),'method'=>'POST' ));
        
        return $form;
    }
}
