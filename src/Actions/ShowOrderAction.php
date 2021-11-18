<?php

namespace App\Actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ShowOrderAction
{
    public function __invoke(Request $request, Response $response, string $id): Response
    {
        // TODO: Make this dynamic later on
        // TODO: Add validation
        $order = <<<ORDER
        {
            "id": "3",
            "customer-id": "3",
            "items": [
            {
            "product-id": "A101",
            "quantity": "12",
            "unit-price": "9.75",
            "total": "19.50"
            },
            {
            "product-id": "A102",
            "quantity": "6",
            "unit-price": "49.50",
            "total": "49.50"
            }
            ],
            "total": "69.00"
            }
        ORDER;

        $order = json_decode($order, true);

        // Validate the request payload
        // Calculate the discount
        // Store the order with the discount data

        $discounts = [];

        // ======== First Discount

        // check order customer and see if order exceeds 1000 euros
        // if yes, give 10% discount on the order total
        // TODO: Extract to a repository
        $customer = $this->getCustomerById($request, $order['customer-id']);
        if ((float) $customer['revenue'] > 1000) {
            // Adjust the order total here
            $discounts[] = [
                'reason' => 'customer_total_spent_over_1000',
                'discount' => round($order['total'] * 0.1, 2),
            ];
        }

        // ======== Second Discount

        // check for "switches" category (id 2) of items
        // for every 5 items, give the 6th for free (just check the ordering of products)

        // Get the available products
        $rootDir = dirname($request->getServerParams()['DOCUMENT_ROOT']);

        $products = json_decode(file_get_contents($rootDir . '/var/products.json'), true);
        $products = array_filter($products, fn ($product) => $product['category'] === '2');
        $productIds = array_map(fn ($product) => $product['id'], $products);

        $items = array_filter($order['items'], fn ($item) => in_array($item['product-id'], $productIds));

        foreach ($items as $item) {
            $freeItemsCount = (int) floor((int) $item['quantity'] / 6);
            $itemDiscount = (float) $item['unit-price'] * $freeItemsCount;
            $discounts[] = [
                'reason' => "sixth_switch_product_bought_{$item['product-id']}",
                'discount' => round($itemDiscount, 2),
            ];
        }

        // ======== Third Discount

        // check for "tools" category (id 1) of items
        // if there are 2 or more items in this category, give a 20% discount on the cheapest item

        // Get the available products
        $rootDir = dirname($request->getServerParams()['DOCUMENT_ROOT']);

        $products = json_decode(file_get_contents($rootDir . '/var/products.json'), true);
        $products = array_filter($products, fn ($product) => $product['category'] === '1');
        $productIds = array_map(fn ($product) => $product['id'], $products);

        $items = array_filter($order['items'], fn ($item) => in_array($item['product-id'], $productIds));
        if (count($items) >= 2) {
            // Adjust the price for the cheapest item
            $cheapestItem = array_reduce($items, function ($cheapestItem, $item) {
                if ($cheapestItem === null) {
                    return $item;
                }

                if ((float) $item['unit-price'] < (float) $cheapestItem['unit-price']) {
                    return $item;
                }

                return $cheapestItem;
            });

            $discounts[] = [
                'reason' => "two_or_more_tools_category_product_discount_{$cheapestItem['product-id']}",
                'discount' => round((float) $cheapestItem['unit-price'] * 0.2, 2),
            ];
        }

        // Q: What happens when two or more discount conditions are met?
        // Probably combine the discounts

        // dd();
        // $response->with
        $response->getBody()->write(json_encode(['discounts' => $discounts]));
        $response = $response->withHeader('Content-Type', 'application/json');

        return $response;
    }

    private function getCustomerById(Request $request, string $customerId): array
    {
        $rootDir = dirname($request->getServerParams()['DOCUMENT_ROOT']);

        $customers = json_decode(file_get_contents($rootDir . '/var/customers.json'), true);

        return array_values((array_filter($customers, fn ($customer) => $customer['id'] === $customerId)))[0];
    }
}
