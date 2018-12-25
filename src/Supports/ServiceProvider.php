<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-21
 * Time: 14:55
 */

namespace Hll\Supports;


use Hll\Foundation\Container;

class ServiceProvider
{
    public $delay = false;

    public function register(Container $container)
    {

    }

    public function when()
    {
        return [];
    }

    public function providers()
    {
        return [];
    }

    public function isDelay()
    {
        return $this->delay;
    }
}