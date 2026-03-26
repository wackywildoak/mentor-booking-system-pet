<?php

declare(strict_types=1);

namespace App\Reservation\Application\DTO;

class RegisterUserRequest
{
    public function __construct(
        public string $email,
        public string $name,
        public string $password,
        public ?string $role,
    ) {}
}