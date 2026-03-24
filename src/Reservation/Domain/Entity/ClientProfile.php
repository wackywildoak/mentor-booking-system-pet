<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Entity;

use App\Reservation\Domain\ValueObject\Money;
use App\Reservation\Domain\ValueObject\Uuid;

class ClientProfile extends Profile
{
    public string $company {
        get => $this->company;
        set => $value;
    }

    public string $position {
        get => $this->position;
        set => $value;
    }

    public string $about {
        get => $this->about;
        set => $value;
    }

    public string $industry {
        get => $this->industry;
        set => $value;
    }
    

    public function __construct(
        Uuid $id,
        Uuid $userId,
        Money $balance,
        string $company = "",
        string $position = "",
        string $about = "",
        string $industry = "",
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->balance = $balance;
        $this->company = $company;
        $this->position = $position;
        $this->about = $about;
        $this->industry = $industry;
    }

    public static function create(Uuid $userId): self
    {
        return new self(Uuid::generate(), $userId, new Money(0.0, 'USD'));
    }
}