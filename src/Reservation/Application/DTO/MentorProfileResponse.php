<?php

declare(strict_types=1);

namespace App\Reservation\Application\DTO;

use App\Reservation\Domain\ValueObject\Money;

class MentorProfileResponse
{
    public function __construct(
        public string $name,
        public string $email,
        public Money $balance,
        public Money $hourlyRate,
        public string $company,
        public string $position,
        public string $about,
        public string $industry,
        public string $status,
    )
    {}
} 