<?php

namespace App\Repositories;

use App\Models\Item;

interface ProductsRepository
{
    public function getByCategoryId(int $categoryId): array;

    /**
     * @return Item[]
     */
    public function getByIds(array $ids): array;
}
