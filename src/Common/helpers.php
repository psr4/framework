<?php
if (!function_exists('app')) {
    function app($abstract = null, $concrete = null, $isShare = false)
    {
        $instance = \Hll\Foundation\Application::getInstance();
        if (is_null($abstract)) {
            return $instance;
        } elseif (is_array($concrete) || is_null($concrete)) {
            return $instance->make($abstract, is_array($concrete) ? $concrete : []);
        } else {
            return $instance->bind($abstract, $concrete, $isShare);
        }
    }
}

if (!function_exists('input')) {
    function input($key = null, $value = null)
    {
        $result = \Hll\Facades\Request::input($key);
        return !is_null($result) ? $result : $value;
    }
}