<?php

declare(strict_types=1);

use App\Actions\HelloAction;
use App\Actions\SaveOrderAction;
use Slim\App;
use Slim\Handlers\Strategies\RequestResponseArgs;

return function (App $app) {
    $routeCollector = $app->getRouteCollector();
    $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

    $app->get('/hello/{name}', HelloAction::class);
    $app->post('/orders', SaveOrderAction::class);
};
