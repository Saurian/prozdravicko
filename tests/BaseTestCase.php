<?php

namespace DevrunTests;

use App\AdminModule\Presenters\UserPresenter;
use Kdyby\Doctrine\Helpers;
use Nette\Application\Request;
use Nette\Application\Responses\TextResponse;
use Nette\DI\MissingServiceException;
use Nette\Reflection\AnnotationsParser;
use Nette\Reflection\Property;

class BaseTestCase extends \PHPUnit_Framework_TestCase {

    /** @var \SystemContainer */
//    public $container;

    private $cleared;

    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

//        global $container;
//        $this->container = $container;



    }


    /**
     * implementace Nette inject metodiky pro pohodlnější testy
     *
     * @param string $name
     */
    private function _injectServices($name = 'inject')
    {
        $reflectClass = new \ReflectionClass(get_called_class());

        foreach ($reflectClass->getProperties() as $property) {
            $res = AnnotationsParser::getAll($property);
            if (isset($res[$name]) ? end($res[$name]) : NULL) {
                $this->injectService($property, $res['var']);
            }
        }
    }


    private function injectService(\ReflectionProperty $property, $resource)
    {
        if (isset($resource[0])) {

            try {
                $service      = $this->getContainer()->getByType($resource[0]);
                $_name        = $property->name;
                $this->$_name = $service;

            } catch (MissingServiceException $exc) {
                die(dump(sprintf('%s [%s] %s - full namespace ?', $exc->getMessage(), __METHOD__, $property->class)));
            }

        }
    }


    public function initDB()
    {

    }


    public function initDatabase()
    {
        if ($this->cleared === NULL) {
            $this->cleared = true;

            dump($this->cleared);
        }

    }


    /**
     * @return \SystemContainer
     */
    public function getContainer()
    {
        return $GLOBALS['container'];
    }


    /**
     * check uri
     *
     * @param string $uri uri
     *
     * @return mixed
     */
    protected function uriCheck($uri)
    {
        return (preg_replace('%^(.*)(\?_.*)$%', '$1', $uri));
    }




    protected function setUp()
    {
        $this->_injectServices();
    }

}

