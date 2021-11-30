<?php

declare(strict_types=1);

namespace Tests\Unit\Discounts;

use App\Discounts\Handlers\TotalCustomerRevenueDiscountHandler;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Repositories\CustomerRepository;
use PHPUnit\Framework\TestCase;

class TotalCustomerRevenueDiscountHandlerTest extends TestCase
{
    /** @test */
    public function discount_is_applied_for_customers_with_revenue_over_1000(): void
    {
        $repository = $this->createStub(CustomerRepository::class);
        $repository->method('getByCustomerId')
            ->willReturn(new Customer(1, 'my name', 100001));

        $handler = new TotalCustomerRevenueDiscountHandler($repository);

        $order = $this->getOrder();
        $handler->handle($order);

        $this->assertCount(1, $order->getDiscounts());
    }

    /** @test */
    public function discount_is_not_applied_for_customers_with_revenue_1000_or_less(): void
    {
        $repository = $this->createStub(CustomerRepository::class);
        $repository->method('getByCustomerId')
            ->willReturn(new Customer(1, 'my name', 100000));

        $handler = new TotalCustomerRevenueDiscountHandler($repository);

        $order = $this->getOrder();
        $handler->handle($order);

        $this->assertCount(0, $order->getDiscounts());
    }

    private function getOrder(): Order
    {
        return new Order(1, 1, [new Item('A101', 2, 200, 1)]);
    }
}
