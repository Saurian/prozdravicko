<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * AbstractRepository
 *
 * @created 22.7.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\AdminModule\Repositories;


use Doctrine\DBAL\LockMode;
use Kdyby\Doctrine\EntityDao;
use Nette\Object;

class AbstractRepository extends Object
{

    /** @var EntityDao */
    protected $dao;


    function __construct(EntityDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @return \Kdyby\Doctrine\EntityDao
     */
    public function getDao()
    {
        return $this->dao;
    }



    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->dao->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        return $this->dao->find($id, $lockMode, $lockVersion);
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