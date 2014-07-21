<?php

namespace DevrunTests\FrontModule\Presenters;

use App\AdminModule\Repositories\AccessRepository;
use Nette\Application\Responses\TextResponse;

global $container;
$container = require __DIR__ . '/../../bootstrap-phpunit.php';



class HomepagePresenterTest extends \DevrunTests\Presenter
{

    /** @var \App\AdminModule\Repositories\AccessRepository @inject */
    public $accessRepository;


    public function testDefault()
    {
        $request = $this->getRequest(array('action' => 'default'));

        /** @var TextResponse $response */
        $response = $this->getResponse($request);

        $source = (string) $response->getSource();

        // otestujeme title
        $title = "<title>Pro zdravíčko</title>";
        $this->assertRegExp(sprintf('#%s#i', $title), $source, 'title not valid ' . $title);
    }


    /**
     * ověří přidání záznamu o uživately, použijeme homepage
     */
    public function testAddAccessRecord()
    {
        // ověříme přístup na stránky, zda se provede záznam
        $recordsBefore = count($this->accessRepository->getDao()->findAll());

        $request = $this->getRequest(array('action' => 'default'));

        /** @var TextResponse $response */
        $response = $this->getResponse($request);
        $this->assertInstanceOf('Nette\Application\Responses\TextResponse', $response);

        // ověříme přístup na stránky, zda se provedl záznam
        $recordsAfter = count($this->accessRepository->getDao()->findAll());
        $this->assertGreaterThan($recordsBefore, $recordsAfter, 'Access record not work');
    }





    protected function setUp()
    {
//        $this->initDatabase();
        parent::setUp();
        $this->init('Front:Homepage');
    }
}

