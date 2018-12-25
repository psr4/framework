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
    public $aliaClass = [
    ];

    public $baseProviders = [

    ];

    public function __construct($dir)
    {
        $this->displayErrors();
        $this->initInstance();
        $this->initDir($dir);
        $this->initConfig();
        $this->initAlias();
        $this->registerProvider();
    }

    public function displayErrors()
    {
        error_reporting(-1);
        set_error_handler([$this, 'errorHandle']);
        set_exception_handler([$this, 'exceptionHandle']);
        register_shutdown_function([$this, 'shutdownHandle']);
    }

    public function errorHandle($code, $message, $file, $line, $errors)
    {
        echo "code: {$code} ,<br> message: {$message},<br> file: {$file},<br> line: {$line}";
    }

    public function exceptionHandle($e)
    {
        echo "code: {$e->getCode()} ,<br> message: {$e->getMessage()},<br> file: {$e->getFile()},<br> line: {$e->getLine()}";
    }

    public function shutdownHandle()
    {
        if (!is_null($error = error_get_last())) {
            echo 'shutdownHandle';
            var_dump($error);
        }
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
        $root_path = $dir;
        $this->bind('path.root', $root_path);
        $this->bind('path.framework', dirname(__FILE__));
        $this->bind('path.config', $root_path . '/config');
        $this->bind('path.app', $root_path . '/app');
        $this->bind('path.public', $root_path . '/public');
    }

    public function initConfig()
    {
        $app_config_file = $this->make('path.config') . '/app.php';
        if (is_file($app_config_file)) {
            $app_config = require_once($app_config_file);
            $this->aliaClass = array_merge($this->aliaClass, $app_config['alias']);
            $this->baseProviders = array_merge($this->baseProviders, $app_config['providers']);
            $this->bind('config', Config::class);
            $this->bind(Config::class, Config::class);
            $this->bind('app_config', function () use ($app_config) {
                return $this->make('config', ['config' => $app_config]);
            });
        }
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