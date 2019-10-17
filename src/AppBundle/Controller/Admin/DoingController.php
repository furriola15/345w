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
use AppBundle\Entity\Donde;
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
 * @Route("/admin-presence")
 * @Security("has_role('ROLE_ADMIN')")
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class DoingController extends Controller {

 
     /**
     *
     * @Route("/listado/by-org/{project}/{lang}/{org}/{campos}", name="get_list_activities", defaults={"project":0, "lang":"0", "org":"0", "campos":"0"})
     * @Method("GET")
     */
    public function activitiesListAction($project, $lang, $org, $campos) {
        $em = $this->getDoctrine()->getManager();
        $location = 0;
        $clang = ($lang == "en" ? '_en':'');
        $campos = explode("|", $campos); 
        
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
        $qb->select('DISTINCT A.id, A.nombre'.$clang.' AS actividad')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.actividad_id","A")
                ->leftjoin("W.sector_id","S")
                ->leftjoin("W.municipio","MU")
                ->leftjoin("W.departamento","DE")
                ->where("P.id = ".$project)
                ->andwhere("O.id = ".$org)
                ->orderBy("A.nombre".$clang,"ASC");
        $records = $qb->getQuery()->getResult();
        foreach ($records as $key => $item) {
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT W.id, S.nombre'.$clang.' AS sector, W.fecha_modifica, MU.nombre AS municipio, DE.nombre AS departamento, W.detalle, 
                B.nombre'.$clang.' AS beneficiario, TO.nombre'.$clang.' AS toficina, CO.nombre AS coverage'.$sqlwhen.$sqlrecurso.$sqlwhom.$sqlfinanced.$sqltipo.$sqlgender)
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
                        ->andwhere("A.id = ".$item['id']);
                        if(in_array("municipio",$campos)){
                                $qb->orderBy("DE.nombre, MU.nombre","ASC");
                        }else{
                                $qb->orderBy("DE.nombre","ASC");
                        }
                $places = $qb->getQuery()->getResult();
                $records[$key]['places'] = $places;
        }


        $total = count($records);

        $html = $this->renderView('admin/blog/activities.html.twig', compact('records','campos','project','record'));

        $response = new Response(json_encode(compact('html','project','org', 'record','total')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
    
   

}