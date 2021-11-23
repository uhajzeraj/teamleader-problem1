<?php

declare(strict_types=1);

namespace App\Models;

final class Customer
{
    public function __construct(
        private int $id,
        private string $name,
        private float $revenue,
    ) {
    }

    public function getRevenue(): float
    {
        return $this->revenue;
    }
}
