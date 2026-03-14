<?php

use DI\ContainerBuilder;

$builder = new ContainerBuilder();

$builder->addDefinitions(__DIR__ . '/../config/di.php');

return $builder->build();