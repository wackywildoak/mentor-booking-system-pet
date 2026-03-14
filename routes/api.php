<?php

use App\Reservation\Presentation\Http\Controller\MentorController;

/** @var \DI\Container $container */

$router = new \Bramus\Router\Router();

$router->set404('(/.*)?', function() {
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: application/json');
    echo json_encode(['status' => '404', 'status_text' => 'route not defined']);
});

$router->mount('/mentors', function() use ($router, $container) {
    $controller = $container->get(MentorController::class);

    $router->get('/', function() use ($controller) {
        $controller->listMentors();
    });

    $router->get('/(\S+)', function($id) use ($controller) {
        $controller->getMentor($id);
    });
});

$router->run();
