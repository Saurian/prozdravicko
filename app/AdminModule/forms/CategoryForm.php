<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * ArticleForm
 *
 * @created 12.6.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\AdminModule\Forms;


use App\Entities\CatalogCategoryEntity;
use App\FrontModule\Forms\AbstractForm;
use App\Model\ImageModel;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Form;
use Nette;


interface ICategoryFactory
{

    /** @return CategoryForm */
    function create();
}


class CategoryForm extends AbstractForm
{

    /** @var \App\Entities\CatalogCategoryEntity */
    protected $entity;

    /** @var ImageModel */
    protected $imageModel;


    function __construct(EntityManager $em, ImageModel $imageModel)
    {
        parent::__construct();
        $this->em         = $em;
        $this->imageModel = $imageModel;
        $this->startup();
        $this->bootstrap3Render();
    }


    public function startup()
    {
        $this->addHidden('id');
        $this->addHidden('url');
        $this->addHidden('parent_id');

        $this->addText('name', 'Jméno:')
            ->addRule(Form::FILLED, 'Vyplnit název je povinné')
            ->addRule(Form::MAX_LENGTH, 'Poznámka je příliš dlouhá', 64);

        $this->addTextArea('text', 'Text:')
            ->addRule(Form::MAX_LENGTH, 'Poznámka je příliš dlouhá', 1024)
            ->setAttribute('class', 'ckeditor');

        $this->addUpload('image', 'Obrázek')
            ->addCondition(Form::FILLED)
            ->addRule(Form::IMAGE, 'Avatar musí být JPEG, PNG nebo GIF.')
            ->addRule(Form::MAX_FILE_SIZE, 'Maximální velikost souboru je 2 MB.', 2 * 1024 * 1024);

        $this->addSubmit('send', 'Vložit článek');
        $this->addButton('close', 'Close')
            ->setAttribute('class', 'btn btn-default')
            ->setAttribute('data-dismiss', 'modal');

        $this->onSuccess[] = array($this, 'formSubmitted');

    }


    public function formSubmitted(Nette\Application\UI\Form $form)
    {
        $values    = $form->getValues();
        $presenter = $this->getPresenter();

        /** @var $file Nette\Http\FileUpload */
        $file = $values['image'];

        if ($file->error == UPLOAD_ERR_OK) {
            try {
                $this->imageModel->saveImage($file);
                $values['image'] = $file->name;

            } catch (Nette\Utils\UnknownImageFileException $exc) {
                $presenter->flashMessage($exc->getMessage(), 'error');
                $presenter->redirect($this->redirect);
            }

        } elseif( $file->error == UPLOAD_ERR_NO_FILE) {
            unset($values['image']);
        }


        $this->save($values);
        $this->getPresenter()->redirect($this->getRedirect());
    }

    public function save($values)
    {
        $values['url'] = Nette\Utils\Strings::webalize($values['name']);
        parent::save($values);
    }


    protected function onPostPersist()
    {
        parent::onPostPersist();
        $presenter = $this->getPresenter();
        $presenter->flashMessage('Přidán druh skladeb');
    }

    protected function onPostUpdate()
    {
        parent::onPostUpdate();
        $presenter = $this->getPresenter();
        $presenter->flashMessage('Druh skladeb upraven');
    }



}