<?php

/**
 * AbstractModel model
 *
 * @created 9.7.14
 * @package pro-zdravicko.cz
 * @author  Saurian
 */

namespace App\Model;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;


/**
 * Model AbstractModel
 */
class AbstractModel extends Object
{

    /** @var EntityManager */
    protected $em;


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
        $entity = $this->em->getRepository($this->entity)->find($id);
        if ($entity) {
            $this->em->getRepository($this->entity)->delete($entity);
            $result = true;
        }
        return $result;
    }

}
