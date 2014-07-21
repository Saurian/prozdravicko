<?php

namespace App\Model\Security;

use App\AdminModule\Repositories\UserRepository;
use App\Entities\UserEntity;
use Nette,
    Nette\Security\Passwords;


/**
 * Users management.
 */
class UserManager extends Nette\Object implements Nette\Security\IAuthenticator
{
    const
        TABLE_NAME           = 'user',
        COLUMN_ID            = 'id',
        COLUMN_NAME          = 'login',
        COLUMN_ROLE          = 'role',
        COLUMN_PASSWORD_HASH = 'password';


    /** @var UserEntity */
    private $user;


    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }


    /**
     * Performs an authentication.
     *
     * @return Nette\Security\Identity
     * @throws Nette\Security\AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;

        $row = $this->user->findByLogin($username);

        if (!$row) {
            throw new Nette\Security\AuthenticationException('Neplatné přihlašovací údaje', self::IDENTITY_NOT_FOUND);

        } elseif (sha1($username . $password) !== $row['password']) {
            throw new Nette\Security\AuthenticationException('Neplatné přihlašovací údaje', self::INVALID_CREDENTIAL);
        }

        $arr = $row;
        unset($arr[self::COLUMN_PASSWORD_HASH]);
        return new Nette\Security\Identity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $arr);
    }


    /**
     * Adds new user.
     *
     * @param  string
     * @param  string
     *
     * @return void
     */
    public function add($username, $password)
    {
        $this->database->table(self::TABLE_NAME)->insert(array(
            self::COLUMN_NAME          => $username,
            self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
        ));
    }

}
