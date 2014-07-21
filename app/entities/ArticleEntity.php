<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\DateTime;


/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="article", indexes={
 * @ORM\Index(name="page_idx", columns={"page"}),
 * })
 */
class ArticleEntity extends IdentifiedEntity
{

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $subtitle = '';

    /**
     * @var string
     * @ORM\Column(type="string",length=64, nullable=true)
     */
    protected $page = '';

    /**
     * @var string
     * @ORM\Column(type="string",length=64)
     */
    protected $section = '';

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $text = '';

    /**
     * @var string
     * @ORM\Column(type="string")
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