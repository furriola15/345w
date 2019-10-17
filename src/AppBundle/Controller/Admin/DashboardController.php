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
 * @Route("/admin/dashboard")
 * @Security("has_role('ROLE_ADMIN')")
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class DashboardController extends Controller {
    /**
     *
     * @Route("/table-caribbean/{project}/{lang}/{country}", name="get_table_caribbean", defaults={"project":0, "lang":"0", "country":"0"})
     * @Method("GET")
     */
    public function OrgListAction($project, $lang, $country) {
        $em = $this->getDoctrine()->getManager();
        $clang = ($lang == "en" ? '_en':'');
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT W.id, O.id AS oid, O.code'.$clang.' AS code, O.nombre'.$clang.' AS nombre, S.nombre'.$clang.' AS sname, T.id AS tipo_id, S.id AS sector, TO.id AS oficina, PA.id AS pais')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.sector_id","S")
                ->leftjoin("W.coverage_id","C")
                ->leftjoin("O.tipo_id","T")
                ->leftjoin("W.toficina_id","TO")
                ->leftjoin("W.pais_id","PA")
                ->where("P.id = ".$project)
                ->andwhere("PA.id = ".$country)
                ->andwhere("C.id IS NULL")
                ->orderBy('O.code'.$clang,"ASC");
        $records = $qb->getQuery()->getResult();     

        $response = new Response(json_encode(compact('records')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     *
     * @Route("/table-sectors-orgs/{project}/{lang}/{country}/{org}", name="get_table_sectors", defaults={"project":0, "lang":"0", "country":"0", "org":"0"})
     * @Method("GET")
     */
    public function OrgListSectorsAction($project, $lang, $country, $org) {
        $em = $this->getDoctrine()->getManager();
        $cond_o = "1=1";
        if($org != 0){
            $cond_o = "O.id = ".$org;
        }
        $clang = ($lang == "en" ? '_en':'');
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT O.id, O.id AS oid, O.code'.$clang.' AS code, O.nombre'.$clang.' AS nombre, S.nombre'.$clang.' AS sname, T.id AS tipo_id, S.id AS sector, DE.id AS pais')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.sector_id","S")
                ->leftjoin("O.tipo_id","T")
                ->leftjoin("W.departamento","DE")
                ->where("P.id = ".$project)
                ->andwhere("DE.id = ".$country)
                ->andwhere($cond_o)
                ->orderBy('O.code'.$clang,"ASC");
        $records = $qb->getQuery()->getResult();     

        $response = new Response(json_encode(compact('records')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     *
     * @Route("/resource-chart/{project}/{lang}/{org}", name="resource_chart", defaults={"project":0, "lang":"0", "org":"0"})
     * @Method("GET")
     */
    public function resourceChartAction($project, $lang, $org) {
        $em = $this->getDoctrine()->getManager();
        $cond_o = "1=1";
        if($org != 0){
            $cond_o = "O.id = ".$org;
        }
        $clang = ($lang == "en" ? '_en':'');

        $qb = $em->createQueryBuilder();
        $qb->select('SUM(W.cantidad) AS total, E.id, U.nombre'.$land.' AS nombre')
                ->from('AppBundle:Tresw', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.unidad_id","U")
                ->leftjoin("W.org_id","O")
                ->where("P.id = ".$project)->andwhere($cond_o)
                ->groupBy("E.nombre".$land)->orderBy("total","DESC");
        $records = $qb->getQuery()->getResult(); 

        return $this->render('admin/blog/chart_recurso.html.twig', compact('records'));

        

    }

    
    
   

}