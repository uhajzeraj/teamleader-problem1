<?php

declare(strict_types=1);

namespace App\Actions;

use App\Discounts\Handlers\DiscountHandler;
use App\Models\Order;
use App\Repositories\ProductsRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SaveOrderAction
{
    /**
     * @param DiscountHandler[] $discountHandlers
     */
    public function __construct(
        private ProductsRepository $productsRepository,
        private array $discountHandlers,
    ) {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        // TODO: Add validation
        $data = json_decode((string) $request->getBody(), true);

        $products = $this->productsRepository->getByIds($data['items']);

        $order = new Order(
            (int) $data['id'],
            (int) $data['customer-id'],
            $products
        );

        foreach ($this->discountHandlers as $discountHandler) {
            $discountHandler->handle($order);
        }

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($order));

        return $response;
    }
}
