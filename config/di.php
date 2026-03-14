<?php

use App\Reservation\Domain\Repository\MentorRepositoryInterface;
use App\Reservation\Infrastructure\Doctrine\Repository\DoctrineMentorRepository;
use Doctrine\ORM\EntityManagerInterface;

return [
    EntityManagerInterface::class => function () {
        require_once __DIR__ . '/../bootstrap/orm.php';
        return $entityManager;
    },

    MentorRepositoryInterface::class => function ($container) {
        return new DoctrineMentorRepository(
            $container->get(EntityManagerInterface::class)
        );
    },
];
