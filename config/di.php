<?php

use App\Reservation\Domain\Repository as Contract;
use App\Reservation\Application\Service;
use App\Reservation\Infrastructure\Doctrine\Repository;
use App\Reservation\Infrastructure\Auth\JwtManager;
use App\Reservation\Infrastructure\Auth\RefreshTokenStorage;
use Doctrine\ORM\EntityManagerInterface;

$config = require __DIR__ . '/config.php';

return [
    EntityManagerInterface::class => function () {
        require_once __DIR__ . '/../bootstrap/orm.php';
        return $entityManager;
    },

    JwtManager::class => function ($container) use ($config) {
        return new JwtManager(
            secretKey: $config['jwt']['secret']
        );
    },

    RefreshTokenStorage::class => function ($container) {
        return new RefreshTokenStorage(
            $container->get(EntityManagerInterface::class)
        );
    },

    Contract\UserRepositoryInterface::class => function ($container) {
        return new Repository\DoctrineUserRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    Contract\ClientProfileRepositoryInterface::class => function ($container) {
        return new Repository\DoctrineClientProfileRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    Service\AuthService::class => function ($container) {
        return new Service\AuthService(
            $container->get(Contract\UserRepositoryInterface::class),
            $container->get(Contract\ClientProfileRepositoryInterface::class),
            $container->get(RefreshTokenStorage::class),
            $container->get(JwtManager::class)
        );
    },
];
