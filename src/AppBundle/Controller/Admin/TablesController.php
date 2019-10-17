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

use AppBundle\Entity\Tipoorg;
use AppBundle\Entity\Unidad;
use AppBundle\Entity\Estado;
use AppBundle\Entity\User;
use AppBundle\Entity\Tactividad;
use AppBundle\Entity\Sector;
use AppBundle\Entity\Toficina;
use AppBundle\Entity\Beneficiario;
use AppBundle\Entity\Recurso;
use AppBundle\Entity\Subsector;
use AppBundle\Entity\Actividad;
use AppBundle\Entity\Provincia;
use AppBundle\Entity\Distrito;
use AppBundle\Utils\Slugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
 * @Route("/admin/tables")
 * @Security("has_role('ADMIN_WWW')")
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class TablesController extends Controller {

    /**
     * Lists all Post entities.
     *
     * This controller responds to two different routes with the same URL:
     *   * 'admin_post_index' is the route with a name that follows the same
     *     structure as the rest of the controllers of this class.
     *   * 'admin_index' is a nice shortcut to the backend homepage. This allows
     *     to create simpler links in the templates. Moreover, in the future we
     *     could move this annotation to any other controller while maintaining
     *     the route name and therefore, without breaking any existing link.
     *
     * @Route("/admin-orgs", name="admin_orgs")
     * @Route("/admin-orgs", name="admin_post_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT T.id, T.nombre')
                ->from('AppBundle:Tipoorg', 'T')
                ->orderBy('T.nombre', "ASC");
        $tipos = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT O.id, O.nombre, O.nombre_en, O.code, T.id AS tipo, O.code_en, T.id AS tipo_id')
                ->from('AppBundle:Organizacion', 'O')
                ->leftjoin("O.tipo_id","T");
        $orgs = $qb->getQuery()->getResult();

        return $this->render('admin/tables/orgs.html.twig', compact('tipos', 'cat','orgs'));
    }

    /**
     * @Route("/tables/{tname}/{table}", name="get_tables",  defaults={"tname":"0", "table":"0"})
     * @Method("GET")
     */
    public function tablesAction($tname, $table)
    {

        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT T.id, T.nombre, T.nombre_en'.($table == "Subsector" || $table == "Actividad" ? ', R.nombre AS tipo, R.id AS id_sector':'').( $table == "Actividad" ? ', SS.nombre AS ssector, S.nombre AS sector':''))
                ->from('AppBundle:'.$table, 'T');
                if($table == "Subsector"){ $qb->leftjoin("T.sector_id","R");  }  
                if($table == "Actividad"){ $qb->leftjoin("T.tactividad_id","R")->leftjoin("T.ssector","SS")->leftjoin("SS.sector_id","S");  }  
        $records = $qb->getQuery()->getResult();
        
        
        return $this->render('admin/tables/tables.html.twig', compact('records','tname','table'));

    }

    /**
     * @Route("/admin-activities/{project}", name="admin_activities",  defaults={"project":"0"})
     * @Method("GET")
     */
    public function adminActivitiesAction($project)
    {

        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT T.id, T.nombre, T.nombre_en, SS.nombre AS ssector, SS.id AS id_ssector, S.nombre AS sector, S.id AS id_sector')
                ->from('AppBundle:Actividad', 'T');
                $qb->leftjoin("T.ssector","SS")->leftjoin("T.sector","S")->leftjoin("T.project_id","P")
                ->where("P.id = ".$project);
        $records = $qb->getQuery()->getResult();
        
        
        return $this->render('admin/tables/actividad.html.twig', compact('records','project'));

    }

    /**
     * @Route("/table-location/{related}/{table}/{tname}", name="admin_location",  defaults={"related":"0", "table":"0", "tname":"0"})
     * @Method("GET")
     */
    public function locationsAction($related, $table, $tname)
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        if($table == "Provincia"){
            $sql = "DISTINCT T.id, T.nombre, T.pcode, T.iso2, R.nombre AS tipo, R.id AS rid, R.nombre AS country, R.id AS pid";
            $paisid = $related;
        }else{
            $sql = "DISTINCT T.id, T.nombre, T.pcode, T.iso2, R.nombre AS tipo, R.id AS rid, P.nombre AS country, P.id AS pid";
            $prov = $this->getDoctrine()->getRepository('AppBundle:Provincia')->find($related);
            $paisid = $prov->getPaisId()->getId();
        }
        $qb->select($sql)
                ->from('AppBundle:'.$table, 'T');
                if($table == "Provincia"){ $qb->leftjoin("T.pais_id","R"); $qb->where("R.id = ".$related);   }  
                if($table == "Distrito"){ $qb->leftjoin("T.provincia_id","R"); $qb->leftjoin("R.pais_id","P"); $qb->where("R.id = ".$related);   } 
                
        $records = $qb->getQuery()->getResult();
        
        
        return $this->render('admin/tables/locations.html.twig', compact('records','related','table','tname','paisid'));

    }
    
    /**
     * @Route("/personas/{id}", defaults={"id": "0"}, name="personas")
     * @Method("GET")
     */
    
    public function personasAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT C.id, C.nombre, C.apellido, C.fecha, C.hora, C.mail, C.telefono')
                ->from('AppBundle:Cita', 'C')
                ->leftjoin("C.post_id","P")->where("P.id = ".$id)
                ->orderBy('C.id', "DESC")
                ;
        $posts = $qb->getQuery()->getResult();
        

        return $this->render('admin/blog/personas.html.twig', compact('posts'));
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin-table/{id}/{tname}/{table}", name="admin_table", defaults={"id":"0", "tname":"0", "table":"0"})
     * @Method({"GET", "POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function addAction(Request $request,  $id, $tname, $table) {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {
            $edita = 0;
            
            $data = $request->request->all();
            $table = $data['table'];
            if ($data['id'] == 0) {
                if($table == "Tipoorg"){
                    $entity = new Tipoorg();
                }elseif($table == "Toficina") { $entity = new Toficina(); }
                elseif($table == "Sector") { $entity = new Sector(); }
                elseif($table == "Recurso") { $entity = new Recurso(); }
                elseif($table == "Unidad") { $entity = new Unidad(); }
                elseif($table == "Tactividad") { $entity = new Tactividad(); }
                elseif($table == "Estado") { $entity = new Estado(); }
                elseif($table == "Subsector") { $entity = new Subsector(); }
                elseif($table == "Actividad") { $entity = new Actividad(); }
                elseif($table == "Beneficiario") { $entity = new Beneficiario(); }
                elseif($table == "Pais") { $entity = new Pais(); }
            } else {
                $entity = $this->getDoctrine()->getRepository('AppBundle:'.$table)->find($data['id']);
                $edita = 1;
            }

           
            $entity->SetNombre($data['nombre']);
            $entity->setNombreEn($data['nombre_en']);
            if($table == "Subsector"){
                $related = $this->getDoctrine()->getRepository('AppBundle:Sector')->find($data['related']);
                $entity->setSectorId($related);
            }
            if($table == "Actividad"){
                $related = $this->getDoctrine()->getRepository('AppBundle:Tactividad')->find($data['related']);
                $subsector = $this->getDoctrine()->getRepository('AppBundle:Subsector')->find($data['ssector_id']);
                $entity->setTactividadId($related);
                $entity->setSsector($subsector);
                $entity->setSector($subsector->getSectorId());
            }
            $em->persist($entity);
            $em->flush();

            $id = $entity->getId();

            $html = "
            <td>".$id."</td>";
            if($table == "Actividad"){
                $html = $html."<td>".$entity->getSector()->getNombre()."</td>";
                $html = $html."<td>".$entity->getSsector()->getNombre()."</td>";
            }
            if($table != "Pais" && $table != "Tipoorg" && $table != "Toficina" && $table != "Sector" && $table != "Recurso" && $table != "Unidad" && $table != "Tactividad" && $table != "Estado" && $table != "Beneficiario"){ 
                $html = $html."<td>".$related->getNombreEn()."</td>";
            }
            $html = $html."<td>".$data['nombre']."</td>
            <td>".$data['nombre_en']."</td>
            <td align='center' width='50'>
                <a class='btn btn-primary btn-icon-split btn-sm edit-record' href='#' data-id='".$id."'>
                    <span class='text'>
                        <i class='fas fa-pencil-alt'></i>
                    </span>
                </a>
            </td>";

            $response = new Response(json_encode(compact('edita','id','html')));
            $response->headers->set('Content-Type', 'application/json');

            return $response;

        } else {
            $tipos = array();
            $sectores = array();
            if($table == "Subsector"){
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT T.id, T.nombre')
                        ->from('AppBundle:Sector', 'T')
                        ->orderBy('T.nombre_en', "ASC");
                $tipos = $qb->getQuery()->getResult();
            }
            if($table == "Actividad"){
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT T.id, T.nombre')
                        ->from('AppBundle:Tactividad', 'T')
                        ->orderBy('T.nombre', "ASC");
                $tipos = $qb->getQuery()->getResult();

                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT T.id, T.nombre, S.nombre AS sector')
                        ->from('AppBundle:Subsector', 'T')
                        ->leftjoin("T.sector_id","S")
                        ->orderBy('sector, T.nombre', "ASC");
                $sectores = $qb->getQuery()->getResult();
            }
            if ($id != 0) {
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT T.id, T.nombre, T.nombre_en'.($table == "Subsector" || $table == "Actividad" ? ', R.id AS rid':'').($table == "Actividad" ? ', SS.id AS ssid':''))
                        ->from('AppBundle:'.$table, 'T');
                        if($table == "Subsector"){ $qb->leftjoin("T.sector_id","R");  }
                        if($table == "Actividad"){ $qb->leftjoin("T.tactividad_id","R"); $qb->leftjoin("T.ssector","SS");  }
                        $qb->where("T.id = ".$id)
                        ->orderBy('T.nombre', "DESC");
                $record = $qb->getQuery()->setMaxResults(1)->getSingleResult();

                
                
            } else {

                $record = array('id' => 0, 'nombre' => '', 'nombre_en' => '', 'rid'=> 0, 'ssid'=> 0);
            }
            
            $html = $this->renderView('admin/tables/form.html.twig', compact('record','table','tname','tipos','sectores'));
            $response = new Response(json_encode(compact('html')));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin-table-activity/{id}/{project}/{tipo}", name="admin_table_activity", defaults={"id":"0", "project":"0", "tipo":"0"})
     * @Method({"GET", "POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function addActivityAction(Request $request,  $id, $project, $tipo) {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {
            $edita = 0;
            
            $data = $request->request->all();
   
            if ($data['id'] == 0) {
                $entity = new Actividad(); 
            } else {
                $entity = $this->getDoctrine()->getRepository('AppBundle:Actividad')->find($data['id']);
                $edita = 1;
            }

            $entity->SetNombre($data['nombre']);
            $entity->setNombreEn($data['nombre_en']);
            //$entity->setProjectId($this->getDoctrine()->getRepository('AppBundle:Project')->find($data['project_id']));
            //$related = $this->getDoctrine()->getRepository('AppBundle:Tactividad')->find($data['related']);
            if($data['tipo'] == "sector"){
                $entity->setSector($this->getDoctrine()->getRepository('AppBundle:Sector')->find($data['ssector_id']));
            }else{
                $subsector = $this->getDoctrine()->getRepository('AppBundle:Subsector')->find($data['ssector_id']);
                //$entity->setTactividadId($related);
                $entity->setSsector($subsector);
                $entity->setSector($subsector->getSectorId());
            }
            
            $em->persist($entity);
            $em->flush();

            $id = $entity->getId();
            $html = "";
            $html = $html."<td>".$entity->getSector()->getNombre()."</td>";
            if($data['tipo'] != "sector"){
                $html = $html."<td>".$entity->getSsector()->getNombre()."</td>";
            }
            $html = $html."<td>".$id."</td>";
            $html = $html."<td>".$data['nombre']."</td>
            <td>".$data['nombre_en']."</td><td>".$data['nombre_en']."</td>";
            $admint = "<td align='center' width='50'>
            <a class='btn btn-danger btn-icon-split btn-sm delete-record' onclick='javascript:return false;' href='#' data-id='".$id."'>
                                <span class='text'>
                                    <i class='fas fa-times'></i>
                                </span>
                            </a>
                <a class='btn btn-primary btn-icon-split btn-sm edit-record' href='#' onclick='javascript:return false;' data-id='".$id."'>
                    <span class='text'>
                        <i class='fas fa-pencil-alt'></i>
                    </span>
                </a>
            </td>";

            $response = new Response(json_encode(compact('edita','id','html')));
            $response->headers->set('Content-Type', 'application/json');

            return $response;

        } else {
            $tipos = array();
            $sectores = array();
 
            $qb = $em->createQueryBuilder();
            $qb->select('DISTINCT T.id, T.nombre')
                ->from('AppBundle:Project', 'T')
                ->orderBy('T.nombre', "ASC");
            $projects = $qb->getQuery()->getResult();

            $qb = $em->createQueryBuilder();
            if($tipo == "sector"){
                $qb->select('DISTINCT S.id, S.nombre, S.nombre AS sector')
                ->from('AppBundle:Sector', 'S')
                ->orderBy('S.nombre', "ASC");
            }else{
                $qb->select('DISTINCT T.id, T.nombre, S.nombre AS sector')
                ->from('AppBundle:Subsector', 'T')
                ->leftjoin("T.sector_id","S")
                ->orderBy('sector, T.nombre', "ASC");
            }

            $sectores = $qb->getQuery()->getResult();

            if ($id != 0) {
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT T.id, T.nombre, T.nombre_en, SS.nombre AS ssector, SS.id AS ssid, S.nombre AS sector, S.id AS id_sector, P.id AS project_id,'.
                ($tipo == 'sector' ? 'S.id AS related':'SS.id AS related'))
                ->from('AppBundle:Actividad', 'T');
                $qb->leftjoin("T.ssector","SS")->leftjoin("T.sector","S")->leftjoin("T.project_id","P")
                ->where("T.id = ".$id);
                $record = $qb->getQuery()->setMaxResults(1)->getSingleResult();
                
            } else {
                $record = array('id' => 0, 'nombre' => '', 'nombre_en' => '', 'rid'=> 0, 'ssid'=> 0, 'project_id'=> $project, 'related'=>'');
            }
            
            $html = $this->renderView('admin/tables/formactivity.html.twig', compact('record','projects','sectores','project','tipo'));
            $response = new Response(json_encode(compact('html')));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin-table-location/{id}/{tname}/{table}/{related}", name="admin_location_add", defaults={"id":"0", "tname":"0", "table":"0", "related":"0"})
     * @Method({"GET", "POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function addLocationAction(Request $request,  $id, $tname, $table, $related) {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {
            $edita = 0;
            
            $data = $request->request->all();
            $table = $data['table'];
            if ($data['id'] == 0) {
                if($table == "Provincia"){
                    $entity = new Provincia();
                }elseif($table == "Distrito") { $entity = new Distrito(); }
            } else {
                $entity = $this->getDoctrine()->getRepository('AppBundle:'.$table)->find($data['id']);
                $edita = 1;
            }
           
            $entity->SetNombre($data['nombre']);
            $entity->setPcode($data['pcode']);
            $entity->setIso2($data['iso2']);
            if($table == "Provincia"){
                $related = $this->getDoctrine()->getRepository('AppBundle:Pais')->find($data['related']);
                //$entity->setPaisId($related);
            }
            
            if($table == "Distrito"){
                $related = $this->getDoctrine()->getRepository('AppBundle:Provincia')->find($data['related_id']);
                $entity->setProvinciaId($related);
             
                $paisr = $related->getPaisId();
            }

            $em->persist($entity);
            $em->flush();

            $id = $entity->getId();

            $html = "
            <td>".$id."</td>";
            
            if($table == "Distrito"){
                $html = $html."<td>".$paisr->getNombre()."</td>";
                $html = $html."<td>".$related->getNombre()."</td>";
            }else{
                $html = $html."<td>".$related->getNombre()."</td>";
            }
            $html = $html."<td>".$data['nombre']."</td>
            <td>".$data['pcode']."</td>";
            $html = $html."<td>".$data['iso2']."</td>";
            if($table == "Provincia"){
                $html = $html."<td align='center' width='50'>
                <a class='btn btn-primary btn-icon-split btn-sm edit-record' data-related='".$related->getId()."' href='#' data-id='".$id."'>
                    <span class='text'>
                        <i class='fas fa-pencil-alt'></i>
                    </span>
                </a>
                <a class='btn btn-success btn-icon-split btn-sm' href='".$this->generateUrl('admin_location', array('related' => $id, 'table'=> 'Distrito', 'tname' => 'ADMIN 2' ))."'>
                    <span class='text'>
                        <i class='fas fa-globe'></i>
                    </span>
                </a> ";
            }else{
                $html = $html."<td align='center' width='50'>
                <a class='btn btn-primary btn-icon-split btn-sm edit-record' data-related='".$paisr->getId()."' href='#' data-id='".$id."'>
                    <span class='text'>
                        <i class='fas fa-pencil-alt'></i>
                    </span>
                </a>";
            }
            $html = $html."</td>";

            $response = new Response(json_encode(compact('edita','id','html')));
            $response->headers->set('Content-Type', 'application/json');

            return $response;

        } else {
            $tipos = array();
            if($table == "Provincia"){
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT T.id, T.nombre')
                        ->from('AppBundle:Pais', 'T')
                        ->where("T.id = ".$related)
                        ->orderBy('T.nombre', "ASC");
                $tipos = $qb->getQuery()->getResult();
            }
            if($table == "Distrito"){
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT P.id')
                        ->from('AppBundle:Provincia', 'T')
                        ->leftjoin("T.pais_id","P")
                        ->where("T.id = ".$related);
                $padre = $qb->getQuery()->setMaxResults(1)->getSingleScalarResult();

                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT T.id, T.nombre')
                        ->from('AppBundle:Provincia', 'T')
                        ->leftjoin("T.pais_id","P")
                        ->where("P.id = ".$padre)
                        ->orderBy('T.nombre', "ASC");
                $tipos = $qb->getQuery()->getResult();

            }
            if ($id != 0) {
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT T.id, T.nombre, T.pcode, R.id AS rid, T.iso2')
                        ->from('AppBundle:'.$table, 'T');
                        if($table == "Provincia"){ $qb->leftjoin("T.pais_id","R");  }
                        if($table == "Distrito"){ $qb->leftjoin("T.provincia_id","R");  }
                        $qb->where("T.id = ".$id)
                        ->orderBy('T.nombre', "DESC");
                $record = $qb->getQuery()->setMaxResults(1)->getSingleResult();

                
                
            } else {

                $record = array('id' => 0, 'nombre' => '', 'pcode' => '', 'rid'=> 0, 'iso2'=>'');
            }
            
            $html = $this->renderView('admin/tables/forml.html.twig', compact('record','table','tname','tipos','related'));
            $response = new Response(json_encode(compact('html')));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        //$post->setSlug($slugger->slugify($post->getTitle()));
        // return $this->redirectToRoute('admin_post_index');
    }


    
    /**
     * @Route("/upload-item", name="upload_item")
     * @Method("POST")
     */
    public function uploadItemAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $fileUploader = new FileUploader($this->getParameter('items_directory'));
        $data = $request->request->all();
        $file = $request->files->get('file');

        $entity = new \AppBundle\Entity\Imagen();
        $entity->setRelated($this->getDoctrine()->getRepository('AppBundle:Post')->find($data['postid']));
        $entity->setPeso($file->getClientSize());
        $entity->setTipo($file->guessExtension());
        $fileName = $fileUploader->upload($file);
        $entity->setTitle($fileName);
        $em->persist($entity);
        $em->flush();


        $em->flush();
        $id = $entity->getId();

        $response = new Response(json_encode(compact("fileName","id")));
        $response->headers->set('Content-Type', 'application/json');
    }

   
    /**
     * Deletes a Post entity.
     *
     * @Route("/deleteim/{id}", defaults={"id":"0"}, name="img_delete")
     * @Method("GET")
     *
     * The Security annotation value is an expression (if it evaluates to false,
     * the authorization mechanism will prevent the user accessing this resource).
     */
    public function deleteimgAction($id) {
        
        $entity = $this->getDoctrine()->getRepository('AppBundle:Imagen')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        $response = new Response(json_encode(compact("id")));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * Deletes a Post entity.
     *
     * @Route("/deleteactivity/{id}", defaults={"id":"0"}, name="activity_delete")
     * @Method("GET")
     *
     * The Security annotation value is an expression (if it evaluates to false,
     * the authorization mechanism will prevent the user accessing this resource).
     */
    public function deleteActivityAction($id) {
        
        $entity = $this->getDoctrine()->getRepository('AppBundle:Actividad')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        $response = new Response(json_encode(compact("id")));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * @Route("/user-list", name="get_user_list")
     * @Security("has_role('ADMIN_USER')")
     * @Method("GET")
     */
    public function userListAction()
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT U.id, U.fullName, U.username, U.email, U.password, O.code AS org_name')
                        ->from('AppBundle:User', 'U')
                        ->leftjoin("U.org","O");
        $records = $qb->getQuery()->getResult();
        
        return $this->render('admin/user/index.html.twig', compact('records'));

    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/add-user/{id}", name="add_user", defaults={"id":"0"})
     * @Method({"GET", "POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function addUserAction(Request $request, UserPasswordEncoderInterface $encoder, $id) {
        $em = $this->getDoctrine()->getManager();
        $roles = array("ROLE_ADMIN","ADMIN_TABLES","ADMIN_WWW","ADMIN_USER");
        if ($request->getMethod() == 'POST') {
            $edita = 0;
            $data = $request->request->all();
            if ($data['id'] == 0) {
                $entity = new User();
                $encoded = $encoder->encodePassword($entity, $data['password']);
                $entity->setPassword($encoded);



            } else {
                $entity = $this->getDoctrine()->getRepository('AppBundle:User')->find($data['id']);
                $edita = 1;
            }

            $entity->setFullName($data['fullName']);
            $entity->setUsername($data['username']);
            $entity->setEmail($data['email']);
            $entity->setOrg($this->getDoctrine()->getRepository('AppBundle:Organizacion')->find($data['org_id']));
            $entity->setRoles($data['roles']);
            $entity->setProjects(json_encode($data['projects']));
            
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('get_user_list');

        }else{

            $qb = $em->createQueryBuilder();
            $qb->select('DISTINCT P.id, P.nombre, P.emergency')
                    ->from('AppBundle:Project', 'P');
            $projects = $qb->getQuery()->getResult();

            if($id != 0){
                $qb = $em->createQueryBuilder();
                $qb->select('DISTINCT U.id, U.fullName, U.username, U.email, U.password, O.id AS org_id, U.roles, O.code AS org_code, O.nombre AS org_name, U.projects')
                        ->from('AppBundle:User', 'U')
                        ->leftjoin("U.org","O")
                        ->where("U.id = ".$id);
                $record = $qb->getQuery()->setMaxResults(1)->getSingleResult();
                
                $roles_user = $record['roles'];
                $pro_user = $record['projects'];

            }else{
                $roles_user = array();
                $pro_user = array();
                $record = array('id'=>0, 'username'=>'', 'email'=>'', 'fullName'=>'', 'org_name'=>'','org_code'=>'','password'=>'');
            }

            return $this->render('admin/user/form.html.twig', compact('record','roles_user','roles','projects','pro_user'));


        }


       

    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/add-user-reset/{id}", name="add_user_reset", defaults={"id":"0"})
     * @Method({"POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function addUserResetAction(Request $request, UserPasswordEncoderInterface $encoder, $id) {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {
            $edita = 0;
            $data = $request->request->all();

            $entity = $this->getDoctrine()->getRepository('AppBundle:User')->find($data['id']);
            $encoded = $encoder->encodePassword($entity, $data['newPassword']);
            $entity->setPassword($encoded);

            $em->persist($entity);
            $em->flush();
            $exito = 1;
            $response = new Response(json_encode(compact("id","exito")));
            $response->headers->set('Content-Type', 'application/json');

            return $response;

        }
    }
}
