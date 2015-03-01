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


use App\AdminModule\repositories\ArticleRepository;
use App\FrontModule\Forms\AbstractForm;
use App\Model\ImageModel;
use Nette\Application\UI\Form;
use Nette;


interface IArticleFactory
{

    /** @return ArticleForm */
    function create();
}


class ArticleForm extends AbstractForm
{

    use \Devrun\DoctrineForms\EntityForm;

    /** @var \App\Model\ImageModel */
    private $imageModel;

    /** @var ArticleRepository */
    private $articleRepository;

    /**
     * @param \App\AdminModule\repositories\ArticleRepository $articleRepository
     * @param \App\Model\ImageModel                           $imageModel
     */
    public function __construct(ArticleRepository $articleRepository, ImageModel $imageModel)
    {
        parent::__construct();
        $this->imageModel        = $imageModel;
        $this->articleRepository = $articleRepository;
        $this->bootstrap3Render();
        $this->startup();

    }


    public function startup()
    {
        $this->addText('title', 'Titulek:');
        $this->addText('page', 'Stránka:');
        $this->addText('uri', 'Url článku:')
            ->addRule(Form::FILLED, 'Vyplňte');
        $this->addText('section', 'Sekce:');
        $this->addTextArea('perex', 'Perex:')
            ->addRule(Form::MAX_LENGTH, 'Poznámka je příliš dlouhá', 10000)
            ->setAttribute('class', 'ckeditor');

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

        $values = $form->getValues();
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
            } catch (Nette\InvalidArgumentException $exc) {
                $presenter->flashMessage($exc->getMessage(), 'error');
                $presenter->redirect($this->redirect);
            }

        } elseif ($file->error == UPLOAD_ERR_NO_FILE) {
            unset($values['image']);
        }

        $this->entityMapper->getEntityManager()->persist($this->entity);
        $this->entityMapper->getEntityManager()->flush();

        $presenter->flashMessage('Článek byl upraven');
        $presenter->redirect($this->redirect);
    }


}