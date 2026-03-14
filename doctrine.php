<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

$dotenv = Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

require __DIR__ . '/bootstrap/orm.php';
ConsoleRunner::run(
    new SingleManagerProvider($entityManager)
);