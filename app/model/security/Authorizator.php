<?php
namespace App\Model\Security;

use Nette\Security\Permission;

class Authorizator extends Permission
{


    public function __construct()
    {
        // roles
        $this->addRole('guest');
        $this->addRole('member');
        $this->addRole('admin', 'member');
        $this->addRole('developer');

        // resources
        $this->addResource('Admin:Dashboard');
        $this->addResource('Admin:Articles');
        $this->addResource('Admin:Guestbook');
        $this->addResource('Admin:Catalog');
        $this->addResource('Admin:User');
        $this->addResource('Admin:Contact');
        $this->addResource('Admin:Settings');

        $this->addResource('Front:Homepage');
        $this->addResource('Front:Error');

        // privileges quest
        $this->deny('guest', Permission::ALL);
        $this->allow('guest', 'Front:Error', Permission::ALL);
        $this->allow('guest', 'Front:Homepage', Permission::ALL);
        $this->allow('guest', 'Admin:User', 'login');

        // privileges admin
        $this->allow('admin', Permission::ALL, Permission::ALL);
//        $this->deny('admin', 'Admin:Articles', array('insert', 'delete'));
//        $this->deny('admin', 'Admin:Contact', array('insert', 'delete'));
//		$this->deny('admin', 'Admin:User', array('login', 'registration'));

        // privileges developer
        $this->allow('developer', Permission::ALL, Permission::ALL);

    }
}
