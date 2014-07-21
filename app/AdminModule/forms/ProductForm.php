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


use App\AdminModule\Repositories\CatalogRepository;
use App\Entities\CatalogCategoryEntity;
use App\FrontModule\Forms\AbstractForm;
use App\Model\ImageModel;
use App\Model\ProductModel;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Form;
use Nette;


interface IProductFactory
{

    /** @return ProductForm */
    function create();
}


class ProductForm extends AbstractForm
{

    /** @var ProductModel */
    private $productModel;

    /** @var CatalogRepository */
    private $catalogRepository;

    /** @var \App\Entities\CatalogItemEntity */
    protected $entity;

    /** @var ImageModel */
    protected $imageModel;

    /**
     * @param \App\Model\ProductModel                         $productModel
     * @param \App\AdminModule\Repositories\CatalogRepository $catalogRepository
     *
     * @internal param null $name
     */
    public function __construct(EntityManager $entityManager, ProductModel $productModel, CatalogRepository $catalogRepository, ImageModel $imageModel)
    {
        parent::__construct();
        $this->imageModel        = $imageModel;
        $this->em                = $entityManager;
        $this->productModel      = $productModel;
        $this->catalogRepository = $catalogRepository;
        $this->startup();
    }


    public function startup()
    {
        $categories = $this->productModel->getSelectList();


        $this->addHidden('id');
        $this->addHidden('url');

        $this->addSelect('category', 'Katalog', $categories)
            ->addRule(Form::FILLED, 'Vyplňte prosím kategorii')
            ->setAttribute('class', 'form-control limited');

        $this->addText('name', 'Jméno:')
            ->addRule(Form::FILLED, 'Vyplňte prosím název')
            ->setAttribute('class', 'form-control limited');

        $this->addText('link', 'Link na tento produkt:')
            ->addRule(Form::FILLED, 'Vyplňte prosím link');

        $this->addTextArea('text', 'Text:')
            ->addRule(Form::MAX_LENGTH, 'Poznámka je příliš dlouhá', 10000)
            ->setAttribute('class', 'ckeditor');

        $this->addTextArea('parameters', 'Parametry:')
            ->addRule(Form::MAX_LENGTH, 'Poznámka je příliš dlouhá', 10000)
            ->setAttribute('class', 'ckeditor');

        $this->addTextArea('content', 'Obsah:')
            ->addRule(Form::MAX_LENGTH, 'Poznámka je příliš dlouhá', 1000)
            ->setAttribute('class', 'ckeditor');
//            ->setAttribute('class', 'form-control limited');

        $this->addTextArea('warning', 'Varování:')
            ->addRule(Form::MAX_LENGTH, 'Poznámka je příliš dlouhá', 10000)
            ->setAttribute('class', 'ckeditor');
//            ->setAttribute('class', 'form-control limited');

        $this->addTextArea('application', 'Použití:')
            ->addRule(Form::MAX_LENGTH, 'Poznámka je příliš dlouhá', 10000)
            ->setAttribute('class', 'ckeditor');
//            ->setAttribute('class', 'form-control limited');

        $this->addTextArea('sign', 'Značka:')
            ->addRule(Form::MAX_LENGTH, 'Poznámka je příliš dlouhá', 10000)
            ->setAttribute('class', 'ckeditor');
//            ->setAttribute('class', 'form-control limited');

        $this->addUpload('image', 'Obrázek')
            ->addCondition(Form::FILLED)
            ->addRule(Form::IMAGE, 'Avatar musí být JPEG, PNG nebo GIF.')
            ->addRule(Form::MAX_FILE_SIZE, 'Maximální velikost souboru je 2 MB.', 2 * 1024 * 1024);

        $this->addSubmit('send', 'Vložit článek');
        $this->onSuccess[] = array($this, 'formSubmitted');


        $this->addButton('close')
            ->setAttribute('class', 'btn btn-default')
            ->setAttribute('data-dismiss', 'modal');

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

    /**
     * preSave
     *
     * @param $values
     */
    public function save($values)
    {
        $values['category'] = $this->catalogRepository->find($values['category']);
        $values['url'] = Nette\Utils\Strings::webalize($values['name']);
        parent::save($values);
    }


    protected function onPostPersist()
    {
        parent::onPostPersist();
        $presenter = $this->getPresenter();
        $presenter->flashMessage('Píseň byla přidána');
    }

    protected function onPostUpdate()
    {
        parent::onPostUpdate();
        $presenter = $this->getPresenter();
        $presenter->flashMessage('Píseň byla upravena');
    }


}