<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Utils\Slugger;
use AppBundle\Entity\Tresw;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \DateTime;
use Symfony\Component\Asset\PathPackage;

/**
 * Controller used to manage blog contents in the backend.
 *
 * Please note that the application backend is developed manually for learning
 * purposes. However, in your real Symfony application you should use any of the
 * existing bundles that let you generate ready-to-use backends without effort.
 *
 * See http://knpbundles.com/keyword/admin
 *
 * @Route("/admin/345w")
 * @Security("has_role('ROLE_ADMIN')")
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class WwwController extends Controller {

    /**
     *
     * @Route("/new-doc/{id}/{org}", name="admin_new_doc", defaults={"id":"0", "org":"0"})
     * @Method("GET")
     */
    public function indexAction($id, $org) {
        $em = $this->getDoctrine()->getManager();
        
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT P.id, P.nombre, P.emergency, P.detalle, P.campos, P.pbi, P.activities, P.paises, P.lang')
                ->from('AppBundle:Project', 'P')
                ->where("P.id = ".$id);
        $project = $qb->getQuery()->setMaxResults(1)->getSingleResult();

        $land =  ($project['lang'] == "en" ? '_en':'');

        $project['paises'] = json_decode($project['paises'], true);
        $project['campos'] = json_decode($project['campos'], true);
        $campos = array();
        $main_country = $project['paises'][0];
        $requires = array();
        foreach ($project['campos'] as $key => $value) {
                $campos[] = $value['field'];
                if($value['requiere'] == 'require'){
                        $requires[] = $value['field'];
                }
        }

        $cond_o = "1=1";
        if($org != 0){
                $cond_o = "O.id = ".$org;
        }
        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(O.id) AS total, O.id, O.nombre'.$land.' AS nombre, O.code'.$land.' AS code, T.id AS tipo')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.project_id","P")
                ->leftjoin("O.tipo_id","T")
                ->where("P.id = ".$id)
                //->andwhere($cond_o)
                ->groupBy("O.id")
                ->orderBy("O.code".$land,"ASC");
        $orgs = $qb->getQuery()->getResult();   

        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(O.id) AS total, T.id, T.nombre'.$land.' AS nombre')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.project_id","P")
                ->leftjoin("O.tipo_id","T")
                ->where("P.id = ".$id)
                //->andwhere($cond_o)
                ->groupBy("T.id")
                ->orderBy("T.nombre".$land,"DESC");
        $tipos = $qb->getQuery()->getResult();   

  
        $qb = $em->createQueryBuilder();
        $qb->select("DISTINCT DE.id, DE.nombre AS departamento")
                ->from('AppBundle:Provincia', 'DE')
                ->leftjoin("DE.pais_id","PA")
                ->where("PA.id = ".$main_country);
                $qb->orderBy("DE.nombre","ASC");   
        $departamentos = $qb->getQuery()->getResult();    

        return $this->render('admin/blog/new.html.twig', compact('org', 'tipos', 'orgs','project','campos','requires','departamentos'));
    }

    /**
     *
     * @Route("/pbi/{id}/{org}", name="admin_pbi", defaults={"id":"0", "org":"0"})
     * @Method("GET")
     */
    public function pbiAction($id, $org) {
        $em = $this->getDoctrine()->getManager();
        
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT P.id, P.nombre, P.emergency, P.detalle, P.campos, P.pbi, P.lang')
                ->from('AppBundle:Project', 'P')
                ->where("P.id = ".$id);
        $project = $qb->getQuery()->setMaxResults(1)->getSingleResult();

        $land =  ($project['lang'] == "en" ? '_en':'');


        return $this->render('admin/blog/pbi.html.twig', compact('project'));
    }

    /**
     *
     * @Route("/get-orgs-filters/{id}/{lang}", name="get_orgs_filters", defaults={"id":"0", "lang":"0"})
     * @Method("GET")
     */
    public function getOrgsFilterAction($id, $lang) {
        $em = $this->getDoctrine()->getManager();

        $land =  ($lang == "en" ? '_en':'');

       
        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(O.id) AS total, O.id, O.nombre'.$land.' AS nombre, O.code'.$land.' AS code, T.id AS tipo')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.project_id","P")
                ->leftjoin("O.tipo_id","T")
                ->where("P.id = ".$id)
                ->groupBy("O.id")
                ->orderBy("O.code".$land,"ASC");
        $orgs = $qb->getQuery()->getResult();   

        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(O.id) AS total, T.id, T.nombre'.$land.' AS nombre')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.project_id","P")
                ->leftjoin("O.tipo_id","T")
                ->where("P.id = ".$id)
                ->groupBy("T.id")
                ->orderBy("T.nombre".$land,"DESC");
        $tipos = $qb->getQuery()->getResult();   


        $html = $this->renderView('admin/blog/filters-orgs.html.twig', compact('orgs','tipos'));

        $response = new Response(json_encode(compact('html')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;


    }

    /**
     *
     * @Route("/listado/by-org/{project}/{lang}/{org}/{campos}", name="get_list_org", defaults={"project":0, "lang":"0", "org":"0", "campos":"0"})
     * @Method("GET")
     */
    public function OrgListAction($project, $lang, $org, $campos) {
        $em = $this->getDoctrine()->getManager();
        $location = 0;
        $clang = ($lang == "en" ? '_en':'');
        $campos = explode("|", $campos); 
           
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT O.id AS o_id, O.nombre'.$clang.' AS org, O.code'.$clang.' AS code, T.id AS tipo_id, T.nombre'.$clang.', COUNT(DISTINCT AC.id) AS t_acts, COUNT(DISTINCT DE.id) AS t_departaments,  COUNT(DISTINCT S.id) AS t_sectors,  COUNT(DISTINCT MU.id) AS t_municipios')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.sector_id","S")
                ->leftjoin("O.tipo_id","T")
                ->leftjoin("W.actividad_id","AC")
                ->leftjoin("W.municipio","MU")
                ->leftjoin("W.departamento","DE")
                ->leftjoin("W.pais_id","PA")
                ->where("P.id = ".$project)
                ->andwhere("O.id = ".$org);
        $record = $qb->getQuery()->setMaxResults(1)->getSingleResult();

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT P.id, P.paises')
                ->from('AppBundle:Project', 'P')
                ->where("P.id = ".$project);
        $projecta = $qb->getQuery()->setMaxResults(1)->getSingleResult();
        $projecta['paises'] = json_decode($projecta['paises'], true);
        $main_country = $projecta['paises'][0];      

        $sqldonde = "DISTINCT DE.id, DE.id AS dep_id, DE.nombre AS departamento";
        if(in_array("municipio",$campos)){
                $sqldonde = "DISTINCT MU.id, MU.nombre AS municipio, DE.id AS dep_id, DE.nombre AS departamento";
        } 
           
        $qb = $em->createQueryBuilder();
        $qb->select($sqldonde)
                ->from('AppBundle:Donde', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.departamento","DE");
                if(in_array("municipio",$campos)){$qb->leftjoin("W.municipio","MU");}
                $qb->leftjoin("W.pais_id","PA")
                ->where("P.id = ".$project)->andwhere("O.id = ".$org);
                if(in_array("municipio",$campos)){
                        $qb->orderBy("DE.nombre, MU.nombre","ASC");
                }else{
                        $qb->orderBy("DE.nombre","ASC");   
                }
        $adminuno = $qb->getQuery()->getResult();

        foreach ($adminuno as $key => $admin1) {
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT S.id')
                        ->from('AppBundle:Donde', 'W')
                        ->leftjoin("W.project_id","P")
                        ->leftjoin("W.sector_id","S")
                        ->leftjoin("W.org_id","O");
                        if(in_array("municipio",$campos)){$qb->leftjoin("W.municipio","MU");}
                        $qb->leftjoin("W.departamento","DE")
                        ->leftjoin("W.pais_id","PA")
                        ->where("P.id = ".$project)->andwhere("O.id = ".$org);
                        if(in_array("municipio",$campos)){ $qb->andwhere("MU.id = ".$admin1['id']); } else { $qb->andwhere("DE.id = ".$admin1['id']); }
                $sectoresrel_array = $qb->getQuery()->getResult();
                $sectoresrel = array();
                foreach ($sectoresrel_array as $a => $aitem) {
                        $sectoresrel[$a] = $aitem['id'];
                }
                $adminuno[$key]['sectores'] = $sectoresrel;

        }
        
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT S.id, S.nombre'.$clang.' AS nombre, S.acronimo')
                ->from('AppBundle:Sector', 'S')
                ->orderBy("S.nombre".$clang,"ASC");
        $sectores = $qb->getQuery()->getResult();

        $total = count($adminuno);

        $html = $this->renderView('admin/blog/listado_orgs.html.twig', compact('record','project','lang','org','locations','campos','adminuno','sectores'));

        $response = new Response(json_encode(compact('html','project','org', 'record','total')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     *
     * @Route("/actividades-by-org/{project}/{lang}/{org}/{campos}", name="get_acts_by_org", defaults={"project":0, "lang":"0", "org":"0", "campos":"0"})
     * @Method("GET")
     */
    public function actsByOrgAction($project, $lang, $org, $campos) {
        $em = $this->getDoctrine()->getManager();
        $location = 0;
        $clang = ($lang == "en" ? '_en':'');
        $campos = explode("|", $campos); 
        $cond_l = "";
        $sqlc_l = "";
        if(in_array("municipio",$campos)){
                $cond_l = "MU.nombre AS municipio = ".$location;
                $sqlc_l = "COUNT(DISTINCT MU.id) AS locations";
        }elseif(in_array("departamento",$campos)){
                $cond_l = "DE.id = ".$location;
                $sqlc_l = "COUNT(DISTINCT DE.id) AS locations";
        }else{
                $cond_l = "PA.id = ".$location;
                $sqlc_l = "COUNT(DISTINCT PA.id) AS locations";
        }
        
        $sqlwhen = "";
        if(in_array("dini",$campos)){
                $sqlwhen = ', W.dini, W.dend, E.nombre'.$clang.' AS estado';
        }
        $sqlrecurso = "";
        if(in_array("recurso_id",$campos)){
                $sqlrecurso = ', R.nombre'.$clang.' AS recurso, U.nombre'.$clang.' AS unidad, W.cantidad ';
        }
        $sqlwhom = "";
        if(in_array("atendidas",$campos)){
                $sqlwhom = $sqlwhom. ", W.atendidas ";
        }
        if(in_array("objetivo",$campos)){
                $sqlwhom = $sqlwhom. ", W.objetivo ";
        }
        if(in_array("afectados",$campos)){
                $sqlwhom = $sqlwhom. ", W.afectados ";
        }
        if(in_array("infancy",$campos)){
                $sqlwhom = $sqlwhom. ", W.infancy ";
        }
        if(in_array("childhood",$campos)){
                $sqlwhom = $sqlwhom. ", W.childhood ";
        }
        if(in_array("adolescencem",$campos)){
                $sqlwhom = $sqlwhom. ", W.adolescencem ";
        }
        if(in_array("adolescencef",$campos)){
                $sqlwhom = $sqlwhom. ", W.adolescencef ";
        }
        if(in_array("pregnant",$campos)){
                $sqlwhom = $sqlwhom. ", W.pregnant ";
        }
        if(in_array("olds",$campos)){
                $sqlwhom = $sqlwhom. ", W.olds ";
        }

        $sqlfinanced = "";
        if(in_array("financed",$campos)){
                $sqlfinanced = ", FI.code".$clang." AS financed";
        }

        $sqltipo = "";
        if(in_array("tipo_id",$campos)){
                $sqltipo = ", TI.nombre".$clang." AS tipo";
        }

        $sqlgender = "";
        if(in_array("gender",$campos)){
                $sqlgender = $sqlgender. ", W.male, W.female, W.boys, W.girls ";
        }
        
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT O.id AS o_id, O.nombre'.$clang.' AS org, O.code'.$clang.' AS code, T.id AS tipo_id, T.nombre'.$clang)
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.sector_id","S")
                ->leftjoin("O.tipo_id","T")
                ->leftjoin("W.municipio","MU")
                ->leftjoin("W.departamento","DE")
                ->leftjoin("W.pais_id","PA")
                ->where("P.id = ".$project)
                ->andwhere("O.id = ".$org);
        $record = $qb->getQuery()->setMaxResults(1)->getSingleResult();
       
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT W.id, O.code'.$clang.' AS org, T.nombre'.$clang.' AS t_org, SS.nombre'.$clang.' AS subsector, A.nombre AS actividad, S.nombre'.$clang.' AS sector, W.fecha_modifica,
        MU.nombre AS municipio, DE.nombre AS departamento, AD.nombre AS admin3, PA.nombre AS pais, B.nombre'.$clang.' AS beneficiario, TO.nombre'.$clang.' AS toficina, 
        CO.nombre AS coverage'.$sqlwhen.$sqlrecurso.$sqlwhom.$sqlfinanced.$sqltipo.$sqlgender)
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.actividad_id","A")
                ->leftjoin("W.sector_id","S")
                ->leftjoin("W.toficina_id","TO")
                ->leftjoin("W.subsector_id","SS")
                ->leftjoin("O.tipo_id","T")
                ->leftjoin("W.beneficiario_id","B");
                if(in_array("recurso_id",$campos)){
                        $qb->leftjoin("W.recurso_id","R")->leftjoin("W.unidad_id","U");
                }
                if(in_array("dini",$campos)){
                        $qb->leftjoin("W.estado_id","E");
                }
                if(in_array("financed",$campos)){
                        $qb->leftjoin("W.financed","FI");
                }
                if(in_array("tipo_id",$campos)){
                        $qb->leftjoin("W.tipo_id","TI");
                }
                $qb->leftjoin("W.coverage_id","CO")
                ->leftjoin("W.admin3","AD")
                ->leftjoin("W.municipio","MU")
                ->leftjoin("W.departamento","DE")
                ->leftjoin("W.pais_id","PA")
                ->where("P.id = ".$project)
                ->andwhere("O.id = ".$org)
                ->orderBy("W.id","DESC");
        $records = $qb->getQuery()->getResult();
        $total = count($records);

        $html = $this->renderView('admin/blog/acts_by_org.html.twig', compact('record','project','lang','org','locations','records','campos'));

        $response = new Response(json_encode(compact('html','project','org', 'record','total')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     *
     * @Route("/dashboard/{id}/{org}", name="tresw_dashboard", defaults={"id":"0", "org":"0" })
     * @Method("GET")
     */
    public function dashboardAction($id,$org) {
        $em = $this->getDoctrine()->getManager();
        $cond_o = "1=1";
        if($org != 0){
                $cond_o = "O.id = ".$org;
        }
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT P.id, P.nombre, P.emergency, P.detalle, P.campos, P.paises, P.lang')
                ->from('AppBundle:Project', 'P')
                ->where("P.id = ".$id);
        $project = $qb->getQuery()->setMaxResults(1)->getSingleResult();

        $land =  ($project['lang'] == "en" ? '_en':'');

        $project['paises'] = json_decode($project['paises'], true);
        $project['campos'] = json_decode($project['campos'], true);
        $main_country = 0;
        foreach ($project['paises'] as $key => $value) {
                $main_country = $value;
        }
        $campos = array();
        $requires = array();
        foreach ($project['campos'] as $key => $value) {
                $campos[] = $value['field'];
                if($value['requiere'] == 'require'){
                        $requires[] = $value['field'];
                }
        }

        $cond_l = "";
        $sqlc_l = "";
        if(in_array("municipio",$campos)){
                $sqlc_l = "COUNT(DISTINCT MU.id) AS locations";
        }elseif(in_array("departamento",$campos)){
                $sqlc_l = "COUNT(DISTINCT DE.id) AS locations";
        }else{
                $sqlc_l = "COUNT(DISTINCT PA.id) AS locations";
        }
        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(DISTINCT W.id) AS total, COUNT(DISTINCT S.id) AS sectors, SUM(W.afectados) AS afectados, SUM(W.objetivo) AS objetivo, SUM(W.atendidas) AS atendidas, COUNT(DISTINCT O.id) AS orgs, '.$sqlc_l)
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.sector_id","S")
                ->leftjoin("O.tipo_id","T")
                ->leftjoin("W.municipio","MU")
                ->leftjoin("W.departamento","DE")
                ->leftjoin("W.pais_id","PA")
                ->where("P.id = ".$id)->andwhere($cond_o);
        $record = $qb->getQuery()->setMaxResults(1)->getSingleResult();
        $municipios = array();
        if(in_array("municipio",$campos) && $main_country != 13){
                $qb = $em->createQueryBuilder();
                $qb->select('COUNT(DISTINCT O.id) AS total, MU.id, MU.nombre AS nombre, MU.pcode, DE.nombre AS departamento, COUNT(DISTINCT AC.id) AS acts')
                        ->from('AppBundle:Tresw', 'W')
                        ->leftjoin("W.project_id","P")
                        ->leftjoin("W.municipio","MU")
                        ->leftjoin("W.org_id","O")
                        ->leftjoin("W.departamento","DE")
                        ->leftjoin("W.actividad_id","AC")
                        ->where("P.id = ".$id)->andwhere($cond_o)->andwhere("MU.id <> ''")
                        ->groupBy("MU.nombre")->orderBy("DE.nombre, MU.nombre","ASC");
                $municipios = $qb->getQuery()->getResult();
                foreach ($municipios as $key => $item) {
                        $qb = $em->createQueryBuilder();
                        $qb->select('DISTINCT S.id, S.nombre'.$land.' AS nombre')
                                ->from('AppBundle:Tresw', 'W')
                                ->leftjoin("W.project_id","P")
                                ->leftjoin("W.org_id","O")
                                ->leftjoin("W.sector_id","S")
                                ->leftjoin("W.municipio","MU")
                                ->where("P.id = ".$id)->andwhere("MU.id = ".$item['id'])->andwhere($cond_o)
                                ->orderBy("S.nombre".$land,"ASC");
                        $municipios[$key]['sectores'] = $qb->getQuery()->getResult();
                }
        }

        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(DISTINCT O.id) AS total, DE.id, DE.nombre AS nombre, DE.pcode, COUNT(DISTINCT AC.id) AS acts')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.departamento","DE")
                ->leftjoin("W.actividad_id","AC")
                ->leftjoin("W.pais_id","PA")
                ->where("P.id = ".$id)->andwhere($cond_o)
                ->groupBy("DE.pcode")->orderBy("DE.nombre","ASC");;
        $departamentos = $qb->getQuery()->getResult();
        foreach ($departamentos as $key => $item) {
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT S.id, S.nombre'.$land.' AS nombre')
                        ->from('AppBundle:Tresw', 'W')
                        ->leftjoin("W.project_id","P")
                        ->leftjoin("W.org_id","O")
                        ->leftjoin("W.sector_id","S")
                        ->leftjoin("W.departamento","DE")
                        ->where("P.id = ".$id)->andwhere("DE.id = ".$item['id'])->andwhere($cond_o)
                        ->orderBy("S.nombre".$land,"ASC");
                $departamentos[$key]['sectores'] = $qb->getQuery()->getResult();
        }

        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(DISTINCT O.id) AS total, DE.id, DE.nombre AS nombre')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.departamento","DE")
                ->where("P.id = ".$id)->andwhere($cond_o)
                ->groupBy("DE.pcode")
                ->orderBy("DE.nombre","ASC");
        $paises = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(DISTINCT O.id) AS total, S.id, S.nombre'.$land.' AS nombre, S.acronimo')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.sector_id","S")
                ->leftjoin("W.org_id","O")
                ->where("P.id = ".$id)->andwhere($cond_o)
                ->groupBy("S.nombre".$land)->orderBy("S.nombre".$land,"ASC");
        $sectores = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(DISTINCT W.id) AS total, T.nombre'.$land.' AS nombre')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.tipo_id","T")
                ->where("P.id = ".$id)->andwhere($cond_o)
                ->groupBy("T.nombre".$land)->orderBy("T.nombre".$land,"ASC");
        $oficinas = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(DISTINCT O.id) AS total, T.id, T.nombre'.$land.' AS nombre ')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("O.tipo_id","T")
                ->where("P.id = ".$id)->andwhere($cond_o)
                ->groupBy("T.nombre".$land)->orderBy("total","DESC");
        $otypes = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(DISTINCT W.id) AS total, E.id, E.nombre'.$land.' AS nombre ')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.estado_id","E")
                ->leftjoin("W.org_id","O")
                ->where("P.id = ".$id)->andwhere($cond_o)
                ->groupBy("E.nombre".$land)->orderBy("total","DESC");
        $estados = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT O.id, O.code'.$land.' AS nombre, COUNT(DISTINCT DE.id) AS tadmin1, COUNT(DISTINCT MU.id) AS tadmin2, COUNT(DISTINCT AC.id) AS acts')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.departamento","DE")
                ->leftjoin("W.municipio","MU")
                ->leftjoin("W.actividad_id","AC")
                ->where("P.id = ".$id)->andwhere($cond_o)->andwhere("MU.id <> ''")
                ->groupBy("O.code".$land)->orderBy("O.code".$land,"ASC");
        $orgspro = $qb->getQuery()->getResult();
        foreach ($orgspro as $key => $item) {
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT S.id, S.nombre'.$land.' AS nombre')
                        ->from('AppBundle:Tresw', 'W')
                        ->leftjoin("W.project_id","P")
                        ->leftjoin("W.org_id","O")
                        ->leftjoin("W.sector_id","S")
                        ->where("P.id = ".$id)->andwhere($cond_o)->andwhere("O.id = ".$item['id'])
                        ->orderBy("S.nombre".$land,"ASC");
                $orgspro[$key]['sectores'] = $qb->getQuery()->getResult();
        }


        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(DISTINCT W.id) AS total, T.nombre'.$land.' AS nombre')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.tipo_id","T")
                ->where("P.id = ".$id)->andwhere($cond_o)
                ->groupBy("T.id")->orderBy("T.nombre".$land,"ASC");
        $oficinas = $qb->getQuery()->getResult();

        $records = array();
        return $this->render('admin/blog/dashboard.html.twig', compact('record','campos','project','departamentos','municipios','paises','sectores','oficinas','otypes','orgspa',
                'main_country','org','orgspro','estados'));


    }

    /**
     *
     * @Route("/dashboard-caribbean/{id}/{org}", name="tresw_dashboard_caribbean", defaults={"id":"0", "org":"0" })
     * @Method("GET")
     */
    public function dashboarCaribbeandAction($id,$org) {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT P.id, P.nombre, P.emergency, P.detalle, P.campos, P.activities, P.paises, P.lang')
                ->from('AppBundle:Project', 'P')
                ->where("P.id = ".$id);
        $project = $qb->getQuery()->setMaxResults(1)->getSingleResult();

        $land =  ($project['lang'] == "en" ? '_en':'');

        $project['paises'] = json_decode($project['paises'], true);
        $project['activities'] = json_decode($project['activities'], true);
        $project['campos'] = json_decode($project['campos'], true);
        $campos = array();
        $requires = array();
        foreach ($project['campos'] as $key => $value) {
                $campos[] = $value['field'];
                if($value['requiere'] == 'require'){
                        $requires[] = $value['field'];
                }
        }

        $cond_l = "";
        $sqlc_l = "";
        if(in_array("municipio",$campos)){
                $sqlc_l = "COUNT(DISTINCT MU.id) AS locations";
        }elseif(in_array("departamento",$campos)){
                $sqlc_l = "COUNT(DISTINCT DE.id) AS locations";
        }else{
                $sqlc_l = "COUNT(DISTINCT PA.id) AS locations";
        }
        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(DISTINCT W.id) AS total, COUNT(DISTINCT S.id) AS sectors, COUNT(DISTINCT O.id) AS orgs, '.$sqlc_l)
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.sector_id","S")
                ->leftjoin("O.tipo_id","T")
                ->leftjoin("W.municipio","MU")
                ->leftjoin("W.departamento","DE")
                ->leftjoin("W.pais_id","PA")
                ->where("P.id = ".$id);
        $record = $qb->getQuery()->setMaxResults(1)->getSingleResult();

        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(DISTINCT O.id) AS total, PA.id, PA.nombre  AS nombre, PA.acronimo')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.pais_id","PA")
                ->where("P.id = ".$id)
                ->groupBy("PA.acronimo")
                ->orderBy("PA.nombre","ASC");
        $paises = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(DISTINCT O.id) AS total, S.id, S.acronimo AS nombre, S.acronimo')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.sector_id","S")
                ->leftjoin("W.org_id","O")
                ->where("P.id = ".$id)
                ->groupBy("S.nombre".$land)->orderBy("S.nombre".$land,"ASC");
        $sectores = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(DISTINCT W.id) AS total, T.id, T.nombre'.$land.' AS nombre')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.toficina_id","T")
                ->where("P.id = ".$id)
                ->groupBy("T.nombre".$land)->orderBy("T.nombre".$land,"ASC");
        $oficinas = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(DISTINCT O.id) AS total, T.nombre'.$land.' AS nombre ')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("O.tipo_id","T")
                ->where("P.id = ".$id)
                ->groupBy("T.nombre".$land)->orderBy("total","DESC");
        $otypes = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT O.id, O.code'.$land.' AS nombre')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->where("P.id = ".$id)
                ->groupBy("O.id")->orderBy("O.code".$land,"ASC");
        $orgspro = $qb->getQuery()->getResult();
        foreach ($orgspro as $key => $item) {
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT S.id, S.nombre'.$land.' AS nombre')
                        ->from('AppBundle:Tresw', 'W')
                        ->leftjoin("W.project_id","P")
                        ->leftjoin("W.org_id","O")
                        ->leftjoin("W.sector_id","S")
                        ->where("P.id = ".$id)->andwhere("O.id = ".$item['id'])
                        ->orderBy("S.nombre".$land,"ASC");
                $orgspro[$key]['sectores'] = $qb->getQuery()->getResult();
        }

        foreach ($paises as $key => $item) {
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT S.id, S.nombre'.$land.' AS nombre')
                        ->from('AppBundle:Tresw', 'W')
                        ->leftjoin("W.project_id","P")
                        ->leftjoin("W.pais_id","PA")
                        ->leftjoin("W.sector_id","S")
                        ->where("P.id = ".$id)->andwhere("PA.id = ".$item['id'])
                        ->orderBy("S.nombre".$land,"ASC");
                $paises[$key]['sectores'] = $qb->getQuery()->getResult();
        }
        $records = array();

        
        return $this->render('admin/blog/dashboardcaribe.html.twig', compact('record','campos','project','departamentos','municipios','paises','sectores','oficinas','otypes','orgspro'));


    }

    /**
     *
     * @Route("/get-orgs/{q}", name="ajax_get_orgs", defaults={"q":"0"})
     * @Method("GET")
     */
    public function getOrgAction(Request $request) {
        $q = $request->query->get('q');
        $lang = $request->query->get('lang');
        $land =  ($lang == "en" ? '_en':'');

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select("DISTINCT O.id, CONCAT(O.code".$land.", ' - ', O.nombre".$land.") AS text ")
                ->from('AppBundle:Organizacion', 'O')->where("(O.code LIKE '%$q%') OR (O.nombre  LIKE '%$q%' ) OR (O.code_en  LIKE '%$q%' ) OR (O.nombre_en  LIKE '%$q%' ) ")
                ->orderBy("O.nombre", 'ASC');
        $items = $qb->getQuery()->getResult();

        $total_count = count($items);
        $response = new Response(json_encode(compact('items', 'total_count', 'q')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     *
     * @Route("/get-activities/{q}", name="ajax_get_activities", defaults={"q":"0"})
     * @Method("GET")
     */
    public function getActivitiesAction(Request $request) {
        $q = $request->query->get('q');
        $lang = $request->query->get('lang');
        $ssector = $request->query->get('ssector');
        $tipo = $request->query->get('tipo');
        $project = $request->query->get('project');
        $land =  ($lang == "en" ? '_en':'');
        if($tipo == "sector"){
                $cond_s = "S.id = ".$ssector;
        }else{
                $cond_s = "SS.id = ".$ssector;
        }
     
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select("DISTINCT O.id, O.nombre".$land." AS text , S.id AS sector")
                ->from('AppBundle:Actividad', 'O')
                ->leftjoin("O.ssector", "SS")
                ->leftjoin("O.sector", "S")
                ->leftjoin("O.project_id", "P")
                ->where("(O.nombre  LIKE '%$q%' ) OR (O.nombre_en  LIKE '%$q%' ) ")
                ->andwhere($cond_s)
                //->andwhere("P.id = ".$project)
                ->orderBy("text", 'ASC');
        $items = $qb->getQuery()->getResult();

        $total_count = count($items);
        $response = new Response(json_encode(compact('items', 'total_count', 'q')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
    * @Route("/get-where/{q}", name="ajax_get_where", defaults={"q":"0"})
    * @Method("GET")
    */
   public function getWhereAction(Request $request) {
        $q = $request->query->get('q');
        
        $search = $request->query->get('search');
        $paises = $request->query->get('paises');
        $campos = $request->query->get('campos');
        $paises = explode("|", $paises); 
        $campos = explode("|", $campos); 
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        if(in_array("admin3",$campos)){
                $sql_l = "DISTINCT AD.id, CONCAT(P.nombre, ' - ', V.nombre, ' - ', D.nombre, ' - ', AD.nombre) AS text";
                $qb->select($sql_l)->from('AppBundle:Admin3', 'AD');
                $qb->leftjoin("AD.distrito_id","D");
                $qb->leftjoin("D.provincia_id","V");
                $qb->leftjoin("V.pais_id","P");
                $qb->where("(D.nombre LIKE '%$q%') OR (V.nombre  LIKE '%$q%' ) OR (P.nombre_en  LIKE '%$q%' ) OR (P.nombre  LIKE '%$q%' ) OR (AD.nombre  LIKE '%$q%' )  ");
                $qb->orderBy("P.nombre, V.nombre, D.nombre", 'ASC');
        }elseif(in_array("municipio",$campos)){
                $sql_l = "DISTINCT D.id, CONCAT(P.nombre, ' - ', V.nombre, ' - ', D.nombre) AS text";
                $qb->select($sql_l)->from('AppBundle:Distrito', 'D');
                $qb->leftjoin("D.provincia_id","V");
                $qb->leftjoin("V.pais_id","P");
                $qb->where("(D.nombre LIKE '%$q%') OR (V.nombre  LIKE '%$q%' ) OR (P.nombre_en  LIKE '%$q%' ) OR (P.nombre  LIKE '%$q%' ) ");
                $qb->orderBy("P.nombre, V.nombre, D.nombre", 'ASC');
        }elseif(in_array("departamento",$campos)){
                $sql_l = "DISTINCT V.id, CONCAT(P.nombre, ' - ', V.nombre) AS text ";
                $qb->select($sql_l)->from('AppBundle:Provincia', 'V');
                $qb->leftjoin("V.pais_id","P");
                $qb->where("(V.nombre  LIKE '%$q%' ) OR (P.nombre_en  LIKE '%$q%' ) OR (P.nombre  LIKE '%$q%' ) ");
                $qb->orderBy("P.nombre, V.nombre", 'ASC');
        }else{
                $sql_l = "DISTINCT P.id, P.nombre AS text ";
                $qb->select($sql_l)->from('AppBundle:Pais', 'P');
                $qb->where("(P.nombre_en  LIKE '%$q%' ) OR (P.nombre  LIKE '%$q%' ) ");
                $qb->orderBy("P.nombre", 'ASC');
        }
               $qb->andwhere('P.id IN (:ids)')
                ->setParameter('ids', $paises);
                
        $items = $qb->getQuery()->getResult();
 
        $total_count = count($items);
        $response = new Response(json_encode(compact('items', 'total_count', 'q','paises','search')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
   }

   /**
     * Creates a new Post entity.
     *
     * @Route("/admin-where/{org}/{project}", name="admin-where", defaults={"org":"0", "project":"0"})
     * @Method({"GET", "POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function adminWhereAction(Request $request, $org, $project) {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {

                $edita = 1;
                $data = $request->request->all();
                if ($data['id'] == 0) {
                        $entity = new Tresw();
                        $edita = 0;
                } else {
                        $entity = $this->getDoctrine()->getRepository('AppBundle:Tresw')->find($data['id']);
                }

                $datetime = new \DateTime();
                $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($data['project_id']);
                $entity->setProjectId($project);
                $organi = $this->getDoctrine()->getRepository('AppBundle:Organizacion')->find($data['org_id']);

        }else{
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT P.id, P.campos, P.paises, P.lang')
                        ->from('AppBundle:Project', 'P')
                        ->where("P.id = ".$id);
                $project = $qb->getQuery()->setMaxResults(1)->getSingleResult();
        
                $land =  ($project['lang'] == "en" ? '_en':'');
        
                $project['paises'] = json_decode($project['paises'], true);
                $project['campos'] = json_decode($project['campos'], true);
                $campos = array();
                $requires = array();
                foreach ($project['campos'] as $key => $value) {
                        $campos[] = $value['field'];
                        if($value['requiere'] == 'require'){
                                $requires[] = $value['field'];
                        }
                }                   


        }
}

   /**
     * Creates a new Post entity.
     *
     * @Route("/new-www/{id}/{project}/{lang}/{org}/{campos}", name="admin_www_new", defaults={"id":"0", "project":0, "lang":"0", "org":"0", "campos":"0"})
     * @Method({"GET", "POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function addAction(Request $request, $id, $project, $lang, $org, $campos) {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {

                $edita = 1;
                $data = $request->request->all();
                if ($data['id'] == 0) {
                        $entity = new Tresw();
                        $edita = 0;
                } else {
                        $entity = $this->getDoctrine()->getRepository('AppBundle:Tresw')->find($data['id']);
                }

                $datetime = new \DateTime();

                $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($data['project_id']);
                $entity->setProjectId($project);
                $organi = $this->getDoctrine()->getRepository('AppBundle:Organizacion')->find($data['org_id']);
                $entity->setOrgId($organi);
                if($data['t_where'] == "admin3"){
                        $admin3 = $this->getDoctrine()->getRepository('AppBundle:Admin3')->find($data['where_id_org']);
                        $entity->setAdmin3($admin3);
                        //print_r($admin3);
                        $municipio = $admin3->getDistritoId();
                        $entity->setMunicipio($municipio);
                        $departamento = $municipio->getProvinciaId();
                        $entity->setDepartamento($departamento);
                        $entity->setPaisId($departamento->getPaisId());
                }
                if($data['t_where'] == "municipio"){
                        $municipio = $this->getDoctrine()->getRepository('AppBundle:Distrito')->find($data['where_id_org']);
                        $entity->setMunicipio($municipio);
                        $departamento = $municipio->getProvinciaId();
                        $entity->setDepartamento($departamento);
                        $entity->setPaisId($departamento->getPaisId());
                }
                if($data['t_where'] == "departamento"){
                        $departamento = $this->getDoctrine()->getRepository('AppBundle:Provincia')->find($data['where_id_org']);
                        $entity->setDepartamento($departamento);
                        $entity->setPaisId($departamento->getPaisId());
                }
                if($data['t_where'] == "pais"){
                        $entity->setPaisId($this->getDoctrine()->getRepository('AppBundle:Pais')->find($data['where_id_org']));
                }
                
                $entity->setFechaModifica(new \DateTime("now"));
                $entity->setTipo("Respuesta");

                if(isset($data['financed'])){ $entity->setFinanced($this->getDoctrine()->getRepository('AppBundle:Organizacion')->find($data['financed'])); }
                if(isset($data['toficina_id'])){ $entity->setToficinaId($this->getDoctrine()->getRepository('AppBundle:Toficina')->find($data['toficina_id'])); }
                if(isset($data['coverage_id'])){ $entity->setCoverageId($this->getDoctrine()->getRepository('AppBundle:Pais')->find($data['coverage_id'])); }
                /** WHAT ************ */
                if(isset($data['recurso_id'])){ $entity->setRecursoId($this->getDoctrine()->getRepository('AppBundle:Recurso')->find($data['recurso_id'])); }
     
                if(isset($data['actividad_id'])){ 
                        $actividad = $this->getDoctrine()->getRepository('AppBundle:Actividad')->find($data['actividad_id']);
                        $entity->setActividadId($actividad); 
                        if(isset($data['tactividad_id'])){
                                $entity->setTactividad($actividad->getTactividadId());
                        }
                        $entity->setSectorId($actividad->getSector());
                }else{
                        $entity->setSectorId($this->getDoctrine()->getRepository('AppBundle:Sector')->find($data['sector_id']));
                }
                if(isset($data['cantidad'])){ $entity->setCantidad($data['cantidad']); }
                if(isset($data['unidad_id'])){ $entity->setUnidadId($this->getDoctrine()->getRepository('AppBundle:Unidad')->find($data['unidad_id'])); }
                if(isset($data['detalle'])){ $entity->setDetalle($data['detalle']); }


                if(isset($data['dini'])){ 
                        $origDate = $data['dini'];
                        $date = str_replace('/', '-', $origDate );
                        $newDate = date("Y-m-d", strtotime($date));
                        $entity->setDini(new \DateTime($newDate)); 
                }
                if(isset($data['dend'])){ 
                        $origDate = $data['dend'];
                        $date = str_replace('/', '-', $origDate );
                        $newDate = date("Y-m-d", strtotime($date));
                        $entity->setDend(new \DateTime($newDate)); 
                }
                if(isset($data['estado_id'])){ $entity->setEstadoId($this->getDoctrine()->getRepository('AppBundle:Estado')->find($data['estado_id'])); }
                if(isset($data['tipo_id'])){ $entity->setTipoId($this->getDoctrine()->getRepository('AppBundle:Tipo')->find($data['tipo_id'])); }
        


                /** WHOM ************ */
                if(isset($data['beneficiario_id'])){ $entity->setBeneficiarioId($this->getDoctrine()->getRepository('AppBundle:Beneficiario')->find($data['beneficiario_id'])); }
                if(isset($data['atendidas'])){ $entity->setAtendidas($data['atendidas']); }
                if(isset($data['description'])){ $entity->setDescription($data['description']); }
                if(isset($data['contacto'])){ $entity->setContacto($data['contacto']); }
                if(isset($data['afectados'])){ $entity->setAfectados($data['afectados']); }
                if(isset($data['objetivo'])){ $entity->setObjetivo($data['objetivo']); }
                if(isset($data['infancy'])){ $entity->setInfancy($data['infancy']); }
                if(isset($data['childhood'])){ $entity->setChildhood($data['childhood']); }
                if(isset($data['adolescencem'])){ $entity->setAdolescencem($data['adolescencem']); }
                if(isset($data['adolescencef'])){ $entity->setAdolescencef($data['adolescencef']); }
                if(isset($data['pregnant'])){ $entity->setPregnant($data['pregnant']); }
                if(isset($data['olds'])){ $entity->setOlds($data['olds']); }
                if(isset($data['male'])){ $entity->setMale($data['male']); }
                if(isset($data['female'])){ $entity->setFemale($data['female']); }
                if(isset($data['boys'])){ $entity->setBoys($data['boys']); }
                if(isset($data['girls'])){ $entity->setGirls($data['girls']); }

                $em->persist($entity);
                $em->flush();
                $lang = $project->getLang();
                $project = $project->getId();

                $idw = $entity->getId();
                $actid = $actividad->getId();
                $land =  ($lang == "en" ? '_en':'');
                $campos = explode("|", $data['campos']); 
        
                $idorg = $organi->getId();   

    
                        $qb = $em->createQueryBuilder();
                        $qb->select('DISTINCT W.id, F.code'.$land.' AS financed,  C.nombre'.$land.' AS coverage, W.atendidas, 
                        TO.nombre'.$land.' AS toficina, S.nombre'.$land.' AS sector,  W.dini, A.nombre'.$land.' AS  actividad, PA.nombre AS pais, DE.nombre AS departamento, 
                        MU.nombre AS municipio, AD.nombre AS admin3, TI.nombre'.$land.' AS tipo, W.cantidad, R.nombre'.$land.' AS recurso, UN.nombre'.$land.' AS unidad, W.detalle, E.nombre'.$land.' AS estado')
                                ->from('AppBundle:Tresw', 'W')
                                ->leftjoin("W.org_id","O")
                                ->leftjoin("W.recurso_id","R")
                                ->leftjoin("W.financed","F")
                                ->leftjoin("W.actividad_id","A")
                                ->leftjoin("W.sector_id","S")
                                ->leftjoin("W.subsector_id","SS")
                                ->leftjoin("O.tipo_id","T")
                                ->leftjoin("W.tipo_id","TI")
                                ->leftjoin("W.toficina_id","TO")
                                ->leftjoin("W.coverage_id","C")
                                ->leftjoin("W.estado_id","E")
                                ->leftjoin("W.unidad_id","UN")
                                ->leftjoin("W.admin3","AD")
                                ->leftjoin("W.municipio","MU")
                                ->leftjoin("W.departamento","DE")
                                ->leftjoin("W.pais_id","PA")
                                ->where("W.id = ".$idw);
                        $item = $qb->getQuery()->setMaxResults(1)->getSingleResult();
                        $html = $this->renderView('admin/blog/listado_acts.html.twig', compact('item', 'campos','edita','project','idorg'));

                  
                $response = new Response(json_encode(compact('edita','idw','campos','html','idorg','item','actid')));
                $response->headers->set('Content-Type', 'application/json');

                return $response;

        }else{

                $land =  ($lang == "en" ? "_en":"");

                $tcampos = $campos;
                $campos = explode("|", $campos); 
                $sql_ss = "S.id AS subsector_id";
                if(in_array("subsector_id",$campos)){
                        $sql_ss = "SS.id AS subsector_id";
                }
        
                $t_where = "";
                if(in_array("admin3",$campos)){
                        $t_where = "admin3";
                        $sql_l = ", AD.id AS where_id, CONCAT(PA.nombre, ' - ', DE.nombre, ' - ', MU.nombre, ' - ', AD.nombre) AS where_name";
                }elseif(in_array("municipio",$campos)){
                        $t_where = "municipio";
                        $sql_l = ", MU.id AS where_id, CONCAT(PA.nombre, ' - ', DE.nombre, ' - ', MU.nombre) AS where_name";
                }elseif(in_array("departamento",$campos)){
                        $t_where = "departamento";
                        $sql_l = ", DE.id AS where_id, CONCAT(PA.nombre, ' - ', DE.nombre) AS where_name";
                }else{
                        $t_where = "pais";
                        $sql_l = ", PA.id AS where_id, PA.nombre AS where_name";
                }

                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT U.id, U.nombre'.$land.' AS nombre, U.code')
                        ->from('AppBundle:Unidad', 'U')
                        ->orderBy("U.nombre","ASC");
                $unidades = $qb->getQuery()->getResult(); 

                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT U.id, U.nombre'.$land.' AS nombre')
                        ->from('AppBundle:Tipo', 'U')
                        ->orderBy("U.id","ASC");
                $tipos = $qb->getQuery()->getResult(); 

                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT B.id, B.nombre'.$land.' AS nombre')
                        ->from('AppBundle:Beneficiario', 'B')
                        ->orderBy('B.nombre'.$land,"ASC");
                $beneficiarios = $qb->getQuery()->getResult(); 

                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT T.id, T.nombre'.$land.' AS nombre')
                        ->from('AppBundle:Toficina', 'T')
                        ->orderBy('T.nombre'.$land,"ASC");
                $toficinas = $qb->getQuery()->getResult(); 

                $actividades = array(); 

                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT T.id, T.nombre'.$land.' AS nombre')
                        ->from('AppBundle:Tactividad', 'T')
                        ->orderBy("T.nombre".$land,"ASC");
                $tactividades = $qb->getQuery()->getResult(); 

                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT P.id, P.nombre AS nombre')
                        ->from('AppBundle:Pais', 'P')
                        ->orderBy("P.nombre", "ASC");
                $paises = $qb->getQuery()->getResult(); 

                if(in_array("subsector_id", $campos)){ 
                        $qb = $em->createQueryBuilder();
                        $qb->select('DISTINCT S.id, S.nombre'.$land.' AS nombre, C.id AS sector, C.nombre'.$land.' AS sector_name')
                                ->from('AppBundle:Subsector', 'S')
                                ->leftjoin("S.sector_id","C")
                                ->orderBy("S.nombre".$land,"ASC");
                        $subsectores = $qb->getQuery()->getResult();
                }else{
                        $qb = $em->createQueryBuilder();
                        $qb->select('DISTINCT C.id, C.nombre'.$land.' AS sector_name')
                                ->from('AppBundle:Donde', 'D')
                                ->leftjoin("D.sector_id","C")
                                ->leftjoin("D.org_id","O")
                                ->leftjoin("D.project_id","P")
                                ->where("O.id = ".$org)->andwhere("P.id = ".$project)
                                ->orderBy("C.nombre".$land,"ASC");
                        $subsectores = $qb->getQuery()->getResult();
                }
        
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT E.id, E.nombre'.$land.' AS nombre')
                        ->from('AppBundle:Estado', 'E')
                        ->orderBy('E.nombre'.$land,"ASC");
                $estados = $qb->getQuery()->getResult(); 
        
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT R.id, R.nombre'.$land.' AS nombre')
                        ->from('AppBundle:Recurso', 'R')
                        ->orderBy("R.id","ASC");
                $recursos = $qb->getQuery()->getResult(); 
                

                if($id != 0){

                        $sql_gender = "";
                        if(in_array("gender",$campos)){
                                $sql_gender = ", W.male, W.female, W.boys, W.girls";
                        }
                        $sql_tipo = "";
                        if(in_array("tipo_id",$campos)){
                                $sql_tipo = ", TI.id AS tipo_id";
                        }

                        $sqlwhen = "";
                        if(in_array("dini",$campos)){
                                $sqlwhen = ', W.dini, W.dend, E.id AS estado_id';
                        }

                        $sqlrecurso = "";
                        if(in_array("recurso_id",$campos)){
                                $sqlrecurso = ', R.id AS recurso_id, U.id AS unidad_id, W.cantidad ';
                        }

                        $sqlwhom = "";
                        $sqlcontacto = "";
                        $sqldescription = "";

                        if(in_array("atendidas",$campos)){
                                $sqlwhom = $sqlwhom. ", W.atendidas ";
                        }
                        if(in_array("objetivo",$campos)){
                                $sqlwhom = $sqlwhom. ", W.objetivo ";
                        }
                        if(in_array("afectados",$campos)){
                                $sqlwhom = $sqlwhom. ", W.afectados ";
                        }
                        if(in_array("infancy",$campos)){
                                $sqlwhom = $sqlwhom. ", W.infancy ";
                        }
                        if(in_array("childhood",$campos)){
                                $sqlwhom = $sqlwhom. ", W.childhood ";
                        }
                        if(in_array("adolescencem",$campos)){
                                $sqlwhom = $sqlwhom. ", W.adolescencem ";
                        }
                        if(in_array("adolescencef",$campos)){
                                $sqlwhom = $sqlwhom. ", W.adolescencef ";
                        }
                        if(in_array("pregnant",$campos)){
                                $sqlwhom = $sqlwhom. ", W.pregnant ";
                        }
                        if(in_array("olds",$campos)){
                                $sqlwhom = $sqlwhom. ", W.olds ";
                        }
                        if(in_array("contact",$campos)){
                                $sqlcontacto = $sqlcontacto. ", W.contacto ";
                        }
                        if(in_array("description",$campos)){
                                $sqldescription = $sqldescription. ", W.description ";
                        }
                        $sqlfinan = "";
                        if(in_array("financed",$campos)){
                                $sqlfinan = $sqlfinan. ", F.id AS financed, F.nombre".$land." AS forg_name, F.code".$land." AS forg_code";
                        }

                        $qb = $em->createQueryBuilder();
                        $qb->select("DISTINCT W.id, T.id AS tipo,  O.id AS org_id, O.nombre".$land." AS org_name, O.code".$land." AS org_code, ".$sql_ss.", 
                        B.id AS beneficiario_id, C.id AS coverage_id, TO.id AS toficina_id,
                         TA.id AS tactividad, A.id AS actividad_id, A.nombre".$land." AS aname, W.detalle".$sql_l.$sql_gender.$sql_tipo.$sqlwhen.$sqlwhom.$sqlrecurso.$sqlfinan.$sqlcontacto.$sqldescription)
                                ->from('AppBundle:Tresw', 'W')
                                ->leftjoin("W.project_id","P")
                                ->leftjoin("W.org_id","O")
                                ->leftjoin("W.tactividad","TA")
                                ->leftjoin("W.actividad_id","A")
                                ->leftjoin("W.sector_id","S")
                                ->leftjoin("W.beneficiario_id","B")
                                ->leftjoin("W.subsector_id","SS")
                                ->leftjoin("O.tipo_id","T")
                                ->leftjoin("W.toficina_id","TO")
                                ->leftjoin("W.coverage_id","C")
                                ->leftjoin("W.admin3","AD")
                                ->leftjoin("W.municipio","MU")
                
                                ->leftjoin("W.departamento","DE")
                                ->leftjoin("W.pais_id","PA");
                                if(in_array("tipo_id",$campos)){
                                        $qb->leftjoin("W.tipo_id","TI");
                                }
                                if(in_array("dini",$campos)){
                                        $qb->leftjoin("W.estado_id","E");
                                }
                                if(in_array("recurso_id",$campos)){
                                        $qb->leftjoin("W.recurso_id","R")->leftjoin("W.unidad_id","U");
                                }
                                if(in_array("financed",$campos)){
                                        $qb->leftjoin("W.financed","F");
                                }
                                $qb->where("W.id = ".$id);
                        $record = $qb->getQuery()->setMaxResults(1)->getSingleResult();

                }else{
                        if($org != 0){ 
                                $qb = $em->createQueryBuilder();
                                $qb->select('DISTINCT O.id, O.nombre'.$land.' AS nombre, O.code'.$land.' AS code')
                                        ->from('AppBundle:Organizacion', 'O')
                                        ->where("O.id = ".$org);
                                $organization = $qb->getQuery()->setMaxResults(1)->getSingleResult();   
                        }
                        $record = array('id'=>0,'org_id'=>($org == 0 ? '':$organization['id']),'org_name'=>($org == 0 ? '':$organization['nombre']),'org_code'=>($org == 0 ? '':$organization['code']),'financed'=>'','forg_name'=>'','forg_code'=>'','recurso_id'=>'','estado_id'=>'', 
                        'atendidas'=>0 ,'objetivo'=>0,'afectados'=>0,'infancy'=>0,'childhood'=>0,'adolescencem'=>0,'adolescencef'=>0,'pregnant'=>0,'olds'=>0, 'beneficiario_id'=>'',
                        'where_id'=>'','where_name'=>'select location','coverage_id'=>'','toficina_id'=>'','subsector_id'=>'',
                        'tactividad'=>'','actividad_id'=>'', 'aname'=> 'Select Activity','detalle'=>'','dend'=>'','dini'=>'','recurso_id'=>'','cantidad'=>'', 'unidad_id'=>'', 
                        'male'=>'', 'female'=>'', 'boys'=>'', 'girls'=>'','tipo_id'=>0, 'description'=>'', 'contacto'=>'');
                }
//print_r($record);

                $html = $this->renderView('admin/blog/form.html.twig', compact('paises', 'tipos', 'project','recursos','estados','subsectores','actividades','beneficiarios','unidades',
                'tactividades','campos','requires','toficinas','id','record','t_where','tcampos'));

                $response = new Response(json_encode(compact('html')));
                $response->headers->set('Content-Type', 'application/json');

                return $response;


        }
}

        /**
     * Creates a new Post entity.
     *
     * @Route("/admin-organization/{project}/{org}/{campos}", name="admin_organization", defaults={"project":0, "org":"0", "campos":"0"})
     * @Method({"GET"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function adminorgAction(Request $request, $project, $org, $campos) {
        $em = $this->getDoctrine()->getManager();
        $project_id = $project;
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT P.id, P.nombre, P.campos, P.activities, P.paises, P.lang')
                ->from('AppBundle:Project', 'P')
                ->where("P.id = ".$project);
        $project = $qb->getQuery()->setMaxResults(1)->getSingleResult();

        

        $land =  ($project['lang'] == "en" ? '_en':'');

        $project['paises'] = json_decode($project['paises'], true);
        $project['campos'] = json_decode($project['campos'], true);
        $campos = array();
        $requires = array();
        foreach ($project['campos'] as $key => $value) {
                $campos[] = $value['field'];
                if($value['requiere'] == 'require'){
                        $requires[] = $value['field'];
                }
        }



        $sql_ss = "S.id AS subsector_id";
        if(in_array("subsector_id",$campos)){
                $sql_ss = "SS.id AS subsector_id";
        }

        $t_where = "";
        if(in_array("admin3",$campos)){
                $t_where = "admin3";
                $sql_l = ", AD.id AS where_id, CONCAT(PA.nombre, ' - ', DE.nombre, ' - ', MU.nombre, ' - ', AD.nombre) AS where_name";
        }elseif(in_array("municipio",$campos)){
                $t_where = "municipio";
                $sql_l = ", MU.id AS where_id, CONCAT(PA.nombre, ' - ', DE.nombre, ' - ', MU.nombre) AS where_name";
        }elseif(in_array("departamento",$campos)){
                $t_where = "departamento";
                $sql_l = ", DE.id AS where_id, CONCAT(PA.nombre, ' - ', DE.nombre) AS where_name";
        }else{
                $t_where = "pais";
                $sql_l = ", PA.id AS where_id, PA.nombre AS where_name";
        }

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT U.id, U.nombre'.$land.' AS nombre, U.code')
                ->from('AppBundle:Unidad', 'U')
                ->orderBy("U.nombre","ASC");
        $unidades = $qb->getQuery()->getResult(); 

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT U.id, U.nombre'.$land.' AS nombre')
                ->from('AppBundle:Tipo', 'U')
                ->orderBy("U.nombre","ASC");
        $tipos = $qb->getQuery()->getResult(); 

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT B.id, B.nombre'.$land.' AS nombre')
                ->from('AppBundle:Beneficiario', 'B')
                ->orderBy('B.nombre'.$land,"ASC");
        $beneficiarios = $qb->getQuery()->getResult(); 

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT T.id, T.nombre'.$land.' AS nombre')
                ->from('AppBundle:Toficina', 'T')
                ->orderBy('T.nombre'.$land,"ASC");
        $toficinas = $qb->getQuery()->getResult(); 

        $actividades = array(); 

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT T.id, T.nombre'.$land.' AS nombre')
                ->from('AppBundle:Tactividad', 'T')
                ->orderBy("T.nombre".$land,"ASC");
        $tactividades = $qb->getQuery()->getResult(); 

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT P.id, P.nombre AS nombre')
                ->from('AppBundle:Pais', 'P')
                ->orderBy("P.nombre", "ASC");
        $paises = $qb->getQuery()->getResult(); 


                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT C.id, C.nombre'.$land.' AS sector_name')
                        ->from('AppBundle:Sector', 'C')
                        ->orderBy("C.nombre".$land,"ASC");
                $subsectores = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT E.id, E.nombre'.$land.' AS nombre')
                ->from('AppBundle:Estado', 'E')
                ->orderBy("E.id","ASC");
        $estados = $qb->getQuery()->getResult(); 

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT R.id, R.nombre'.$land.' AS nombre')
                ->from('AppBundle:Recurso', 'R')
                ->orderBy("R.id","ASC");
        $recursos = $qb->getQuery()->getResult(); 
        

        if($org == 0){

                $sql_gender = "";
                if(in_array("gender",$campos)){
                        $sql_gender = ", W.male, W.female, W.boys, W.girls";
                }
                $sql_tipo = "";
                if(in_array("tipo_id",$campos)){
                        $sql_tipo = ", TI.id AS tipo_id";
                }

                $sqlwhen = "";
                if(in_array("dini",$campos)){
                        $sqlwhen = ', W.dini, W.dend, E.nombre'.$land.' AS estado_id';
                }

                $sqlrecurso = "";
                if(in_array("recurso_id",$campos)){
                        $sqlrecurso = ', R.nombre'.$land.' AS recurso_id, U.nombre'.$land.' AS unidad_id, W.cantidad ';
                }

                $sqlwhom = "";
                if(in_array("atendidas",$campos)){
                        $sqlwhom = $sqlwhom. ", W.atendidas ";
                }
                if(in_array("objetivo",$campos)){
                        $sqlwhom = $sqlwhom. ", W.objetivo ";
                }
                if(in_array("afectados",$campos)){
                        $sqlwhom = $sqlwhom. ", W.afectados ";
                }
                if(in_array("infancy",$campos)){
                        $sqlwhom = $sqlwhom. ", W.infancy ";
                }
                if(in_array("childhood",$campos)){
                        $sqlwhom = $sqlwhom. ", W.childhood ";
                }
                if(in_array("adolescencem",$campos)){
                        $sqlwhom = $sqlwhom. ", W.adolescencem ";
                }
                if(in_array("adolescencef",$campos)){
                        $sqlwhom = $sqlwhom. ", W.adolescencef ";
                }
                if(in_array("pregnant",$campos)){
                        $sqlwhom = $sqlwhom. ", W.pregnant ";
                }
                if(in_array("olds",$campos)){
                        $sqlwhom = $sqlwhom. ", W.olds ";
                }
                $sqlfinan = "";
                if(in_array("financed",$campos)){
                        $sqlfinan = $sqlfinan. ", F.id AS financed, F.nombre".$land." AS forg_name, F.code".$land." AS forg_code";
                }

                $qb = $em->createQueryBuilder();
                $qb->select("DISTINCT W.id, T.id AS tipo,  O.id AS org_id, O.nombre".$land." AS org_name, O.code".$land." AS org_code, ".$sql_ss.", 
                B.id AS beneficiario_id, C.id AS coverage_id, TO.id AS toficina_id,
                 TA.id AS tactividad, A.id AS actividad_id, A.nombre".$land." AS aname, W.detalle".$sql_l.$sql_gender.$sql_tipo.$sqlwhen.$sqlwhom.$sqlrecurso.$sqlfinan)
                        ->from('AppBundle:Tresw', 'W')
                        ->leftjoin("W.project_id","P")
                        ->leftjoin("W.org_id","O")
                        ->leftjoin("W.tactividad","TA")
                        ->leftjoin("W.actividad_id","A")
                        ->leftjoin("W.sector_id","S")
                        ->leftjoin("W.beneficiario_id","B")
                        ->leftjoin("W.subsector_id","SS")
                        ->leftjoin("O.tipo_id","T")
                        ->leftjoin("W.toficina_id","TO")
                        ->leftjoin("W.coverage_id","C")
                        ->leftjoin("W.admin3","AD")
                        ->leftjoin("W.municipio","MU")
                        ->leftjoin("W.departamento","DE")
                        ->leftjoin("W.pais_id","PA");
                        if(in_array("tipo_id",$campos)){
                                $qb->leftjoin("W.tipo_id","TI");
                        }
                        if(in_array("tipo_id",$campos)){
                                $qb->leftjoin("W.estado_id","E");
                        }
                        if(in_array("recurso_id",$campos)){
                                $qb->leftjoin("W.recurso_id","R")->leftjoin("W.unidad_id","U");
                        }
                        if(in_array("financed",$campos)){
                                $qb->leftjoin("W.financed","F");
                        }
                        $qb->where("W.id = ".$id);
                $record = $qb->getQuery()->setMaxResults(1)->getSingleResult();

        }else{
                if($org != 0){ 
                        $qb = $em->createQueryBuilder();
                        $qb->select('DISTINCT O.id, O.nombre'.$land.' AS nombre, O.code'.$land.' AS code')
                                ->from('AppBundle:Organizacion', 'O')
                                ->where("O.id = ".$org);
                        $organization = $qb->getQuery()->setMaxResults(1)->getSingleResult();   
                }
                
                $record = array('id'=>0,'org_id'=>($org == 0 ? '':$organization['id']),'org_name'=>($org == 0 ? '':$organization['nombre']),'org_code'=>($org == 0 ? '':$organization['code']),'financed'=>'','forg_name'=>'','forg_code'=>'','recurso_id'=>'','estado_id'=>'', 
                'atendidas'=>0 ,'objetivo'=>0,'afectados'=>0,'infancy'=>0,'childhood'=>0,'adolescencem'=>0,'adolescencef'=>0,'pregnant'=>0,'olds'=>0, 'beneficiario_id'=>'',
                'where_id'=>'','where_name'=>'select location','coverage_id'=>'','toficina_id'=>'','subsector_id'=>'',
                'tactividad'=>'','actividad_id'=>'', 'aname'=> 'Select Activity','detalle'=>'','dend'=>'','dini'=>'','recurso_id'=>'','cantidad'=>'', 'unidad_id'=>'', 
                'male'=>'', 'female'=>'', 'boys'=>'', 'girls'=>'','tipo_id'=>0);
        }


        return $this->render('admin/blog/adminorg.html.twig', compact('paises', 'tipos','project', 'project_id','recursos','estados','subsectores','actividades','beneficiarios','unidades',
        'tactividades','campos','requires','toficinas','id','record','t_where','tcampos'));              


        }

        /**
         *
         * @Route("/www-list/{id}", name="admin_www_list", defaults={"id":"0"})
         * @Method("GET")
         */

        public function listAction($id) {
                $em = $this->getDoctrine()->getManager();

                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT P.id, P.nombre, P.emergency, P.detalle, P.campos, P.lang')
                        ->from('AppBundle:Project', 'P')
                        ->where("P.id = ".$id);
                $project = $qb->getQuery()->setMaxResults(1)->getSingleResult();

                $clang =  ($project['lang'] == "en" ? '_en':'');

                $camposd = json_decode($project['campos'], true);

                $campos = array();
                $requires = array();
                foreach ($camposd as $key => $value) {
                        $campos[] = $value['field'];
                        if($value['requiere'] == 'require'){
                                $requires[] = $value['field'];
                        }
                }
   
                $cond_l = "";
        
        $sqlwhen = "";
        if(in_array("dini",$campos)){
                $sqlwhen = ', W.dini, W.dend, E.nombre'.$clang.' AS estado';
        }

        $sqlrecurso = "";
        if(in_array("recurso_id",$campos)){
                $sqlrecurso = ', R.nombre'.$clang.' AS recurso, U.nombre'.$clang.' AS unidad, W.cantidad ';
        }
        $sqlwhom = "";

        if(in_array("atendidas",$campos)){
                $sqlwhom = $sqlwhom. ", W.atendidas ";
        }
        if(in_array("objetivo",$campos)){
                $sqlwhom = $sqlwhom. ", W.objetivo ";
        }
        if(in_array("afectados",$campos)){
                $sqlwhom = $sqlwhom. ", W.afectados ";
        }
        if(in_array("infancy",$campos)){
                $sqlwhom = $sqlwhom. ", W.infancy ";
        }
        if(in_array("childhood",$campos)){
                $sqlwhom = $sqlwhom. ", W.childhood ";
        }
        if(in_array("adolescencem",$campos)){
                $sqlwhom = $sqlwhom. ", W.adolescencem ";
        }
        if(in_array("adolescencef",$campos)){
                $sqlwhom = $sqlwhom. ", W.adolescencef ";
        }
        if(in_array("pregnant",$campos)){
                $sqlwhom = $sqlwhom. ", W.pregnant ";
        }
        if(in_array("olds",$campos)){
                $sqlwhom = $sqlwhom. ", W.olds ";
        }
        $sqlgender = "";
        if(in_array("gender",$campos)){
                $sqlgender = $sqlgender. ", W.male, W.female, W.boys, W.girls ";
        }
        $sqltipo = "";
        if(in_array("tipo_id",$campos)){
                $sqltipo = ", TI.nombre".$clang." AS tipo";
        }

        $sqlfinanced = "";
        if(in_array("financed",$campos)){
                $sqlfinanced = ", FI.code".$clang." AS financed, FI.nombre".$clang." AS fi_name, FIO.nombre".$clang." AS fit_org";
        }


                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT W.id, O.code'.$clang.' AS code, W.detalle, W.fecha_modifica, O.nombre'.$clang.' AS org, T.nombre'.$clang.' AS t_org,  SS.nombre'.$clang.' AS subsector, A.nombre'.$clang.' AS actividad, S.nombre'.$clang.' AS sector, W.objetivo,
                MU.nombre AS municipio, MU.pcode AS m_code, DE.pcode AS d_code, DE.nombre AS departamento, PA.acronimo AS p_code, AD.nombre AS admin3, AD.pcode AS a_code, 
                PA.nombre AS pais, B.nombre'.$clang.' AS beneficiario, TO.nombre'.$clang.' AS toficina, W.contacto, W.description, CO.nombre AS coverage'.$sqlwhen.$sqlrecurso.$sqlwhom.$sqlfinanced.$sqltipo.$sqlgender)
                        ->from('AppBundle:Tresw', 'W')
                        ->leftjoin("W.project_id","P")
                        ->leftjoin("W.org_id","O")
                        ->leftjoin("W.actividad_id","A")
                        ->leftjoin("W.sector_id","S")
                        ->leftjoin("W.toficina_id","TO")
                        ->leftjoin("W.subsector_id","SS")
                        ->leftjoin("O.tipo_id","T")
                        ->leftjoin("W.beneficiario_id","B");
                        if(in_array("recurso_id",$campos)){
                                $qb->leftjoin("W.recurso_id","R")->leftjoin("W.unidad_id","U");
                        }
                        if(in_array("tipo_id",$campos)){
                                $qb->leftjoin("W.tipo_id","TI");
                        }
                        if(in_array("dini",$campos)){
                                $qb->leftjoin("W.estado_id","E");
                        }
                        if(in_array("financed",$campos)){
                                $qb->leftjoin("W.financed","FI");
                                $qb->leftjoin("FI.tipo_id","FIO");
                        }
                        $qb->leftjoin("W.coverage_id","CO")
                        ->leftjoin("W.admin3","AD")
                        ->leftjoin("W.municipio","MU")
                        ->leftjoin("W.departamento","DE")
                        ->leftjoin("W.pais_id","PA")
                        ->where("P.id = ".$id)
                        ->orderBy("W.id","DESC");
                $records = $qb->getQuery()->getResult();
                $total = count($records);

        return $this->render('admin/blog/show.html.twig', compact('records','project','campos'));
        }

        /**
     * Deletes a Post entity.
     *
     * @Route("/delete-record/{id}", defaults={"id":"0"}, name="record_delete")
     * @Method("GET")
     *
     * The Security annotation value is an expression (if it evaluates to false,
     * the authorization mechanism will prevent the user accessing this resource).
     */
    public function deleteimgAction($id) {
        
        $entity = $this->getDoctrine()->getRepository('AppBundle:Tresw')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        $response = new Response(json_encode(compact("id")));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
    
   

}