<?php

namespace App\Reservation\Infrastructure\Doctrine\Repository;

use App\Reservation\Domain\Entity\User;
use App\Reservation\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function all(): array
    {
        return $this->em->getRepository(User::class)->findAll();
    }

    public function find(string $id): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['id' => $id]);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['email.value' => $email]);
    }

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}