<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Tools\Console\Command;

$dotenv = Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

require __DIR__ . '/bootstrap/orm.php';

$entityManagerProvider = new SingleManagerProvider($entityManager);

$migrationConfig = new PhpFile(__DIR__ . '/config/migration.php');
$dependencyFactory = DependencyFactory::fromEntityManager($migrationConfig, new ExistingEntityManager($entityManager));

ConsoleRunner::run($entityManagerProvider, [
    new Command\DiffCommand($dependencyFactory),
    new Command\ExecuteCommand($dependencyFactory),
    new Command\GenerateCommand($dependencyFactory),
    new Command\LatestCommand($dependencyFactory),
    new Command\ListCommand($dependencyFactory),
    new Command\MigrateCommand($dependencyFactory),
    new Command\RollupCommand($dependencyFactory),
    new Command\StatusCommand($dependencyFactory),
    new Command\VersionCommand($dependencyFactory),
]);