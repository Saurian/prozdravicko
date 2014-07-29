<?php

namespace App\FrontModule\Presenters;

use App\AdminModule\Repositories\CatalogRepository;
use App\Model\SitemapModel;
use Nette;


/**
 * Homepage presenter.
 */
class SitemapPresenter extends BasePresenter
{

    /** @var CatalogRepository @inject */
    public $catalogRepository;

    /** @var SitemapModel @inject */
    public $sitemapModel;

    protected function beforeRender()
    {
        parent::beforeRender();
        $this->setLayout(false);
    }


    public function renderSitemap()
    {
        $this->template->sitemap =  $this->sitemapModel->getSitemap();
    }


}
