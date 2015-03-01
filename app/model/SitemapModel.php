<?php

namespace App\Model;

use App\AdminModule\repositories\ArticleRepository;
use App\AdminModule\Repositories\CatalogItemRepository;
use App\AdminModule\Repositories\CatalogRepository;
use Doctrine\ORM\Query;
use Nette\Utils\DateTime;

class SitemapModel extends AbstractModel
{
    /** @var CatalogRepository */
    private $catalogRepository;

    /** @var CatalogItemRepository */
    private $catalogItemRepository;

    /** @var ArticleRepository */
    private $articleRepository;


    public function __construct(
        CatalogRepository $catalogRepository,
        CatalogItemRepository $catalogItemRepository,
        ArticleRepository $articleRepository)
    {
        $this->catalogRepository     = $catalogRepository;
        $this->catalogItemRepository = $catalogItemRepository;
        $this->articleRepository     = $articleRepository;
    }


    public function getSitemap()
    {
        $result     = array();
        $categories = $this->catalogRepository->findBy(array());
        $articles   = $this->articleRepository->findBy(array());

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

        foreach ($articles as $article) {
            $result[] = array(
                'presenter' => 'Homepage',
                'action'    => 'article',
                'id'        => $article->id,
                'updated'   => $article->updated,
            );
        }

        return $result;
    }


}