<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * ArticleRepository
 *
 * @created 13.6.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\AdminModule\repositories;


use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityRepository;

class ArticleRepository extends EntityRepository
{

    private $_dao;

    function __construct(EntityDao $dao)
    {
        parent::__construct($dao->_em, $dao->_class);
        $this->_dao = $dao;
    }


    public function getIdByUrl($uri)
    {
        if (is_numeric($uri)) {
            return $uri;
        }
        $result = $this->_dao->createQueryBuilder()
            ->select('e.id')
            ->from($this->_dao->getClassName(), 'e')
            ->where("e.uri = ?1")
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
        $result = $this->_dao->createQueryBuilder()
            ->select('e.uri')
            ->from($this->_dao->getClassName(), 'e')
            ->where("e.id = ?1")
            ->setParameter('1', $id)
            ->getQuery()
            ->getResult();

        return $result ? $result[0]['uri'] : null;
    }


}