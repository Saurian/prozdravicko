<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 *
 * slider
 *
 * @created 15.9.14
 * @package ${MODULE_NAME}Module
 * @author  Pavel
 */

namespace App\FrontModule\components;


use App\AdminModule\repositories\ArticleRepository;
use Nette\Application\UI\Control;


interface ISliderFactory
{
    /** @return Slider */
    function create();
}


class Slider extends Control {


    /** @var ArticleRepository */
    private $articleRepository;

    function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/slider.latte');
        $template->slides = $this->articleRepository->findBy(array('section' => 'slider'));
        $template->render();
    }


    public function renderDefault() {
        die(dump($this));
    }

}

