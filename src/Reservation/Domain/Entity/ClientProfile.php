<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Entity;

use App\Reservation\Domain\ValueObject\Money;
use App\Reservation\Domain\ValueObject\Uuid;

class ClientProfile extends Profile
{
    public function __construct(
        Uuid $id,
        Uuid $userId,
        Money $balance
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->balance = $balance;
    }

    public static function create(Uuid $id, Uuid $userId): self
    {
        return new self($id, $userId, new Money(0.0, 'USD'));
    }
}