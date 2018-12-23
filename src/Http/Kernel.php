<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-20
 * Time: 16:30
 */

namespace Hll\Http;
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
        return function ($request) {
            $module = $request->module();
            $controller = $request->controller();
            $action = $request->action();
            $namespace = $this->buildNameSpace($module, $controller);
            $this->app->bind($namespace);
            $controller = $this->app->make($namespace);
            $result = $this->app->invokeMethod([$controller, $action]);
            $response = $this->app->make('response', $result);
            return $response;
        };
    }

    public function buildNameSpace($module, $controller)
    {
        return 'App\\' . ucfirst($module) . '\\Controller\\' . ucfirst($controller);
    }
}