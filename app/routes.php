<?php

use App\Actions\HelloAction;
use Slim\App;
use Slim\Handlers\Strategies\RequestResponseArgs;

return function (App $app) {
    $routeCollector = $app->getRouteCollector();
    $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

    $app->get('/hello/{name}', HelloAction::class);
};
