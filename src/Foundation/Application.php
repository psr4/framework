<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-20
 * Time: 16:28
 */

namespace Hll\Foundation;

use Hll\Facades\Request;
use Hll\Facades\Test;
use Hll\Provider\TestProvider;

class Application extends Container
{
    private $base_dir;

    public $alias = [
        'Test' => Test::class,
        'Request' => Request::class
    ];

    public function __construct($dir)
    {
        static::setInstance($this);
        $this->instance('app', $this);
        $this->instance(Container::class, $this);
        $this->base_dir = $dir;
        $this->base_src = dirname(__FILE__);
        $this->initAlias();
        $this->registerProvider();
    }

    public function initAlias()
    {
        spl_autoload_register([$this, 'load'], true, false);
    }

    public function load($name)
    {
        if (array_key_exists($name, $this->alias)) {
            class_alias($this->alias[$name], $name);
        }
    }

    public function registerProvider()
    {
        foreach ([
                     TestProvider::class
                 ] as $provider) {
            (new $provider())->register($this);
        }
    }
}