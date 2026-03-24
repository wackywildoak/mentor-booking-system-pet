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

    public function handle(User $user): string
    {
        $payload = [
            'sub' => $user->id->value,
            'email' => $user->email->value,
            'name' => $user->name,
            'role' => $user->role,
            'createdAt' => (new \DateTime())->format('H:i:s')
        ];

        return $this->generateToken($payload, 900);
    }

    private function generateToken(array $payload, int $expirationSeconds = 3600): string
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