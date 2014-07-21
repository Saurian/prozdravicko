<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 *
 * ArticlesPresenterTest
 *
 * @created 16.7.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace DevrunTests\AdminModule\Presenters;

use Nette\Application\Responses\TextResponse;

//$container;
//global
//$container = require __DIR__ . '/../../bootstrap-phpunit.php';


class ArticlesPresenterTest extends \DevrunTests\Presenter {


    public function testDefault()
    {

    }


    public function testArticleInsert()
    {

        $requestData = array(
            'action' => 'insert',
            'do' => 'articleForm-submit',
        );


        $post = array(

        );

        $params = array(
            'action' => 'insert',
            'do' => 'articleForm-submit',
        );

        $method= 'GET';

        $request          = new \Nette\Application\Request($this->presenterName, $method, $params, $post);

        /** @var TextResponse $response */
        $response         = $this->presenter->run($request);




        $html = $this->createHtmlFromResponse($response);

        $err_out = $this->getErrors($response);

        die(dump($html));

        $this->assertNotEmpty($err_out, 'neni chybova zprava');

        // formulář je vyplněn FAIL, nejsme nalogovaní, nesmíme se přesměrovat nikam
        $this->assertInstanceOf("NRenderResponse", $response, "Presmerovano ! chyba v $key formulari");


//        $html =(string) $response->getSource();




        dump($html);
//        dump($response);
//        die(dump($request));

//        $response = $this->_test($action, 'GET', $params, $post);




        return;
        $this->sendLoginForm();

        $requestData = array(
            'action' => 'youngStep1',
            'do'     => 'contactTeamYoungForm-submit',
        );

        $values  = TestFormRegistration::getInstance()->getReadyForm(TestFormRegistration::$contactFormDefs);
        $request = $this->getRequest('Frontend:Registration', 'POST', $requestData, $values);

        /** @var $response NRedirectingResponse */
        $response = $this->getResponse($this->presenter, $request);
        $err_out  = $this->getErrors($response);

        // formulář je vyplněn OK, bude přesměrováno
        $this->assertInstanceOf("NRedirectingResponse", $response, 'Nepresmerovano ' . $err_out);
        $this->assertEquals('http:///prihlas-projekt/step2', $this->uriCheck($response->getUri()));

        // musí být založen projekt s levelem 2
        $project = $this->presenter->getProject();
        $this->assertEquals(ProjectsModel::LEVEL2, $project['level'], sprintf("Neodpovídá level projektu [%s] given", $project['level']));


    }

    protected function setUp()
    {

//        $this->initDatabase();
        $this->init('Admin:Articles');


    }







}
 