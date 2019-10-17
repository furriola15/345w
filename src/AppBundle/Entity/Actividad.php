<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tresw
 *
 * @ORM\Table(name="actividad")
 * @ORM\Entity
 */
class Actividad
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
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tactividad")
     * @ORM\JoinColumn(name="tactividad_id", referencedColumnName="id")
     */
    private $tactividad_id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Subsector")
     * @ORM\JoinColumn(name="ssector", referencedColumnName="id")
     */
    private $ssector;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project_id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sector")
     * @ORM\JoinColumn(name="sector", referencedColumnName="id")
     */
    private $sector;

   



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
     * @return Actividad
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
     * @return Actividad
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
     * Set tactividadId
     *
     * @param \AppBundle\Entity\Tactividad $tactividadId
     *
     * @return Actividad
     */
    public function setTactividadId(\AppBundle\Entity\Tactividad $tactividadId = null)
    {
        $this->tactividad_id = $tactividadId;

        return $this;
    }

    /**
     * Get tactividadId
     *
     * @return \AppBundle\Entity\Tactividad
     */
    public function getTactividadId()
    {
        return $this->tactividad_id;
    }

    /**
     * Set ssector
     *
     * @param \AppBundle\Entity\Subsector $ssector
     *
     * @return Actividad
     */
    public function setSsector(\AppBundle\Entity\Subsector $ssector = null)
    {
        $this->ssector = $ssector;

        return $this;
    }

    /**
     * Get ssector
     *
     * @return \AppBundle\Entity\Subsector
     */
    public function getSsector()
    {
        return $this->ssector;
    }

    /**
     * Set sector
     *
     * @param \AppBundle\Entity\Sector $sector
     *
     * @return Actividad
     */
    public function setSector(\AppBundle\Entity\Sector $sector = null)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get sector
     *
     * @return \AppBundle\Entity\Sector
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set projectId
     *
     * @param \AppBundle\Entity\Project $projectId
     *
     * @return Actividad
     */
    public function setProjectId(\AppBundle\Entity\Project $projectId = null)
    {
        $this->project_id = $projectId;

        return $this;
    }

    /**
     * Get projectId
     *
     * @return \AppBundle\Entity\Project
     */
    public function getProjectId()
    {
        return $this->project_id;
    }
}
