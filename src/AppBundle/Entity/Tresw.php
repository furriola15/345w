<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tresw
 *
 * @ORM\Table(name="tresw")
 * @ORM\Entity
 */
class Tresw
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable = true)
     */
    private $project_id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="integer", length=255, nullable = true)
     */
    private $tipo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="objetivo", type="integer", nullable = true)
     */
    private $objetivo;

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad", type="integer", nullable = true)
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle", type="text", nullable = true)
     */
    private $detalle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable = true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="contacto", type="text", nullable = true)
     */
    private $contacto;

    /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", nullable = true)
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="afectados", type="integer", nullable = true)
     */
    private $afectados;
    
    /**
     * @var string
     *
     * @ORM\Column(name="atendidas", type="integer", nullable = true)
     */
    private $atendidas;

    /**
     * @var string
     *
     * @ORM\Column(name="infancy", type="integer", nullable = true)
     */
    private $infancy;

    /**
     * @var string
     *
     * @ORM\Column(name="childhood", type="integer", nullable = true)
     */
    private $childhood;

    /**
     * @var string
     *
     * @ORM\Column(name="adolescencem", type="integer", nullable = true)
     */
    private $adolescencem;

    /**
     * @var string
     *
     * @ORM\Column(name="adolescencef", type="integer", nullable = true)
     */
    private $adolescencef;

    /**
     * @var string
     *
     * @ORM\Column(name="pregnant", type="integer", nullable = true)
     */
    private $pregnant;

    /**
     * @var string
     *
     * @ORM\Column(name="olds", type="integer", nullable = true)
     */
    private $olds;

    /**
     * @var string
     *
     * @ORM\Column(name="male", type="integer", nullable = true)
     */
    private $male;

    /**
     * @var string
     *
     * @ORM\Column(name="female", type="integer", nullable = true)
     */
    private $female;

    /**
     * @var string
     *
     * @ORM\Column(name="boys", type="integer", nullable = true)
     */
    private $boys;

    /**
     * @var string
     *
     * @ORM\Column(name="girls", type="integer", nullable = true)
     */
    private $girls;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pais")
     * @ORM\JoinColumn(name="pais_id", referencedColumnName="id", nullable = true)
     */
    private $pais_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pais")
     * @ORM\JoinColumn(name="coverage_id", referencedColumnName="id", nullable = true)
     */
    private $coverage_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Toficina")
     * @ORM\JoinColumn(name="toficina_id", referencedColumnName="id", nullable = true)
     */
    private $toficina_id;
    
    
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Admin3")
     * @ORM\JoinColumn(name="admin3", referencedColumnName="id", nullable = true)
     */
    private $admin3;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organizacion")
     * @ORM\JoinColumn(name="org_id", referencedColumnName="id")
     */
    private $org_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organizacion")
     * @ORM\JoinColumn(name="financed", referencedColumnName="id", nullable = true)
     */
    private $financed;

    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sector")
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id", nullable = true)
     */
    private $sector_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Subsector")
     * @ORM\JoinColumn(name="subsector_id", referencedColumnName="id", nullable = true)
     */
    private $subsector_id;

    /**
     * @var datetime $dini
     * @ORM\Column(name="dini", type="datetime", nullable = true)
     */
    private $dini;

    /**
     * @var datetime $dend
     * @ORM\Column(name="dend", type="datetime", nullable = true)
     */
    private $dend;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Estado")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id", nullable = true)
     */
    private $estado_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="usuario_modifica", referencedColumnName="id", nullable = true)
     */
    private $usuario_modifica;

    /**
     * @var datetime $fecha_modifica
     *
     * @ORM\Column(name="fecha_modifica", type="datetime", nullable = true)
     */
    private $fecha_modifica;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Actividad")
     * @ORM\JoinColumn(name="actividad_id", referencedColumnName="id", nullable = true)
     */
    private $actividad_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Recurso")
     * @ORM\JoinColumn(name="recurso_id", referencedColumnName="id", nullable = true)
     */
    private $recurso_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Beneficiario")
     * @ORM\JoinColumn(name="beneficiario_id", referencedColumnName="id", nullable = true)
     */
    private $beneficiario_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Unidad")
     * @ORM\JoinColumn(name="unidad_id", referencedColumnName="id", nullable = true)
     */
    private $unidad_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tactividad")
     * @ORM\JoinColumn(name="tactividad", referencedColumnName="id", nullable = true)
     */
    private $tactividad;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tipo")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id", nullable = true)
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
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return Tresw
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set objetivo
     *
     * @param integer $objetivo
     *
     * @return Tresw
     */
    public function setObjetivo($objetivo)
    {
        $this->objetivo = $objetivo;

        return $this;
    }

    /**
     * Get objetivo
     *
     * @return integer
     */
    public function getObjetivo()
    {
        return $this->objetivo;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return Tresw
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return Tresw
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set lang
     *
     * @param string $lang
     *
     * @return Tresw
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set afectados
     *
     * @param integer $afectados
     *
     * @return Tresw
     */
    public function setAfectados($afectados)
    {
        $this->afectados = $afectados;

        return $this;
    }

    /**
     * Get afectados
     *
     * @return integer
     */
    public function getAfectados()
    {
        return $this->afectados;
    }

    /**
     * Set atendidas
     *
     * @param integer $atendidas
     *
     * @return Tresw
     */
    public function setAtendidas($atendidas)
    {
        $this->atendidas = $atendidas;

        return $this;
    }

    /**
     * Get atendidas
     *
     * @return integer
     */
    public function getAtendidas()
    {
        return $this->atendidas;
    }

    /**
     * Set infancy
     *
     * @param integer $infancy
     *
     * @return Tresw
     */
    public function setInfancy($infancy)
    {
        $this->infancy = $infancy;

        return $this;
    }

    /**
     * Get infancy
     *
     * @return integer
     */
    public function getInfancy()
    {
        return $this->infancy;
    }

    /**
     * Set childhood
     *
     * @param integer $childhood
     *
     * @return Tresw
     */
    public function setChildhood($childhood)
    {
        $this->childhood = $childhood;

        return $this;
    }

    /**
     * Get childhood
     *
     * @return integer
     */
    public function getChildhood()
    {
        return $this->childhood;
    }

    /**
     * Set adolescencem
     *
     * @param integer $adolescencem
     *
     * @return Tresw
     */
    public function setAdolescencem($adolescencem)
    {
        $this->adolescencem = $adolescencem;

        return $this;
    }

    /**
     * Get adolescencem
     *
     * @return integer
     */
    public function getAdolescencem()
    {
        return $this->adolescencem;
    }

    /**
     * Set adolescencef
     *
     * @param integer $adolescencef
     *
     * @return Tresw
     */
    public function setAdolescencef($adolescencef)
    {
        $this->adolescencef = $adolescencef;

        return $this;
    }

    /**
     * Get adolescencef
     *
     * @return integer
     */
    public function getAdolescencef()
    {
        return $this->adolescencef;
    }

    /**
     * Set pregnant
     *
     * @param integer $pregnant
     *
     * @return Tresw
     */
    public function setPregnant($pregnant)
    {
        $this->pregnant = $pregnant;

        return $this;
    }

    /**
     * Get pregnant
     *
     * @return integer
     */
    public function getPregnant()
    {
        return $this->pregnant;
    }

    /**
     * Set olds
     *
     * @param integer $olds
     *
     * @return Tresw
     */
    public function setOlds($olds)
    {
        $this->olds = $olds;

        return $this;
    }

    /**
     * Get olds
     *
     * @return integer
     */
    public function getOlds()
    {
        return $this->olds;
    }

    /**
     * Set dini
     *
     * @param \DateTime $dini
     *
     * @return Tresw
     */
    public function setDini($dini)
    {
        $this->dini = $dini;

        return $this;
    }

    /**
     * Get dini
     *
     * @return \DateTime
     */
    public function getDini()
    {
        return $this->dini;
    }

    /**
     * Set dend
     *
     * @param \DateTime $dend
     *
     * @return Tresw
     */
    public function setDend($dend)
    {
        $this->dend = $dend;

        return $this;
    }

    /**
     * Get dend
     *
     * @return \DateTime
     */
    public function getDend()
    {
        return $this->dend;
    }

    /**
     * Set fechaModifica
     *
     * @param \DateTime $fechaModifica
     *
     * @return Tresw
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
     * Set projectId
     *
     * @param \AppBundle\Entity\Project $projectId
     *
     * @return Tresw
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
     * @return Tresw
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
     * Set coverageId
     *
     * @param \AppBundle\Entity\Pais $coverageId
     *
     * @return Tresw
     */
    public function setCoverageId(\AppBundle\Entity\Pais $coverageId = null)
    {
        $this->coverage_id = $coverageId;

        return $this;
    }

    /**
     * Get coverageId
     *
     * @return \AppBundle\Entity\Pais
     */
    public function getCoverageId()
    {
        return $this->coverage_id;
    }

    /**
     * Set toficinaId
     *
     * @param \AppBundle\Entity\Toficina $toficinaId
     *
     * @return Tresw
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

    /**
     * Set departamento
     *
     * @param \AppBundle\Entity\Provincia $departamento
     *
     * @return Tresw
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
     * @return Tresw
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
     * Set orgId
     *
     * @param \AppBundle\Entity\Organizacion $orgId
     *
     * @return Tresw
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
     * Set financed
     *
     * @param \AppBundle\Entity\Organizacion $financed
     *
     * @return Tresw
     */
    public function setFinanced(\AppBundle\Entity\Organizacion $financed = null)
    {
        $this->financed = $financed;

        return $this;
    }

    /**
     * Get financed
     *
     * @return \AppBundle\Entity\Organizacion
     */
    public function getFinanced()
    {
        return $this->financed;
    }

    /**
     * Set sectorId
     *
     * @param \AppBundle\Entity\Sector $sectorId
     *
     * @return Tresw
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
     * Set subsectorId
     *
     * @param \AppBundle\Entity\Subsector $subsectorId
     *
     * @return Tresw
     */
    public function setSubsectorId(\AppBundle\Entity\Subsector $subsectorId = null)
    {
        $this->subsector_id = $subsectorId;

        return $this;
    }

    /**
     * Get subsectorId
     *
     * @return \AppBundle\Entity\Subsector
     */
    public function getSubsectorId()
    {
        return $this->subsector_id;
    }

    /**
     * Set estadoId
     *
     * @param \AppBundle\Entity\Estado $estadoId
     *
     * @return Tresw
     */
    public function setEstadoId(\AppBundle\Entity\Estado $estadoId = null)
    {
        $this->estado_id = $estadoId;

        return $this;
    }

    /**
     * Get estadoId
     *
     * @return \AppBundle\Entity\Estado
     */
    public function getEstadoId()
    {
        return $this->estado_id;
    }

    /**
     * Set usuarioModifica
     *
     * @param \AppBundle\Entity\User $usuarioModifica
     *
     * @return Tresw
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
     * Set actividadId
     *
     * @param \AppBundle\Entity\Actividad $actividadId
     *
     * @return Tresw
     */
    public function setActividadId(\AppBundle\Entity\Actividad $actividadId = null)
    {
        $this->actividad_id = $actividadId;

        return $this;
    }

    /**
     * Get actividadId
     *
     * @return \AppBundle\Entity\Actividad
     */
    public function getActividadId()
    {
        return $this->actividad_id;
    }

    /**
     * Set recursoId
     *
     * @param \AppBundle\Entity\Recurso $recursoId
     *
     * @return Tresw
     */
    public function setRecursoId(\AppBundle\Entity\Recurso $recursoId = null)
    {
        $this->recurso_id = $recursoId;

        return $this;
    }

    /**
     * Get recursoId
     *
     * @return \AppBundle\Entity\Recurso
     */
    public function getRecursoId()
    {
        return $this->recurso_id;
    }

    /**
     * Set beneficiarioId
     *
     * @param \AppBundle\Entity\Beneficiario $beneficiarioId
     *
     * @return Tresw
     */
    public function setBeneficiarioId(\AppBundle\Entity\Beneficiario $beneficiarioId = null)
    {
        $this->beneficiario_id = $beneficiarioId;

        return $this;
    }

    /**
     * Get beneficiarioId
     *
     * @return \AppBundle\Entity\Beneficiario
     */
    public function getBeneficiarioId()
    {
        return $this->beneficiario_id;
    }

    /**
     * Set unidadId
     *
     * @param \AppBundle\Entity\Unidad $unidadId
     *
     * @return Tresw
     */
    public function setUnidadId(\AppBundle\Entity\Unidad $unidadId = null)
    {
        $this->unidad_id = $unidadId;

        return $this;
    }

    /**
     * Get unidadId
     *
     * @return \AppBundle\Entity\Unidad
     */
    public function getUnidadId()
    {
        return $this->unidad_id;
    }

    /**
     * Set tactividad
     *
     * @param \AppBundle\Entity\Tactividad $tactividad
     *
     * @return Tresw
     */
    public function setTactividad(\AppBundle\Entity\Tactividad $tactividad = null)
    {
        $this->tactividad = $tactividad;

        return $this;
    }

    /**
     * Get tactividad
     *
     * @return \AppBundle\Entity\Tactividad
     */
    public function getTactividad()
    {
        return $this->tactividad;
    }

    /**
     * Set male
     *
     * @param integer $male
     *
     * @return Tresw
     */
    public function setMale($male)
    {
        $this->male = $male;

        return $this;
    }

    /**
     * Get male
     *
     * @return integer
     */
    public function getMale()
    {
        return $this->male;
    }

    /**
     * Set female
     *
     * @param integer $female
     *
     * @return Tresw
     */
    public function setFemale($female)
    {
        $this->female = $female;

        return $this;
    }

    /**
     * Get female
     *
     * @return integer
     */
    public function getFemale()
    {
        return $this->female;
    }

    /**
     * Set boys
     *
     * @param integer $boys
     *
     * @return Tresw
     */
    public function setBoys($boys)
    {
        $this->boys = $boys;

        return $this;
    }

    /**
     * Get boys
     *
     * @return integer
     */
    public function getBoys()
    {
        return $this->boys;
    }

    /**
     * Set girls
     *
     * @param integer $girls
     *
     * @return Tresw
     */
    public function setGirls($girls)
    {
        $this->girls = $girls;

        return $this;
    }

    /**
     * Get girls
     *
     * @return integer
     */
    public function getGirls()
    {
        return $this->girls;
    }

    /**
     * Set tipoId
     *
     * @param \AppBundle\Entity\Tipo $tipoId
     *
     * @return Tresw
     */
    public function setTipoId(\AppBundle\Entity\Tipo $tipoId = null)
    {
        $this->tipo_id = $tipoId;

        return $this;
    }

    /**
     * Get tipoId
     *
     * @return \AppBundle\Entity\Tipo
     */
    public function getTipoId()
    {
        return $this->tipo_id;
    }

    /**
     * Set admin3
     *
     * @param \AppBundle\Entity\Admin3 $admin3
     *
     * @return Tresw
     */
    public function setAdmin3(\AppBundle\Entity\Admin3 $admin3 = null)
    {
        $this->admin3 = $admin3;

        return $this;
    }

    /**
     * Get admin3
     *
     * @return \AppBundle\Entity\Admin3
     */
    public function getAdmin3()
    {
        return $this->admin3;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Tresw
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     *
     * @return Tresw
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return string
     */
    public function getContacto()
    {
        return $this->contacto;
    }
}
