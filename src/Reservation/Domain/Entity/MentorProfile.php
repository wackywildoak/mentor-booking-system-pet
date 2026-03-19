<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Entity;

use App\Reservation\Domain\ValueObject\Uuid;
use App\Reservation\Domain\ValueObject\Money;

class MentorProfile extends Profile
{
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

    /** @var string Статус */
    public string $status {
        get => $this->status;
        set => $value;
    }

    public function __construct(
        Uuid $id,
        Uuid $userId,
        array $specializations,
        Money $hourlyRate,
        Money $balance,
        string $bio,
        float $rating,
        string $status
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->specializations = $specializations;
        $this->hourlyRate = $hourlyRate;
        $this->balance = $balance;
        $this->bio = $bio;
        $this->rating = $rating;
        $this->status = $status;
    }
}