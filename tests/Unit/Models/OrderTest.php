<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Discounts\Discount;
use App\Models\Item;
use App\Models\Order;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    private Order $order;

    public function setUp(): void
    {
        parent::setUp();

        $items = [new Item('1', 2, 2535, 1), new Item('2', 1, 2795, 2)];
        $this->order = new Order(1, 1, $items);
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Order::class, $this->order);
    }

    /** @test */
    public function valid_items_should_be_passed()
    {
        $this->expectException(InvalidArgumentException::class);

        new Order(
            1,
            1,
            [1, new Item('1', 2, 2535, 1)]
        );
    }

    /** @test */
    public function it_can_calculate_the_total()
    {
        $item1 = 2535;
        $item2 = 2795 * 2;
        $this->assertSame($this->order->getTotal(), $item1 + $item2);
    }

    /** @test */
    public function it_can_calculate_the_grand_total()
    {
        $this->assertSame(
            $this->order->getGrandTotal(),
            $this->order->getTotal()
        );

        $this->order->applyDiscount(new Discount(
            'discount_reason_1',
            1150,
        ));

        $this->order->applyDiscount(new Discount(
            'discount_reason_2',
            1200,
        ));

        $this->assertSame(
            $this->order->getGrandTotal(),
            $this->order->getTotal() - 2350
        );
    }
}
