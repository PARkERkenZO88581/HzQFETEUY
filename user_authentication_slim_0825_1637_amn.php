<?php
// 代码生成时间: 2025-08-25 16:37:48
// 导入Slim框架
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// 配置数据库连接信息
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'slim_auth');

// 创建应用
AppFactory::setCodingStylePsr4();
$app = AppFactory::create();

// 错误处理中间件
$app->addErrorMiddleware(true, true, true);

// 用户认证中间件
$app->add(function ($request, $handler) {
    $response = $handler($request);
    // 尝试从会话中获取用户ID
    if (!isset($_SESSION['user_id'])) {
        // 重定向到登录页面
        return $response->withStatus(302)->withHeader('Location', '/login');
    }
    return $response;
});

// 登录路由
$app->get('/login', function ($request, $response, $args) {
    // 渲染登录页面
    return $response->getBody()->write('Login Page');
});

// 登录处理
$app->post('/login', function ($request, $response, $args) {
    // 获取请求中的用户名和密码
    $username = $request->getParsedBodyParam('username');
    $password = $request->getParsedBodyParam('password');

    // 连接数据库
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        // 处理连接错误
        die('Connection failed: ' . $conn->connect_error);
    }

    // 验证用户凭据
    $stmt = $conn->prepare('SELECT id FROM users WHERE username = ? AND password = ?');
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // 用户认证成功，设置会话
        $_SESSION['user_id'] = $result->fetch_assoc()['id'];
        // 重定向到受保护的页面
        return $response->withStatus(302)->withHeader('Location', '/dashboard');
    } else {
        // 用户认证失败，显示错误信息
        return $response->getBody()->write('Invalid username or password');
    }
    $conn->close();
});

// 受保护的路由
$app->get('/dashboard', function ($request, $response, $args) {
    // 渲染受保护的页面
    return $response->getBody()->write('Dashboard');
});

// 运行应用
$app->run();
