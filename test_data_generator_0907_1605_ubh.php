<?php
// 代码生成时间: 2025-09-07 16:05:23
// 引入Slim框架
require 'vendor/autoload.php';

// 创建一个Slim应用程序
$app = new \Slim\App();

// 定义路由'/test-data'来生成测试数据
$app->get('/test-data', function ($request, $response, $args) {
    // 生成测试数据
    $testData = generateTestDataSet();

    // 将测试数据转换为JSON格式
    $response->getBody()->write(\$testData);

    // 返回响应
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

// 生成测试数据集
function generateTestDataSet() {
    // 创建测试数据数组
    $testData = [
        ['id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@example.com'],
        ['id' => 2, 'name' => 'Jane Doe', 'email' => 'jane.doe@example.com'],
        ['id' => 3, 'name' => 'Bob Smith', 'email' => 'bob.smith@example.com']
    ];

    // 将测试数据转换为JSON格式
    return json_encode(\$testData);
}

// 运行Slim应用程序
\$app->run();
