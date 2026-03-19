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
        $dto = new \App\Reservation\Application\DTO\RegisterUserRequest(
            email: $email,
            name: $name,
            password: $password,
        );

        try {
            $this->authService->register($dto);
        } catch (\Exception $e) {
            $this->response(
                statusCode: $e->getCode(),
                data: ['error' => $e->getMessage()]
            );
        }
    }

    public function login(
        $email,
        $password,
    ): void
    {
        $dto = new \App\Reservation\Application\DTO\LoginUserRequest(
            email: $email,
            password: $password,
        );

        try {
            $this->response(
                data: $this->authService->login($dto)
            );
        } catch (\Exception $e) {
            $this->response(
                statusCode: $e->getCode(),
                data: ['error' => $e->getMessage()]
            );
        }
    }

    public function logout(): void
    {
        $token = $this->getRequest()->getHeader('Authorization');

        if (!$token) {
            $this->response(
                statusCode: 401,
                data: ['error' => 'Не указан токен']
            );
        }

        try {
            $this->authService->logout($token);
        } catch (\Exception $e) {
            $this->response(
                statusCode: $e->getCode(),
                data: ['error' => $e->getMessage()]
            );
        }
    }

    public function refresh(): void
    {
        $token = $this->getRequest()->getHeader('Authorization');

        if (!$token) {
            $this->response(
                statusCode: 401,
                data: ['error' => 'Не указан токен']
            );
        }

        try {
            $tokens = $this->authService->refreshToken($token);

            $this->response(
                data: $tokens
            );
        } catch (\Exception $e) {
            $this->response(
                statusCode: $e->getCode(),
                data: ['error' => $e->getMessage()]
            );
        }
    }
}