<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

$configurator->setDebugMode(array('37.221.241.252'));
//$configurator->setDebugMode(false);  // debug mode MUST NOT be enabled on production server
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../vendor/others')
	->register();

$environment = Nette\Configurator::detectDebugMode('127.0.0.1')
    ? $configurator::DEVELOPMENT
    : $configurator::PRODUCTION;

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . "/config/config.development.neon");
//$configurator->addConfig(__DIR__ . "/config/config.$environment.neon");
//$configurator->addConfig(__DIR__ . "/config/config.production.neon");

$container = $configurator->createContainer();

return $container;
