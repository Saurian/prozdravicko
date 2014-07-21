<?php

use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';


if (!class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

Tester\Environment::setup();

$configurator = new Nette\Configurator;
//$configurator->setDebugMode(true);
$configurator->setDebugMode(FALSE);

//$configurator->enableDebugger(__DIR__ . '/log');

$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../app')
	->register();

$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
$configurator->addConfig(__DIR__ . '/../app/config/config.test.neon');

return $configurator->createContainer();
