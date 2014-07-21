<?php

namespace App\FrontModule\Presenters;

use App\AdminModule\Repositories\CatalogItemRepository;
use App\AdminModule\Repositories\CatalogRepository;
use App\Model\Common;
use Nette;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

    /** @var CatalogRepository @inject */
    public $category;

    /** @var CatalogItemRepository @inject */
    public $item;

    /** @var Common @inject */
    public $commonModel;


    protected function startup()
    {
        parent::startup();
        $this->template->categories = $this->category->findBy(array());
    }


    public function renderDefault()
    {
        $this->template->items = $this->item->findBy(array());
    }


    public function renderDetail($id)
    {
        $this->template->items = $this->category->findAllBy($id);
    }


    public function renderProductDetail($year, $id)
    {
        $product = $this->item->find($id);
        $this->template->item = $product;


        $this->template->link = $this->commonModel->getAbsoluteUrl($product->link);

    }

}
