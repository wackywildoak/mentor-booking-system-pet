<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Repository;

use App\Reservation\Domain\Entity\User;

interface UserRepositoryInterface
{
    /** 
     * @return User[] 
     */
    public function all(): array;

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * @param User $user
     * @return void
     */
    public function save(User $user): void;
}