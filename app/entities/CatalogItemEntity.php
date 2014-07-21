<?php

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\DateTime;


/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_item")
 * @ORM\HasLifecycleCallbacks
 */
class CatalogItemEntity extends IdentifiedEntity
{

    /**
     * @var ArrayCollection|CatalogCategoryEntity
     * @ORM\ManyToOne(targetEntity="\App\Entities\CatalogCategoryEntity", inversedBy="items")
     * @ORM\JoinColumn(name="catalog_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $category;

    /**
     * @var string
     * @ORM\Column(type="string",length=64)
     */
    protected $name = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $link = '';

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
     * @ORM\Column(type="text")
     */
    protected $parameters = '';

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $content = '';

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $warning = '';

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $application = '';

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $sign = '';

    /**
     * @var string
     * @ORM\Column(type="string",length=64)
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