<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Forms\CategoryForm;
use App\AdminModule\Forms\ICategoryFactory;
use App\AdminModule\Forms\IProductFactory;
use App\AdminModule\Forms\ProductForm;
use App\AdminModule\Repositories\CatalogProductPriceRepository;
use App\AdminModule\Repositories\CatalogRepository;
use App\Entities\CatalogCategoryEntity;
use App\Entities\CatalogItemEntity;
use App\Entities\CatalogProductPriceEntity;
use Doctrine\ORM\Query;
use Grido\Grid;
use Nette\Utils\Strings;


/**
 * Class CatalogPresenter
 *
 * @package AdminModule
 */
class CatalogPresenter extends BasePresenter
{

    /** @var CatalogCategoryEntity @inject */
    public $category;

    /** @var CatalogItemEntity @inject */
    public $products;

    /** @var CatalogRepository @inject */
    public $categoryRepository;

    /** @var CatalogProductPriceRepository @inject */
    public $categoryPriceRepository;

    /** @var ICategoryFactory @inject */
    public $categoryFormFactory;

    /** @var IProductFactory @inject */
    public $productFormFactory;


    protected function startup()
    {
        parent::startup();

        if ($this->isAjax()) {
            $this->setLayout(false);
        }
    }


    public function renderDefault()
    {

    }

    public function actionCategoryInsert()
    {
        /** @var $form CategoryForm */
        $form = $this['categoryForm'];
        $form->setRedirect('Catalog:');
    }


    public function actionCategoryEdit($id)
    {
        /** @var $form CategoryForm */
        $form = $this['categoryForm'];

        $entity = $this->em->getDao(CatalogCategoryEntity::getClassName())->find($id);
        $form->setDefaultsFromEntity($entity);
        $form->setRedirect('Catalog:');

    }


    public function actionCategoryDelete($id)
    {
        $this->categoryRepository->delete($id);
        $this->flashMessage('Article was deleted');
        $this->redirectDefault();
    }



    public function actionProductInsert()
    {
        /** @var $form ProductForm */
        $form = $this['productForm'];
        $form->setRedirect('Catalog:');
    }


    public function actionProductEdit($id)
    {
        /** @var $form ProductForm */
        $form = $this['productForm'];

        $entity = $this->em->getDao(CatalogItemEntity::getClassName())->find($id);
        if (!$entity) {
            $this->error('Record not found');
        }

        $form->setDefaultsFromEntity($entity);
        $form->setRedirect('Catalog:');
    }


    public function actionPriceToggle($id)
    {
        $entity = $this->em->getDao(CatalogProductPriceEntity::getClassName())->find($id);
        if (!$entity) {
            $this->error('Record not found');
        }

        $entity->accepted = !$entity->accepted;
        $this->em->getDao(CatalogProductPriceEntity::getClassName())->save($entity);

        $this->flashMessage('Cena upravena');
        $this->redirect('Catalog:prices');
    }


    public function actionPriceDelete($id)
    {
        $this->categoryPriceRepository->delete($id);
        $this->flashMessage('Price was deleted');
        $this->redirect('Catalog:prices');
    }


    /*
     * ---------------------------------------------------------------------------------
     * Grids
     * ---------------------------------------------------------------------------------
     */
    protected function createComponentCategoryGrid($name)
    {
        $grid = new Grid($this, $name);

        $repository  = $this->em->getRepository('App\Entities\CatalogCategoryEntity');
        $model       = new \Grido\DataSources\Doctrine($repository->createQueryBuilder('a'));
        $grid->model = $model;


        $grid->addColumnText('id', 'ID')
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('name', 'Jméno')
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('text', 'Popis')
            ->setCustomRender(function ($entity) {
                return Strings::truncate(strip_tags($entity->text), 32);
            })
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->addActionHref('categoryEdit', 'Edit')
            ->setIcon('pencil');

        $grid->addActionHref('categoryDelete', 'Delete')
            ->setIcon('trash')
            ->setConfirm(function ($item) {
                return "Are you sure you want to delete {$item->name} {$item->name}?";
            });

//        $operation = array('print' => 'Print', 'delete' => 'Delete');
//        $grid->setOperation($operation, $this->gridOperationsHandler)->setConfirm('delete', 'Are you sure you want to delete %i items?');
//        $grid->filterRenderType = $this->filterRenderType;
//        $grid->setExport();

    }

    protected function createComponentItemsGrid($name)
    {
        $grid = new Grid($this, $name);

        $repository = $this->em->getRepository($this->products);
        $model      = new \Grido\DataSources\Doctrine(
            $repository->createQueryBuilder('a')
                ->addSelect('c.name as nazev')
                ->innerJoin('a.category', 'c'),
            array('category' => 'c.nazev')); // Map country column to the title of the Country entity
        $grid->model = $model;

        $grid->addColumnText('id', 'ID')
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('category', 'Katalog')
            ->setCustomRender(function ($entity) {
                return $entity->category->name;
            })
            ->setSortable()
            ->setFilterText();

        $grid->addColumnText('name', 'Name')
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('text', 'Text')
            ->setCustomRender(function ($entity) {
                return Strings::truncate(strip_tags($entity->text), 32);
            })
            ->setSortable()
            ->cellPrototype->class[] = 'center';

        $grid->addActionHref('productEdit', 'Edit')
            ->setIcon('pencil');

        $grid->addActionHref('delete', 'Delete')
            ->setIcon('trash')
            ->setConfirm(function ($item) {
                return "Are you sure you want to delete {$item->name} {$item->id}?";
            });

//        $operation = array('print' => 'Print', 'delete' => 'Delete');
//        $grid->setOperation($operation, $this->gridOperationsHandler)
//            ->setConfirm('delete', 'Are you sure you want to delete %i items?');

//        $grid->filterRenderType = $this->filterRenderType;
        $grid->setExport();
    }


    protected function createComponentPricesGrid($name)
    {
        $grid = new Grid($this, $name);

        $repository = $this->em->getRepository(CatalogProductPriceEntity::getClassName());
        $model      = new \Grido\DataSources\Doctrine(
            $repository->createQueryBuilder('a')
                ->addSelect('p.name as nazev')
                ->innerJoin('a.product', 'p'),
            array('product' => 'p.nazev')); // Map country column to the title of the Country entity


        $grid->model = $model;

        $grid->addColumnText('product', 'Product')
            ->setCustomRender(function ($entity) {
                return $entity->product;
            })
            ->setSortable()
            ->setFilterText();

        $grid->addColumnNumber('price', 'Cena')
            ->setSortable()
            ->setFilterNumber()
            ->setSuggestion();

        $grid->addColumnText('accepted', 'Acceptováno')
            ->setCustomRender(function ($entity) {
                return $entity->accepted == true ? 'ano' : 'ne';
            })
            ->setSortable()
            ->setFilterText();

        $grid->addColumnText('readOk', 'Přečteno správně')
            ->setCustomRender(function ($entity) {
                return $entity->readOk == true ? 'ano' : 'ne';
            })
            ->setSortable()
            ->setFilterText();


        $grid->addColumnDate('created', 'Datum zápisu')
            ->setSortable()
            ->setFilterDate();


        $grid->addActionHref('priceToggle', 'Toggle')
            ->setIcon('pencil');

        $grid->addActionHref('priceDelete', 'Delete')
            ->setIcon('trash')
            ->setConfirm(function ($item) {
                return "Are you sure you want to delete ?";
            });

        $operation = array('enableAccepted' => 'Acceptovat', 'disableAccepted' => 'Neacceptovat', 'delete' => 'Smazat');
        $grid->setOperation($operation, $this->gridOperationsHandler)->setConfirm('delete', 'Are you sure you want to delete %i items?');
//        $grid->filterRenderType = $this->filterRenderType;
        $grid->setExport();

    }


    /**
     * Handler for operations.
     * @param string $operation
     * @param array $id
     */
    public function gridOperationsHandler($operation, $id)
    {
        die(dump($id));
        if ($id) {
            $row = implode(', ', $id);
            $this->flashMessage("Process operation '$operation' for row with id: $row...", 'info');
        } else {
            $this->flashMessage('No rows selected.', 'error');
        }

        $this->redirect($operation, array('id' => $id));
    }


    /*
     * ---------------------------------------------------------------------------------
     * Components
     * ---------------------------------------------------------------------------------
     */
    protected function createComponentCategoryForm()
    {
        $form = $this->categoryFormFactory->create();
        return $form;
    }


    protected function createComponentProductForm()
    {
        $form = $this->productFormFactory->create();
        return $form;
    }


    /*
     * ---------------------------------------------------------------------------------
     * Services
     * ---------------------------------------------------------------------------------
     */
    private function redirectDefault()
    {
        $this->redirect(':Admin:Catalog:');
    }

}
