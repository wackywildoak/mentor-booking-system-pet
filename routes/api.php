<?php

$router = new \Bramus\Router\Router();

$router->setNamespace('\App\Reservation\Presentation\Http\Controller');

$router->set404('(/.*)?', function() {
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: application/json');

    $jsonArray = array();
    $jsonArray['status'] = "404";
    $jsonArray['status_text'] = "route not defined";

    echo json_encode($jsonArray);
});

$router->mount('/mentors', function() use ($router) {
    $router->get('/', 'MentorController@listMentors');
    $router->get('/{id}', 'MentorController@getMentor');
});

$router->run();