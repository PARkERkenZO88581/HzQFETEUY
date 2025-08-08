<?php
// 代码生成时间: 2025-08-09 01:47:51
// 使用Slim框架创建的压缩文件解压工具
require 'vendor/autoload.php';

$app = new \Slim\App();

// 定义一个路由用于上传并解压文件
$app->post('/unzip', function ($request, $response, $args) {
    // 检查是否有文件上传
    if (empty($_FILES['file']['name'])) {
        return $response->withJson(['error' => 'No file uploaded.'], 400);
    }

    // 获取上传的文件
    $file = $_FILES['file'];
    // 检查文件类型是否为ZIP
    if (pathinfo($file['name'], PATHINFO_EXTENSION) !== 'zip') {
        return $response->withJson(['error' => 'Invalid file type. Only ZIP files are allowed.'], 400);
    }

    // 尝试移动上传的文件到临时目录
    $tmpName = tempnam(sys_get_temp_dir(), 'zip');
    if (!move_uploaded_file($file['tmp_name'], $tmpName)) {
        return $response->withJson(['error' => 'Failed to move uploaded file.'], 500);
    }

    // 解压文件
    if (!zip_open($tmpName)) {
        return $response->withJson(['error' => 'Failed to open ZIP file.'], 500);
    }

    $zip = zip_open($tmpName);
    $destination = sys_get_temp_dir() . '/unzipped/';
    // 确保目标目录存在
    if (!is_dir($destination)) {
        mkdir($destination, 0777, true);
    }

    // 解压文件到目标目录
    while ($zip_entry = zip_read($zip)) {
        if (zip_entry_open($zip, $zip_entry)) {
            $file_name = zip_entry_name($zip_entry);
            $file_content = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            file_put_contents($destination . $file_name, $file_content);
            zip_entry_close($zip_entry);
        }
    }

    zip_close($zip);
    unlink($tmpName);

    // 返回解压成功的消息
    return $response->withJson(['message' => 'File successfully unzipped.'], 200);
});

// 运行应用
$app->run();
