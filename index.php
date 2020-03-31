<?php
require 'vendor/autoload.php';
$app = (new Hll\Foundation\Application('./'))->run()->send();

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
// 服务提供者懒加载
//echo $app->make('test');

// 别名测试
//var_dump($app->make('Test'));

//门脸测试
//var_dump(\Hll\Facades\Test::query('select * from ad')->fetch_all(MYSQLI_ASSOC));