<?php
// 代码生成时间: 2025-09-08 04:01:46
// 引入Slim框架
require 'vendor/autoload.php';

// 创建Slim应用
$app = new \Slim\App();

// 配置中间件
$app->add(new \Slim\Middleware\Session(["name" => "slim_session"]));

// 定义路由
$app->get('/theme/{theme}', function ($request, $response, $args) {
    // 获取主题参数
    $theme = $args['theme'];

    // 检查主题是否有效
    if (!in_array($theme, ['dark', 'light'])) {
        return $response->withStatus(400)
                         ->withJson(['error' => 'Invalid theme']);
    }

    // 设置会话中的主题
    $_SESSION['theme'] = $theme;

    // 响应主题切换成功
    return $response->withJson(['message' => 'Theme switched to ' . $theme]);
});

// 获取当前主题
$app->get('/theme', function ($request, $response, $args) {
    // 从会话中获取主题
    $theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light';

    // 响应当前主题
    return $response->withJson(['theme' => $theme]);
});

// 运行应用
$app->run();
