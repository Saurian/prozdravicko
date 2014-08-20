<?php

namespace App\Model;

use App\AdminModule\Repositories\CatalogItemRepository;
use App\AdminModule\Repositories\CatalogRepository;
use App\Entities\CatalogCategoryEntity;
use Doctrine\ORM\Query;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette\Utils\DateTime;

class SitemapModel extends AbstractModel
{
    /** @var CatalogRepository */
    private $catalogRepository;

    /** @var CatalogItemRepository */
    private $catalogItemRepository;


    public function __construct(CatalogRepository $catalogRepository, CatalogItemRepository $catalogItemRepository)
    {
        $this->catalogRepository     = $catalogRepository;
        $this->catalogItemRepository = $catalogItemRepository;
    }


    public function getSitemap()
    {
        $result     = array();
        $categories = $this->catalogRepository->findBy(array());

        $result[] = array(
            'presenter' => 'Homepage',
            'action'    => 'default',
            'id'        => null,
            'updated'   => new DateTime(),
        );

        foreach ($categories as $category) {
            $result[] = array(
                'presenter' => 'Homepage',
                'action'    => 'detail',
                'id'        => $category->id,
                'updated'   => $category->updated,
            );
        }

        foreach ($categories as $category) {
            foreach ($category->items as $product) {
                $result[] = array(
                    'presenter' => 'Homepage',
                    'action'    => 'productDetail',
                    'id'        => $product->id,
                    'updated'   => $product->updated,
                );
            }
        }

        return $result;
    }


}