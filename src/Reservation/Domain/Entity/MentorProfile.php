<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Entity;

use App\Reservation\Domain\ValueObject\Uuid;
use App\Reservation\Domain\ValueObject\Money;
use App\Reservation\Domain\ValueObject\ProfileStatus;

class MentorProfile extends Profile
{
    private const DEFAULT_STATUS = ProfileStatus::Pending;

    /** @var string[] Список специализаций (финансы, маркетинг, HR...) */
    public array $specializations {
        get => $this->specializations;
        set => $value;
    }

    /** @var Money Стоимость часа */
    public Money $hourlyRate {
        get => $this->hourlyRate;
        set => $value;
    }

    /** @var string Описание опыта */
    public string $bio {
        get => $this->bio;
        set => $value;
    }

    /** @var float Средний рейтинг (вычисляемое) */
    public float $rating {
        get => $this->rating;
        set => $value;
    }

    /** @var ProfileStatus Статус */
    public ProfileStatus $status {
        get => $this->status;
        set => $value;
    }

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
        Money $hourlyRate,
        Money $balance,
        ProfileStatus $status,
        array $specializations = [],
        string $bio = "",
        float $rating = 0,
        string $company = "",
        string $position = "",
        string $about = "",
        string $industry = "", 
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->specializations = $specializations;
        $this->hourlyRate = $hourlyRate;
        $this->balance = $balance;
        $this->bio = $bio;
        $this->rating = $rating;
        $this->status = $status;
        $this->company = $company;
        $this->position = $position;
        $this->about = $about;
        $this->industry = $industry;
    }

    public static function create(Uuid $userId): self
    {
        return new self(
            id: Uuid::generate(),
            userId: $userId,
            hourlyRate: new Money(0, 'USD'),
            balance: new Money(0, 'USD'),
            status: self::DEFAULT_STATUS
        );
    }

    public function activate(): self
    {
        $this->status = ProfileStatus::Active;
        return $this;
    }

    public function suspend(): self
    {
        $this->status = ProfileStatus::Paused;
        return $this;
    }

    public function blocked(): self
    {
        $this->status = ProfileStatus::Blocked;
        return $this;
    }
}