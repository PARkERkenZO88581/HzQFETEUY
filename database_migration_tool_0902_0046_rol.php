<?php
// 代码生成时间: 2025-09-02 00:46:45
// database_migration_tool.php
// 使用SLIM框架实现的数据库迁移工具

// 引入Slim框架
require 'vendor/autoload.php';

use Slim\Factory\AppFactory';
use PDO;
use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// .env文件加载
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// 创建Slim App
$app = AppFactory::create();

// 数据库配置
$host = $_ENV['DB_HOST'];
$db   = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$charset = $_ENV['DB_CHARSET'];

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// 依赖注入容器
$container = $app->getContainer();
$container['db'] = function (ContainerInterface $c) use ($dsn, &$options) {
    return new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);
};

// 迁移数据库
$app->post('/migrate', function (Request $request, Response $response) {
    $db = $this->get('db');
    $migrationsDir = __DIR__ . '/migrations';

    // 获取请求体中的迁移文件名
    $migrations = $request->getParsedBody()['migrations'] ?? [];

    // 检查迁移文件是否存在
    $pendingMigrations = [];
    foreach ($migrations as $migration) {
        $filePath = $migrationsDir . '/' . $migration . '.sql';
        if (file_exists($filePath)) {
            $pendingMigrations[] = $filePath;
        } else {
            // 错误处理
            $errorMessage = "Migration file '{$migration}.sql' not found.";
            return $response->withJson(['error' => $errorMessage], 404);
        }
    }

    // 执行迁移
    try {
        foreach ($pendingMigrations as $filePath) {
            $sql = file_get_contents($filePath);
            $db->exec($sql);
        }
        $response->getBody()->write('Migrations completed successfully.');
    } catch (PDOException $e) {
        // 错误处理
        $response->getBody()->write("Error: {$e->getMessage()}");
        return $response->withStatus(500);
    }

    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

// 运行Slim App
$app->run();
