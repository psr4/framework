<?php
require 'vendor/autoload.php';

$app = new Hll\Foundation\Application('./');

$app->bind(
    Hll\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);
// 助手函数绑定单类 必须使用bind
$app->bind(App\Http\Kernel::class);
$app->bind('response', \Hll\Http\Response::class);
//$app->bind(
//    'mysqli',
//    function () {
//        return new MySqli('localhost', 'root', 'root', 'test', '3306');
//    },
//    true
//);
$request = \Hll\Http\Request::capture();
$app->instance('request', $request);
$app->instance(\Hll\Http\Request::class, $request);

$kernel = $app->make(Hll\Contracts\Http\Kernel::class);

$response = $kernel->handle($request);

$response->send();

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