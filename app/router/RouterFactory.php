<?php

namespace App;

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

    function __construct(CatalogRepository $_catalogRepository, CatalogItemRepository $_catalogItemRepository)
    {
        $this->_catalogRepository = $_catalogRepository;
        $this->_catalogItemRepository = $_catalogItemRepository;
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

        $router[]      = $frontRouter = new RouteList('Front');
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

        $productRoute = new ProductRoute('[<year=15>/]<id>', array(
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
        $frontRouter[] = $productRoute;

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
