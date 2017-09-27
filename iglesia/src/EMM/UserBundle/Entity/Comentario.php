<?php

namespace EMM\UserBundle\Entity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert; 

use Doctrine\ORM\Mapping as ORM;

/**
 * Comentario
 *
 * @ORM\Table(name="comentario")
 * @ORM\Entity(repositoryClass="EMM\UserBundle\Repository\ComentarioRepository")
 */
class Comentario
{
    
    /**
     * @ORM\ManyToOne(targetEntity="Archivo", inversedBy="comentarios")
     * @ORM\JoinColumn(name="entradaid", referencedColumnName="id", onDelete="CASCADE")
     */ 
    
     protected $entradaid;
    
     /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comentarios")
     * @ORM\JoinColumn(name="userid", referencedColumnName="id", onDelete="CASCADE")
     */ 
    
     protected $user ;
     
     
     /**
     *@ORM\Column(type="string")
     *  
     * @Assert\Image(
     *     minWidth = 100,
     *     maxWidth = 4000,
     *     minHeight = 100,
     *     maxHeight = 1000
     * )
     */ 
     
     
    protected $headshot;

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
     * @ORM\Column(name="titulo", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="string", length=255)
     */
    private $texto;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;


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
     * Set titulo
     *
     * @param string $titulo
     * @return Comentario
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set texto
     *
     * @param string $texto
     * @return Comentario
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string 
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Comentario
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
     * @return Comentario
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

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
     * Set entradaid
     *
     * @param \EMM\UserBundle\Entity\Archivo $entradaid
     * @return Comentario
     */
    public function setEntradaid(\EMM\UserBundle\Entity\Archivo $entradaid = null)
    {
        $this->entradaid = $entradaid;

        return $this;
    }

    /**
     * Get entradaid
     *
     * @return \EMM\UserBundle\Entity\Archivo 
     */
    public function getEntradaid()
    {
        return $this->entradaid;
    }

    /**
     * Set user
     *
     * @param \EMM\UserBundle\Entity\User $user
     * @return Comentario
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
    
    public function setHeadshot($foto)
    {
        $this->headshot = $foto;
    }

    public function getHeadshot()
    {
        return $this->headshot;
    }
    
 
}
