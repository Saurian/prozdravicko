<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Forms\IUserLoginFactory;
use App\AdminModule\Forms\UserLoginForm;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;


/**
 * Class UserPresenter
 *
 * @package AdminModule
 */
final class UserPresenter extends BasePresenter
{

    /** @persistent */
    public $backlink = '';

    /** @var IUserLoginFactory @inject */
    public $loginFormFactory;


    public function actionLogin()
    {
        $this->setLayout('layout-login');

        /** @var $form UserLoginForm */
        $form = $this['loginForm'];
        $form->setRedirect('Dashboard:');
    }


    public function actionLogout()
    {
        $this->user->logout();
        $this->redirect('login');
    }


    protected function createComponentLoginForm()
    {
        $form = $this->loginFormFactory->create();
        $form->onSuccess[] = $this->logInFormSubmitted;
        return $form;
    }


    public function logInFormSubmitted(Form $form)
    {
        try {
            $values = $form->getValues();
            $user = $this->getUser();
            if ($values['remember']) {
                $user->setExpiration('14 days', FALSE);
            } else {
                $user->setExpiration('40 minutes', TRUE);
            }

            $user->login($values['login'], $values['password']);

            $this->restoreRequest($this->backlink);
            $this->redirect('Dashboard:');

        } catch (AuthenticationException $e) {
            $form->addError($e->getMessage());
        }

    }


}
