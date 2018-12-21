<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-21
 * Time: 17:06
 */

namespace App\Http\Middleware;

use Hll\Http\Request;
use Hll\Middleware\Middleware;

class Fitler extends Middleware
{
    public function handle(Request $request, \Closure $closure)
    {
        echo 'before';
        $response = $closure($request);

        echo 'after';
        return $response;
    }
}