<?php

declare(strict_types=1);

namespace App\Reservation\Application\UseCase;

use App\Reservation\Domain\Entity\User;
use App\Reservation\Domain\Entity\ClientProfile;
use App\Reservation\Domain\ValueObject\Email;
use App\Reservation\Domain\ValueObject\UserRole;
use App\Reservation\Application\DTO\RegisterUserRequest;
use App\Reservation\Domain\Entity\MentorProfile;
use App\Reservation\Domain\Repository\ClientProfileRepositoryInterface;
use App\Reservation\Domain\Repository\MentorProfileRepositoryInterface;
use App\Reservation\Domain\Repository\UserRepositoryInterface;

class RegisterUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ClientProfileRepositoryInterface $clientProfileRepository,
        private MentorProfileRepositoryInterface $mentorProfileRepository
    ) {}

    public function execute(RegisterUserRequest $userDTO): void
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

        $user = User::register(
            email: new Email($userDTO->email),
            name: $userDTO->name,
            passwordHash: password_hash($userDTO->password, PASSWORD_DEFAULT),
            role: UserRole::tryFrom($userDTO->role)
        );

        $this->userRepository->save($user);

        switch($user->role) {
            case UserRole::Client:
                $clientProfile = ClientProfile::create(
                    userId: $user->id
                );
                $this->clientProfileRepository->save($clientProfile);
            case UserRole::Mentor:
                $mentorProfile = MentorProfile::create(
                    userId: $user->id
                );
                $this->mentorProfileRepository->save($mentorProfile);
        }
    }
}