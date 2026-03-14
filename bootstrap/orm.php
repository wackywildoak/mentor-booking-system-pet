<?php

use App\Reservation\Infrastructure\Doctrine\Type\UuidType;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

if (!Type::hasType(UuidType::NAME)) {
    Type::addType(UuidType::NAME, UuidType::class);
}

$config = require __DIR__ . '/../config/config.php';

$ormConfig = ORMSetup::createXMLMetadataConfig(
    paths: [__DIR__ . '/../src/Reservation/Infrastructure/Doctrine/Mapping'],
    isDevMode: true
);

$ormConfig->setProxyDir(__DIR__ . '/../../proxies');
$ormConfig->setProxyNamespace('App\Proxies');
$ormConfig->setAutoGenerateProxyClasses(true);

$connection = DriverManager::getConnection(
    params: [
        'dbname' => $config['db']['name'],
        'user' => $config['db']['user'],
        'password' => $config['db']['password'],
        'host' => $config['db']['host'],
        'port' => $config['db']['port'],
        'driver' => 'pdo_mysql',
    ],
    config: $ormConfig
);

$entityManager = new EntityManager($connection, $ormConfig);