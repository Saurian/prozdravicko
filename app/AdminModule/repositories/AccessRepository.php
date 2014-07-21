<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * SongModel
 *
 * @created 29.6.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\AdminModule\Repositories;


use App\Entities\AccessEntity;
use App\Model\AccessModel;
use Doctrine\ORM\Query;
use Kdyby\Doctrine\EntityDao;
use Nette\Object;

class AccessRepository extends Object
{

    /** @var EntityDao */
    private $dao;

    /** @var AccessModel */
    private $accessModel;

    function __construct(EntityDao $dao, AccessModel $accessModel)
    {
        $this->dao         = $dao;
        $this->accessModel = $accessModel;
    }


    public function add()
    {
        $entity              = new AccessEntity();
        $entity->ip          = $this->accessModel->getClientIP();
        $entity->uri         = $this->accessModel->getRequestUri();
        $entity->userAgent   = $this->accessModel->getUserAgent();
        $entity->requestTime = $this->accessModel->getRequestTime();

        $this->dao->save($entity);
    }


    /**
     * @return \Kdyby\Doctrine\EntityDao
     */
    public function getDao()
    {
        return $this->dao;
    }


    /**
     * entity delete
     *
     * @param int $id id
     *
     * @return bool
     */
    public function delete($id)
    {
        $result = false;
        $entity = $this->dao->find($id);
        if ($entity) {
            $this->dao->delete($entity);
        }
        return $result;
    }

} 