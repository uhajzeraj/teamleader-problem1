<?php

declare(strict_types=1);

namespace App\Models;

use App\Discounts\Discount;
use JsonSerializable;
use Webmozart\Assert\Assert;

final class Order implements JsonSerializable
{
    private array $discounts = [];

    /**
     * @param Item[] $items
     */
    public function __construct(
        private int $id,
        private int $customerId,
        private array $items
    ) {
        Assert::allIsInstanceOf($items, Item::class);
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    public function getTotal(): float
    {
        return array_reduce($this->items, function (float $total, Item $item) {
            return $total + $item->getTotal();
        }, 0);
    }

    public function getGrandTotal(): float
    {
        $discountsTotal = array_reduce($this->discounts, function (float $total, Discount $discount): float {
            return $total + $discount->getAmount();
        }, 0);

        return $this->getTotal() - $discountsTotal;
    }

    public function applyDiscount(Discount $discount): void
    {
        $this->discounts[] = $discount;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'customer-id' => $this->customerId,
            'items' => $this->items,
            'total' => $this->getTotal(),
            'grand_total' => $this->getGrandTotal(),
            'discounts' => $this->discounts,
        ];
    }
}
