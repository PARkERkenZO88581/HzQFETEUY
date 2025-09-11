<?php
// 代码生成时间: 2025-09-11 23:04:17
// 使用Slim框架和PDO防止SQL注入

// 引入Slim框架
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;
use PDOException;

// 创建Slim应用
AppFactory::setCodingStylePreset(AppFactory::CODING_STYLE_PSR12);
$app = AppFactory::create();

// 数据库配置
const DB_HOST = 'localhost';
const DB_NAME = 'your_db_name';
const DB_USER = 'your_db_user';
const DB_PASS = 'your_db_password';

// 创建数据库连接
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    throw new Exception('Connection error: ' . $e->getMessage());
}

// 注册GET路由
$app->get('/search', function (Request $request, Response $response, $args) use ($pdo) {
    // 获取查询参数
    $searchTerm = $request->getQueryParams()['term'] ?? '';

    // 准备SQL语句
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE :term");

    // 绑定参数
    $stmt->bindParam(':term', $searchTerm, PDO::PARAM_STR);

    // 执行查询
    try {
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(['error' => 'Query failed: ' . $e->getMessage()]));
        $response = $response->withStatus(500);
    }

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

// 运行Slim应用
$app->run();