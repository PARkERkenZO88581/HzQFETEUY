<?php
// 代码生成时间: 2025-08-07 12:02:20
// api_response_formatter.php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Slim\Psr7\ServerRequest;
use Slim\Routing\RouteContext;
use DI\Container;

// 创建一个中间件，用于格式化API响应
class ApiResponseFormatterMiddleware {
    public function __invoke(Request $request, Response $response, $next) {
        $response = $next($request, $response);
        $body = (string) $response->getBody();
        
        // 格式化响应体
        $formattedResponse = $this->formatApiResponse($body);
        
        // 设置响应体和头部
        return $response->withBody(\Slim\Psr7\Utils::streamFor($formattedResponse))
                         ->withHeader('Content-Type', 'application/json');
    }
    
    // 格式化API响应体
    private function formatApiResponse($body) {
        // 尝试解析响应体作为JSON
        $parsedBody = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // 如果JSON解析失败，返回原始响应体
            return $body;
        }
        
        // 检查是否已经是格式化的响应体
        if (isset($parsedBody['status']) && isset($parsedBody['data'])) {
            return json_encode($parsedBody);
        }
        
        // 返回默认的格式化响应体
        return json_encode(['status' => 'success', 'data' => $parsedBody]);
    }
}

// 设置依赖注入容器
$container = new Container();

// 创建Slim应用
$app = AppFactory::create($container);

// 添加中间件以格式化响应
$app->add(ApiResponseFormatterMiddleware::class);

// 定义一个示例路由，返回一个简单的JSON响应
$app->get('/api/example', function (Request $request, Response $response, $args) {
    $response->getBody()->write(json_encode(['message' => 'Hello World']));
    return $response;
});

// 运行应用
$app->run();
