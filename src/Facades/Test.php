<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-21
 * Time: 11:06
 */

namespace Hll\Facades;

use Hll\Supports\Facades;

class Test extends Facades
{
    public static function getFacadeAccessor()
    {
        return 'Test';
    }
}