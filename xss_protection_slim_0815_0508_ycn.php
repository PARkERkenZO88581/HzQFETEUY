<?php
// 代码生成时间: 2025-08-15 05:08:12
// 引入Slim框架
require 'vendor/autoload.php';

// 创建一个新实例
$app = new \Slim\Slim();

// 中间件：用于XSS攻击防护
// 我们将所有的输入数据进行清理，以防止XSS攻击
$app->add(function($request, \$ response, \$ next) {
    // 检查请求中的所有参数
    \$request->getParams();
    \$request->postParams;
    \$request->cookiesParams;
    \$request->queryParams;
    // 清理所有参数
    foreach (\$request->params() as \$key => \$value) {
        \$request->params[\$key] = htmlspecialchars(\$value);
    }
    \$next();
});

// 路由：展示一个简单的表单
\$app->get('/', function() use (\$app) {
    \$app->render('index.php', array(
        'title' => 'XSS Protection Form',
        'error' => false
    ));
});

// 路由：处理表单提交
\$app->post('/submit', function() use (\$app) {
    \$input = \$app->request->post('input');
    if (empty(\$input)) {
        \$app->render('index.php', array(
            'title' => 'XSS Protection Form',
            'error' => 'Input cannot be empty'
        ));
    } else {
        \$app->response->status(200);
        \$app->response->body("You submitted: " . \$input);
    }
});

// 运行应用
\$app->run();

/*
 * index.php - 视图文件
 * 这个文件将用于显示表单以及任何潜在的错误信息
 */
?php 
// 表单标题
\$title = \$args['title'];
// 错误信息
\$error = \$args['error'];

echo "<html><body>";
if (\$error) {
    echo "<p style='color: red;'>\$error</p>";
}

echo "<form action='/submit' method='post'>";
echo "<p>Enter your input: <input type='text' name='input'></p>";
echo "<input type='submit' value='Submit'></form>";
echo "</body></html>";