<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sector
 *
 * @ORM\Table(name="subsector")
 * @ORM\Entity
 */
class Subsector
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sector")
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     */
    private $sector_id;
    



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
     * @return Subsector
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
     * @return Subsector
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
     * Set sectorId
     *
     * @param \AppBundle\Entity\Sector $sectorId
     *
     * @return Subsector
     */
    public function setSectorId(\AppBundle\Entity\Sector $sectorId = null)
    {
        $this->sector_id = $sectorId;

        return $this;
    }

    /**
     * Get sectorId
     *
     * @return \AppBundle\Entity\Sector
     */
    public function getSectorId()
    {
        return $this->sector_id;
    }
}
