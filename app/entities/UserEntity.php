<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\DateTime;


/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks
 */
class UserEntity extends IdentifiedEntity
{

    /**
     * @var string
     * @ORM\Column(type="string",length=32,unique=true,nullable=false)
     */
    protected $login;

    /**
     * @var string
     * @ORM\Column(type="string",length=40)
     */
    protected $password;

    /**
     * @var string
     * @ORM\Column(type="string",length=64)
     */
    protected $name = '';

    /**
     * @var string
     * @ORM\Column(type="string",length=64)
     */
    protected $role = '';

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