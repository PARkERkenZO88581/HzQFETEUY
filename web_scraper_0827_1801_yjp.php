<?php
// 代码生成时间: 2025-08-27 18:01:16
// web_scraper.php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use GuzzleHttp\Client;

// Define the namespace for the application
namespace App;

require __DIR__ . '/../vendor/autoload.php';

// Set up the Slim app
AppFactory::setContainer(new \Slim\Container());
$app = AppFactory::create();

// Define a route to handle GET requests to scrape a website
$app->get('/scrape/{url}', function(Request \$request, Response \$response, \$args): Response {
    \$url = \$args['url'];
    \$client = new Client();
    try {
        // Attempt to fetch the webpage content
        \$response = \$client->request('GET', \$url);
        \$body = \$response->getBody();
        \$content = \$body->getContents();

        // Return the webpage content as a response
        return \$response->getBody()->write(\$content);
    } catch (\Exception \$e) {
        // Handle errors and return a 500 status code
        return \$response->withStatus(500)->withJson(["error" => \$e->getMessage()]);
    }
});

// Run the application
$app->run();