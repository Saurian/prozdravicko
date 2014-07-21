<?php

namespace App\AdminModule\Presenters;


/**
 * Class DashboardPresenter
 *
 * @package AdminModule
 */
final class DashboardPresenter extends BasePresenter
{

    public function renderDefault()
    {
        dump($_SERVER);
    }

}
