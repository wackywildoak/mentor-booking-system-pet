<?php

declare(strict_types=1);

namespace App\Reservation\Application\UseCase;

use App\Reservation\Domain\ValueObject\Uuid;
use App\Reservation\Application\DTO\LoginUserRequest;
use App\Reservation\Application\Service\AuthService;
use App\Reservation\Domain\Repository\UserRepositoryInterface;

class LoginUserUseCase
{
    public function __construct(
        private AuthService $authService,
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(LoginUserRequest $userDTO)
    {
        $user = $this->userRepository->findByEmail($userDTO->email);
        if ($user === null || !password_verify($userDTO->password, $user->passwordHash)) {
            throw new \Exception('Invalid email or password', 401);
        }

        return $this->authService->generateTokens($user, Uuid::generate());
    }
}