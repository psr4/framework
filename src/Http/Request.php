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

    public static function getInstance()
    {
        if (static::$instance) {
            return static::$instance;
        }
        $instance = new Static;
        static::$instance = $instance;
        return $instance;
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
}