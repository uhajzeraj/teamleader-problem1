<?php

namespace App\Actions;

use App\Models\Item;
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
        // Return the order with the discount data

        $order = json_decode($request->getBody(), true);

        $products = $this->productsRepository->getByIds($order['items']);
        // Create order

        $discounts = [];

        // ======== First Discount
        // check order customer and see if order exceeds 1000 euros
        // if yes, give 10% discount on the order total
        $discounts = $this->applyFirstDiscount($discounts, $order);

        // ======== Second Discount
        // check for "switches" category (id 2) of items
        // for every 5 items, give the 6th for free (just check the ordering of products)
        $discounts = $this->applySecondDiscount($discounts, $products);

        // ======== Third Discount
        // check for "tools" category (id 1) of items
        // if there are 2 or more items in this category, give a 20% discount on the cheapest item
        $discounts = $this->applyThirdDiscount($discounts, $products);

        // Q: What happens when two or more discount conditions are met?
        // Probably combine the discounts

        $discountsTotal = array_reduce($discounts, fn ($total, $discount) => $total + $discount['discount'], 0);
        $grandTotal = (string) ($order['total'] - $discountsTotal);

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(array_merge(
            $order,
            ['grand_total' => $grandTotal],
            ['discounts' => $discounts],
        )));

        return $response;
    }

    private function applyFirstDiscount(array $discounts, $order): array
    {
        $customer = $this->customerRepository->getByCustomerId($order['customer-id']);
        if ((float) $customer['revenue'] > 1000) {
            $discounts[] = [
                'reason' => 'customer_total_spent_over_1000',
                'discount' => round($order['total'] * 0.1, 2),
            ];
        }

        return $discounts;
    }

    /**
     * @param Item[] $items
     */
    private function applySecondDiscount(array $discounts, $items): array
    {
        $items = array_filter($items, fn (Item $item) => $item->getCategory() === '1');

        foreach ($items as $item) {
            $freeItemsCount = (int) floor((int) $item->getQuantity() / 6);
            $itemDiscount = (float) $item->getPrice() * $freeItemsCount;
            $discounts[] = [
                'reason' => "sixth_switch_product_bought_{$item->getId()}",
                'discount' => round($itemDiscount, 2),
            ];
        }

        return $discounts;
    }

    /**
     * @param Item[] $items
     */
    private function applyThirdDiscount(array $discounts, $items): array
    {
        $items = array_filter($items, fn (Item $item) => $item->getCategory() === '1');

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

            $discounts[] = [
                'reason' => "two_or_more_tools_category_product_discount_{$cheapestItem->getId()}",
                'discount' => round((float) $cheapestItem->getPrice() * 0.2, 2),
            ];
        }

        return $discounts;
    }
}
