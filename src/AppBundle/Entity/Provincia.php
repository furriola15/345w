<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ocha\ParametroBundle\Entity\Provincia
 *
 * @ORM\Table(name="provincia")
 * @ORM\Entity
 */
class Provincia
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pais")
     * @ORM\JoinColumn(name="pais_id", referencedColumnName="id")
     */
    private $pais_id;

    /**
     * @var string $pcode
     *
     * @ORM\Column(name="pcode", type="string", length=255, nullable = true)
     */
    private $pcode;

    /**
     * @var string $iso2
     *
     * @ORM\Column(name="iso2", type="string", length=255, nullable = true)
     */
    private $iso2;
    
    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string $capital
     *
     * @ORM\Column(name="capital", type="string", length=255, nullable = true)
     */
    private $capital;

    /**
     * @var boolean $eliminado
     *
     * @ORM\Column(name="eliminado", type="boolean", nullable = true)
     */
    private $eliminado;

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
     * @var datetime $fecha_modifica
     *
     * @ORM\Column(name="fecha_modifica", type="datetime", nullable = true)
     */
    private $fecha_modifica;

   /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="usuario_baja", referencedColumnName="id")
     */
    private $usuario_baja;

    /**
     * @var datetime $fecha_baja
     *
     * @ORM\Column(name="fecha_baja", type="datetime", nullable = true)
     */
    private $fecha_baja;
    
    /**
     * @var bigint $old_id
     *
     * @ORM\Column(name="old_id", type="bigint", nullable = true)
     */
    private $old_id;
    


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
     * Set pcode
     *
     * @param string $pcode
     *
     * @return Provincia
     */
    public function setPcode($pcode)
    {
        $this->pcode = $pcode;

        return $this;
    }

    /**
     * Get pcode
     *
     * @return string
     */
    public function getPcode()
    {
        return $this->pcode;
    }

    /**
     * Set iso2
     *
     * @param string $iso2
     *
     * @return Provincia
     */
    public function setIso2($iso2)
    {
        $this->iso2 = $iso2;

        return $this;
    }

    /**
     * Get iso2
     *
     * @return string
     */
    public function getIso2()
    {
        return $this->iso2;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Provincia
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
     * Set capital
     *
     * @param string $capital
     *
     * @return Provincia
     */
    public function setCapital($capital)
    {
        $this->capital = $capital;

        return $this;
    }

    /**
     * Get capital
     *
     * @return string
     */
    public function getCapital()
    {
        return $this->capital;
    }

    /**
     * Set eliminado
     *
     * @param boolean $eliminado
     *
     * @return Provincia
     */
    public function setEliminado($eliminado)
    {
        $this->eliminado = $eliminado;

        return $this;
    }

    /**
     * Get eliminado
     *
     * @return boolean
     */
    public function getEliminado()
    {
        return $this->eliminado;
    }

    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     *
     * @return Provincia
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
     * @return Provincia
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
     * Set fechaBaja
     *
     * @param \DateTime $fechaBaja
     *
     * @return Provincia
     */
    public function setFechaBaja($fechaBaja)
    {
        $this->fecha_baja = $fechaBaja;

        return $this;
    }

    /**
     * Get fechaBaja
     *
     * @return \DateTime
     */
    public function getFechaBaja()
    {
        return $this->fecha_baja;
    }

    /**
     * Set oldId
     *
     * @param integer $oldId
     *
     * @return Provincia
     */
    public function setOldId($oldId)
    {
        $this->old_id = $oldId;

        return $this;
    }

    /**
     * Get oldId
     *
     * @return integer
     */
    public function getOldId()
    {
        return $this->old_id;
    }

    /**
     * Set paisId
     *
     * @param \AppBundle\Entity\Pais $paisId
     *
     * @return Provincia
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
     * Set usuarioAlta
     *
     * @param \AppBundle\Entity\User $usuarioAlta
     *
     * @return Provincia
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
     * @return Provincia
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
     * Set usuarioBaja
     *
     * @param \AppBundle\Entity\User $usuarioBaja
     *
     * @return Provincia
     */
    public function setUsuarioBaja(\AppBundle\Entity\User $usuarioBaja = null)
    {
        $this->usuario_baja = $usuarioBaja;

        return $this;
    }

    /**
     * Get usuarioBaja
     *
     * @return \AppBundle\Entity\User
     */
    public function getUsuarioBaja()
    {
        return $this->usuario_baja;
    }
}
