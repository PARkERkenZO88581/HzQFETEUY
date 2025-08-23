<?php
// 代码生成时间: 2025-08-23 08:56:53
// 使用Slim框架实现的用户身份认证系统
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Middleware\ErrorMiddleware;
use Tuupola\Middleware\JwtAuthentication as JwtAuth;
use Tuupola\Http\Factory\StreamFactory;
use Tuupola\Http\Factory\ResponseFactory;
use Tuupola\Middleware\JwtAuthentication\RequestExtractor;
use Firebase\JWT\JWT;

require_once 'vendor/autoload.php';

// 创建应用
AppFactory::setContainer(new DI\Container());
$app = AppFactory::create();

// 错误处理
$app->addErrorMiddleware(true, true, true, ErrorMiddleware::DEFAULT_LOGO);

// JWT认证中间件配置
$app->add(new JwtAuth([
    "secure" => false,
    "attribute" => "jwt",
    "secret" => "your_secret_key",
    "error" => function ($request, $arguments) {
        return new ResponseFactory()->createResponse(401)
            ->withBody(new StreamFactory()->createStream("Unauthorized"));
    },
    "path" => "/",
    "ignore" => ["/login", "/error"],
    "callback" => function ($request, $response, $arguments) {
        // 用户登录验证
        $username = $request->getParsedBodyParam("username");
        $password = $request->getParsedBodyParam("password");

        // 这里应添加数据库验证逻辑
        // 验证用户名和密码
        if ($username === "admin" && $password === "password") {
            $payload = [
                "sub" => $username,
                "iat" => time(),
                "exp" => time() + 3600
            ];

            $token = JWT::encode($payload, "your_secret_key");
            return new ResponseFactory()->createResponse(200)
                ->withBody(new StreamFactory()->createStream("{\"jwt\":\"$token\"}"));
        } else {
            return new ResponseFactory()->createResponse(401)
                ->withBody(new StreamFactory()->createStream("Unauthorized"));
        }
    }
]));

// 登录接口
$app->post("/login", function (Request $request, Response $response, $args) {
    // 调用中间件进行JWT认证
    $jwt = $request->getAttribute("jwt");
    if ($jwt) {
        return $response->withJson(["message" => "Logged in"]);
    } else {
        return $response->withStatus(401)->withJson(["message" => "Unauthorized"]);
    }
});

// 受保护的接口
$app->get("/protected", function (Request $request, Response $response, $args) {
    $jwt = $request->getAttribute("jwt");
    if ($jwt) {
        return $response->withJson(["message" => "Hello from protected route"]);
    } else {
        return $response->withStatus(401)->withJson(["message" => "Unauthorized"]);
    }
});

// 运行应用
$app->run();