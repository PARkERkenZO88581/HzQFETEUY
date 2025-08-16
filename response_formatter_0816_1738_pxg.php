<?php
// 代码生成时间: 2025-08-16 17:38:41
// 使用Slim框架创建一个API响应格式化工具
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

class ResponseFormatter {
    public static function formatResponse(array $data, int $statusCode): Response {
        // 创建响应对象
        $response = AppFactory::determineResponseFactory()->createResponse();
        // 设置响应状态码
        $response = $response->withStatus($statusCode);
        // 设置响应内容类型
        $response = $response->withHeader('Content-Type', 'application/json');
        // 将数据转换为JSON并返回
        return $response->getBody()->write(json_encode($data));
    }
}

// 定义一个简单的错误处理中间件
$errorMiddleware = function ($request, $handler) {
    return function (Request $request, Response $response) use ($handler) {
        try {
            return $handler($request, $response);
        } catch (\Exception $e) {
            // 格式化错误响应
            $response = ResponseFormatter::formatResponse(['error' => $e->getMessage()], 500);
            return $response->withStatus(500);
        }
    };
};

// 创建Slim应用
$app = AppFactory::create();

// 添加中间件
$app->add($errorMiddleware);

// 定义一个GET路由，返回格式化的响应
$app->get('/', function (Request $request, Response $response) {
    // 格式化成功响应
    $response = ResponseFormatter::formatResponse(['message' => 'Hello, World!'], 200);
    return $response;
});

// 运行应用
$app->run();