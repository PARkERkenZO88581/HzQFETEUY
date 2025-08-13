<?php
// 代码生成时间: 2025-08-14 03:20:14
// 使用Slim框架创建的随机数生成器
// 遵循PHP最佳实践，代码结构清晰，易于理解

require 'vendor/autoload.php';

$app = new \Slim\Slim();

// 路由定义：GET请求获取随机数
$app->get('/random', function () {
    // 获取参数
    $min = isset($_GET['min']) ? (int) $_GET['min'] : 1; // 最小值
    $max = isset($_GET['max']) ? (int) $_GET['max'] : 100; // 最大值
    
    // 错误处理：检查参数有效性
    if ($min > $max) {
        // 发送错误响应
        header('Content-Type: application/json');
        echo json_encode(array(
            'error' => 'Invalid range, minimum value cannot be greater than maximum value.'
        ));
        exit;
    }
    
    // 生成随机数
    $randomNumber = rand($min, $max);
    
    // 发送响应
    header('Content-Type: application/json');
    echo json_encode(array(
        'randomNumber' => $randomNumber,
        'range' => array(
            'min' => $min,
            'max' => $max
        )
    ));
});

// 运行应用
$app->run();
