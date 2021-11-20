<?php

namespace App\Actions;

use App\Models\Item;
use App\Models\Order;
use App\Repositories\CustomerRepository;
use App\Repositories\ProductsRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SaveOrderAction
{
    public function __construct(
        private CustomerRepository $customerRepository,
        private ProductsRepository $productsRepository,
    ) {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        // TODO: Add validation
        $data = json_decode($request->getBody(), true);

        $products = $this->productsRepository->getByIds($data['items']);

        $order = new Order($data['id'], $data['customer-id'], $products);

        // ======== First Discount
        // check order customer and see if order exceeds 1000 euros
        // if yes, give 10% discount on the order total
        $this->applyFirstDiscount($order);

        // ======== Second Discount
        // check for "switches" category (id 2) of items
        // for every 5 items, give the 6th for free (just check the ordering of products)
        $this->applySecondDiscount($order);

        // ======== Third Discount
        // check for "tools" category (id 1) of items
        // if there are 2 or more items in this category, give a 20% discount on the cheapest item
        $this->applyThirdDiscount($order);

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($order));

        return $response;
    }

    private function applyFirstDiscount(Order $order): void
    {
        $customer = $this->customerRepository->getByCustomerId($order->getCustomerId());

        if ((float) $customer['revenue'] > 1000) {
            $order->applyDiscount([
                'reason' => 'customer_total_spent_over_1000',
                'discount' => round($order->getTotal() * 0.1, 2),
            ]);
        }
    }

    /**
     * @param Item[] $items
     */
    private function applySecondDiscount(Order $order): void
    {
        $items = array_filter($order->getItems(), fn (Item $item) => $item->getCategory() === '1');

        foreach ($items as $item) {
            $freeItemsCount = (int) floor((int) $item->getQuantity() / 6);
            $itemDiscount = (float) $item->getPrice() * $freeItemsCount;
            $order->applyDiscount([
                'reason' => "sixth_switch_product_bought_{$item->getId()}",
                'discount' => round($itemDiscount, 2),
            ]);
        }
    }

    /**
     * @param Item[] $items
     */
    private function applyThirdDiscount(Order $order): void
    {
        $items = array_filter($order->getItems(), fn (Item $item) => $item->getCategory() === '1');

        if (count($items) >= 2) {
            $cheapestItem = array_reduce($items, function (?Item $cheapestItem, Item $item): Item {
                if ($cheapestItem === null) {
                    return $item;
                }

                if ($item->getPrice() < $cheapestItem->getPrice()) {
                    return $item;
                }

                return $cheapestItem;
            });

            $order->applyDiscount([
                'reason' => "more_than_two_category_tools_20_percent_discount_{$cheapestItem->getId()}",
                'discount' => round((float) $cheapestItem->getPrice() * 0.2, 2),
            ]);
        }
    }
}
