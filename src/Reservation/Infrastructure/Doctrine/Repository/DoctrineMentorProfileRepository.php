<?php

namespace App\Reservation\Infrastructure\Doctrine\Repository;

use App\Reservation\Domain\Entity\MentorProfile;
use App\Reservation\Domain\Repository\MentorProfileRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineMentorProfileRepository implements MentorProfileRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function find(string $id): ?MentorProfile
    {
        return $this->em->getRepository(MentorProfile::class)->findOneBy(['id' => $id]);
    }

    public function findByUserId(string $userId): ?MentorProfile
    {
        return $this->em->getRepository(MentorProfile::class)->findOneBy(['userId' => $userId]);
    }

    public function save(MentorProfile $profile): void
    {
        $this->em->persist($profile);
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