<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-21
 * Time: 17:06
 */

namespace App\Http\Middleware;

use Hll\Http\Request;
use Hll\Http\Middleware;

class Fitler extends Middleware
{
    public function handle(Request $request, \Closure $closure)
    {
        $response = $closure($request);
        return $response;
    }
}