<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Organizacion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
 * @Route("/admin/tables")
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */

class TablesController extends Controller
{
    /**
     * @Route("/orgs", name="get_orgs")
     * @Method("GET")
     */
    public function orgsAction()
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT O.id, O.nombre, O.nombre_en, O.code, T.id AS tipo, O.code_en')
                ->from('AppBundle:Organizacion', 'O')
                ->leftjoin("O.tipo_id","T");
        $records = $qb->getQuery()->getResult();

        $total = count($records);
        $response = new Response(json_encode(compact('records','total')));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

     /**
     * Creates a new Post entity.
     *
     * @Route("/org/update", name="admin_update_org")
     * @Method({"GET", "POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function addAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {

            $data = $request->request->all();
            if (!isset($data['id'])) {
                $entity = new Organizacion();
            } else {
                $entity = $this->getDoctrine()->getRepository('AppBundle:Organizacion')->find($data['id']);
            }
            $entity->setNombre($data['nombre']);
            $entity->SetNombreEn($data['nombre_en']);
            $entity->SetCode($data['code']);
            $entity->SetCodeEn($data['code_en']);
            $entity->SetTipoId($this->getDoctrine()->getRepository('AppBundle:Tipoorg')->find($data['tipo']));
            $em->persist($entity);
            $em->flush();
            $exito = 1;
            $response = new Response(json_encode(compact('exito')));
            $response->headers->set('Content-Type', 'application/json');
            return $response;

        }
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
