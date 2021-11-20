<?php

use App\Repositories\CustomerRepository;
use App\Repositories\JsonCustomerRepository;
use App\Repositories\JsonOrderRepository;
use App\Repositories\OrderRepository;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'root_dir' => fn () => dirname($_SERVER['DOCUMENT_ROOT']),
        OrderRepository::class => fn (ContainerInterface $c) => new JsonOrderRepository($c->get('root_dir')),
        CustomerRepository::class => fn (ContainerInterface $c) => new JsonCustomerRepository($c->get('root_dir')),
    ]);
};
