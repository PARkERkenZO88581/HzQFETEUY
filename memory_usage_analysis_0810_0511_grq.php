<?php
// 代码生成时间: 2025-08-10 05:11:16
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义一个用于内存使用情况分析的类
class MemoryUsageAnalysis {
    public static function getMemoryUsage() {
        // 获取当前内存使用量
        $memoryUsage = memory_get_usage();
        // 获取当前峰值内存使用量
        $memoryPeakUsage = memory_get_peak_usage();

        // 返回内存使用情况的数组
        return [
            'memory_usage' => $memoryUsage,
            'memory_peak_usage' => $memoryPeakUsage
        ];
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 添加一个路由来显示内存使用情况
$app->get('/memory-usage', function (Request $request, Response $response, $args) {
    try {
        // 获取内存使用情况数据
        $memoryData = MemoryUsageAnalysis::getMemoryUsage();

        // 创建响应内容
        $responseBody = json_encode($memoryData);

        // 设置响应头
        $response = $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200)
            ->getBody()
            ->write($responseBody);

        // 返回响应
        return $response;
    } catch (Exception $e) {
        // 错误处理
        $response = $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500)
            ->getBody()
            ->write(json_encode(['error' => 'Internal Server Error']));

        // 返回错误响应
        return $response;
    }
});

// 运行应用
$app->run();