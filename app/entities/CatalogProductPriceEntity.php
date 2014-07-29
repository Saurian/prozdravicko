<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\DateTime;


/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="catalog_product_price", indexes={
 * @ORM\Index(name="created_idx", columns={"created"}),
 * @ORM\Index(name="product_idx", columns={"product_id"}),
 * })
 */
class CatalogProductPriceEntity extends IdentifiedEntity
{

    /**
     * @var CatalogItemEntity
     * @ORM\ManyToOne(targetEntity="\App\Entities\CatalogItemEntity", inversedBy="prices")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $product;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $price = 0;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $accepted = FALSE;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $readOk;

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

    /**
     * @param int $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = intval(preg_replace('/[^0-9]/', '', $price));
        $this->setReadOk($price);
        return $this;
    }

    /**
     * @param \App\Entities\CatalogItemEntity $product
     *
     * @return $this
     */
    public function setProduct($product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @param boolean $readOk
     *
     * @return $this
     */
    public function setReadOk($readOk)
    {
        $this->readOk = $readOk !== FALSE ? TRUE : FALSE;
        return $this;
    }

    /**
     * @param boolean $accepted
     *
     * @return $this
     */
    public function setAccepted($accepted)
    {
        $this->accepted = (bool) $accepted;
        return $this;
    }



}