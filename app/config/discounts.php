<?php

declare(strict_types=1);

use App\Discounts\Handlers\SwitchesCategoryDiscountHandler;
use App\Discounts\Handlers\ToolsCategoryDiscountHandler;
use App\Discounts\Handlers\TotalCustomerRevenueDiscountHandler;

return [
    TotalCustomerRevenueDiscountHandler::class,
    SwitchesCategoryDiscountHandler::class,
    ToolsCategoryDiscountHandler::class,
];
