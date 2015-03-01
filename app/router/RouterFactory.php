<?php

namespace App;

use App\AdminModule\repositories\ArticleRepository;
use App\AdminModule\Repositories\CatalogItemRepository;
use App\AdminModule\Repositories\CatalogRepository;
use App\Router\ProductRoute;
use Nette,
    Nette\Application\Routers\RouteList,
    Nette\Application\Routers\Route;


/**
 * Router factory.
 */
class RouterFactory
{

    /** @var CatalogItemRepository */
    private $_catalogItemRepository;

    /** @var CatalogRepository */
    private $_catalogRepository;

    /** @var ArticleRepository */
    private $_articleRepository;


    function __construct(CatalogRepository $catalogRepository, CatalogItemRepository $catalogItemRepository, ArticleRepository $articleRepository)
    {
        $this->_catalogRepository     = $catalogRepository;
        $this->_articleRepository     = $articleRepository;
        $this->_catalogItemRepository = $catalogItemRepository;
    }


    /**
     * @return \Nette\Application\IRouter
     */
    public function createRouter()
    {
        $router   = new RouteList();
        $router[] = new Route('index.php', 'Front:Homepage:default', Route::ONE_WAY);

        $router[]      = $adminRouter = new RouteList('Admin');
        $adminRouter[] = new Route('admin/[<locale=cs cs|en>/]<presenter>/<action>[/<id>]', 'Dashboard:default');

        $router[]     = $cronRouter = new RouteList('Cron');
        $cronRouter[] = new Route('cron/<presenter>/<action>[/<id>]', 'Cron:default');

        $router[] = $frontRouter = new RouteList('Front');

        $frontRouter[] = new Route('sitemap.xml', array(
            'presenter' => 'Sitemap',
            'action'    => 'sitemap',
        ));

        $frontRouter[] = new Route('<id>', array(
            'presenter' => 'Homepage',
            'action'    => 'detail',
            'id'        => array(
                Route::FILTER_IN  => function ($url) {
                        return $this->_catalogRepository->getIdByUrl($url);
                    },
                Route::FILTER_OUT => function ($url) {
                        return $this->_catalogRepository->getUrlById($url);
                    },
            ),
        ));

        $frontRouter[] = new Route('<id>', array(
            'presenter' => 'Homepage',
            'action'    => 'article',
            'id'        => array(
                Route::FILTER_IN  => function ($url) {
                        return $this->_articleRepository->getIdByUrl($url);
                    },
                Route::FILTER_OUT => function ($url) {
                        return $this->_articleRepository->getUrlById($url);
                    },
            ),
        ));

        $productRoute   = new ProductRoute('[<category>/]<id>', array(
            'presenter' => 'Homepage',
            'action'    => 'productDetail',
            'id'        => array(
                Route::FILTER_IN  => function ($url) {
                        return $this->_catalogItemRepository->getIdByUrl($url);
                    },
                Route::FILTER_OUT => function ($url) {
                        return $this->_catalogItemRepository->getUrlById($url);
                    },
            ),
        ));
        $productRoute->catalogItemRepository = $this->_catalogItemRepository;
        $frontRouter[]                       = $productRoute;

        $frontRouter[] = new Route('<presenter>/<action>[/<id>]', array(
                'presenter' => array(
                    Route::VALUE        => 'Homepage',
                    Route::FILTER_TABLE => array(
                        'kontakt' => 'Contact'
                    ),
                ),
                'action'    => 'default',
            )
        );

        return $router;
    }

}
