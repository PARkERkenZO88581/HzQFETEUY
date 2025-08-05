<?php
// 代码生成时间: 2025-08-05 22:30:16
// config_manager.php
require 'vendor/autoload.php';

use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Slim\Views\Twig;
use DI\ContainerBuilder;
use DI\BridgeSlim\Bridge;

// 创建一个工厂实例来生成一个Slim应用
AppFactory::setContainerBuilder(new ContainerBuilder());
$app = AppFactory::create();

// 设置Twig视图
$view = Twig::create('templates', ['cache' => 'cache/twig']);
$app->add($view);

// 添加错误处理中间件
$app->add(ErrorMiddleware::create(false, false, false));

// 配置文件管理器
$app->get('/config', function (Request $request, Response $response, $args) {
    // 从请求中获取配置文件名称
    $configName = $request->getQueryParam('name', 'default');

    // 加载配置文件
    $config = loadConfig($configName);

    // 如果配置不存在，返回错误响应
    if (!$config) {
        return $response->withJson(['error' => 'Config not found'], 404);
    }

    // 返回配置
    return $response->withJson($config);
});

// 加载配置文件的函数
function loadConfig($name) {
    $configPath = __DIR__ . '/config/' . $name . '.php';

    // 检查配置文件是否存在
    if (!file_exists($configPath)) {
        return null;
    }

    // 包含配置文件并返回配置数据
    return include $configPath;
}

// 运行应用
$app->run();