<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-20
 * Time: 16:28
 */

namespace Hll\Foundation;

use Hll\Config\Config;
use Hll\Http\Request;
use Hll\Http\Response;

class Application extends Container
{
    private $aliaClass = [
    ];

    private $baseProviders = [
    ];

    private $serviceProviders = [
        'request' => Request::class,
        'response' => Response::class
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
        $this->bind('path.runtime', $root_path . '/runtime');
    }

    public function initConfig()
    {
        $app_config_file = $this->make('path.config') . '/app.php';
        if (is_file($app_config_file)) {
            $app_config = require_once($app_config_file);
            $this->aliaClass = array_merge($this->aliaClass, $app_config['alias']);
            $this->baseProviders = array_merge($this->baseProviders, $app_config['providers']);
            $config = new Config($app_config);
            $this->bind('config', $config);
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

    public function run()
    {
        $this->registerServiceProviders();
        $request = $this->initRequest();

        $kernel = $this->make(\Hll\Http\Kernel::class);
        $response = $kernel->handle($request);
        return $response;
    }

    public function registerServiceProviders()
    {
        foreach ($this->serviceProviders as $k => $v) {
            $this->bind($k, $v);
        }
    }

    public function initRequest()
    {
        $request = $this->request;
        $request->setGet($_GET);
        $request->setPost($_POST);
        $request->setRaw(file_get_contents('php://input'));

        $this->instance('request', $request);
        $this->instance(\Hll\Http\Request::class, $request);
        return $request;
    }

    public function registerProvider()
    {
        $cache = $this->loadCacheProvider();
        if (is_null($cache) || !isset($cache['providers']) || $cache['providers'] != $this->baseProviders) {
            $cache = $this->makeCacheProvider($this->baseProviders);
        }
        $this->providerCache = $cache;
        foreach ($cache['dependency'] as $provider) {
            $this->loadProvider($provider);
        }
    }

    public function makeCacheProvider($providers)
    {
        $array = [
            'providers' => $providers,
            'delay' => [],
            'dependency' => [],
            'alias' => []
        ];

        foreach ($providers as $provider) {
            $instance = new $provider();

            $isDelay = $instance->isDelay();

            if ($isDelay) {
                $array['delay'][] = $provider;
            } else {
                $array['dependency'][] = $provider;
            }

            $alias = $instance->providers();
            foreach ($alias as $k => $v) {
                $array['alias'][$v] = $provider;
            }
        }
        $this->writeCacheProvider($array);
        return $array;
    }

    public function writeCacheProvider($content)
    {
        is_dir($this->make('path.runtime')) || mkdir($this->make('path.runtime'), 0777);
        $file_name = $this->make('path.runtime') . '/providers.php';
        $data = '<?php return ' . var_export($content, true) . ';';
        file_put_contents($file_name, $data);
    }

    public function loadCacheProvider()
    {
        $file_name = $this->make('path.runtime') . '/providers.php';

        if (!is_file($file_name)) {
            return null;
        }
        return include_once($file_name);
    }
}