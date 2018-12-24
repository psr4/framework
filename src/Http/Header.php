<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-24
 * Time: 10:23
 */

namespace Hll\Http;


class Header
{
    private $header = [];

    public function set($key, $value = null, $isImportant = true)
    {
//        if (is_array($key)) {
//            $this->set(...$key);
//        }
        if (!$isImportant && array_key_exists($key, $this->header) && $this->header['isImportant']) {
            return;
        } elseif (is_null($value)) {
            $raw = $key;
            list($key, $value) = explode(':', $raw);
            $key = trim(strtolower($key));
            $this->header[$key] = compact('raw', 'isImportant');
        } else {
            $raw = "$key: $value";
            $key = trim(strtolower($key));
            $this->header[$key] = compact('raw', 'isImportant');
        }
    }

    public function build()
    {
        foreach ($this->header as $header) {
            header($header['raw']);
        }
    }
}