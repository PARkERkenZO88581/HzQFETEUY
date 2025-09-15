<?php
// 代码生成时间: 2025-09-16 01:33:21
// folder_structure_organizer.php
// 使用SLIM框架实现文件夹结构整理器

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义一个简单的文件夹结构整理器
class FolderStructureOrganizer {
    private $sourceDirectory;
    private $destinationDirectory;
    
    public function __construct($source, $destination) {
        $this->sourceDirectory = $source;
        $this->destinationDirectory = $destination;
    }
    
    // 整理文件夹结构
    public function organize() {
        // 检查源目录是否存在
        if (!is_dir($this->sourceDirectory)) {
            throw new \u0027InvalidArgumentException\u0027('源目录不存在');
        }
        
        // 检查目标目录是否存在，不存在则创建
        if (!is_dir($this->destinationDirectory)) {
            mkdir($this->destinationDirectory, 0777, true);
        }
        
        // 递归整理文件夹结构
        return $this->recursiveOrganize($this->sourceDirectory);
    }
    
    // 递归整理文件夹结构
    private function recursiveOrganize($directory) {
        // 获取目录下所有文件和文件夹
        $items = new DirectoryIterator($directory);
        foreach ($items as $item) {
            if ($item->isDir() && !$item->isDot()) {
                // 递归整理子文件夹
                $this->recursiveOrganize($item->getPathname());
            } elseif ($item->isFile()) {
                // 处理文件，根据需要复制、移动或重命名
                $filename = $item->getFilename();
                $newPath = $this->destinationDirectory . '/' . $filename;
                if (!file_exists($newPath)) {
                    copy($item->getPathname(), $newPath);
                } else {
                    // 文件已存在，可以选择重命名或覆盖
                    // 这里简单重命名
                    $newFilename = uniqid('', true) . '_' . $filename;
                    $newPath = $this->destinationDirectory . '/' . $newFilename;
                    copy($item->getPathname(), $newPath);
                }
            }
        }
    }
}

// 创建SLIM应用
$app = AppFactory::create();

// 配置路由
$app->get('/organize', function (Request $request, Response $response) {
    $sourceDir = $request->getQueryParams()['source'] ?? null;
    $destDir = $request->getQueryParams()['destination'] ?? null;
    
    if (!$sourceDir || !$destDir) {
        return $response->withStatus(400)
                     ->withHeader('Content-Type', 'application/json')
                     ->write(json_encode(['error' => '缺少必要的参数']));
    }
    
    try {
        $organizer = new FolderStructureOrganizer($sourceDir, $destDir);
        $organizer->organize();
        return $response->withStatus(200)
                     ->withHeader('Content-Type', 'application/json')
                     ->write(json_encode(['message' => '文件夹结构整理完成']));
    } catch (Exception $e) {
        return $response->withStatus(500)
                     ->withHeader('Content-Type', 'application/json')
                     ->write(json_encode(['error' => $e->getMessage()]));
    }
});

// 运行SLIM应用
$app->run();