<?php

namespace App\FrontModule\Presenters;

use App\AdminModule\repositories\ArticleRepository;
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

    /** @var ArticleRepository @inject */
    public $articleRepository;


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
        $product       = $this->item->find($id);
        $acceptedPrice = $this->item->findByLastAcceptedPrice($id);

        $this->template->item        = $product;
        $this->template->link        = $this->commonModel->getAbsoluteUrl($product->link);
        $this->template->price       = isset($acceptedPrice->prices) ? ($acceptedPrice->prices[0]->price) : null;
        $this->template->description = trim(html_entity_decode($product->text));

    }


    public function renderArticle($id) {
        $this->template->article = $this->articleRepository->find($id);
    }

}
