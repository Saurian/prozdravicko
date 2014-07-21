<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * AccessModel
 *
 * @created 20.7.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\Model;


use Nette\Object;
use Nette\Utils\DateTime;

class AccessModel extends Object
{

    public function getClientIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '?';
        }

        return $ip;
    }


    public function getReferer()
    {
        return !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }

    public function getUserAgent()
    {
        return !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }

    public function getRequestUri()
    {
        return !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    }

    /**
     * @return int|null
     */
    public function getRequestTime()
    {
        return !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : NULL;
    }

}