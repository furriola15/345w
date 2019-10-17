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
class PresenceController extends Controller {

 
   /**
     * Creates a new Post entity.
     *
     * @Route("/admin-where/{org}/{project}", name="admin_donde_add", defaults={"org":"0", "project":"0"})
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

                $entity = new Donde();

                //$datetime = new \DateTime();
                $entity->setProjectId($this->getDoctrine()->getRepository('AppBundle:Project')->find($data['project']));
                $entity->setOrgId($this->getDoctrine()->getRepository('AppBundle:Organizacion')->find($data['org']));
                $entity->setSectorId($this->getDoctrine()->getRepository('AppBundle:Sector')->find($data['sector']));

                if($data['level'] == "municipio"){
                        $municipio = $this->getDoctrine()->getRepository('AppBundle:Distrito')->find($data['id']);
                        $entity->setMunicipio($municipio);
                        $provincia = $municipio->getProvinciaId();
                        $entity->setDepartamento($this->getDoctrine()->getRepository('AppBundle:Provincia')->find($provincia));
                        $entity->setPaisId($this->getDoctrine()->getRepository('AppBundle:Pais')->find($provincia->getPaisId()));
                }else{
                        $provincia = $this->getDoctrine()->getRepository('AppBundle:Provincia')->find($data['id']);
                        $entity->setDepartamento($provincia);
                        $entity->setPaisId($this->getDoctrine()->getRepository('AppBundle:Pais')->find($provincia->getPaisId()));
                }
                $em->persist($entity);
                $em->flush();
                $id = $entity->getId();
                $response = new Response(json_encode(compact('id')));
                $response->headers->set('Content-Type', 'application/json');

                return $response;

        }else{
                


        }
}


/**
    * @Route("/ajax-admins/{table}/{id}/{lang}/{org}/{project}", name="ajax_admins", defaults={"table":"0","id":"0", "lang":"0", "org":"0", "project":"0"})
    * @Method("GET")
    */
    public function getadminsAction(Request $request, $table, $id, $lang, $org, $project) {
   
        $em = $this->getDoctrine()->getManager();
        $clang = ($lang == "en" ? '_en':'');
        $qb = $em->createQueryBuilder();
        if($table == "admin3"){
              
                $sql_l = "DISTINCT AD.id, CONCAT(P.nombre, ' - ', V.nombre, ' - ', D.nombre, ' - ', AD.nombre) AS text";
                $qb->select($sql_l)->from('AppBundle:Admin3', 'AD');
                $qb->leftjoin("AD.distrito_id","D");
                $qb->leftjoin("D.provincia_id","V");
                $qb->leftjoin("V.pais_id","P");
                $qb->where("(D.nombre LIKE '%$q%') OR (V.nombre  LIKE '%$q%' ) OR (P.nombre_en  LIKE '%$q%' ) OR (P.nombre  LIKE '%$q%' ) OR (AD.nombre  LIKE '%$q%' )  ");
                $qb->orderBy("P.nombre, V.nombre, D.nombre", 'ASC');
        }
        if($table == "admin2"){
                
                $sql_l = "DISTINCT D.id, D.nombre AS nombre";
                $qb->select($sql_l)->from('AppBundle:Distrito', 'D');
                $qb->leftjoin("D.provincia_id","V");
                $qb->leftjoin("V.pais_id","P");
                $qb->where("V.id = ".$id);
                $qb->orderBy("D.nombre", 'ASC');
        }     

        $items = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT S.id')
                ->from('AppBundle:Donde', 'W')
                ->leftjoin("W.project_id","P")
                ->leftjoin("W.sector_id","S")
                ->leftjoin("W.org_id","O")
                ->leftjoin("W.municipio","MU")
                ->leftjoin("W.departamento","DE")
                ->leftjoin("W.pais_id","PA")
                ->where("P.id = ".$project)->andwhere("O.id = ".$org)
                ->andwhere("DE.id = ".$id); 
        $sectoresrel_array = $qb->getQuery()->getResult();

        $sectoresrel = array();
        foreach ($sectoresrel_array as $a => $aitem) {
                $sectoresrel[$a] = $aitem['id'];
        }

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT S.id, S.nombre'.$clang.' AS nombre, S.acronimo')
                ->from('AppBundle:Sector', 'S')
                ->orderBy("S.nombre".$clang,"ASC");
        $sectores = $qb->getQuery()->getResult();

        $html = $this->renderView('admin/blog/admins.html.twig', compact('items','sectores','org','sectoresrel'));
        $response = new Response(json_encode(compact('html')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
   }

   /**
    * @Route("/get-where-org/{q}", name="ajax_get_where_org", defaults={"q":"0"})
    * @Method("GET")
    */
    public function getWhereOrgAction(Request $request) {
        $q = $request->query->get('q');
        
        $org = $request->query->get('org');
        $project = $request->query->get('project');
        $campos = $request->query->get('campos');
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
                $qb->select($sql_l)->from('AppBundle:Donde', 'W');
                $qb->leftjoin("W.municipio","D");
                $qb->leftjoin("W.departamento","V");
                $qb->leftjoin("W.pais_id","P");
                $qb->where("(D.nombre LIKE '%$q%') OR (V.nombre  LIKE '%$q%' ) OR (P.nombre_en  LIKE '%$q%' ) OR (P.nombre  LIKE '%$q%' ) ");
                $qb->orderBy("P.nombre, V.nombre, D.nombre", 'ASC');
        }elseif(in_array("departamento",$campos)){
                $sql_l = "DISTINCT V.id, CONCAT(P.nombre, ' - ', V.nombre) AS text ";
                $qb->select($sql_l)->from('AppBundle:Provincia', 'V');
                $qb->leftjoin("V.pais_id","P");
                $qb->where("(V.nombre  LIKE '%$q%' ) OR (P.nombre_en  LIKE '%$q%' ) OR (P.nombre  LIKE '%$q%' ) ");
                $qb->orderBy("P.nombre, V.nombre", 'ASC');
        }     
        $qb->leftjoin("W.org_id","O")->leftjoin("W.project_id","PR");  
        $qb->andwhere("O.id = ".$org)->andwhere("PR.id = ".$project);
        $items = $qb->getQuery()->getResult();
 
        $total_count = count($items);
        $response = new Response(json_encode(compact('items', 'total_count', 'q','paises','search')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
   }
   
        /**
     * Deletes a Post entity.
     *
     * @Route("/where-record", name="where_delete")
     * @Method("POST")
     *
     * The Security annotation value is an expression (if it evaluates to false,
     * the authorization mechanism will prevent the user accessing this resource).
     */
    public function deletewhereAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        if ($request->getMethod() == 'POST') {
                $edita = 1;
                $data = $request->request->all();
                $entity = $this->getDoctrine()->getRepository('AppBundle:Donde')->findOneBy(array('project_id' => $data['project'], 'org_id' => $data['org'], 'sector_id' => $data['sector'], 'municipio' => $data['id']));
                $em->remove($entity);
                $em->flush();
                $id = $entity->getId();
                $response = new Response(json_encode(compact("id")));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
        }

    }

      
   

}