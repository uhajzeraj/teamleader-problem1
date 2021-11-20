<?php

use App\Repositories\CustomerRepository;
use App\Repositories\JsonCustomerRepository;
use App\Repositories\JsonProductsRepository;
use App\Repositories\ProductsRepository;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'root_dir' => fn () => dirname($_SERVER['DOCUMENT_ROOT']),
        ProductsRepository::class => fn (ContainerInterface $c) => new JsonProductsRepository($c->get('root_dir')),
        CustomerRepository::class => fn (ContainerInterface $c) => new JsonCustomerRepository($c->get('root_dir')),
    ]);
};
