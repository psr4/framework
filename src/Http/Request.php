<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-21
 * Time: 15:24
 */

namespace Hll\Http;

class Request
{
    public static $instance = null;

    protected $post = [];
    protected $get = [];
    protected $raw;

    protected $module;
    protected $controller;
    protected $action;

    public static function getInstance()
    {
        if (static::$instance) {
            return static::$instance;
        }
        $instance = new Static;
        static::$instance = $instance;

        return $instance;
    }

    public function __construct()
    {
        $r = $this->input('r');
        $array = array_filter(explode('/', $r));
        $this->module = isset($array[0]) ? $array[0] : 'index';
        $this->controller = isset($array[1]) ? $array[1] : 'index';
        $this->action = isset($array[2]) ? $array[2] : 'index';
    }

    public static function capture()
    {
        $request = Static::getInstance();
        $request->get = $_GET;
        $request->post = $_POST;
        $request->input = array_merge($_GET, $_POST);
        $request->raw = file_get_contents('php://input');
        return $request;
    }

    public function input($key, $default = null)
    {
        return isset($this->input[$key]) ? $this->input[$key] : $default;
    }

    public function module()
    {
        return $this->module;
    }

    public function controller()
    {
        return $this->controller;
    }

    public function action()
    {
        return $this->action;
    }
}