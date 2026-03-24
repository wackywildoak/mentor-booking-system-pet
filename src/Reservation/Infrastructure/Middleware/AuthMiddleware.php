<?php

namespace App\Reservation\Infrastructure\Middleware;

use App\Reservation\Application\Service\AuthService;
use App\Reservation\Presentation\Http\Request\Request;

class AuthMiddleware
{
    public function __construct(
        private AuthService $service,
    ) {}

    public function handle(Request $request)
    {
        $token = $request->getHeader('Authorization');

        header('Content-Type: application/json');

        if (!$token) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        try {
            $user = $this->service->validateToken($token);
            $request->setUser($user);
        } catch (\Exception $e) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error' => 'Invalid token: ' . $e->getMessage()]);
            exit;
        }
    }   
}