<?php

use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

// Register dependencies
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

$app = Bridge::create($containerBuilder->build());

$app->addErrorMiddleware(true, true, false);

// Register routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

$app->run();
