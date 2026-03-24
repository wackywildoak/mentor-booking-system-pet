<?php

declare(strict_types=1);

namespace App\Reservation\Application\Service;

use App\Reservation\Domain\Entity\User;
use App\Reservation\Domain\ValueObject\Uuid;
use App\Reservation\Domain\ValueObject\Email;
use App\Reservation\Domain\ValueObject\UserRole;
use App\Reservation\Domain\Repository\UserRepositoryInterface;
use App\Reservation\Domain\Repository\ClientProfileRepositoryInterface;
use App\Reservation\Application\DTO\RegisterUserRequest;
use App\Reservation\Application\DTO\LoginUserRequest;
use App\Reservation\Domain\Entity\ClientProfile;
use App\Reservation\Infrastructure\Auth\JwtManager;
use App\Reservation\Infrastructure\Auth\RefreshTokenStorage;
use OutOfBoundsException;
use Exception;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ClientProfileRepositoryInterface $clientProfileRepository,
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

        switch($user->role) {
            case UserRole::Client:
                $clientProfile = ClientProfile::create(
                    id: Uuid::generate(),
                    userId: $user->id
                );
                $this->clientProfileRepository->save($clientProfile);
        }

        $this->userRepository->save($user);
    }

    public function login(LoginUserRequest $userDTO): array
    {
        $user = $this->userRepository->findByEmail($userDTO->email);
        if ($user === null || !password_verify($userDTO->password, $user->passwordHash)) {
            throw new \Exception('Invalid email or password', 401);
        }

        return $this->generateTokens($user, Uuid::generate()->value);
    }

    public function logout(string $token): void
    {
        $tokenHash = hash('sha256', $token);

        $tokenData = $this->tokenStorage->find('token_hash', $tokenHash);

        if (empty($tokenData)) {
            throw new Exception('Неверный токен', 401);
        } 

        $this->tokenStorage->update(
            id: $tokenData['id'],
            isRevoked: true,
        );
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

    public function refreshToken(string $token): array
    {
        $tokenHash = hash('sha256', $token);

        $tokenData = $this->tokenStorage->find('token_hash', $tokenHash);
        
        if (!$tokenData || empty($tokenData)) {
            throw new \Exception("Некорректный токен или срок действия токена окончено", 401);
        }

        $expiresAt = new \DateTime($tokenData['expires_at']);
        $now = new \DateTime();

        if ($now->diff($expiresAt)->days >= 30) {
            $this->tokenStorage->update(
                id: $tokenData['id'],
                isRevoked: true,
            );
            throw new \Exception("Cрок действия токена окончен", 401);
        }

        $familyId = $tokenData['family_id'];
        if ($tokenData['is_revoked']) {
            $allTokenData = $this->tokenStorage->findAll('family_id', $familyId);
            foreach ($allTokenData as $tokenItem) {
                 $this->tokenStorage->update(
                    id: $tokenItem['id'],
                    isRevoked: true,
                );
            }

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

        return $this->generateTokens($user, $familyId);
    }

    public function validateToken(string $token): User
    {
        try {
            $payload = $this->jwtManager->validateToken($token);
        } catch (\Exception $e) {
            throw $e;
        }

        if ($payload['exp'] < time()) {
            throw new Exception('Время действия токена истекло', 401);
        }

        $user = $this->userRepository->find($payload['sub']);

        if (!$user) {
            throw new OutOfBoundsException('Пользователь не найден');
        }

        return $user;
    }
}