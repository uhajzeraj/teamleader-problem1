<?php

namespace App\Discounts\Handlers;

use App\Discounts\Discount;
use App\Models\Item;
use App\Models\Order;

class SwitchesCategoryDiscountHandler implements DiscountHandler
{
    public function handle(Order $order): void
    {
        $items = array_filter($order->getItems(), fn (Item $item) => $item->getCategory() === Item::CATEGORY_SWITCHES);

        foreach ($items as $item) {
            $freeItemsCount = floor($item->getQuantity() / 6);

            $itemDiscount = $item->getPrice() * $freeItemsCount;

            $order->applyDiscount(new Discount(
                "sixth_switch_product_bought_{$item->getId()}",
                round($itemDiscount, 2),
            ));
        }
    }
}
