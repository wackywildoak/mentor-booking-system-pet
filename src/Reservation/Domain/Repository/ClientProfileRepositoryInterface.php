<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Repository;

interface ClientProfileRepositoryInterface
{
    public function find(string $id): ?\App\Reservation\Domain\Entity\ClientProfile;
    
    public function findByUserId(string $userId): ?\App\Reservation\Domain\Entity\ClientProfile;

    public function save(\App\Reservation\Domain\Entity\ClientProfile $clientProfile): void;

    public function delete(string $id): void;
}