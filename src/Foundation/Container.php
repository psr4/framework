<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/17 0017
 * Time: 21:53
 */

namespace Hll\Foundation;

class Container implements \ArrayAccess
{
    protected static $instance = null;

    public $bindings = [];

    public $with = [];

    public $alias = [];

    public $instances = [];

    public $resolved = [];

    public function alias($alias, $abstract)
    {
        $this->alias[$alias] = $abstract;
    }

    public function isAlias($alias)
    {
        return array_key_exists($alias, $this->alias);
    }

    public function getAlias($alias)
    {
        return $this->alias[$alias];
    }

    public static function setInstance($instance)
    {
        return static::$instance = $instance;
    }

    public static function getInstance()
    {
        return static::$instance;
    }

    public function singleton($abstract, $concrete)
    {
        $this->bind($abstract, $concrete, true);
    }

    public function bind($abstract, $concrete = null, $isShare = false)
    {
        // 解绑已经绑定的
        $this->unbind($abstract);

        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        // 绑定类名
        if (!$concrete instanceof \Closure) {
            $concrete = $this->getClosure($abstract, $concrete);
        }
        $this->bindings[$abstract] = compact('concrete', 'isShare');
    }

    public function getClosure($abstract, $concrete)
    {
        return function ($container, $parameters = []) use ($abstract, $concrete) {
            if ($abstract == $concrete) {
                return $container->InvokeClass($concrete);
            }
            return is_string($concrete) || $concrete instanceof \Closure ? $container->make($concrete, $parameters) : $concrete;
        };
    }

    public function instance($abstract, $instance)
    {
        $this->instances[$abstract] = $instance;
    }

    public function unbind($abstract)
    {
        unset($this->bindings[$abstract]);
    }

    public function invokeMethod($method)
    {
        if (is_array($method)) {
            $class = $method[0];
            $method = $method[1];
        } else {
            $instance = null;
        }
        $reflector = new \ReflectionMethod($class, $method);
        $parameter = $this->resolveParameter($reflector);
        return $reflector->invokeArgs($class, $parameter);
    }

    public function InvokeClass($concrete)
    {
        if (class_exists($concrete)) {
            $reflector = new \ReflectionClass($concrete);
            $construct = $reflector->getConstructor();
            if (is_null($construct)) {
                return $reflector->newInstance();
            }
            $parameter = $this->resolveParameter($construct);
            return $reflector->newInstanceArgs($parameter);
        }
        if ($this->isDelay($concrete)) {
            $this->loadProvider($this->providerCache['alias'][$concrete]);
        }

        return $concrete;
    }

    public $providerCache = [];

    public function isDelay($concrete)
    {
        return isset($this->providerCache['alias']) && array_key_exists($concrete, $this->providerCache['alias']);
    }

    public function loadProvider($provider)
    {
        (new $provider())->register($this);
    }

    public function resolveParameter(\ReflectionMethod $method)
    {
        $parameters = $method->getParameters();
        $result = [];
        $with = $this->getLastWith();
        foreach ($parameters as $parameter) {
            $param_name = $parameter->getName();
            if (is_array($with) && array_key_exists($param_name, $with)) {
                $result[] = $with[$param_name];
                continue;
            }
            if (!is_null($parameter->getClass())) {
                $result[] = $this->make($parameter->getClass()->getName());
            } elseif ($parameter->isDefaultValueAvailable()) {
                $result[] = $parameter->getDefaultValue();
            } else {
                $result[] = $param_name;
            }
        }
        return $result;
    }

    public function getLastWith()
    {
        return count($this->with) ? end($this->with) : [];
    }

    public function bound($abstract)
    {
        return isset($this->bindings[$abstract]) || isset($this->alias[$abstract]) || isset($this->instances[$abstract]);
    }

    public function make($abstract, $parameter = [])
    {
        if ($this->isAlias($abstract)) {
            return $this->make($this->getAlias($abstract), $parameter);
        }

        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = array_key_exists($abstract, $this->bindings) ? $this->bindings[$abstract]['concrete'] : $abstract;

        $isShare = array_key_exists($abstract, $this->bindings) ? $this->bindings[$abstract]['isShare'] : false;
        $this->with[] = $parameter;

        // 绑定闭包函数
        if ($concrete instanceof \Closure) {
            $instance = $concrete($this, $parameter);
        } else {
            $instance = $this->InvokeClass($abstract);
        }

        array_pop($this->with);
        if ($isShare) {
            $this->resolved[$abstract] = true;
            $this->instances[$abstract] = $instance;
        }
        return $instance;
    }

    public function __get($key)
    {
        return $this[$key];
    }

    public function __set($key, $value)
    {
        return $this[$key] = $value;
    }

    public function offsetExists($offset)
    {
        return $this->bound($offset);
    }

    public function offsetGet($offset)
    {
        return $this->make($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->bind($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->unbind($offset);
    }
}