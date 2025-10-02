<?php
// 代码生成时间: 2025-10-03 02:52:24
// 使用Slim框架创建项目管理工具
require 'vendor/autoload.php';

$app = new Slim\App();

// 数据库配置
\$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'project_management',
    'user' => 'root',
    'pass' => ''
];

// 定义错误处理中间件
$app->addErrorMiddleware(true, true, true);

// 项目列表路由
$app->get('/projects', function ($request, $response, $args) {
    // 获取项目列表
    \$projects = getProjects();
    return \$response->withJson(\$projects);
});

// 添加项目路由
$app->post('/projects', function ($request, $response, $args) {
    // 获取请求数据
    \$data = \$request->getParsedBody();
    // 验证数据
    if (!\$data || !isset(\$data['name'])) {
        return \$response->withStatus(400)
            ->withJson(['error' => 'Missing project name']);
    }
    // 添加项目
    \$result = addProject(\$data['name']);
    if (!\$result) {
        return \$response->withStatus(500)
            ->withJson(['error' => 'Failed to add project']);
    }
    return \$response->withStatus(201)
        ->withJson(['message' => 'Project added successfully']);
});

// 运行应用
$app->run();

/**
 * 获取项目列表
 *
 * @return array
 */
function getProjects() {
    global \$dbConfig;
    try {
        \$db = new PDO(
            