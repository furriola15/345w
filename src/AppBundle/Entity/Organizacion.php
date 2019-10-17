<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Organizacion
 *
 * @ORM\Table(name="organizacion")
 * @ORM\Entity
 */
class Organizacion
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nombre_en", type="string", length=255)
     */
    private $nombre_en;
    
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=50)
     */
    private $code;
    
    /**
     * @var string
     *
     * @ORM\Column(name="code_en", type="string", length=50)
     */
    private $code_en;
    
    /**
     * @var string
     *
     * @ORM\Column(name="link", type="text", nullable = true)
     */
    private $link;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tipoorg")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     */
    private $tipo_id;


    

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
     * @return Organizacion
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
     * Set nombreEn
     *
     * @param string $nombreEn
     *
     * @return Organizacion
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
     * Set code
     *
     * @param string $code
     *
     * @return Organizacion
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set codeEn
     *
     * @param string $codeEn
     *
     * @return Organizacion
     */
    public function setCodeEn($codeEn)
    {
        $this->code_en = $codeEn;

        return $this;
    }

    /**
     * Get codeEn
     *
     * @return string
     */
    public function getCodeEn()
    {
        return $this->code_en;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Organizacion
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set tipoId
     *
     * @param \AppBundle\Entity\Tipoorg $tipoId
     *
     * @return Organizacion
     */
    public function setTipoId(\AppBundle\Entity\Tipoorg $tipoId = null)
    {
        $this->tipo_id = $tipoId;

        return $this;
    }

    /**
     * Get tipoId
     *
     * @return \AppBundle\Entity\Tipoorg
     */
    public function getTipoId()
    {
        return $this->tipo_id;
    }
}
