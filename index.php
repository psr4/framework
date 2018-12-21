<?php
require 'vendor/autoload.php';

$app = new Hll\Foundation\Application('./');

$app->bind(
    Hll\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);
$app->bind(App\Http\Kernel::class);
$app->bind('response', \Hll\Http\Response::class);

$request = \Hll\Http\Request::capture();
$app->instance('request', $request);
$app->instance(\Hll\Http\Request::class, $request);


var_dump(\Request::input());
die();
$kernel = $app->make(Hll\Contracts\Http\Kernel::class);

$response = $kernel->handle($request);

$response->send();

// 容器测试

//$app->bind(
//    'mysqli',
//    function () {
//        return new MySqli('localhost', 'root', 'root', 'test', '3306');
//    },
//    true
//);

//$app->alias(
//    'Test', 'mysqli'
//);
// 助手函数测试
//var_dump(app('Test'));

// 获取单例
//var_dump(\Hll\Foundation\Container::getInstance());
//\Test::query('select * from ad')->fetch_all(MYSQLI_ASSOC);

// 服务提供者测试
//var_dump($app->Test->query('select * from ad')->fetch_all(MYSQLI_ASSOC));

// 别名测试
//var_dump($app->make('Test'));

//门脸测试
//var_dump(\Hll\Facades\Test::query('select * from ad')->fetch_all(MYSQLI_ASSOC));