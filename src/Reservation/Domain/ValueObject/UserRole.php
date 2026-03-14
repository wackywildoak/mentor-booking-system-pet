<?php

declare(strict_types=1);

namespace App\Reservation\Domain\ValueObject;

enum UserRole: string
{
    case Mentor = 'mentor';
    case Client = 'client';
    case Admin = 'admin';

    public function isMentor(): bool
    {
        return $this === self::Mentor;
    }

    public function isClient(): bool
    {
        return $this === self::Client;
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }

    public function value(): string
    {
        return $this->value;
    }
}