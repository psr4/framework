<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-20
 * Time: 16:32
 */

namespace App\Http;

use App\Http\Middleware\Fitler;

class Kernel extends \Hll\Http\Kernel
{
    protected $middleware = [
        Fitler::class
    ];
}