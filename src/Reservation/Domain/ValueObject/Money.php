<?php

declare(strict_types=1);

namespace App\Reservation\Domain\ValueObject;

class Money
{   
    public function __construct(
        public float $amount,
        public string $currency
    ) {}

    public function add(float $other): void
    {
        $this->amount += $other;
    }

    public function subtract(float $other): void
    {
        $this->amount -= $other;
    }
}