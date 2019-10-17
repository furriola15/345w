<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tipoorg
 *
 * @ORM\Table(name="tipoorg")
 * @ORM\Entity
 */
class Tipoorg
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
     * @ORM\Column(name="code", type="string", length=255, nullable = true)
     */
    private $code;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nombre_en", type="string", length=255)
     */
    private $nombre_en;
    
    /**
     * @var string
     *
     * @ORM\Column(name="code_en", type="string", length=255, nullable = true)
     */
    private $code_en;
       


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
     * @return Tipoorg
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
     * Set code
     *
     * @param string $code
     *
     * @return Tipoorg
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
     * Set nombreEn
     *
     * @param string $nombreEn
     *
     * @return Tipoorg
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
     * Set codeEn
     *
     * @param string $codeEn
     *
     * @return Tipoorg
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
}
