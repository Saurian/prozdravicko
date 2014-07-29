<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * WebLoaderModel
 *
 * @created 21.7.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\Model;


use Nette\Object;

class WebContentLoaderModel extends Object
{

    protected $priceRegex = array(
        '%<span\s+class=\"o-cena\">.*?(\d+[\s]*\d*).*Kč</span>%si', // tato maska by měla odhalit vše, proto je dobré ji mít na prvním místě
        '%<span\s+class=\"o-cena\">.*?>(\d+[\s]*\d*).*Kč</div></span>%si',
    );

    /**
     * @param string $priceRegex
     */
    public function setPriceRegex($priceRegex)
    {
        $this->priceRegex = $priceRegex;
    }

    /**
     * @return string
     */
    public function getPriceRegex()
    {
        return $this->priceRegex;
    }


    /**
     * pozn ... $pattern = '/<div\s+class="wherebuy">\s*<p\s+class="price">\s*<a\s+(.*?)href="(?P<url>.*?)"/si';
     */
    public function getPrice($url)
    {
        $content = file_get_contents($url);
        $result  = false;
        $matches = array();
        foreach ($this->priceRegex as $priceRegex) {
            $result = preg_match($priceRegex, $content, $matches);
            if ($result) {
                break;
            }
        }

        return $result ? $matches[1] : false;
    }

} 