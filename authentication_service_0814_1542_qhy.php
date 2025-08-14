<?php
// 代码生成时间: 2025-08-14 15:42:43
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

// 创建Slim应用
AppFactory::setContainer(new DI\Container());
$app = AppFactory::create();

// 定义用户认证中间件
$container = $app->getContainer();
$container['auth'] = function ($c) {
    return function (Request $request, Response $response, callable $next) {
        // 从请求中获取用户凭据
        $credentials = $request->getHeader('Authorization');
        if (!$credentials) {
            return $response->withStatus(401)
                ->getBody()
                ->write('Missing credentials');
        }

        // 这里简化处理，实际项目中需要进行密码加密和验证
        if ($credentials[0] !== 'Bearer token123') {
            return $response->withStatus(403)
                ->getBody()
                ->write('Invalid credentials');
        }

        // 将请求传递给下一个中间件或路由
        return $next($request, $response);
    };
};

// 定义受保护的路由
$app->get('/secure', function (Request $request, Response $response, $args) {
    $response->getBody()->write('This is a secure page.');
    return $response;
})->add($container->get('auth'));

// 定义不受保护的路由
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write('This is an unprotected page.');
    return $response;
});

// 运行应用
$app->run();
