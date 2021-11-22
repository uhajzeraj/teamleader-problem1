<?php

namespace App\Actions;

use App\Discounts\Handlers\DiscountHandler;
use App\Discounts\Handlers\SwitchesCategoryDiscountHandler;
use App\Discounts\Handlers\ToolsCategoryDiscountHandler;
use App\Discounts\Handlers\TotalCustomerRevenueDiscountHandler;
use App\Models\Order;
use App\Repositories\CustomerRepository;
use App\Repositories\ProductsRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SaveOrderAction
{
    /**
     * @var string[]
     */
    private array $discountClasses = [
        TotalCustomerRevenueDiscountHandler::class,
        SwitchesCategoryDiscountHandler::class,
        ToolsCategoryDiscountHandler::class,
    ];

    public function __construct(
        private CustomerRepository $customerRepository,
        private ProductsRepository $productsRepository,
        private ContainerInterface $container,
    ) {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        // TODO: Add validation
        $data = json_decode($request->getBody(), true);

        $products = $this->productsRepository->getByIds($data['items']);

        $order = new Order($data['id'], $data['customer-id'], $products);

        foreach ($this->discountClasses as $class) {
            /** @var DiscountHandler */
            $class = $this->container->get($class);

            $class->handle($order);
        }

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($order));

        return $response;
    }
}
