<?php

namespace App\Models;

final class Item
{
    public function __construct(
        private string $id,
        private string $category,
        private int $price,
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

    public function getPrice(): int
    {
        return $this->price;
    }
}
