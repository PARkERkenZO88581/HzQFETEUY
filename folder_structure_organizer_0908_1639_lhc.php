<?php
// 代码生成时间: 2025-09-08 16:39:09
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// FolderStructureOrganizer 类定义
class FolderStructureOrganizer {
    private $directory;

    public function __construct($directory) {
        $this->directory = $directory;
    }

    // 整理文件夹结构
    public function organize() {
        if (!is_dir($this->directory)) {
            throw new \Exception('The specified directory does not exist.');
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->directory),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                // 处理文件，例如：重命名，移动等
                // 这里仅打印文件路径作为示例
                echo $file->getRealPath() . "\
";
            }
        }
    }
}

// 设置 Slim 应用
$app = AppFactory::create();

// 定义路由和处理程序
$app->get('/', function (Request $request, Response $response) {
    $directory = $request->getQueryParams()['directory'] ?? 'default_directory';
    try {
        $organizer = new FolderStructureOrganizer($directory);
        $organizer->organize();
        $response->getBody()->write('Folder structure organized successfully.');
    } catch (Exception $e) {
        $response->getBody()->write('Error: ' . $e->getMessage());
        $response->withStatus(500);
    }
    return $response;
});

// 运行应用
$app->run();