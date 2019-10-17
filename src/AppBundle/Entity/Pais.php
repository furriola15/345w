<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ocha\ParametroBundle\Entity\Pais
 *
 * @ORM\Table(name="pais")
 * @ORM\Entity
 */
class Pais
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre_en", type="string", length=255)
     */
    protected $nombre_en;
    
    /**
     * @var string $nombrees
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable = true)
     */
    protected $nombre;
    
    /**
     * @var string $acronimo
     *
     * @ORM\Column(name="acronimo", type="string", length=255)
     */
    protected $acronimo;
    
    /**
     * @var integer $statusepr
     *
     * @ORM\Column(name="statusepr", type="integer", options={"default" = 0})
     */
    protected $statusepr;
    
    /**
     * @var text $commentepr
     *
     * @ORM\Column(name="commentepr", type="text")
     */
    protected $commentepr;
    
    /**
     * @var string $latitud
     *
     * @ORM\Column(name="latitud", type="string", length=100, nullable = true)
     */
    protected $latitud;
    
    /**
     * @var string $longitud
     *
     * @ORM\Column(name="longitud", type="string", length=100, nullable = true)
     */
    protected $longitud;
    
    /**
     * @var string $codigo_tel
     *
     * @ORM\Column(name="codigo_tel", type="string", length=10, nullable = true)
     */
    protected $codigo_tel;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="usuario_alta", referencedColumnName="id")
     */
    protected $usuario_alta;

    /**
     * @var datetime $fecha_alta
     *
     * @ORM\Column(name="fecha_alta", type="datetime")
     * 
     */
    protected $fecha_alta;

   /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="usuario_modifica", referencedColumnName="id")
     */
    protected $usuario_modifica;

    /**
     * @var datetime $fecha_modifica
     *
     * @ORM\Column(name="fecha_modifica", type="datetime", nullable = true)
     */
    protected $fecha_modifica;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    protected $region_id;
        


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
     * Set nombreEn
     *
     * @param string $nombreEn
     *
     * @return Pais
     */
    public function setNombreEn($nombreEn)
    {
        $this->nombre_en = $nombreEn;

        return $this;
    }

    /**
     * Get nombreEn
     *
     * @return string
     */
    public function getNombreEn()
    {
        return $this->nombre_en;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Pais
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set acronimo
     *
     * @param string $acronimo
     *
     * @return Pais
     */
    public function setAcronimo($acronimo)
    {
        $this->acronimo = $acronimo;

        return $this;
    }

    /**
     * Get acronimo
     *
     * @return string
     */
    public function getAcronimo()
    {
        return $this->acronimo;
    }

    /**
     * Set statusepr
     *
     * @param integer $statusepr
     *
     * @return Pais
     */
    public function setStatusepr($statusepr)
    {
        $this->statusepr = $statusepr;

        return $this;
    }

    /**
     * Get statusepr
     *
     * @return integer
     */
    public function getStatusepr()
    {
        return $this->statusepr;
    }

    /**
     * Set commentepr
     *
     * @param string $commentepr
     *
     * @return Pais
     */
    public function setCommentepr($commentepr)
    {
        $this->commentepr = $commentepr;

        return $this;
    }

    /**
     * Get commentepr
     *
     * @return string
     */
    public function getCommentepr()
    {
        return $this->commentepr;
    }

    /**
     * Set latitud
     *
     * @param string $latitud
     *
     * @return Pais
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get latitud
     *
     * @return string
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud
     *
     * @param string $longitud
     *
     * @return Pais
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * Get longitud
     *
     * @return string
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set codigoTel
     *
     * @param string $codigoTel
     *
     * @return Pais
     */
    public function setCodigoTel($codigoTel)
    {
        $this->codigo_tel = $codigoTel;

        return $this;
    }

    /**
     * Get codigoTel
     *
     * @return string
     */
    public function getCodigoTel()
    {
        return $this->codigo_tel;
    }

    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     *
     * @return Pais
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
     * @return Pais
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
     * Set usuarioAlta
     *
     * @param \AppBundle\Entity\User $usuarioAlta
     *
     * @return Pais
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
     * @return Pais
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
     * Set regionId
     *
     * @param \AppBundle\Entity\Region $regionId
     *
     * @return Pais
     */
    public function setRegionId(\AppBundle\Entity\Region $regionId = null)
    {
        $this->region_id = $regionId;

        return $this;
    }

    /**
     * Get regionId
     *
     * @return \AppBundle\Entity\Region
     */
    public function getRegionId()
    {
        return $this->region_id;
    }
}
