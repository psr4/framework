<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-21
 * Time: 11:06
 */

namespace Hll\Supports;

use Hll\Foundation\Application;

abstract class Facades
{

    public static function getFacadeAccessor()
    {
        throw new \Exception('please redefine this method!');
    }

    public static function __callStatic($name, $arguments)
    {
        $abstract = static::getFacadeAccessor();
        $app = Application::getInstance();
        if ($app->bound($abstract)) {
            $instance = $app->make($abstract);
            return call_user_func_array([$instance, $name], $arguments);
        }
    }

}