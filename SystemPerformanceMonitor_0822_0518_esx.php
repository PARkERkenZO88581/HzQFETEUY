<?php
// 代码生成时间: 2025-08-22 05:18:21
// SystemPerformanceMonitor.php
// 使用Slim框架创建的系统性能监控工具

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义一个类来封装性能监控逻辑
class PerformanceMonitor {
    public function getSystemStatus() {
        // 获取系统信息
        $systemStatus = [];
        $systemStatus['load'] = sys_getloadavg();
        $systemStatus['memory_usage'] = memory_get_usage();
        $systemStatus['memory_limit'] = ini_get('memory_limit');
        return $systemStatus;
    }

    public function getDiskSpace() {
        // 获取磁盘空间信息
        $diskTotalSpace = disk_total_space('/');
        $diskFreeSpace = disk_free_space('/');
        return [
            'total' => $diskTotalSpace,
            'free' => $diskFreeSpace
        ];
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 获取系统状态的路由
$app->get('/system-status', function (Request $request, Response $response, $args) {
    $monitor = new PerformanceMonitor();
    try {
        $systemStatus = $monitor->getSystemStatus();
        $response->getBody()->write(json_encode($systemStatus));
    } catch (Exception $e) {
        // 错误处理
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
    }
    return $response->withHeader('Content-Type', 'application/json');
});

// 获取磁盘空间信息的路由
$app->get('/disk-space', function (Request $request, Response $response, $args) {
    $monitor = new PerformanceMonitor();
    try {
        $diskSpace = $monitor->getDiskSpace();
        $response->getBody()->write(json_encode($diskSpace));
    } catch (Exception $e) {
        // 错误处理
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
    }
    return $response->withHeader('Content-Type', 'application/json');
});

// 运行Slim应用
$app->run();
