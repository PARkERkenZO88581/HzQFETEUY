<?php
// 代码生成时间: 2025-09-06 20:16:28
// 用户权限管理系统
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// 定义用户权限
define('ADMIN_PERMISSION', 'admin');
define('USER_PERMISSION', 'user');

// 用户权限管理类
class PermissionManager {
    private $permissions;

    public function __construct() {
        $this->permissions = [
            ADMIN_PERMISSION => ['create', 'read', 'update', 'delete'],
            USER_PERMISSION => ['read']
        ];
    }

    // 检查用户是否有权限执行操作
    public function hasPermission($role, $operation) {
        if (!isset($this->permissions[$role])) {
            throw new \u0027InvalidArgumentException\u0027('Invalid role');
        }

        return in_array($operation, $this->permissions[$role]);
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 添加路由
$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write('Welcome to the user permission management system!');
    return $response;
});

// 添加权限检查中间件
$app->add(function ($request, $response, $next) {
    $permissionManager = new PermissionManager();
    $role = $request->getAttribute('role');
    $operation = $request->getAttribute('operation');

    if ($role && $operation) {
        if (!$permissionManager->hasPermission($role, $operation)) {
            return $response->withStatus(403)->getBody()->write('Forbidden');
        }
    }

    return $next($request, $response);
});

// 添加路由并应用权限检查
$app->get('/data', function (Request $request, Response $response, array $args) {
    $role = $request->getAttribute('role');
    $operation = 'read';
    $response->getBody()->write('Data access granted for role: ' . $role);
    return $response;
})->add(new class() {
    public function __invoke(Request $request, Response $response, $next) {
        $request = $request->withAttribute('role', 'user');
        $request = $request->withAttribute('operation', 'read');
        return $next($request, $response);
    }
});

// 运行Slim应用
$app->run();
