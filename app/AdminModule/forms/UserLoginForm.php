<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * ArticleForm
 *
 * @created 12.6.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\AdminModule\Forms;


use App\FrontModule\Forms\AbstractForm;
use Kdyby\Doctrine\EntityManager;
use Kdyby\DoctrineForms\Builder\ControlFactory;
use Nette;
use Nette\Application\UI\Form;
use Tester\Environment;
use Tracy\Debugger;


interface IUserLoginFactory
{
    /** @return UserLoginForm */
    function create();
}


class UserLoginForm extends AbstractForm
{


    /** @var \App\Entities\UserEntity */
    protected $entity;


    private $_backlink;

    function __construct(EntityManager $em, $backlink = '')
    {
        parent::__construct();
        $this->em = $em;
        $this->_backlink = $backlink;
        $this->startup();
    }


    public function startup()
    {
        if (Debugger::$productionMode) {
//        if (Nette\Environment::isProduction() && !Nette\Environment::isConsole()) {
            $this->addProtection('Prosím odešlete přihlašovací údaje znovu (vypršela platnost bezpečnostního tokenu).');
        }

        $this->addHidden('backlink', $this->_backlink);

        $this->addText('login', 'Přihlašovací jméno:')
            ->addRule(Form::FILLED, 'Vyplňte prosím přihlašovací jméno')
            ->addRule(Form::MIN_LENGTH, 'Přihlašovací jméno musí mít minimálně 4 znaky.', 4)
            ->addRule(Form::MAX_LENGTH, 'Přihlašovací jméno může mít maximálně 32 znaků.', 32)
            ->setAttribute('class', 'form-control')
            ->setAttribute('placeholder', 'Uživatelské jméno');


        $this->addPassword('password', 'Heslo:')
            ->addRule(Form::FILLED, 'Vyplňte prosím heslo')
            ->setAttribute('class', 'form-control')
            ->setAttribute('placeholder', 'Heslo');

        $this->addCheckbox('remember', 'Pamatovat si na tomto počítači');
//            ->setAttribute('class', 'ace');

        $this->addSubmit('send', 'Login')->setAttribute('class', 'width-35 pull-right btn btn-sm btn-primary');

    }


    public function formSubmitted(Nette\Application\UI\Form $form)
    {
        try {
            $user = $this->getPresenter()->getUser();
            if ($form['remember']->value) {
                $user->setExpiration('14 days', FALSE);
            } else {
                $user->setExpiration('40 minutes', TRUE);
            }

            $user = $this->getPresenter()->getUser();
            $user->login($form['login']->value, $form['password']->value);

            $this->presenter->restoreRequest($this->presenter->backlink());


//            $this->getPresenter()->getApplication()->restoreRequest($this->getPresenter()->backlink);
            $this->getPresenter()->redirect(':Admin:Homepage:');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError($e->getMessage());
        }

    }


}