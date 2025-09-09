<?php
// 代码生成时间: 2025-09-09 14:03:09
// user_permission_manager.php
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// Define the UserPermissionManager class
class UserPermissionManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Method to get user permissions
    public function getUserPermissions($userId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM permissions WHERE user_id = ?");
            $stmt->execute([$userId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (Exception $e) {
            // Handle error
            return ['error' => $e->getMessage()];
        }
    }

    // Method to add a permission to a user
    public function addPermission($userId, $permission) {
        try {
            $stmt = $this->db->prepare("INSERT INTO permissions (user_id, permission) VALUES (?, ?)");
            $stmt->execute([$userId, $permission]);
            return ['success' => 'Permission added'];
        } catch (Exception $e) {
            // Handle error
            return ['error' => $e->getMessage()];
        }
    }
}

// Set up the Slim app
$app = AppFactory::create();

// Define the route to get user permissions
$app->get('/permissions/{id}', function (Request $request, Response $response, array $args) {
    $userId = $args['id'];
    $userPermissionManager = new UserPermissionManager($db);
    $permissions = $userPermissionManager->getUserPermissions($userId);
    $response->getBody()->write(json_encode($permissions));
    return $response->withHeader('Content-Type', 'application/json');
});

// Define the route to add a permission to a user
$app->post('/permissions', function (Request $request, Response $response) {
    $body = $request->getParsedBody();
    $userId = $body['userId'];
    $permission = $body['permission'];
    $userPermissionManager = new UserPermissionManager($db);
    $result = $userPermissionManager->addPermission($userId, $permission);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

// Run the app
$app->run();