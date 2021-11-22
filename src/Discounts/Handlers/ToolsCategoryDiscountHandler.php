<?php

namespace App\Discounts\Handlers;

use App\Models\Item;
use App\Models\Order;

class ToolsCategoryDiscountHandler implements DiscountHandler
{
    public function handle(Order $order): void
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
