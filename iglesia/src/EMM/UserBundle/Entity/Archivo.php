<?php

namespace EMM\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Archivo
 *
 * @ORM\Table(name="archivo")
 * @ORM\Entity(repositoryClass="EMM\UserBundle\Repository\ArchivoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Archivo
{
    
     /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="archivos")
     * @ORM\JoinColumn(name="userid", referencedColumnName="id", onDelete="CASCADE")
     */ 
    
     protected $user;
    
      /**
     * @ORM\OneToMany(targetEntity="Comentario", mappedBy="entradaid")  
     */ 
    protected $comentarios;
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;
    

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="archivo", type="string", length=255)
     */
    private $archivo;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Archivo
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Archivo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Archivo
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    
     
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Archivo
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    
    
   
    
    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Archivo
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    
    
    public function setUpdatedAtValue()/* fecha la hora correspondiente ala que se va a tuilizar*/
    {
        $this->updatedAt =new \DateTime();/* esto se setea en prepersist y tambien adiciamente preudate en el cmomento que actualizemos o modifquemos uno de nuestros registrso*/

        return $this;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue( )/*va a sestiar nuestra fecha actual a la que se esta registrarse*/
    {
        $this->createdAt = new \DateTime();/*al momento qaue perssiste , setea por eso prepersisit*/

        return $this;
    }
    
    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set archivo
     *
     * @param string $archivo
     * @return Archivo
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;

        return $this;
    }

    /**
     * Get archivo
     *
     * @return string 
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set user
     *
     * @param \EMM\UserBundle\Entity\User $user
     * @return Archivo
     */
    public function setUser(\EMM\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \EMM\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Archivo
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comentarios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add comentarios
     *
     * @param \EMM\UserBundle\Entity\Comentario $comentarios
     * @return Archivo
     */
    public function addComentario(\EMM\UserBundle\Entity\Comentario $comentarios)
    {
        $this->comentarios[] = $comentarios;

        return $this;
    }

    /**
     * Remove comentarios
     *
     * @param \EMM\UserBundle\Entity\Comentario $comentarios
     */
    public function removeComentario(\EMM\UserBundle\Entity\Comentario $comentarios)
    {
        $this->comentarios->removeElement($comentarios);
    }

    /**
     * Get comentarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }
    
    public function __toString()
{
  return   $this->getArchivo();
}
}
