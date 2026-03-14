<?php

declare(strict_types=1);

namespace App\Reservation\Infrastructure\Auth;

use App\Reservation\Domain\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtManager
{
    public function __construct(
        private string $secretKey
    ) {}

    public function handle(User $user): array
    {
        $payload = [
            'sub' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'role' => $user->role,
        ];

        return [
            'refresh_token' => $this->generateRefreshToken($payload),
            'access_token' => $this->generateAccessToken($payload),
        ];
    }

    private function generateRefreshToken(array $payload, int $expirationSeconds = 2592000): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + $expirationSeconds;

        $tokenPayload = array_merge($payload, [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
        ]);

        return JWT::encode($tokenPayload, $this->secretKey, 'HS256');
    }

    private function generateAccessToken(array $payload, int $expirationSeconds = 900): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + $expirationSeconds;

        $tokenPayload = array_merge($payload, [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
        ]);

        return JWT::encode($tokenPayload, $this->secretKey, 'HS256');
    }

    public function validateToken(string $token): array
    {
        try {
            return (array) JWT::decode($token, new Key($this->secretKey, 'HS256'));
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid token: ' . $e->getMessage());
        }
    }
}