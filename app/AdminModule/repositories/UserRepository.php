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


use App\Entities\UserEntity;
use Doctrine\ORM\Query;
use Kdyby\Doctrine\EntityDao;
use Nette\Object;

class UserRepository extends Object
{

    /** @var EntityDao */
    private $dao;


    function __construct(EntityDao $dao)
    {
        $this->dao = $dao;
    }


    public function findByLogin($login)
    {
        $result = $this->dao->createQueryBuilder()
            ->select('e')
            ->from(UserEntity::getClassName(), 'e')
            ->where("e.login = :username")
            ->setParameter('username', $login)
            ->getQuery()
            ->getArrayResult();

        return $result ? $result[0] : null;
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