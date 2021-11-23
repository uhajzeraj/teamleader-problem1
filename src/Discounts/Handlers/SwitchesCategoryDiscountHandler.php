<?php

declare(strict_types=1);

namespace App\Discounts\Handlers;

use App\Discounts\Discount;
use App\Models\Item;
use App\Models\Order;

class SwitchesCategoryDiscountHandler implements DiscountHandler
{
    private const FREE_ITEM_THRESHOLD = 5;

    public function handle(Order $order): void
    {
        $switchCategoryItems = array_filter(
            $order->getItems(),
            fn (Item $item) => $item->getCategory() === Item::CATEGORY_SWITCHES
        );

        $items = array_filter(
            $switchCategoryItems,
            fn (Item $item) => floor($item->getQuantity() / self::FREE_ITEM_THRESHOLD) > 0
        );

        foreach ($items as $item) {
            $freeItemsCount = (int) floor($item->getQuantity() / self::FREE_ITEM_THRESHOLD);

            $item->increaseQuantity($freeItemsCount);

            $itemDiscount = $item->getPrice() * $freeItemsCount;

            $order->applyDiscount(new Discount(
                "five_switch_products_bought_{$item->getId()}",
                $itemDiscount,
            ));
        }
    }
}
