<?php

require __DIR__ . '/../vendor/autoload.php';

//require_once __DIR__ . '/BaseTestCase.php';
//require_once __DIR__ . '/Presenter.php';
require_once __DIR__ . '/../app/model/security/DummyUserStorage.php';

$configurator = new Nette\Configurator;
//$configurator->setDebugMode(true);
$configurator->setDebugMode(FALSE);

//$configurator->enableDebugger(__DIR__ . '/log');

$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../app')
	->addDirectory(__DIR__ . '/../tests')
	->register();

$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
$configurator->addConfig(__DIR__ . '/../app/config/config.test.neon');


$container = $configurator->createContainer();

$container->removeService('nette.userStorage');
$container->addService('nette.userStorage', new \App\Model\Security\DummyUserStorage());

return $container;