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
use App\Entities\CatalogItemEntity;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Query;
use Kdyby\Doctrine\EntityDao;
use Nette\Object;

class CatalogItemRepository extends Object
{

    /** @var  EntityDao */
    private $dao;

    private $entityClassName;

    function __construct(EntityDao $dao)
    {
        $this->dao = $dao;
        $this->entityClassName = $dao->getClassName();
    }


    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->dao->findBy($criteria, $orderBy, $limit, $offset);
    }


    public function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        return $this->dao->find($id, $lockMode, $lockVersion);
    }


    public function findByLastAcceptedPrice($id)
    {

//        $sub_query = $this->dao->createQueryBuilder()
//            ->addSelect('w.price')
//            ->from(CatalogProductPriceEntity::getClassName(), 'w')
//            ->where('w.product = ?1')
//            ->setParameter('1', $id)
//            ->setMaxResults(1)
//            ->getDQL();
//
//        die(dump($sub_query));
//
//        die(dump($sub_query->getQuery()->getArrayResult()));



//        $query = $this->dao->createQueryBuilder()
//            ->select('e')
//            ->addSelect('(' . $sub_query . ') as cislo')
//            ->from(CatalogItemEntity::getClassName(), 'e')
////            ->leftJoin('e.prices', 'p')
//            ->where('e.id = ?1')
//            ->setParameter('1', $id);

//        $query = $this->dao->createQueryBuilder()
//            ->select('e')
//            ->addSelect('(SELECT p1.price
//            FROM App\Entities\CatalogProductPriceEntity p1
//            WHERE 1 = p1.product
//            LIMIT 1
//            ) AS addresstypeName'
//            )
//            ->from(CatalogItemEntity::getClassName(), 'e')
//            ->leftJoin('e.prices', 'p');
//

//        $result = $query->getQuery()->getArrayResult();
//
//
//
//        die(dump($result));
//
//
//        $subQuery = $this->dao->createQueryBuilder()
//            ->select('p')
//            ->andWhere('p.accepted = :accepted')
//            ->orderBy('p.created', 'DESC')
//            ->setMaxResults(1);
//

        return $this->dao->createQueryBuilder()
            ->select('e', 'p')
            ->from(CatalogItemEntity::getClassName(), 'e')
            ->leftJoin('e.prices', 'p')
            ->where("e.id = ?1")
            ->andWhere('p.accepted = :accepted')
            ->orderBy('p.created', 'DESC')
            ->setMaxResults(1)
            ->setParameter('1', $id)
            ->setParameter('accepted', true)
            ->getQuery()
            ->getOneOrNullResult();

    }


    public function getCategory($id)
    {
        $result = $this->dao->getEntityManager()->createQueryBuilder()
            ->select('e')
            ->from(CatalogItemEntity::getClassName(), 'e')
            ->where("e.id = ?1")
            ->setParameter('1', $id)
            ->getQuery()
            ->getResult();

        return isset($result[0])
            ? $result[0]->category
            : null;

    }


    public function findOneByCategory($id)
    {
        $result = $this->dao->getEntityManager()->createQueryBuilder()
            ->select('e', 'i')
            ->from(CatalogCategoryEntity::getClassName(), 'e')
            ->leftJoin('e.items', 'i')
            ->where("e.id = ?1")
            ->orderBy('i.name', 'ASC')
            ->setParameter('1', $id)
            ->getQuery()
            ->getResult();

        return isset($result[0])
            ? $result[0]
            : null;
    }


    public function getIdByUrl($uri)
    {
        if (is_numeric($uri)) {
            return $uri;
        }
        $result = $this->dao->createQueryBuilder()
            ->select('e.id')
            ->from(CatalogItemEntity::getClassName(), 'e')
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
            ->from(CatalogItemEntity::getClassName(), 'e')
            ->where("e.id = ?1")
            ->setParameter('1', $id)
            ->getQuery()
            ->getResult();

        return $result ? $result[0]['url'] : null;
    }


}