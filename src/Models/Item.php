<?php

declare(strict_types=1);

namespace App\Models;

use JsonSerializable;
use Webmozart\Assert\Assert;

final class Item implements JsonSerializable
{
    public const CATEGORY_TOOLS = 1;
    public const CATEGORY_SWITCHES = 2;

    public function __construct(
        private string $id,
        private int $category,
        private int $price,
        private int $quantity = 1,
    ) {
        Assert::greaterThan($quantity, 0);
    }

    public function increaseQuantity(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getTotal(): int
    {
        return $this->quantity * $this->price;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit-price' => round($this->price / 100, 2),
            'total' => round($this->getTotal() / 100, 2),
        ];
    }
}
