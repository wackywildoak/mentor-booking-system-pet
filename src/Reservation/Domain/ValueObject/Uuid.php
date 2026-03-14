<?php

declare(strict_types=1);

namespace App\Reservation\Domain\ValueObject;

class Uuid
{
    public function __construct(
        public string $value
    ) {
    }

    public static function generate(): Uuid
    {
        return new self(bin2hex(random_bytes(16)));
    }

    public function __toString(): string
    {
        return $this->value;
    }
}