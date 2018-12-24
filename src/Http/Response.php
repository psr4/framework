<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-21
 * Time: 15:35
 */

namespace Hll\Http;

use Hll\Foundation\Container;

class Response extends Container
{
    protected $response;

    public function __construct($response)
    {
        $this->response = $response;
        $this->bind('header', Header::class, true);
    }

    public function send()
    {
        $response = $this->response;
        $this->respFilter($response);
        $this->header->build();
        echo $this->response;
    }

    public function respHeader($response)
    {
        if (is_array($response)) {

        }

        $this->header->build();
    }

    public function respFilter($response)
    {
        if (is_array($this->response)) {
            $this->header->set('Content-Type', 'Application/json; charset=utf8;', false);
            $this->response = json_encode($response);
        }
    }

}