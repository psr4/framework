<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-20
 * Time: 16:30
 */

namespace Hll\Contracts\Http;

use Hll\Foundation\Container;
use Hll\Http\Request;

class Kernel
{
    public $app;

    protected $middleware = [];

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function handle(Request $request)
    {
        $closure = $this->getClosure();
        $middlewares = $this->middleware;
        while ($middleware = array_pop($middlewares)) {
            $closure = (new $middleware)->getClosure($closure);
        }
        return $closure($request);
    }

    public function getClosure()
    {
        $app = $this->app;
        return function ($request) use ($app) {
            $r = $request->input('r');
            $array = array_filter(explode('/', $r));
            $module = isset($array[0]) ? $array[0] : 'index';
            $controller = isset($array[1]) ? $array[1] : 'index';
            $function = isset($array[2]) ? $array[2] : 'index';
            $namespace = 'App\\' . ucfirst($module) . '\\Controller\\' . ucfirst($controller);
            $this->app->bind($namespace);
            $controller = $this->app->make($namespace);
            $result = $app->invokeMethod([$controller, $function]);
            $response = $this->app->make('response', $result);
            return $response;
        };
    }
}