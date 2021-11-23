<?php

declare(strict_types=1);

use App\Actions\SaveOrderAction;
use App\Discounts\Handlers\DiscountHandler;
use App\Repositories\CustomerRepository;
use App\Repositories\JsonCustomerRepository;
use App\Repositories\JsonProductsRepository;
use App\Repositories\ProductsRepository;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface as Container;
use Webmozart\Assert\Assert;

use function DI\autowire;
use function DI\get;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'available_discounts' => fn () => require 'config/discounts.php',
    ]);

    $containerBuilder->addDefinitions([
        'root_dir' => fn () => dirname($_SERVER['DOCUMENT_ROOT']),

        ProductsRepository::class => fn (Container $c) => new JsonProductsRepository($c->get('root_dir')),
        CustomerRepository::class => fn (Container $c) => new JsonCustomerRepository($c->get('root_dir')),

        'discount_handlers' => function (Container $c) {
            return array_map(function (string $handler) use ($c) {
                $classHandler = $c->get($handler);
                Assert::isInstanceOf($classHandler, DiscountHandler::class);
                return $classHandler;
            }, $c->get('available_discounts'));
        },

        SaveOrderAction::class => autowire()
            ->constructorParameter('discountHandlers', get('discount_handlers')),
    ]);
};
