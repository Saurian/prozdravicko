<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * EmailModel
 *
 * @created 20.7.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\Model;


use Kdyby\Events\Subscriber;
use Nette\Application\Application;
use Nette\Mail\IMailer;
use Nette\Object;

class EmailModel extends Object implements Subscriber
{

    private $mailer;

    public function __construct(IMailer $mailer)
    {
        $this->mailer = $mailer;
    }


    public function sendInfo()
    {

    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array('Nette\Application\Application::onStartup');
    }


    public function onStartup(Application $app)
    {
//        die(dump($app));
    }
}