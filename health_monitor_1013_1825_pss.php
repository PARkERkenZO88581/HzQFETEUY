<?php
// 代码生成时间: 2025-10-13 18:25:02
// 使用Slim框架创建健康监护设备应用
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// 创建Slim应用
AppFactory::setCachedConfigFile(__DIR__ . '/config/settings.php');
$app = AppFactory::create();

// 获取健康数据的路由
$app->get('/health', function (Request $request, Response $response, $args) {
    // 模拟获取健康数据
    $healthData = getHealthData();
    
    // 返回JSON响应
    return $response->getBody()->write(json_encode($healthData));
});

// 错误处理中间件
$app->addErrorMiddleware(true, true, true);

// 运行应用
$app->run();

/**
 * 模拟从设备获取健康数据
 *
 * @return array
 */
function getHealthData() {
    // 这里只是模拟数据，实际应用中可能需要从数据库或API获取
    $data = [
        'heart_rate' => 72,
        'blood_pressure' => 120,
        'blood_glucose' => 90,
        'body_temperature' => 98.6
    ];
    return $data;
}

// 配置文件路径
define('CONFIG_FILE', __DIR__ . '/config/settings.php');
