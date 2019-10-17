<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ocha\ParametroBundle\Entity\Admin3
 *
 * @ORM\Table(name="admin3")
 * @ORM\Entity
 */
class Admin3
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Distrito")
     * @ORM\JoinColumn(name="distrito_id", referencedColumnName="id")
     */
    private $distrito_id;
    
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
     * @var string $pcode
     *
     * @ORM\Column(name="pcode", type="string", length=255, nullable = true)
     */
    private $pcode;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="usuario_alta", referencedColumnName="id", nullable = true)
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
     * @ORM\JoinColumn(name="usuario_modifica", referencedColumnName="id", nullable = true)
     */
    private $usuario_modifica;

    /**
     * @var datetime $fecha_modifica
     *
     * @ORM\Column(name="fecha_modifica", type="datetime", nullable = true, nullable = true)
     */
    private $fecha_modifica;
    
    /**
     * @var integer $num
     *
     * @ORM\Column(name="old_id", type="bigint", nullable = true, nullable = true)
     */
    private $num;

    /**
     * @var string $iso2
     *
     * @ORM\Column(name="iso2", type="string", length=255, nullable = true)
     */
    private $iso2;
    


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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Admin3
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
     * @return Admin3
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
     * Set pcode
     *
     * @param string $pcode
     *
     * @return Admin3
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
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     *
     * @return Admin3
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
     * @return Admin3
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
     * Set num
     *
     * @param integer $num
     *
     * @return Admin3
     */
    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    /**
     * Get num
     *
     * @return integer
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set iso2
     *
     * @param string $iso2
     *
     * @return Admin3
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
     * Set distritoId
     *
     * @param \AppBundle\Entity\Distrito $distritoId
     *
     * @return Admin3
     */
    public function setDistritoId(\AppBundle\Entity\Distrito $distritoId = null)
    {
        $this->distrito_id = $distritoId;

        return $this;
    }

    /**
     * Get distritoId
     *
     * @return \AppBundle\Entity\Distrito
     */
    public function getDistritoId()
    {
        return $this->distrito_id;
    }

    /**
     * Set usuarioAlta
     *
     * @param \AppBundle\Entity\User $usuarioAlta
     *
     * @return Admin3
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
     * @return Admin3
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
}
