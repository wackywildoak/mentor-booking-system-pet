<?php

declare(strict_types=1);

namespace App\Reservation\Application\Service;

use App\Reservation\Domain\Entity\User;
use App\Reservation\Domain\ValueObject\Uuid;
use App\Reservation\Domain\ValueObject\Email;
use App\Reservation\Domain\ValueObject\UserRole;
use App\Reservation\Domain\Repository\UserRepositoryInterface;
use App\Reservation\Application\DTO\RegisterUserRequest;
use App\Reservation\Application\DTO\LoginUserRequest;
use App\Reservation\Infrastructure\Auth\JwtManager;
use App\Reservation\Infrastructure\Auth\RefreshTokenStorage;
use Exception;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RefreshTokenStorage $tokenStorage,
        private JwtManager $jwtManager
    ) {}

    public function register(
        RegisterUserRequest $userDTO,
    ): void
    {
        if ($this->userRepository->findByEmail($userDTO->email) !== null) {
            throw new \Exception('User with this email already exists', 401);
        }

        if (strlen($userDTO->password) < 6) {
            throw new \Exception('Password must be at least 6 characters long', 401);
        }

        if (strlen($userDTO->name) < 2) {
            throw new \Exception('Name must be at least 2 characters long', 401);
        }

        if (!filter_var($userDTO->email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email address', 401);
        }

        $user = new User(
            id: Uuid::generate(),
            email: new Email($userDTO->email),
            name: $userDTO->name,
            passwordHash: password_hash($userDTO->password, PASSWORD_DEFAULT),
            role: UserRole::Client,
            createdAt: new \DateTime()
        );

        $this->userRepository->save($user);
    }

    private function generateTokens(User $user, string $familyId): array
    {
        $refreshToken = bin2hex(random_bytes(16));
        $accessToken = $this->jwtManager->handle($user);

        $this->tokenStorage->store(
            id: Uuid::generate()->value,
            userId: $user->id->value,
            tokenHash: hash('sha256', $refreshToken),
            familyId: $familyId,
            isRevoked: false,
        );

        return [
            'refreshToken' => $refreshToken,
            'accessToken' => $accessToken,
        ];
    }

    public function login(LoginUserRequest $userDTO): array
    {
        $user = $this->userRepository->findByEmail($userDTO->email);
        if ($user === null || !password_verify($userDTO->password, $user->passwordHash)) {
            throw new \Exception('Invalid email or password', 401);
        }

        return $this->generateTokens($user, Uuid::generate()->value);
    }

    public function refreshToken(string $token): array
    {
        $tokenHash = hash('sha256', $token);

        $tokenData = $this->tokenStorage->find('token_hash', $tokenHash);

        if (!$tokenData || empty($tokenData)) {
            throw new \Exception("Некорректный токен", 401);
        }

        if ($tokenData['is_revoked']) {
            throw new \Exception('Токен недействителен', 401);
        }

        $user = $this->userRepository->find($tokenData['user_id']);

        if (!$user instanceof User || is_null($user)) {
            throw new \Exception('Пользователь не найден', 404);
        }

        $this->tokenStorage->update(
            id: $tokenData['id'],
            isRevoked: true,
        );

        return $this->generateTokens($user, $tokenData['family_id']);
    }

    public function validateToken(string $token): void
    {
        try {
            $payload = $this->jwtManager->validateToken($token);
        } catch (\Exception $e) {
            throw $e;
        }

        if ($payload['exp'] < time()) {
            throw new Exception('Время действия токена истекло', 401);
        }
    }
}