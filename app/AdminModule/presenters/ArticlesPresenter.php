<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Forms\ArticleForm;
use App\AdminModule\Forms\IArticleFactory;
use App\AdminModule\repositories\ArticleRepository;
use App\Entities\ArticleEntity;
use Grido\Grid;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;


/**
 * Class ArticlesPresenter
 *
 * @package AdminModule
 */
class ArticlesPresenter extends BasePresenter
{

    /** @var IArticleFactory @inject */
    public $articleFormFactory;

    /** @var ArticleEntity @inject */
    public $articleEntity;

    /** @var ArticleRepository @inject */
    public $articleRepository;



    protected function startup()
    {
        parent::startup();

        if ($this->isAjax()) {
            $this->setLayout(false);
        }

    }


    public function renderDefault()
    {

//        die(dump($this->database->getRepository('App\Entities\ArticleEntity')));
    }


    public function actionInsert()
    {
        /** @var $form ArticleForm */
        $form = $this['articleForm'];
        $form->setRedirect('Articles:');
    }


    public function actionEdit($id)
    {
        $this->articleEntity = $this->articleRepository->find($id);

        /** @var $form ArticleForm */
        $form = $this['articleForm'];
        $form->setRedirect('Articles:');
    }


    public function actionDelete($id)
    {
        $article = $this->em->getRepository($this->articleEntity)->find($id);
        if ($article) {
            $this->em->getRepository($this->articleEntity)->delete($article);
        }

        $this->flashMessage('Article was deleted');
        $this->redirectDefault();
    }

    protected function createComponentArticleGrid($name)
    {
        $grid = new Grid($this, $name);

        $repository = $this->em->getRepository('App\Entities\ArticleEntity');
        $model      = new \Grido\DataSources\Doctrine(
            $repository->createQueryBuilder('a'));
//                ->addSelect('c')
//                ->innerJoin('a.country', 'c'),
//            array('country' => 'c.title')); // Map country column to the title of the Country entity
        $grid->model = $model;

        $grid->addColumnText('id', 'ID')
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('page', 'Page')
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('section', 'Sekce')
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('title', 'Titulek')
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('text', 'Text')
            ->setTruncate(32)
            ->setSortable()
            ->cellPrototype->class[] = 'center';


//        $grid->addColumnDate('birthday', 'Birthday', Date::FORMAT_TEXT)
//            ->setSortable()
//            ->setFilterDate()
//            ->setCondition($this->gridBirthdayFilterCondition);
//        $grid->getColumn('birthday')->cellPrototype->class[] = 'center';

//        $baseUri = $this->template->baseUri;
//        $grid->addColumnText('country', 'Country')
//            ->setSortable()
//            ->setCustomRender(function ($item) use ($baseUri) {
//                $img = Html::el('img')->src("$baseUri/img/flags/$item->country_code.gif");
//                return "$img $item->country";
//            })
//            ->setFilterText()
//            ->setSuggestion();

//        $grid->addColumnText('card', 'Card')
//            ->setSortable()
//            ->setColumn('cctype') //name of db column
//            ->setReplacement(array('MasterCard' => Html::el('b')->setText('MasterCard')))
//            ->cellPrototype->class[] = 'center';

//        $grid->addColumnEmail('emailaddress', 'Email')
//            ->setSortable()
//            ->setFilterText();
//        $grid->getColumn('emailaddress')->cellPrototype->class[] = 'center';
//
//        $grid->addColumnText('centimeters', 'Height')
//            ->setSortable()
//            ->setFilterNumber();
//        $grid->getColumn('centimeters')->cellPrototype->class[] = 'center';

//        $grid->addFilterSelect('gender', 'Gender', array(
//            ''       => '',
//            'female' => 'female',
//            'male'   => 'male'
//        ));
//
//        $grid->addFilterSelect('card', 'Card', array(
//            ''           => '',
//            'MasterCard' => 'MasterCard',
//            'Visa'       => 'Visa'
//        ))
//            ->setColumn('cctype');

//        $grid->addFilterCheck('preferred', 'Only preferred girls :)')
//            ->setCondition(array(
//                    TRUE => array(array('gender', 'AND', 'centimeters'), array('= ?', '>= ?'), array('female', 170)))
//            );

        $grid->addActionHref('edit', 'Edit')
            ->setIcon('pencil');

        $grid->addActionHref('delete', 'Delete')
            ->setIcon('trash')
            ->setConfirm(function ($item) {
//                die(dump($item));
                return "Are you sure you want to delete {$item->title} {$item->page}?";
            });

        $operation = array('print' => 'Print', 'delete' => 'Delete');
//        $grid->setOperation($operation, $this->gridOperationsHandler)
//            ->setConfirm('delete', 'Are you sure you want to delete %i items?');

//        $grid->filterRenderType = $this->filterRenderType;
        $grid->setExport();
    }


    protected function createComponentArticleForm()
    {
        $form = $this->articleFormFactory->create();
        $mapper = new \Devrun\DoctrineForms\EntityFormMapper($this->em);

        $form->injectEntityMapper($mapper);
        $form->bindEntity($this->articleEntity);
        return $form;
    }

    public function articleFormSubmitted(Form $form)
    {
        $values = $form->getValues();
        if (!$values['id']) {
            $this->article->title   = $values->title;
            $this->article->page    = $values->page;
            $this->article->section = $values->section;
            $this->article->text    = $values->text;
            $this->article->created = new DateTime("now");

            $this->em->persist($this->article);
            $this->em->flush();
            $this->flashMessage('Článek byl vytvořen');

        } else {
            $article = $this->em->getRepository($this->article)->find($values['id']);

            $article->title   = $values->title;
            $article->page    = $values->page;
            $article->section = $values->section;
            $article->text    = $values->text;
            $article->updated = new DateTime("now");

            $this->em->merge($article);
            $this->em->flush();
            $this->flashMessage('Článek byl upraven');
        }

        $this->redirectDefault();
    }


    private function redirectDefault()
    {
        $this->redirect(':Admin:Articles:');
    }

}
