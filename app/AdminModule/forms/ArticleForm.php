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


use App\Entities\ArticleEntity;
use App\FrontModule\Forms\AbstractForm;
use App\Model\ImageModel;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Form;
use Nette;


interface IArticleFactory
{

    /** @return ArticleForm */
    function create();
}


class ArticleForm extends AbstractForm
{

    /** @var \App\Entities\ArticleEntity */
    protected $entity;

    private $imageModel;

    /**
     * @param \Kdyby\Doctrine\EntityManager $entityManager
     * @param \App\Model\ImageModel         $imageModel
     */
    public function __construct(EntityManager $entityManager, ImageModel $imageModel)
    {
        parent::__construct();
        $this->imageModel = $imageModel;
        $this->em         = $entityManager;
    }


    public function startup()
    {

        $this->addHidden('id');
        $this->addText('title', 'Titulek:');
        $this->addText('page', 'Stránka:');
        $this->addText('section', 'Sekce:');
        $this->addTextArea('text', 'Text:')
            ->addRule(Form::MAX_LENGTH, 'Poznámka je příliš dlouhá', 10000)
            ->setAttribute('class', 'ckeditor');

        $this->addUpload('image', 'Obrázek')
            ->addCondition(Form::FILLED)
            ->addRule(Form::IMAGE, 'Avatar musí být JPEG, PNG nebo GIF.')
            ->addRule(Form::MAX_FILE_SIZE, 'Maximální velikost souboru je 2 MB.', 2 * 1024 * 1024);


        $this->addSubmit('send', 'Vložit článek');
        $this->onSuccess[] = array($this, 'formSubmitted');
    }


    public function formSubmitted(Form $form)
    {
        $values    = $form->getValues();

        die(dump($values));
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

        } elseif ($file->error == UPLOAD_ERR_NO_FILE) {
            unset($values['image']);
        }

        $this->save($values);
        $presenter->redirect($this->redirect);
    }


    protected function onPostPersist()
    {
        parent::onPostPersist();
        $presenter = $this->getPresenter();
        $presenter->flashMessage('Článek byl přidán');
        $presenter->flashMessage("Soubor byl přidán jako avatar", 'info');
    }

    protected function onPostUpdate()
    {
        parent::onPostUpdate();
        $presenter = $this->getPresenter();
        $presenter->flashMessage('Článek byl upraven');
    }


}