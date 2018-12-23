<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/23 0023
 * Time: 10:34
 */

namespace Hll\Config;


class Config
{
    public $config;

    public function __construct($config = [])
    {
        $this->config = $config;
    }

    public function get($key, $default)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
    }

    public function set($key, $value)
    {
        return $this->config[$key] = $value;
    }
}