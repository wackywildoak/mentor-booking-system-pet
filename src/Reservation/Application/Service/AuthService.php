<?php

declare(strict_types=1);

namespace App\Reservation\Application\Service;

use App\Reservation\Domain\Entity\User;
use App\Reservation\Domain\ValueObject\Uuid;
use App\Reservation\Domain\Repository\UserRepositoryInterface;
use App\Reservation\Infrastructure\Auth\JwtManager;
use App\Reservation\Infrastructure\Auth\RefreshTokenStorage;
use OutOfBoundsException;
use Exception;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RefreshTokenStorage $tokenStorage,
        private JwtManager $jwtManager
    ) {}

    public function logout(string $token): void
    {
        $tokenHash = hash('sha256', $token);

        $tokenData = $this->tokenStorage->find('token_hash', $tokenHash);

        if (empty($tokenData)) {
            throw new Exception('Неверный токен', 401);
        } 

        $this->revokeRefreshToken($tokenData['id']);
    }

    public function revokeRefreshToken(string $tokenId): void
    {
        $this->tokenStorage->update(
            id: $tokenId,
            isRevoked: true,
        );
    }

    public function generateTokens(User $user, Uuid $familyId): array
    {
        $refreshToken = bin2hex(random_bytes(16));
        $accessToken = $this->jwtManager->handle($user);

        $this->tokenStorage->store(
            id: Uuid::generate()->value,
            userId: $user->id->value,
            tokenHash: hash('sha256', $refreshToken),
            familyId: $familyId->value,
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
            $this->revokeRefreshToken($tokenData['id']);
            throw new \Exception("Cрок действия токена окончен", 401);
        }

        $familyId = $tokenData['family_id'];
        if ($tokenData['is_revoked']) {
            $allTokenData = $this->tokenStorage->findAll('family_id', $familyId);
            foreach ($allTokenData as $tokenItem) {
                $this->revokeRefreshToken($tokenItem['id']);
            }

            throw new \Exception('Токен недействителен', 401);
        }

        $user = $this->userRepository->find($tokenData['user_id']);

        if (!$user instanceof User || is_null($user)) {
            throw new \Exception('Пользователь не найден', 404);
        }

        $this->revokeRefreshToken($tokenData['id']);

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