<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity
 */
class Contact
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;
    
    /**
     * @var string
     *
     * @ORM\Column(name="mail2", type="string", length=255, nullable = true)
     */
    private $mail2;
    
    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string", length=255, nullable = true)
     */
    private $detail;
    
    /**
     * @var string
     *
     * @ORM\Column(name="baja", type="integer",  nullable = true)
     */
    private $baja;

    /**
     * @var string
     *
     * @ORM\Column(name="activo", type="integer",  nullable = true)
     */
    private $activo;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="clead", type="integer",  nullable = true)
     */
    private $clead;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=255, nullable = true)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable = true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=255, nullable = true )
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="skype", type="string", length=255, nullable = true )
     */
    private $skype;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pais")
     * @ORM\JoinColumn(name="pais_id", referencedColumnName="id")
     */
    private $pais_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organizacion")
     * @ORM\JoinColumn(name="org_id", referencedColumnName="id", nullable = true)
     */
    private $org_id;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="usuario_alta", referencedColumnName="id")
     */
    private $usuario_alta;

    /**
     * @var datetime $fecha_alta
     *
     * @ORM\Column(name="fecha_alta", type="datetime", nullable = true)
     * 
     */
    private $fecha_alta;

   /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="usuario_modifica", referencedColumnName="id")
     */
    private $usuario_modifica;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sector")
     * @ORM\JoinColumn(name="clead_id", referencedColumnName="id")
     */
    private $clead_id;

    /**
     * @var datetime $fecha_modifica
     *
     * @ORM\Column(name="fecha_modifica", type="datetime", nullable = true)
     */
    private $fecha_modifica;
    
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Cgroup", inversedBy="contacts")
     * @ORM\JoinTable(name="contact_group")
     *
     */
    private $grupos;
    
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Sector", inversedBy="contacts")
     * @ORM\JoinTable(name="sectore_contact")
     *
     */
    private $sectores;

    public function __construct()
    {
        $this->grupos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sectores = new \Doctrine\Common\Collections\ArrayCollection();
    }
    



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
     * Set name
     *
     * @param string $name
     *
     * @return Contact
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Contact
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set mail2
     *
     * @param string $mail2
     *
     * @return Contact
     */
    public function setMail2($mail2)
    {
        $this->mail2 = $mail2;

        return $this;
    }

    /**
     * Get mail2
     *
     * @return string
     */
    public function getMail2()
    {
        return $this->mail2;
    }

    /**
     * Set detail
     *
     * @param string $detail
     *
     * @return Contact
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set baja
     *
     * @param integer $baja
     *
     * @return Contact
     */
    public function setBaja($baja)
    {
        $this->baja = $baja;

        return $this;
    }

    /**
     * Get baja
     *
     * @return integer
     */
    public function getBaja()
    {
        return $this->baja;
    }

    /**
     * Set clead
     *
     * @param integer $clead
     *
     * @return Contact
     */
    public function setClead($clead)
    {
        $this->clead = $clead;

        return $this;
    }

    /**
     * Get clead
     *
     * @return integer
     */
    public function getClead()
    {
        return $this->clead;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Contact
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
     * Set email
     *
     * @param string $email
     *
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     *
     * @return Contact
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Contact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Contact
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set skype
     *
     * @param string $skype
     *
     * @return Contact
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * Get skype
     *
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     *
     * @return Contact
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fecha_alta = $fechaAlta;

        return $this;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime
     */
    public function getFechaAlta()
    {
        return $this->fecha_alta;
    }

    /**
     * Set fechaModifica
     *
     * @param \DateTime $fechaModifica
     *
     * @return Contact
     */
    public function setFechaModifica($fechaModifica)
    {
        $this->fecha_modifica = $fechaModifica;

        return $this;
    }

    /**
     * Get fechaModifica
     *
     * @return \DateTime
     */
    public function getFechaModifica()
    {
        return $this->fecha_modifica;
    }

    /**
     * Set paisId
     *
     * @param \AppBundle\Entity\Pais $paisId
     *
     * @return Contact
     */
    public function setPaisId(\AppBundle\Entity\Pais $paisId = null)
    {
        $this->pais_id = $paisId;

        return $this;
    }

    /**
     * Get paisId
     *
     * @return \AppBundle\Entity\Pais
     */
    public function getPaisId()
    {
        return $this->pais_id;
    }

    /**
     * Set orgId
     *
     * @param \AppBundle\Entity\Organizacion $orgId
     *
     * @return Contact
     */
    public function setOrgId(\AppBundle\Entity\Organizacion $orgId = null)
    {
        $this->org_id = $orgId;

        return $this;
    }

    /**
     * Get orgId
     *
     * @return \AppBundle\Entity\Organizacion
     */
    public function getOrgId()
    {
        return $this->org_id;
    }

    

    /**
     * Set usuarioAlta
     *
     * @param \AppBundle\Entity\User $usuarioAlta
     *
     * @return Contact
     */
    public function setUsuarioAlta(\AppBundle\Entity\User $usuarioAlta = null)
    {
        $this->usuario_alta = $usuarioAlta;

        return $this;
    }

    /**
     * Get usuarioAlta
     *
     * @return \AppBundle\Entity\User
     */
    public function getUsuarioAlta()
    {
        return $this->usuario_alta;
    }

    /**
     * Set usuarioModifica
     *
     * @param \AppBundle\Entity\User $usuarioModifica
     *
     * @return Contact
     */
    public function setUsuarioModifica(\AppBundle\Entity\User $usuarioModifica = null)
    {
        $this->usuario_modifica = $usuarioModifica;

        return $this;
    }

    /**
     * Get usuarioModifica
     *
     * @return \AppBundle\Entity\User
     */
    public function getUsuarioModifica()
    {
        return $this->usuario_modifica;
    }

    /**
     * Set cleadId
     *
     * @param \AppBundle\Entity\Sector $cleadId
     *
     * @return Contact
     */
    public function setCleadId(\AppBundle\Entity\Sector $cleadId = null)
    {
        $this->clead_id = $cleadId;

        return $this;
    }

    /**
     * Get cleadId
     *
     * @return \AppBundle\Entity\Sector
     */
    public function getCleadId()
    {
        return $this->clead_id;
    }

    /**
     * Add grupo
     *
     * @param \AppBundle\Entity\Cgroup $grupo
     *
     * @return Contact
     */
    public function addGrupo(\AppBundle\Entity\Cgroup $grupo)
    {
        $this->grupos[] = $grupo;

        return $this;
    }

    /**
     * Remove grupo
     *
     * @param \AppBundle\Entity\Cgroup $grupo
     */
    public function removeGrupo(\AppBundle\Entity\Cgroup $grupo)
    {
        $this->grupos->removeElement($grupo);
    }

    /**
     * Get grupos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGrupos()
    {
        return $this->grupos;
    }

    /**
     * Add sectore
     *
     * @param \AppBundle\Entity\Sector $sectore
     *
     * @return Contact
     */
    public function addSectore(\AppBundle\Entity\Sector $sectore)
    {
        $this->sectores[] = $sectore;

        return $this;
    }

    /**
     * Remove sectore
     *
     * @param \AppBundle\Entity\Sector $sectore
     */
    public function removeSectore(\AppBundle\Entity\Sector $sectore)
    {
        $this->sectores->removeElement($sectore);
    }

    /**
     * Get sectores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSectores()
    {
        return $this->sectores;
    }
}
