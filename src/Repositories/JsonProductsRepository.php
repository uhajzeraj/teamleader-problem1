<?php

namespace App\Repositories;

class JsonProductsRepository implements ProductsRepository
{
    public function __construct(private string $rootDir)
    {
    }

    public function getByCategoryId(int $categoryId): array
    {
        $products = json_decode(file_get_contents($this->rootDir . '/var/products.json'), true);

        return array_filter($products, fn ($product) => (int) $product['category'] === $categoryId);
    }
}
