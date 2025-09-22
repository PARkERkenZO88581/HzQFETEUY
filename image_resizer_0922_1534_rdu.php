<?php
// 代码生成时间: 2025-09-22 15:34:04
// 使用Slim框架创建一个简单的API
require 'vendor/autoload.php';

$app = new \Slim\Slim();

// 定义路由处理图片尺寸调整
$app->post('/resize-images', function() use ($app) {
    // 检查请求中是否有图片文件
    if (!isset($_FILES['images'])) {
        $app->response()->status(400);
        $app->response()->body(json_encode(['error' => 'No images provided']));
        return;
    }
    
    // 获取上传的图片数组
    $images = $_FILES['images'];
    
    // 检查是否有图片被上传
    if (count($images['tmp_name']) === 0) {
        $app->response()->status(400);
        $app->response()->body(json_encode(['error' => 'No images uploaded']));
        return;
    }
    
    // 设置目标尺寸
    $targetWidth = 800;
    $targetHeight = 600;
    
    // 存储调整后的图片路径
    $resizedImagePaths = [];
    
    // 遍历上传的图片并进行尺寸调整
    foreach ($images['tmp_name'] as $index => $tmpName) {
        // 读取图片信息
        $imageInfo = getimagesize($tmpName);
        $imageType = $imageInfo[2];
        
        // 根据图片类型创建图像资源
        $image = imagecreatefromstring(file_get_contents($tmpName));
        
        // 创建一个新的真彩色画布
        $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);
        
        // 调整图片到指定尺寸
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $imageInfo[0], $imageInfo[1]);
        
        // 保存调整后的图片
        $resizedImagePath = 'resized/' . basename($images['name'][$index]);
        
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($resizedImage, $resizedImagePath);
                break;
            case IMAGETYPE_GIF:
                imagegif($resizedImage, $resizedImagePath);
                break;
            case IMAGETYPE_PNG:
                imagepng($resizedImage, $resizedImagePath);
                break;
            default:
                $app->response()->status(400);
                $app->response()->body(json_encode(['error' => 'Unsupported image type']));
                return;
        }
        
        // 添加调整后的图片路径到数组
        $resizedImagePaths[] = $resizedImagePath;
    }
    
    // 返回调整后的图片路径
    $app->response()->status(200);
    $app->response()->body(json_encode($resizedImagePaths));
});

// 运行应用
$app->run();