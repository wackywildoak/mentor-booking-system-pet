<?php

namespace App\Reservation\Infrastructure\Doctrine\Repository;

use App\Reservation\Domain\Entity\ClientProfile;
use App\Reservation\Domain\Repository\ClientProfileRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineClientProfileRepository implements ClientProfileRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function find(string $id): ?ClientProfile
    {
        return $this->em->getRepository(ClientProfile::class)->findOneBy(['id' => $id]);
    }

    public function findByUserId(string $userId): ?ClientProfile
    {
        return $this->em->getRepository(ClientProfile::class)->findOneBy(['user_id' => $userId]);
    }

    public function save(ClientProfile $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function delete(string $id): void
    {
        $entity = $this->find($id);
        if ($entity !== null) {
            $this->em->remove($entity);
            $this->em->flush();
        }
    }
}