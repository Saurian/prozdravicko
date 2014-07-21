<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * IdentifiedEntity
 *
 * @created 14.6.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * Class IdentifiedEntity
 * @ORM\MappedSuperclass()
 *
 * @package App\Entities
 */
abstract class IdentifiedEntity extends BaseEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


}