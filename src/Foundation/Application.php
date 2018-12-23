<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-20
 * Time: 16:28
 */

namespace Hll\Foundation;

use Hll\Config\Config;

class Application extends Container
{
    private $base_dir;

    public $aliaClass = [
    ];

    public $baseProviders = [

    ];

    public function __construct($dir)
    {
        $this->initInstance();
        $this->initDir($dir);
        $this->initConfig();
        $this->initAlias();
        $this->registerProvider();
    }

    public function initInstance()
    {
        static::setInstance($this);
        $this->instance('app', $this);
        $this->instance(Container::class, $this);
        $this->instance(Application::class, $this);
    }

    public function initDir($dir)
    {
        $this->base_dir = $dir;
        $this->base_src = dirname(__FILE__);
        $this->base_config = $this->base_dir . '/config';
        $this->base_app = $this->base_dir . '/app';
        $this->base_public = $this->base_dir . '/public';
    }

    public function initConfig()
    {
        $app_config = require_once($this->base_config . '/app.php');

        $this->aliaClass = array_merge($this->aliaClass, $app_config['alias']);
        $this->baseProviders = array_merge($this->baseProviders, $app_config['providers']);

        $this->bind('config', Config::class);
        $this->bind(Config::class, Config::class);

    }

    public function initAlias()
    {
        spl_autoload_register([$this, 'load'], true, false);
    }

    public function load($name)
    {
        if (array_key_exists($name, $this->aliaClass)) {
            class_alias($this->alias[$name], $name);
        }
    }

    public function registerProvider()
    {
        foreach ($this->baseProviders as $provider) {
            (new $provider())->register($this);
        }
    }
}