<?php
// 代码生成时间: 2025-09-23 06:15:54
// 使用Slim框架创建用户身份认证的程序
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
require __DIR__ . '/../vendor/autoload.php'; // 引入Composer的autoload文件

$app = new \Slim\App();

// 用户身份认证中间件
$app->add(function ($request, $response, $next) {
    // 从请求中获取用户的认证信息
    $username = $request->getHeaderLine('Username');
    $password = $request->getHeaderLine('Password');

    // 检查用户名和密码
    if ($username !== 'expectedUsername' || $password !== 'expectedPassword') {
        // 如果认证失败，返回401 Unauthorized状态码
        return $response->withStatus(401)->withJson(['error' => 'Unauthorized']);
    }

    // 如果认证成功，继续处理请求
    return $next($request, $response);
});

// 一个示例路由，需要用户认证
$app->get('/secure-data', function (Request $request, Response $response, $args) {
    $response->getBody()->write('This is secure data.');
    return $response;
});

// 启动Slim应用
$app->run();