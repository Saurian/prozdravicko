<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * SongModel
 *
 * @created 29.6.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\Model;

use App\Entities\CatalogCategoryEntity;
use Doctrine\ORM\Query;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;

class ProductModel extends AbstractModel
{
    /** @var \App\Entities\CatalogItemEntity  */
    private $entity;

    /** @var EntityDao */
    private $dao;

    public function __construct(EntityDao $dao)
    {
//        $this->entity = $entity;
//        $this->em = $em;
        $this->dao = $dao;
    }


    public function getSelectList()
    {
        return $this->getDao(CatalogCategoryEntity::getClassName())->findAssoc(array(), 'id');
//        return $this->em->getDao(CatalogCategoryEntity::getClassName())->findAssoc(array(), 'id');
    }


    /**
     * @return \Kdyby\Doctrine\EntityDao
     */
    public function getDao()
    {
        return $this->dao;
    }



} 