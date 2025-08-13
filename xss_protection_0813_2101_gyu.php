<?php
// 代码生成时间: 2025-08-13 21:01:50
// 使用Slim框架创建一个简单的HTTP服务器，提供XSS攻击防护
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as Response;
use Slim\Psr7\ServerRequest as ServerRequest;

// 创建Slim应用
AppFactory::setEncodingOptions(["application/json