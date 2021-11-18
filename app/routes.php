<?php

use App\Actions\HelloAction;
use App\Actions\ShowOrderAction;
use Slim\App;
use Slim\Handlers\Strategies\RequestResponseArgs;

return function (App $app) {
    $routeCollector = $app->getRouteCollector();
    $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

    $app->get('/hello/{name}', HelloAction::class);
    $app->get('/orders/{order}', ShowOrderAction::class);
};
