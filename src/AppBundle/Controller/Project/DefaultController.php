<?php

namespace AppBundle\Controller\Project;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Project;
use AppBundle\Entity\Organizacion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Controller used to manage blog contents in the backend.
 *
 * Please note that the application backend is developed manually for learning
 * purposes. However, in your real Symfony application you should use any of the
 * existing bundles that let you generate ready-to-use backends without effort.
 *
 * See http://knpbundles.com/keyword/admin
 *
 * @Route("/admin/projects")
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */

class DefaultController extends Controller
{
    /**
     * @Route("/list", name="get_projects")
     * @Method("GET")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $pros = $this->get('security.token_storage')->getToken()->getUser()->getProjects();
        $pros = json_decode($pros, true);
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT P.id, P.nombre, P.emergency, P.detalle, P.lang, P.pbi')
                ->from('AppBundle:Project', 'P')
                ->Where('P.id IN (:pros)')
                ->setParameter('pros', $pros);
        $records = $qb->getQuery()->getResult();
        
        return $this->render('admin/project/index.html.twig', compact('records'));

    }

     /**
     * Creates a new Post entity.
     *
     * @Route("/update/{id}", name="admin_update_project" , defaults={"id":"0"})
     * @Security("has_role('ADMIN_WWW')")
     * @Method({"GET", "POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function addAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {

            $data = $request->request->all();
            if ($data['id'] == 0) {
                $entity = new Project();
            } else {
                $entity = $this->getDoctrine()->getRepository('AppBundle:Project')->find($data['id']);
            }
            $entity->setNombre($data['nombre']);
            $entity->setDetalle($data['detalle']);
            $entity->setLang($data['lang']);
            $entity->SetEmergency($data['emergency']);
            $entity->SetPbi($data['pbi']);
            $entity->setPaises(json_encode($data['countries']));
            if(isset($data['activities'])){
                $entity->SetActivities(json_encode($data['activities']));
            }
            $fields = array();
            foreach ($data['campos'] as $key => $item) {
                $fields[$key]['field'] = $item;
                $fields[$key]['requiere'] = (in_array($item, $data['requires']) ? 'require':'') ;  
            }

            $entity->setCampos(json_encode($fields));

            $em->persist($entity);
            $em->flush();
            return $this->redirectToRoute('get_projects');

        }
        $project = array();
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT T.id, T.nombre, T.nombre_en, SS.nombre AS ssector, SS.id AS id_ssector, S.nombre AS sector, S.id AS id_sector')
                ->from('AppBundle:Actividad', 'T');
                $qb->leftjoin("T.ssector","SS")->leftjoin("T.sector","S")->leftjoin("T.project_id","P")
;
        $activities = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT P.id, P.nombre')
                ->from('AppBundle:Pais', 'P');
        $countries = $qb->getQuery()->getResult();

        $fields = array();
        $requs = array();
        if($id != 0){

            $qb = $em->createQueryBuilder();
            $qb->select('DISTINCT P.id, P.nombre, P.emergency, P.detalle, P.campos, P.activities, P.paises, P.lang, P.pbi')
                    ->from('AppBundle:Project', 'P')
                    ->where("P.id = ".$id);
            $project = $qb->getQuery()->setMaxResults(1)->getSingleResult();

            $project['paises'] = json_decode($project['paises'], true);
            //$project['activities'] = json_decode($project['activities'], true);
            $project['campos'] = json_decode($project['campos'], true);

            
            foreach ($project['campos'] as $key => $value) {
                $fields[] = $value['field'];
            }
            foreach ($project['campos'] as $key => $value) {
                if($value['requiere'] == "require"){ 
                    $requs[$key] = $value['field'];
                }
            }
        }else{
            $project = array("id"=>0, "detalle"=>'', "nombre"=>'', "emergency"=>'',"lang"=>'es', "campos"=>array(), "activities"=>array(), "paises"=>array(), 'pbi'=>'');
        }
        return $this->render('admin/project/form.html.twig', compact('project','activities','id','countries','fields','requs','tipo'));
    }


/**
     * Creates a new Post entity.
     *
     * @Route("/item-delete", name="admin_delete_item")
     * @Method({"POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function deleteItemAction()
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {

            $data = $request->request->all();
            $guest = $em->getRepository('AppBundle:'.$data['table'])->find($data['id']);

            if (!$guest) {
                throw $this->createNotFoundException('No guest found for id '.$data['id']);
            }

            //$em->remove($guest);
            //$em->flush();

            $exito = 1;
            $response = new Response(json_encode(compact('exito')));
            $response->headers->set('Content-Type', 'application/json');
            return $response;

        }
    }

}
