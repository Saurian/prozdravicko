<?php

namespace App\Model\Security;


use Nette\Object,
	 Nette\Security\Identity,
	 Nette\Security\IAuthenticator,
	 Nette\Security\AuthenticationException;

class Authenticator extends Object implements IAuthenticator {
	const ERROR_MESSAGE = 'Zadali jste neplatné údaje';

    /** @var UserManager */
    private $users;



    public function __construct(UserManager $users)
    {
        $this->users = $users;
    }


	public function authenticate(array $credentials) {
		$username = $credentials[self::USERNAME];
		$user = $this->users;

		$row = $user->findByUserName($username);

		if (!$row) {
			throw new AuthenticationException(self::ERROR_MESSAGE, self::IDENTITY_NOT_FOUND);
		}
		//		$config = Environment::getConfig('security');
		$password = $credentials[self::PASSWORD];
		//		$password = sha1($credentials[self::PASSWORD] . $config->salt);
		if ($row->password !== sha1($username . $password)) {
			throw new AuthenticationException(
					  self::ERROR_MESSAGE, self::INVALID_CREDENTIAL);
		}
		$identity = new Identity($row->id, $row->role);
		$identity->user = $row->username;
		return $identity;
	}

}
