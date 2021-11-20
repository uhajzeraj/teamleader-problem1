<?php

namespace App\Models;

use JsonSerializable;

final class Item implements JsonSerializable
{
    public function __construct(
        private string $id,
        private string $category,
        private float $price,
        private int $quantity = 1,
    ) {
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCategory(): string
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
        return $this->quantity * $this->price;
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
