<?php

namespace App\Discounts\Handlers;

use App\Discounts\Discount;
use App\Models\Item;
use App\Models\Order;

class SwitchesCategoryDiscountHandler implements DiscountHandler
{
    public function handle(Order $order): void
    {
        $items = array_filter($order->getItems(), fn (Item $item) => $item->getCategory() === '1');

        foreach ($items as $item) {
            $freeItemsCount = (int) floor((int) $item->getQuantity() / 6);

            $itemDiscount = (float) $item->getPrice() * $freeItemsCount;

            $order->applyDiscount(new Discount(
                "sixth_switch_product_bought_{$item->getId()}",
                round($itemDiscount, 2),
            ));
        }
    }
}
