<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-21
 * Time: 18:00
 */

namespace Hll\Facades;


use Hll\Supports\Facades;

class Request extends Facades
{
    public static function getFacadeAccessor()
    {
        return 'request';
    }
}