<?php

namespace App\Discounts\Handlers;

use App\Models\Order;

interface DiscountHandler
{
    public function handle(Order $order): void;
}
