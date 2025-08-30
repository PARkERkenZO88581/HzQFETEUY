<?php
// 代码生成时间: 2025-08-30 19:19:32
// 图片尺寸批量调整器
// 使用PHP和Slim框架实现

require 'vendor/autoload.php';

$app = new \Slim\Slim();

// 路由：上传图片并调整尺寸
$app->post('/upload', function () use ($app) {
    \$files = \$app->request->getBody();
    \$input = json_decode(\$files, true);

    // 检查输入是否为空和是否包含必要的字段
    if (empty(\$input['images']) || !is_array(\$input['images'])) {
        \$app->response->status(400);
        \$app->response->body(json_encode(['error' => 'Invalid input']));
        return;
    }

    \$resizeConfig = \$input['resizeConfig'] ?? [];
    if (empty(\$resizeConfig)) {
        \$app->response->status(400);
        \$app->response->body(json_encode(['error' => 'Resize configuration is required']));
        return;
    }

    try {
        \$resizedImages = [];

        // 遍历图像进行尺寸调整
        foreach (\$input['images'] as \$imagePath) {
            \$resizedImagePath = resizeImage(\$imagePath, \$resizeConfig);
            \$resizedImages[] = \$resizedImagePath;
        }

        \$app->response->body(json_encode(['resizedImages' => \$resizedImages]));
    } catch (Exception \$e) {
        \$app->response->status(500);
        \$app->response->body(json_encode(['error' => \$e->getMessage()]));
    }
});

// 运行应用
\$app->run();

// 图片尺寸调整函数
function resizeImage(\$imagePath, \$config) {
    // 检查文件是否存在
    if (!file_exists(\$imagePath)) {
        throw new Exception('Image file not found.');
    }

    \$image = new Imagick(\$imagePath);
    \$image->resizeImage(\$config['width'], \$config['height'], Imagick::FILTER_LANCZOS, true);
    \$newPath = str_replace('.', '_resized.', \$imagePath);
    \$image->writeImage(\$newPath);
    \$image->clear();
    \$image->destroy();

    return \$newPath;
}

// 错误处理
\$app->error(function (Exception \$e) use (\$app) {
    \$app->response->status(500);
    \$app->response->body(json_encode(['error' => \$e->getMessage()]));
});
