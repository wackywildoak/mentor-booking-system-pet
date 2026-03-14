<?php

declare(strict_types=1);

namespace App\Reservation\Domain\ValueObject;

class Email
{
    public string $value {
        get => $this->value;
        set => $value;
    }

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email address: $value");
        }
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}