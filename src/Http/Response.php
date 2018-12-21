<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-21
 * Time: 15:35
 */

namespace Hll\Http;


class Response
{
    protected $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function send()
    {
        echo $this->response;
    }
}