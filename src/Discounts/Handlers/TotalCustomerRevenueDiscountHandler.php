<?php

namespace App\Discounts\Handlers;

use App\Models\Order;
use App\Repositories\CustomerRepository;

class TotalCustomerRevenueDiscountHandler implements DiscountHandler
{
    public function __construct(private CustomerRepository $customerRepository)
    {
    }

    public function handle(Order $order): void
    {
        $customer = $this->customerRepository->getByCustomerId($order->getCustomerId());

        if ((float) $customer['revenue'] <= 1000) {
            return;
        }

        $order->applyDiscount([
            'reason' => 'customer_total_spent_over_1000',
            'discount' => round($order->getTotal() * 0.1, 2),
        ]);
    }
}
