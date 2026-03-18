<?php

declare(strict_types=1);

namespace App\Reservation\Application\DTO;

class RegisterUserRequest
{
    public function __construct(
        public string $email,
        public string $name,
        public string $password,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            name: $data['name'],
            password: $data['password'],
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'password' => $this->password,
        ];
    }
}