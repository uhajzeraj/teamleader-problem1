<?php

namespace App\Repositories;

use App\Models\Item;

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

    public function getByIds(array $items): array
    {
        $products = json_decode(file_get_contents($this->rootDir . '/var/products.json'), true);

        $itemIds = array_map(fn ($item) => $item['product-id'], $items);
        $products = array_filter($products, fn ($product) => in_array($product['id'], $itemIds));

        $result = [];

        foreach ($products as $product) {
            $item = array_values(array_filter($items, fn ($item) => $item['product-id'] === $product['id']))[0];

            $result[] = new Item(
                $product['id'],
                $product['category'],
                $product['price'],
                $item['quantity'],
            );
        }

        return $result;
    }
}
