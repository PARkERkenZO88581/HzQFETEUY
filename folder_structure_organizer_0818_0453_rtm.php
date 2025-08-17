<?php
// 代码生成时间: 2025-08-18 04:53:42
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

// Create a Slim app
AppFactory::setContainer(new DI\Container());
$app = AppFactory::create();

// Middleware to handle errors
$app->addErrorMiddleware(true, true, true, false);

// Define the route for organizing folder structure
$app->post('/organize', function (Request $request, Response $response, $args) {
    $body = $request->getParsedBody();
    $targetPath = $body['path'] ?? null;
    
    // Validate the input
    if (!v::stringType()->notEmpty()->validate($targetPath)) {
        return $response->withJson(['error' => 'Invalid path provided.'], 400);
    }
    
    // Initialize filesystem adapter
    $adapter = new Local($targetPath);
    $filesystem = new Filesystem($adapter);
    
    try {
        // Organize the folder structure
        $this->organizeFolderStructure($filesystem, $targetPath);
        
        return $response->withJson(['message' => 'Folder structure organized successfully.'], 200);
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 500);
    }
});

// Define the method to organize folder structure
protected function organizeFolderStructure(Filesystem $filesystem, string $path): void {
    // Retrieve the directory contents
    $contents = $filesystem->listContents($path, true);
    
    foreach ($contents as $file) {
        if ($file['type'] === 'file') {
            // Move files to a new directory based on their extensions
            $newPath = $path . '/' . $file['extension'];
            $filesystem->move($file['path'], $newPath . '/' . basename($file['path']));
        }
    }
}

// Run the app
$app->run();