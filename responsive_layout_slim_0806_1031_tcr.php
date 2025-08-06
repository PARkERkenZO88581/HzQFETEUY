<?php
// 代码生成时间: 2025-08-06 10:31:40
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Route;
use Psr\Log\LoggerInterface;

require 'vendor/autoload.php';

// 创建 Slim 应用
$app = new Slim\App();

// 定义响应式布局的路由
$app->get('/responsive-layout', function (Request $request, Response $response, array $args) {
    // 渲染视图
    return $response->write(view('responsive_layout.php'));
});

// 错误处理
$app->addErrorMiddleware(true, true, true);

// 运行应用
$app->run();

// 辅助函数，用于渲染视图
function view($filename) {
    extract($GLOBALS);
    ob_start();
    require __DIR__ . "/views/{$filename}";
    return ob_get_clean();
}

/**
 * 视图文件：responsive_layout.php
 */

// 视图文件应位于项目目录中的 views 子目录中
/*
view('responsive_layout.php');
*/

?>