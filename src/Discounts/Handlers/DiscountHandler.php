<?php

declare(strict_types=1);

namespace App\Discounts\Handlers;

use App\Models\Order;

interface DiscountHandler
{
    public function handle(Order $order): void;
}
