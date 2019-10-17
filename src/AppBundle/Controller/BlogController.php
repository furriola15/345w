<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Events;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \DateTime;

/**
 * Controller used to manage blog contents in the public part of the site.
 *
 * @Route("/")
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class BlogController extends Controller
{

    /**
     * @Route("/preguntas", name="blog_preguntas")
     * @Method("GET")
     */
    public function preguntasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = array();
        return $this->render('blog/preguntas.html.twig', compact('posts'));
    }


    /**
     * @Route("/", name="blog_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT C.id, C.title, C.slug, CA.title AS categoria, C.summary, C.content')
                ->from('AppBundle:Post', 'C')
                ->leftjoin("C.cat_id", "CA")
                ->where("CA.id = 3")
                ->orderBy('C.id', "DESC");
        $posts = $qb->getQuery()->getResult();
        
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT C.id, C.title, C.slug, CA.title AS categoria, C.summary, C.dini, C.content, C.publishedAt')
                ->from('AppBundle:Post', 'C')
                ->leftjoin("C.cat_id", "CA")
                ->where("CA.id = 1")
                ->orderBy('C.dini', "DESC");
        $talleres = $qb->getQuery()->setMaxResults(4)->getResult();
        foreach ($talleres as $key => $value) {
            $qb = $em->createQueryBuilder();
            $qb->select('DISTINCT R.id, R.title')
                    ->from('AppBundle:Imagen', 'R')
                    ->leftjoin("R.related", "I")
                    ->where("I.id = ".$value['id'])
                    ->orderBy('R.id', "ASC");
            $imgs = $qb->getQuery()->setMaxResults(1)->getSingleResult();
            $talleres[$key]['imagen'] =  (count($imgs) > 0 ? $imgs['title']:'nophotoc.png' );
            
        }
        
        
        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('blog/index.html.twig', compact('posts','talleres'));
    }
    
    /**
     * @Route("/nosotros", name="about")
     * @Method("GET")
     */
    public function aboutAction()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = array();

        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('blog/about.html.twig', ['posts' => $posts]);
    }
    
    
    /**
     * @Route("/service/{id}", name="service", defaults={"id":"3"})
     * @Method("GET")
     */
    public function servicesAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT C.id, C.title, C.content')
                ->from('AppBundle:Categoria', 'C')
                ->where("C.id = ".$id);
        $post = $qb->getQuery()->setMaxResults(1)->getSingleResult();
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT C.id, C.title, C.slug, CA.title AS categoria, C.summary, C.content')
                ->from('AppBundle:Post', 'C')
                ->leftjoin("C.cat_id", "CA")
                ->where("CA.id = ".$id)
                ->orderBy('C.id', "DESC");
        $posts = $qb->getQuery()->getResult();
        foreach ($posts as $key => $item) {
            $qb = $em->createQueryBuilder();
            $qb->select('DISTINCT I.id, I.title, I.peso, I.tipo')
                    ->from('AppBundle:Imagen', 'I')
                    ->leftjoin("I.related", "R")
                    ->where("R.id = " . $item['id'])
                    ->orderBy('I.id', "ASC");
            $img = $qb->getQuery()->getResult();
            $posts[$key]['image'] =  $img;
        }

        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('blog/service.html.twig', compact('posts','id','post'));
    }
    
    /**
     * @Route("/taller", name="taller")
     * @Method("GET")
     */
    public function tallerAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT C.id, C.title, C.slug, CA.title AS categoria, C.summary, C.content, C.dini, C.publishedAt')
                ->from('AppBundle:Post', 'C')
                ->leftjoin("C.cat_id", "CA")
                ->where("CA.id = 1")
                ->orderBy('C.id', "DESC");
        $talleres = $qb->getQuery()->setMaxResults(16)->getResult();
        foreach ($talleres as $key => $value) {
            $qb = $em->createQueryBuilder();
            $qb->select('DISTINCT R.id, R.title')
                    ->from('AppBundle:Imagen', 'R')
                    ->leftjoin("R.related", "I")
                    ->where("I.id = ".$value['id'])
                    ->orderBy('R.id', "ASC");
            $imgs = $qb->getQuery()->setMaxResults(1)->getSingleResult();
            $talleres[$key]['imagen'] = $imgs['title'];
            
        }
 
        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('blog/taller.html.twig', ['talleres' => $talleres]);
    }
    
   
    
    /**
     * @Route("/taller-info/{slug}", defaults={"slug": "0"}, name="taller_info")
     * @Method("GET")
     */
    public function tinfoAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT C.id, C.title, C.slug, CA.title AS categoria, C.dini, C.summary, C.content, C.publishedAt')
                ->from('AppBundle:Post', 'C')
                ->leftjoin("C.cat_id", "CA")
                ->where("CA.id = 1")->andwhere("C.slug <> '".$slug."'")
                ->orderBy('C.id', "DESC");
        $talleres = $qb->getQuery()->setMaxResults(4)->getResult();
        foreach ($talleres as $key => $value) {
            $qb = $em->createQueryBuilder();
            $qb->select('DISTINCT R.id, R.title')
                    ->from('AppBundle:Imagen', 'R')
                    ->leftjoin("R.related", "I")
                    ->where("I.id = ".$value['id'])
                    ->orderBy('R.id', "ASC");
            $imgs = $qb->getQuery()->setMaxResults(1)->getSingleResult();
            $talleres[$key]['imagen'] =  (count($imgs) > 0 ? $imgs['title']:'nophotoc.png' );
            
        }
        
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT C.id, C.title, C.slug, CA.title AS categoria, C.dini, C.precio, C.summary, C.content, C.publishedAt')
                ->from('AppBundle:Post', 'C')
                ->leftjoin("C.cat_id", "CA")
                ->where("C.slug = '".$slug."'")
                ->andwhere("CA.id = 1")
                ->orderBy('C.id', "DESC");
        $taller = $qb->getQuery()->setMaxResults(1)->getSingleResult();
        
        $qb = $em->createQueryBuilder();
            $qb->select('DISTINCT R.id, R.title')
                    ->from('AppBundle:Imagen', 'R')
                    ->leftjoin("R.related", "I")
                    ->where("I.slug = '".$slug."'")
                    ->orderBy('R.id', "ASC");
            $imgs = $qb->getQuery()->SetMaxResults(1)->getSingleResult();


        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('blog/taller_info.html.twig', compact('talleres','taller','imgs'));
    }
       
}
