<?php

use App\Reservation\Presentation\Http\Controller;

/** @var \DI\Container $container */

$router = new \Bramus\Router\Router();

$router->set404('(/.*)?', function() {
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: application/json');
    echo json_encode(['status' => '404', 'status_text' => 'route not defined']);
});

$router->mount('/users', function() use ($router, $container) {
    $controller = $container->get(Controller\UserController::class);

    $router->get('/', function() use ($controller) {
        $controller->listUsers();
    });

    $router->get('/(\S+)', function($email) use ($controller) {
        $controller->getUserByEmail($email);
    });
});

$router->mount('/auth', function() use ($router, $container) {
    $controller = $container->get(Controller\AuthController::class);

    $router->post('/register', function() use ($controller) {
        $data = json_decode(file_get_contents('php://input'), true);
        $controller->register(
            $data['email'] ?? '',
            $data['name'] ?? '',
            $data['password'] ?? ''
        );
    });
});

$router->run();
