<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-21
 * Time: 14:55
 */

namespace Hll\Supports;


use Hll\Foundation\Container;

interface ServiceProvider
{
    public function register(Container $container);
}