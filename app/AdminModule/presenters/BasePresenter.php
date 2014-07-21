<?php

namespace App\AdminModule\Presenters;
use Nette\Http\UserStorage;


/**
 * Base presenter for all application presenters.
 */
class BasePresenter extends \App\Presenters\BasePresenter
{
    protected function startup()
    {
        parent::startup();

        $user = $this->getUser();


        if (!$user->isAllowed($this->name, $this->action)) {
            if ($user->logoutReason === UserStorage::INACTIVITY) {
                $message = 'You have been signed out due to inactivity. Please sign in again.';

            } else {
                $message = 'Neoprávněný vstup!';
            }


            $this->flashMessage($message, 'danger');

            $this->redirect(':Admin:User:login', array('backlink' => $this->storeRequest()));
        }

    }


}
