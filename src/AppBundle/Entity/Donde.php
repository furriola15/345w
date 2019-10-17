<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Where
 *
 * @ORM\Table(name="donde")
 * @ORM\Entity
 */
class Donde
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organizacion")
     * @ORM\JoinColumn(name="org_id", referencedColumnName="id")
     */
    private $org_id;
       
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable = true)
     */
    private $project_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pais")
     * @ORM\JoinColumn(name="pais_id", referencedColumnName="id", nullable = true)
     */
    private $pais_id;   
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Provincia")
     * @ORM\JoinColumn(name="departamento", referencedColumnName="id", nullable = true)
     */
    private $departamento;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Distrito")
     * @ORM\JoinColumn(name="municipio", referencedColumnName="id", nullable = true)
     */
    private $municipio;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sector")
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id", nullable = true)
     */
    private $sector_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Toficina")
     * @ORM\JoinColumn(name="toficina_id", referencedColumnName="id", nullable = true)
     */
    private $toficina_id;

   


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
     * Set orgId
     *
     * @param \AppBundle\Entity\Organizacion $orgId
     *
     * @return Donde
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
     * Set projectId
     *
     * @param \AppBundle\Entity\Project $projectId
     *
     * @return Donde
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

    /**
     * Set paisId
     *
     * @param \AppBundle\Entity\Pais $paisId
     *
     * @return Donde
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
     * Set departamento
     *
     * @param \AppBundle\Entity\Provincia $departamento
     *
     * @return Donde
     */
    public function setDepartamento(\AppBundle\Entity\Provincia $departamento = null)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento
     *
     * @return \AppBundle\Entity\Provincia
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Set municipio
     *
     * @param \AppBundle\Entity\Distrito $municipio
     *
     * @return Donde
     */
    public function setMunicipio(\AppBundle\Entity\Distrito $municipio = null)
    {
        $this->municipio = $municipio;

        return $this;
    }

    /**
     * Get municipio
     *
     * @return \AppBundle\Entity\Distrito
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * Set sectorId
     *
     * @param \AppBundle\Entity\Sector $sectorId
     *
     * @return Donde
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

    /**
     * Set toficinaId
     *
     * @param \AppBundle\Entity\Toficina $toficinaId
     *
     * @return Donde
     */
    public function setToficinaId(\AppBundle\Entity\Toficina $toficinaId = null)
    {
        $this->toficina_id = $toficinaId;

        return $this;
    }

    /**
     * Get toficinaId
     *
     * @return \AppBundle\Entity\Toficina
     */
    public function getToficinaId()
    {
        return $this->toficina_id;
    }
}
