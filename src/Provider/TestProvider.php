<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-21
 * Time: 14:55
 */

namespace Hll\Provider;

use Hll\Foundation\Container;
use Hll\Supports\ServiceProvider;

class TestProvider extends ServiceProvider
{
    public $delay = true;

    public function register(Container $container)
    {
        $container->bind('test', '123');
    }

    public function providers()
    {
        return ['test'];
    }
}