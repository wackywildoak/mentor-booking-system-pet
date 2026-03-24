<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Entity;

use App\Reservation\Domain\ValueObject\Money;
use App\Reservation\Domain\ValueObject\Uuid;

class Profile
{
    public Uuid $id {
        get => $this->id;
        set => $value;
    }

    public Uuid $userId {
        get => $this->userId;
        set => $value;
    }

    public Money $balance {
        get => $this->balance;
        set => $value;
    }
    
    public function addMoney(float $amount): void
    {
        $this->balance->add($amount);
    }

    public function subtractMoney(float $amount): void
    {
        $this->balance->subtract($amount);
    }
}