<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-21
 * Time: 17:06
 */

namespace Hll\Middleware;


class Middleware
{

    public function getClosure(\Closure $baseClosure)
    {
        return function ($request) use ($baseClosure) {
            return $this->handle($request, $baseClosure);
        };
    }
}