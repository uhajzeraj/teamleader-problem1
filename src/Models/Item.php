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
        private float $price,
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getTotal(): float
    {
        return round($this->quantity * $this->price, 2);
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit-price' => $this->price,
            'total' => $this->getTotal(),
        ];
    }
}
