<?php
// 代码生成时间: 2025-08-11 18:54:43
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php'; // Assuming autoload.php is in the parent directory

// Create Slim App
$app = AppFactory::create();

// Middleware to handle errors
$app->addErrorMiddleware(true, true, true, 'text/plain');

/**
 * Route to analyze a text file
 *
 * @param Request  $request  The PSR-7 request object
 * @param Response $response The PSR-7 response object
 * @param array    $args     The route parameters
 *
 * @return Response The response object
 */
$app->get('/analyze', function (Request $request, Response $response, array $args) {
    // Get file path from query parameter
    $filePath = $request->getQueryParams()['file'] ?? null;

    // Check if file path is provided
    if (!$filePath) {
        return $response->withStatus(400)
                       ->withHeader('Content-Type', 'text/plain')
                       ->getBody()
                       ->write('Error: No file path provided.');
    }

    // Check if file exists
    if (!file_exists($filePath)) {
        return $response->withStatus(404)
                       ->withHeader('Content-Type', 'text/plain')
                       ->getBody()
                       ->write('Error: File not found.');
    }

    // Read file content
    $content = file_get_contents($filePath);
    if ($content === false) {
        return $response->withStatus(500)
                       ->withHeader('Content-Type', 'text/plain')
                       ->getBody()
                       ->write('Error: Failed to read file.');
    }

    // Analyze file content (e.g., count words)
    $wordCount = str_word_count($content);

    // Prepare response
    $response->getBody()->write(json_encode(['word_count' => $wordCount]));
    return $response
                ->withHeader('Content-Type', 'application/json');
});

// Run Slim App
$app->run();