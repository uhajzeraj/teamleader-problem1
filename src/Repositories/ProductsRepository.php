<?php

namespace App\Repositories;

interface ProductsRepository
{
    public function getByCategoryId(int $categoryId): array;
}
