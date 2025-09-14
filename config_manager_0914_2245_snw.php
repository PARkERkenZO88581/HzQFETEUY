<?php
// 代码生成时间: 2025-09-14 22:45:52
// config_manager.php
// A simple configuration manager using PHP and Slim framework.

use Slim\Factory\AppFactory;
use Psr\Container\ContainerInterface;
use DI\Container;
use DI\ContainerBuilder;

class ConfigManager {
    /**
     * @var ContainerInterface The Slim DI container.
     */
    private $container;

    /**
     * ConfigManager constructor.
     * @param ContainerInterface $container The Slim DI container.
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * Load configuration from a file.
     *
     * @param string $filename The path to the configuration file.
     * @return array The configuration data.
     * @throws Exception If the file does not exist or is not readable.
     */
    public function loadConfig(string $filename): array {
        if (!file_exists($filename)) {
            throw new Exception('Configuration file not found.');
        }
        if (!is_readable($filename)) {
            throw new Exception('Configuration file is not readable.');
        }

        return include $filename;
    }
}

// Create a new DI container builder.
$builder = new ContainerBuilder();

// Set up the configuration manager.
$builder->addDefinitions(["config.manager" => function (Container $container) {
    return new ConfigManager($container);
}]);

// Build the DI container.
$container = $builder->build();

// Set up the Slim application.
$app = AppFactory::create($container);

// Define routes and middleware.
$app->get('/config', function (Request $request, Response $response, $args) use ($container) {
    try {
        $configManager = $container->get('config.manager');
        $config = $configManager->loadConfig(__DIR__ . '/config.php');
        $response->getBody()->write(json_encode($config));
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
    }
    return $response->withHeader('Content-Type', 'application/json');
});

// Run the Slim application.
$app->run();