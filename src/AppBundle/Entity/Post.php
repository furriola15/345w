<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 * @ORM\Table(name="post")
 *
 * Defines the properties of the Post entity to represent the blog posts.
 *
 * See https://symfony.com/doc/current/book/doctrine.html#creating-an-entity-class
 *
 * Tip: if you have an existing database, you can generate these entity class automatically.
 * See https://symfony.com/doc/current/cookbook/doctrine/reverse_engineering.html
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class Post
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     *
     * See https://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $title;
    
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $activo;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $summary;
    
    /**
     * @var string
     *
     * @ORM\Column(type="float")
     */
    private $precio;
    
    /**
     * @var string
     *
     * @ORM\Column(type="float")
     */
    private $descuento;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $publishedAt;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable = true)
     * @Assert\DateTime
     */
    private $dini;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable = true)
     * @Assert\DateTime
     */
    private $dfin;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Categoria")
     * @ORM\JoinColumn(name="cat_id", referencedColumnName="id", nullable = true)
     */
    private $cat_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Subcategoria")
     * @ORM\JoinColumn(name="scat_id", referencedColumnName="id", nullable = true)
     */
    private $scat_id;

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
        $this->dini = new \DateTime();
        $this->dfin = new \DateTime();
    }




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
     * Set title
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set activo
     *
     * @param integer $activo
     *
     * @return Post
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return integer
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Post
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Post
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return Post
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set descuento
     *
     * @param float $descuento
     *
     * @return Post
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get descuento
     *
     * @return float
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     *
     * @return Post
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set dini
     *
     * @param \DateTime $dini
     *
     * @return Post
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
     * Set dfin
     *
     * @param \DateTime $dfin
     *
     * @return Post
     */
    public function setDfin($dfin)
    {
        $this->dfin = $dfin;

        return $this;
    }

    /**
     * Get dfin
     *
     * @return \DateTime
     */
    public function getDfin()
    {
        return $this->dfin;
    }

    /**
     * Set catId
     *
     * @param \AppBundle\Entity\Categoria $catId
     *
     * @return Post
     */
    public function setCatId(\AppBundle\Entity\Categoria $catId = null)
    {
        $this->cat_id = $catId;

        return $this;
    }

    /**
     * Get catId
     *
     * @return \AppBundle\Entity\Categoria
     */
    public function getCatId()
    {
        return $this->cat_id;
    }

    /**
     * Set scatId
     *
     * @param \AppBundle\Entity\Subcategoria $scatId
     *
     * @return Post
     */
    public function setScatId(\AppBundle\Entity\Subcategoria $scatId = null)
    {
        $this->scat_id = $scatId;

        return $this;
    }

    /**
     * Get scatId
     *
     * @return \AppBundle\Entity\Subcategoria
     */
    public function getScatId()
    {
        return $this->scat_id;
    }
}
