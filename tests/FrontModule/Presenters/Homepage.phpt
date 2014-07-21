<?php
/**
 * Test: Kdyby\prozdravi\Homepageprvni test.
 *
 * @testCase Kdyby\prozdravi\HomepageTest
 * @author   Filip Procházka <filip@prochazka.su>
 * @package  Kdyby\prozdravi
 */
namespace DevrunTests\prozdravi;

use Kdyby;
use Nette;
use Tester;
use Tester\Assert;

$container = require __DIR__ . '/../../bootstrap.php';

/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class HomepageTest extends Tester\TestCase
{



    function __construct(Nette\DI\Container $container)
    {
        $this->container = $container;

        Tester\Dumper::toLine("asdqd");

    }


    public function setUp()
    {
    }


    function testDefault()
    {

    }


    /**
     * TEST: Basic database query test.
     *
     * @phpVersion < 5.6
     */
    function testSomething()
    {

        // z DI kontejneru, který vytvořil bootstrap.php, získáme instanci PresenterFactory

        /** @var Nette\Application\PresenterFactory $presenterFactory */
        $presenterFactory = $this->container->getByType('Nette\Application\IPresenterFactory');

        // a vyrobíme presenter Sign
        $presenter = $presenterFactory->createPresenter('Front:Homepage');


        $request = new Nette\Application\Request('Sign', 'GET', array('action' => 'in'));
        $response = $presenter->run($request);

        die(dump($request));



        Assert::true(true);


    }
}

$test = new HomepageTest($container);
$test->run();
