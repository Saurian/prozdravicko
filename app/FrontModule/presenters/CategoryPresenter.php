<?php

namespace App\FrontModule\Presenters;
use App\AdminModule\Repositories\CatalogItemRepository;
use App\AdminModule\repositories\CatalogRepository;
use App\Entities\CatalogCategoryEntity;


/**
 * Homepage presenter.
 */
class CategoryPresenter extends BasePresenter
{

    /** @var CatalogRepository @inject */
    public $category;

    /** @var CatalogItemRepository @inject */
    public $item;

    public function renderDefault()
    {
        $q = $this->em->getDao(CatalogCategoryEntity::getClassName())->findAll();

        die(dump($q));



        $this->template->catalog = $this->category->findBy(array());
        $this->template->items = $this->item->findBy(array('id' => 1));



    }


}
