<?php

namespace App\FrontModule\Presenters;

use App\AdminModule\Repositories\CatalogItemRepository;
use App\AdminModule\Repositories\CatalogProductPriceRepository;
use App\AdminModule\Repositories\CatalogRepository;
use App\Entities\CatalogProductPriceEntity;
use App\Model\Common;
use App\Model\WebContentLoaderModel;
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

    /** @var WebContentLoaderModel @inject */
    public $webContentLoaderModel;


    /** @var CatalogProductPriceRepository @inject */
    public $catalogProductPriceRepository;


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


    public function renderProductDetail($product, $id)
    {
//        die(dump($this->link("Homepage:productDetail", array('id' => 2))));
        $product = $this->item->find($id);
        $this->template->item = $product;


        dump($product->link);

        $price = $this->webContentLoaderModel->getPrice($product->link);

        $entity = new CatalogProductPriceEntity();
        $entity
            ->setPrice($price)
            ->setProduct($product);

        $this->catalogProductPriceRepository->getDao()->save($entity);

        $this->template->link = $this->commonModel->getAbsoluteUrl($product->link);

    }

}
