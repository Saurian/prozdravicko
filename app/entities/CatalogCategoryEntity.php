<?php

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\DateTime;


/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_category")
 * @ORM\HasLifecycleCallbacks
 */
class CatalogCategoryEntity extends IdentifiedEntity
{

    /**
     * @var CatalogCategoryEntity
     * @ORM\ManyToOne(targetEntity="\App\Entities\CatalogCategoryEntity", inversedBy="children")
     */
    protected $parent;


    /**
     * @var CatalogCategoryEntity[]
     * @ORM\OneToMany(targetEntity="\App\Entities\CatalogCategoryEntity", mappedBy="parent")
     */
    protected $children;


    /**
     * @var ArrayCollection|CatalogItemEntity[]
     * @ORM\OneToMany(targetEntity="\App\Entities\CatalogItemEntity", mappedBy="category")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $items;


    /**
     * @var string
     * @ORM\Column(type="string",length=64)
     */
    protected $name = '';



    /**
     * @var string
     * @ORM\Column(type="string",length=64,unique=true,nullable=false)
     */
    protected $url;



    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $text = '';



    /**
     * @var string
     * @ORM\Column(type="text",length=64)
     */
    protected $image = '';



    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;



    function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }


    /** @ORM\PrePersist */
    public function onPrePersist()
    {
        $this->created = new DateTime();
    }


    /**
     * @ORM\PreUpdate preUpdate
     */
    public function onPreUpdate()
    {
        $this->updated = new DateTime();
    }




}