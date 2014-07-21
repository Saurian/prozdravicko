<?php

/**
 * volá se jen jednou pomocí editoru
 * slouží především pro inicializaci databáze
 * pro urychlení používáme dibi
 */

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/model/security/DummyUserStorage.php';

$configurator = new Nette\Configurator;
$configurator->setDebugMode(FALSE);

//$configurator->enableDebugger(__DIR__ . '/log');

$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->createRobotLoader()
    ->addDirectory(__DIR__ . '/../app')
    ->register();

$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
$configurator->addConfig(__DIR__ . '/../app/config/config.test.neon');

$container = $configurator->createContainer();


$container->removeService('nette.userStorage');
$container->addService('nette.userStorage', new \App\Model\Security\DummyUserStorage());


TestInit::initDatabase();



class TestInit
{
    public static function initDatabase()
    {
        dibi::connect(\Nette\Environment::getConfig('dibiConnect'));

        dibi::query("TRUNCATE TABLE %n", 'catalog_category');
        dibi::query("TRUNCATE TABLE %n", 'catalog_item');
        dibi::query("TRUNCATE TABLE %n", 'article');
        dibi::query("TRUNCATE TABLE %n", 'user');

        dibi::loadFile(__DIR__ . '/files//dump-test.sql');

//        \Nette\Database\Helpers::loadFromFile($doctrineConnect, __DIR__ . '/dump-test.sql');
    }
}

