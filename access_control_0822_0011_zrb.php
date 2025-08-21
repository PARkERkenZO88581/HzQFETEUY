<?php
// 代码生成时间: 2025-08-22 00:11:55
// 使用Composer引入Slim框架
use Slim\Factory\ServerRequestCreator;
use Slim\Psr7\Response;
use Slim\Slim;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// 定义用户角色
class Role {
    const ADMIN = 'admin';
    const USER = 'user';
}

// 权限中间件
class AccessMiddleware {
    private $allowedRoles;

    public function __construct($allowedRoles) {
        $this->allowedRoles = $allowedRoles;
    }

    public function __invoke(Request $request, Response $response, callable $next) {
        // 获取用户角色
        $userRole = $request->getAttribute('role');

        // 检查用户是否具有访问权限
        if (!in_array($userRole, $this->allowedRoles)) {
            // 如果没有权限，返回403 Forbidden响应
            return $response
                ->withStatus(403)
                ->withHeader('Content-Type', 'application/json')
                ->getBody()
                ->write(json_encode(['error' => 'Forbidden']));
        }

        // 如果有权限，继续执行下一个中间件或路由处理函数
        return $next($request, $response);
    }
}

// 创建Slim应用
$app = new Slim($c);

// 添加中间件进行访问权限控制
$app->add(new AccessMiddleware([Role::ADMIN])); // 仅为管理员开放

// 定义路由并添加到Slim应用
$app->get('/admin', function (Request $request, Response $response) {
    $response->getBody()->write('Welcome, Admin!');
    return $response;
});

// 错误处理
$app->addErrorMiddleware(true, true, true);

// 运行应用
$app->run();

// 注意：实际使用时需要替换$c为实际配置数组，并且需要实现用户认证来判断用户角色。