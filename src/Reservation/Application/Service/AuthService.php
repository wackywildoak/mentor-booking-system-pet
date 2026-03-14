<?php

declare(strict_types=1);

namespace App\Reservation\Application\Service;

use App\Reservation\Domain\Entity\User;
use App\Reservation\Domain\ValueObject\Uuid;
use App\Reservation\Domain\ValueObject\Email;
use App\Reservation\Domain\ValueObject\UserRole;
use App\Reservation\Domain\Repository\UserRepositoryInterface;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function register(
        $email,
        $name,
        $password,
    ): void
    {
        if ($this->userRepository->findByEmail($email) !== null) {
            throw new \Exception('User with this email already exists');
        }

        if (strlen($password) < 6) {
            throw new \Exception('Password must be at least 6 characters long');
        }

        if (strlen($name) < 2) {
            throw new \Exception('Name must be at least 2 characters long');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email address');
        }

        $user = new User(
            id: Uuid::generate(),
            email: new Email($email),
            name: $name,
            passwordHash: password_hash($password, PASSWORD_DEFAULT),
            role: UserRole::Client,
            createdAt: new \DateTime()
        );

        $this->userRepository->save($user);
    }
}