<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * ProductRoute
 *
 * @created 13.7.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\Router;


use App\AdminModule\Repositories\CatalogItemRepository;
use App\Entities\CatalogCategoryEntity;
use Nette\Application\Routers\Route;
use Nette\Application;
use Nette;

class ProductRoute extends Route
{

    /** @var CatalogItemRepository */
    public $catalogItemRepository;


    public function match(Nette\Http\IRequest $httpRequest)
    {
        $appRequest = parent::match($httpRequest);

        if ($appRequest) {
            if (is_numeric($appRequest->parameters['id'])) {

                /** @var CatalogCategoryEntity $category */
                $category = $this->catalogItemRepository->getCategory($appRequest->parameters['id']);

                if ($category == null) {
                    return null;
                }

                $parameters             = $appRequest->getParameters();
                $parameters['category'] = $category->url;
                $appRequest->setParameters($parameters);

            }

        }

        return $appRequest;
    }

    public function constructUrl(Application\Request $appRequest, Nette\Http\Url $refUrl)
    {
        $parameters = $appRequest->getParameters();

        if (isset($parameters['id']) && $parameters['id']) {

            /** @var CatalogCategoryEntity $category */
            $category = $this->catalogItemRepository->getCategory($appRequest->parameters['id']);
            if ($category == NULL) {
                return NULL;
            }

            $parameters['category'] = $category->url;
        }

        $appRequest->setParameters($parameters);
        return parent::constructUrl($appRequest, $refUrl);
    }


}