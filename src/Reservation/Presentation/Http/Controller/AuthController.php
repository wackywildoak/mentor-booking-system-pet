<?php

namespace App\Reservation\Presentation\Http\Controller;

use App\Reservation\Application\Service\AuthService;
use App\Reservation\Presentation\Http\Shared\AbstractController;

class AuthController extends AbstractController
{
    public function __construct(
        private AuthService $authService,
    ) {}

    public function register(
        $email,
        $name,
        $password,
    ): void
    {  
        try {
            $this->authService->register($email, $name, $password);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}