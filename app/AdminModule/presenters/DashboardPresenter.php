<?php

namespace App\AdminModule\Presenters;

use App\Entities\AccessEntity;
use Grido\Components\Filters\Filter;
use Grido\Grid;


/**
 * Class DashboardPresenter
 *
 * @package AdminModule
 */
final class DashboardPresenter extends BasePresenter
{

    protected function createComponentAccessGrid($name)
    {
        $grid = new Grid($this, $name);

        $repository  = $this->em->getRepository(AccessEntity::getClassName());
        $model       = new \Grido\DataSources\Doctrine(
            $repository->createQueryBuilder('a')
        );
        $grid->model = $model;

        $grid->addColumnDate('created', 'Datum')
            ->setSortable()
            ->setFilterDateRange();

        $grid->addColumnText('uri', 'Uri')
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('referer', 'Referer')
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('userAgent', 'User agent')
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->setFilterRenderType(Filter::RENDER_INNER);

    }


    public function renderDefault()
    {

    }

}
