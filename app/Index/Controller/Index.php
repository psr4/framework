<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-21
 * Time: 16:05
 */

namespace App\Index\Controller;

use Hll\Http\Request;

class Index
{
    public function index(Request $request)
    {
        return input();
    }
}