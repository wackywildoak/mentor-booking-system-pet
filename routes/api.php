<?php

use App\Reservation\Presentation\Http\Controller;
use App\Reservation\Presentation\Http\Request\Request;
use App\Reservation\Infrastructure\Middleware\AuthMiddleware;

/** @var \DI\Container $container */

$router = new \Bramus\Router\Router();

$router->set404('(/.*)?', function() {
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: application/json');
    echo json_encode(['status' => '404', 'status_text' => 'route not defined']);
});

$router->before('GET|POST', '/.*', function() use ($container) {
    $publicRoutes = [
        '/auth/login',
        '/auth/register',
        '/auth/refresh',
    ];

    if (in_array($_SERVER['REQUEST_URI'], $publicRoutes)) {
        return;
    }

    $authMiddleware = $container->get(AuthMiddleware::class);

    $request = Request::fromGlobals();
    $authMiddleware->handle($request);
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

    $router->post('/login', function() use ($controller) {
        $data = json_decode(file_get_contents('php://input'), true);
        $controller->login(
            $data['email'] ?? '',
            $data['password'] ?? ''
        );
    });

    $router->post('/refresh', function() use ($controller) {
        $controller->refresh();
    });
});

$router->run();
