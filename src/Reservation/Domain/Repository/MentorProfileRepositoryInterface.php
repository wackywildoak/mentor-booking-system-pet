<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Repository;

interface MentorProfileRepositoryInterface
{
    public function find(string $id): ?\App\Reservation\Domain\Entity\MentorProfile;
    
    public function findByUserId(string $userId): ?\App\Reservation\Domain\Entity\MentorProfile;

    public function save(\App\Reservation\Domain\Entity\MentorProfile $mentorProfile): void;

    public function delete(string $id): void;
}