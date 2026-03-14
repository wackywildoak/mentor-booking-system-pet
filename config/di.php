<?php

use App\Reservation\Domain\Repository as Contract;
use App\Reservation\Application\Service;
use App\Reservation\Infrastructure\Doctrine\Repository;
use Doctrine\ORM\EntityManagerInterface;

return [
    EntityManagerInterface::class => function () {
        require_once __DIR__ . '/../bootstrap/orm.php';
        return $entityManager;
    },

    Contract\MentorRepositoryInterface::class => function ($container) {
        return new Repository\DoctrineMentorRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    Contract\UserRepositoryInterface::class => function ($container) {
        return new Repository\DoctrineUserRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    Service\AuthService::class => function ($container) {
        return new Service\AuthService(
            $container->get(Contract\UserRepositoryInterface::class)
        );
    },
];
