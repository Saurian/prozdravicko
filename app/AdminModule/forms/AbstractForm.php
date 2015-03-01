<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * AbstractForm
 *
 * @created 29.6.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\FrontModule\Forms;

use App\Entities;
use App\Model\InvalidArgumentException;
use Doctrine\ORM\Mapping\Entity;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\Button;
use Nette\Forms\Controls\Checkbox;
use Nette\Forms\Controls\CheckboxList;
use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\RadioList;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\TextBase;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\Utils\DateTime;

class AbstractForm extends Form implements IDevrunFormFactory
{

//    abstract protected function startup();


    protected $redirect;


    /** @var \Kdyby\Doctrine\EntityManager */
    protected $em;


    function OFF__construct(EntityManager $em)
    {
        $this->em = $em;
        parent::__construct();

        $this->startup();
    }


    protected function bootstrap3Render()
    {
        /** @var $renderer DefaultFormRenderer */
        $renderer                                        = $this->getRenderer();
        $renderer->wrappers['controls']['container']     = NULL;
        $renderer->wrappers['pair']['container']         = 'div class=form-group';
        $renderer->wrappers['pair']['.error']            = 'has-error';
        $renderer->wrappers['control']['container']      = 'div class=col-sm-9';
        $renderer->wrappers['label']['container']        = 'div class="col-sm-3 control-label"';
        $renderer->wrappers['control']['description']    = 'span class=help-block';
        $renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';

        // make form and controls compatible with Twitter Bootstrap
        $this->getElementPrototype()->class('form-horizontal');

        foreach ($this->getControls() as $control) {
            if ($control instanceof Button) {
                $control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-default');
                $usedPrimary = TRUE;

            } /** @var $control BaseControl */
            elseif ($control instanceof TextBase ||
                $control instanceof SelectBox ||
                $control instanceof MultiSelectBox
            ) {
                $control->getControlPrototype()->addClass('form-control');

            } elseif ($control instanceof Checkbox ||
                $control instanceof CheckboxList ||
                $control instanceof RadioList
            ) {
                $control->getSeparatorPrototype()->setName('div')->addClass($control->getControlPrototype()->type);
            }
        }
    }


    /**
     * @param array|Entity $entity
     *
     * @return $this
     */
    public function setDefaultsFromEntity($entity)
    {
        // implementace default from entity není kompletní
        $this->entity = $entity;

        if ($this->entity !== null) {
            $defaults = array();
            foreach ($this->getComponents() as $control) {
                $name = $control->name;
                if (isset($this->entity->$name)) {

                    // jedná se o entitu, musíme získat její hodnotu
                    if (is_object($this->entity->$name)) {
                        /*
                         * získání primárního klíče
                         * @ todo je to pouze jednoduchá metoda, neporadí si s vazbou m:n
                         */
                        $meta            = $this->em->getClassMetadata(get_class($entity));
                        $identifier      = $meta->getSingleIdentifierFieldName();
                        $defaults[$name] = $this->entity->$name->$identifier;

                    } else {
                        $defaults[$name] = $this->entity->$name;
                    }

                }
            }

            $this->setDefaults($defaults);
        }
        return $this;
    }

    /**
     * @param mixed $redirect
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @return mixed
     */
    public function getRedirect()
    {
        return $this->redirect ? $this->redirect : 'this';
    }


    public function save($values)
    {
        $class = $this->reflection->getProperty('entity')->getAnnotation('var');

        if (!class_exists($class, false)) {
            throw new \Nette\InvalidArgumentException("property 'entity' must have anotation of entity with full path");
        }

        if ($this->entity === null) {
            $this->entity = new $class();

            $this->onPrePersist($values);

            foreach ($values as $key => $value) {
                if (isset($this->entity->$key)) {
                    $this->entity->$key = $value;
                }
            }

            // berlička, nejedou události
            if (is_object($this->entity) && method_exists($class, 'onPrePersist')) {
                $this->entity->onPrePersist();
            }

//            die(dump($this->entity));

            $this->em->persist($this->entity);
            $this->em->flush();

            $this->onPostPersist();

        } else {
            foreach ($values as $key => $value) {
                if (isset($this->entity->$key)) {
                    $this->entity->$key = $value;
                }
            }
            // berlička, nejedou události
            if (is_object($this->entity) && method_exists($class, 'onPreUpdate')) {
                $this->entity->onPreUpdate();
            }

            $this->em->merge($this->entity);
            $this->em->flush();

            $this->onPostUpdate();
        }

    }


    protected function onPrePersist(&$values)
    {
    }


    protected function onPreUpdate()
    {
    }


    protected function onPostPersist()
    {
    }


    protected function onPostUpdate()
    {
    }


    function startup()
    {
    }
}