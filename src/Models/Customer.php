<?php

declare(strict_types=1);

namespace App\Models;

final class Customer
{
    public function __construct(
        private int $id,
        private string $name,
        private int $revenue,
    ) {
    }

    public function getRevenue(): int
    {
        return $this->revenue;
    }
}
