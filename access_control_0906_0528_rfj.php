<?php
// 代码生成时间: 2025-09-06 05:28:26
// 使用Composer autoload
require 'vendor/autoload.php';

// 创建Slim应用实例
$app = new \Slim\Slim();

// 中间件用于检查用户是否已登录
$app->add(new \Slim\Middleware\SessionCookie(array(
    'secret' => 'your_secret_key'
)));

// 用户登录中间件
$app->add(function ($request, $response, $next) use ($app) {
    // 检查会话中是否有'user'键
    if (!$app->request()->getEnvironment()['session']['user']) {
        // 如果没有'user'键，重定向到登录页面
        $app->redirect('/?error=not_authenticated');
    }
    return $next($request, $response);
});

// 登录路由
$app->post('/login', function () use ($app) {
    // 获取用户名和密码
    $username = $app->request()->post('username');
    $password = $app->request()->post('password');

    // 检查用户名和密码是否有效
    if ($username === 'admin' && $password === 'password') {
        // 设置会话变量
        $_SESSION['user'] = $username;
        $app->response()->status(200);
        $app->response()->body('Logged in successfully.');
    } else {
        // 如果无效，设置错误消息并重定向到登录页面
        $app->flash('error', 'Invalid username or password.');
        $app->redirect('/?error=invalid_credentials');
    }
});

// 登出路由
$app->get('/logout', function () use ($app) {
    // 销毁会话
    session_destroy();
    $app->response()->status(200);
    $app->response()->body('Logged out successfully.');
});

// 受保护的路由
$app->get('/', function () use ($app) {
    // 仅允许已登录用户访问
    $user = $_SESSION['user'];
    $app->response()->status(200);
    $app->response()->body('Welcome, ' . $user . '!');
});

// 错误处理路由
$app->get('/?', function () use ($app) {
    // 显示登录表单或错误消息
    if ($app->request()->get('error') === 'not_authenticated') {
        $app->response()->status(401);
        $app->response()->body('You must be logged in to access this page.');
    } elseif ($app->request()->get('error') === 'invalid_credentials') {
        $app->response()->status(401);
        $app->response()->body('Invalid username or password. Please try again.');
    } else {
        $app->response()->status(200);
        $app->response()->body('Login form goes here.');
    }
});

// 运行应用
$app->run();

// 注释说明：
// 1. 使用Composer的autoload自动加载依赖。
// 2. 创建Slim应用实例。
// 3. 添加中间件以支持会话，并设置密钥。
// 4. 添加用于检查会话中'user'键的中间件。
// 5. 定义登录路由，处理用户登录逻辑。
// 6. 定义登出路由，处理用户登出逻辑。
// 7. 定义受保护的首页路由，仅允许已登录用户访问。
// 8. 定义错误处理路由，显示登录表单或错误消息。
// 9. 运行应用。