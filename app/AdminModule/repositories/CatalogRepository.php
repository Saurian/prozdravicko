<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * CatalogRepository
 *
 * @created 19.6.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\AdminModule\Repositories;


use App\Entities\CatalogCategoryEntity;
use Doctrine\DBAL\LockMode;
use Kdyby\Doctrine\EntityDao;
use Nette\Object;
use Tracy\Debugger;

class CatalogRepository extends Object
{

    /** @var EntityDao */
    private $dao;


    function __construct(EntityDao $dao)
    {
        $this->dao = $dao;
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
     * vrátí všechny potomky z kategorie id
     */
    public function findAllBy($id)
    {
        return $this->dao->getEntityManager()->createQueryBuilder()
            ->select('e', 'i')
            ->from(CatalogCategoryEntity::getClassName(), 'e')
            ->leftJoin('e.items', 'i')
            ->where("e.id = ?1")
            ->orderBy('i.name', 'ASC')
            ->setParameter('1', $id)
            ->getQuery()
            ->getSingleResult();

    }


    public function delete($id)
    {
        $entity = $this->find($id);
        if ($entity) {
            $this->dao->delete($entity);
        }
    }


    public function getIdByUrl($uri)
    {
        if (is_numeric($uri)) {
            return $uri;
        }
        $result = $this->dao->createQueryBuilder()
            ->select('e.id')
            ->from(CatalogCategoryEntity::getClassName(), 'e')
            ->where("e.url = ?1")
            ->setParameter('1', $uri)
            ->getQuery()
            ->getResult();

        return $result ? $result[0]['id'] : null;
    }


    public function getUrlById($id)
    {
        if (!is_numeric($id)) {
            return $id;
        }
        $result = $this->dao->createQueryBuilder()
            ->select('e.url')
            ->from(CatalogCategoryEntity::getClassName(), 'e')
            ->where("e.id = ?1")
            ->setParameter('1', $id)
            ->getQuery()
            ->getResult();

        return $result ? $result[0]['url'] : null;
    }


    public function getLastId()
    {
        return $this->dao->createQueryBuilder()
            ->select('MAX(e.id)')
            ->from(CatalogCategoryEntity::getClassName(), 'e')
            ->getQuery()
            ->getSingleScalarResult();

    }

}