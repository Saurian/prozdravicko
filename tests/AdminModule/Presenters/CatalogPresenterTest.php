<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * CatalogPresenterTest
 *
 * @created 16.7.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace DevrunTests\AdminModule\Presenters;

use Nette\Application\Responses\RedirectResponse;
use Nette\Application\Responses\TextResponse;
use Nette\Bridges\ApplicationLatte\Template;

$container = require __DIR__ . '/../../bootstrap-phpunit.php';

class CatalogPresenterTest extends \DevrunTests\Presenter
{

    /** @var \App\AdminModule\Repositories\CatalogRepository @inject */
    public $categoryRepository;


    public function testDefault()
    {
        $post = array();

        $params = array(
            'action' => 'default',
        );

        $method = 'POST';

        $request = new \Nette\Application\Request($this->presenterName, $method, $params, $post);

//        die(dump($this->presenter));

        /** @var TextResponse $response */
//        $response = $this->presenter->run($request);


        /** @var Template $source */
//        $source = $response->getSource();


//        $html = $this->createHtmlFromResponse($response);
//
//        $err_out = $this->getErrors($response);


    }

    public function testIsVisibleInsertCategoryForm()
    {
        $request = $this->getRequest(array('action' => 'categoryInsert'));

        /** @var $response TextResponse */
        $response = $this->getResponse($request);

        $source = $response->getSource();
        $form   = $source->presenter->getComponent('categoryForm');

        $this->assertInstanceOf('App\AdminModule\Forms\CategoryForm', $form);
    }

    /**
     * @return int
     */
    public function testInsertCategoryForm()
    {
        $beforeId = $this->categoryRepository->getLastId();

        $request = $this->getRequest(
            array(
                'action' => 'categoryInsert',
                'do'     => 'categoryForm-submit',
            ),
            array(
                'name' => 'Pokus čeština Hókuš',
                'send' => "Vložit článek",
                'text' => "<p>Test národa českého</p>",
            )
        );

        /** @var RedirectResponse $response */
        $response = $this->getResponse($request);

        $this->assertInstanceOf('Nette\Application\Responses\RedirectResponse', $response);
        $this->assertEquals('http://pro-zdravicko.local/admin/catalog/', $this->uriCheck($response->getUrl()));

        $afterId = $this->categoryRepository->getLastId();
        $this->assertGreaterThan($beforeId, $afterId);

        return $afterId;
    }

    /**
     * @dataProvider InsertCategoryFormFailProvider
     */
    public function testInsertCategoryFormFail($name, $text)
    {
        $post = array(
            'name'      => $name,
            'text'      => $text,
        );

        $request = $this->getRequest(
            array(
                'action' => 'categoryInsert',
                'do' => 'categoryForm-submit',
            ),
            $post
        );

        $response = $this->getResponse($request);

        $this->assertInstanceOf('Nette\Application\Responses\TextResponse', $response, 'Chyba '. __FUNCTION__. ' params ' . implode('; ', $post));
    }

    public function InsertCategoryFormFailProvider()
    {
        return array(
            array('', 'Lorem Ipsum'),
        );
    }

    /**
     * @depends testInsertCategoryForm
     * @dataProvider EditCategoryFormProvider
     */
    public function testEditCategoryForm($name, $text, $id)
    {
        $request = $this->getRequest(
            array(
                'action' => 'categoryEdit',
                'id'     => $id,
                'do'     => 'categoryForm-submit',
            ),
            array(
                'name' => $name,
                'text' => $text,
            )
        );

        $response = $this->getResponse($request);

        $this->assertInstanceOf('Nette\Application\Responses\RedirectResponse', $response);
    }


    /**
     * @return array
     */
    public function EditCategoryFormProvider()
    {
        return array(
            array('Testovací', 'Lorem Ipsum'),
        );
    }


    /**
     * action categoryDelete
     *
     * @depends testInsertCategoryForm
     * @param $id
     */
    public function testCategoryDelete($id)
    {
        $recordBefore = $this->categoryRepository->find($id);
        $this->assertNotEmpty($recordBefore);

        $request     = $this->getRequest(array('action' => 'categoryDelete', 'id' => $id));
        $response    = $this->getResponse($request);
        $recordAfter = $this->categoryRepository->find($id);

        $this->assertInstanceOf('Nette\Application\Responses\RedirectResponse', $response);
        $this->assertNull($recordAfter);
    }


    protected function setUp()
    {
        parent::setUp();
//        $this->initDatabase();
//        \dibi::query("TRUNCATE TABLE %n", 'catalog_category');



        $this->init('Admin:Catalog');
        $this->sendLoginForm();

    }


}
 