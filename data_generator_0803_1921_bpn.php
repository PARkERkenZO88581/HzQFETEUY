<?php
// 代码生成时间: 2025-08-03 19:21:31
// DataGenerator.php
// 测试数据生成器类，用于生成测试数据。

require 'vendor/autoload.php'; // 引入Slim框架

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

class DataGenerator {
    // 生成随机用户数据的方法
    public function generateUser(): array {
        $name = "User_" . rand(1, 100);
        $email = $name . "@example.com";
        $id = uniqid("user\_", true);
        return [
            'id' => $id,
            'name' => $name,
            'email' => $email
        ];
    }

    // 生成随机订单数据的方法
    public function generateOrder(): array {
        $userId = uniqid("order\_", true);
        $total = rand(100, 1000);
        return [
            'userId' => $userId,
            'total' => $total
        ];
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 定义生成用户数据的路由
$app->get('/generateUser', function (Request $request, Response $response, $args) {
    $dataGenerator = new DataGenerator();
    $userData = $dataGenerator->generateUser();
    $response->getBody()->write(json_encode($userData));
    return $response->withHeader('Content-Type', 'application/json');
});

// 定义生成订单数据的路由
$app->get('/generateOrder', function (Request $request, Response $response, $args) {
    $dataGenerator = new DataGenerator();
    $orderData = $dataGenerator->generateOrder();
    $response->getBody()->write(json_encode($orderData));
    return $response->withHeader('Content-Type', 'application/json');
});

// 运行应用
$app->run();