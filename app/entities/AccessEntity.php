<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\DateTime;


/**
 * @ORM\Entity
 * @ORM\Table(name="access")
 * @ORM\HasLifecycleCallbacks
 */
class AccessEntity extends IdentifiedEntity
{

    /**
     * @var string
     * @ORM\Column(type="string",length=32,nullable=false)
     */
    protected $ip;

    /**
     * @var string
     * @ORM\Column(type="string",length=64)
     */
    protected $referer = '';

    /**
     * @var string
     * @ORM\Column(type="string",length=64)
     */
    protected $userAgent = '';

    /**
     * @var string
     * @ORM\Column(type="string",length=64)
     */
    protected $uri = '';

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $requestTime;

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
     * if $requestTime is int, this is timestamp
     *
     * @param int|DateTime $requestTime
     */
    public function setRequestTime($requestTime)
    {
        if (is_int($requestTime)) {
            $this->requestTime = new DateTime();
            $this->requestTime->setTimestamp($requestTime);

        } else {
            $this->requestTime = $requestTime;
        }

    }



}