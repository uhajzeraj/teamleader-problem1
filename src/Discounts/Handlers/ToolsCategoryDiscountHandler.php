<?php

declare(strict_types=1);

namespace App\Discounts\Handlers;

use App\Discounts\Discount;
use App\Models\Item;
use App\Models\Order;

class ToolsCategoryDiscountHandler implements DiscountHandler
{
    public function handle(Order $order): void
    {
        $items = array_filter(
            $order->getItems(),
            fn (Item $item) => $item->getCategory() === Item::CATEGORY_TOOLS
        );

        if (count($items) < 2) {
            return;
        }

        $cheapestItem = $this->getCheapestItem($items);

        $order->applyDiscount(new Discount(
            "more_than_two_category_tools_20_percent_discount_{$cheapestItem->getId()}",
            (int) ($cheapestItem->getPrice() * 0.2),
        ));
    }

    /**
     * @param Item[] $items
     */
    private function getCheapestItem($items): Item
    {
        return array_reduce($items, function (?Item $cheapestItem, Item $item): Item {
            if ($cheapestItem === null) {
                return $item;
            }

            if ($item->getPrice() < $cheapestItem->getPrice()) {
                return $item;
            }

            return $cheapestItem;
        });
    }
}
