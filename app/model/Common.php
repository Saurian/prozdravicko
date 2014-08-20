<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * ImageModel
 *
 * @created 29.6.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\Model;

use Nette\Object;

class Common extends Object
{

    /** @var array */
    private $_prozdravi;

    function __construct(array $prozdravi)
    {
        $this->_prozdravi = $prozdravi;
    }

    public function getAbsoluteUrl($url)
    {
        return $this->_prozdravi['debugger']
            ? $url
            : $url . '?d=' . $this->_prozdravi['id'];
    }


    /**
     * odstraní mezery z čísla
     *
     * @param $price
     *
     * @return int
     */
    public function numericDBFormat($price)
    {
        return intval(preg_replace('/[^0-9]/', '', $price));
    }

} 