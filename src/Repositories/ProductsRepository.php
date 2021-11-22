<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Item;

interface ProductsRepository
{
    /**
     * @return Item[]
     */
    public function getByIds(array $ids): array;
}
