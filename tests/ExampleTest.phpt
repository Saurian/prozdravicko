<?php

namespace Test;

use App\AdminModule\Repositories\CatalogRepository;
use Nette,
	Tester,
	Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';


class ExampleTest extends Tester\TestCase
{
	private $container;

    private $presenter;

    private $presenterName;


    /** @var CatalogRepository @inject */
    public $categoryRepository;


	function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;

	}


	function setUp()
	{
	}


	function testSomething()
	{
		Assert::true( false );
	}

}


$test = new ExampleTest($container);

$test->run();
