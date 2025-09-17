<?php
// 代码生成时间: 2025-09-17 17:05:51
// 使用Slim框架实现批量文件重命名工具
// 代码遵循PHP最佳实践

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义一个简单的文件重命名工具
# 添加错误处理
class BatchRenameTool {
    public function renameFiles($source, $destination, $prefix) {
        if (!is_dir($source)) {
            throw new \Exception("Source directory does not exist");
        }
# 添加错误处理

        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $files = new DirectoryIterator($source);
        foreach ($files as $fileinfo) {
            if (!$fileinfo->isDot() && $fileinfo->isFile()) {
                $newFilename = $prefix . $fileinfo->getFilename();
                $sourcePath = $fileinfo->getPathname();
                $destinationPath = $destination . DIRECTORY_SEPARATOR . $newFilename;

                if (!rename($sourcePath, $destinationPath)) {
                    throw new \Exception("Failed to rename file: {$sourcePath} to {$destinationPath}");
                }
            }
        }
    }
}

// 设置Slim应用
$app = AppFactory::create();

// 路由：批量文件重命名
$app->get('(rename)', function (Request $request, Response $response, $args) {
    $source = $request->getQueryParams()['source'] ?? '';
    $destination = $request->getQueryParams()['destination'] ?? '';
    $prefix = $request->getQueryParams()['prefix'] ?? '';
# 改进用户体验

    if (empty($source) || empty($destination) || empty($prefix)) {
        return $response->withJson(['error' => 'Missing parameters'], 400);
    }

    try {
        $renameTool = new BatchRenameTool();
        $renameTool->renameFiles($source, $destination, $prefix);
        return $response->withJson(['message' => 'Files renamed successfully'], 200);
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 500);
    }
});

// 运行Slim应用
$app->run();