<?php
// 代码生成时间: 2025-09-18 14:54:16
 * to generate and return a random number to the client.
 *
 * @author Your Name
 * @version 1.0
 * @package RandomNumberGenerator
 */
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;

// Create Slim App
AppFactory::setCodingStyle(array(
    'namespace' => 'App',
    'controllersNamespace' => 'App\Controllers',
));

$app = AppFactory::create();

// Define route for generating random number
$app->get('/random', function ($request, $response, $args) {
    // Try to generate a random number and handle any potential errors
    try {
        $randomNumber = rand(1, 100); // Generate a random number between 1 and 100
        return $response->withJson(['number' => $randomNumber]);
    } catch (Exception $e) {
        // Handle any exceptions and return error message
        return $response->withJson(['error' => 'Failed to generate a random number.'], 500);
    }
});

// Run the application
$app->run();