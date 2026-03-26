<?php

declare(strict_types=1);

namespace App\Reservation\Domain\ValueObject;

enum ProfileStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Paused = 'paused';
    case Blocked = 'blocked';

    public function value(): string
    {
        return $this->value;
    }
}