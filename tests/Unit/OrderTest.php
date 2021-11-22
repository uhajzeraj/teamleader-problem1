<?php

declare(strict_types=1);

namespace Tests\Unit;

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

        $items = [new Item('1', '2', 25.35, 1), new Item('2', '1', 27.95, 2)];
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
            [1, ['id' => 1, 'category' => '5'], new Item('1', '2', 25.35, 1)]
        );
    }

    /** @test */
    public function it_can_calculate_the_total()
    {
        $item1 = 25.35;
        $item2 = 27.95 * 2;
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
            11.5,
        ));

        $this->order->applyDiscount(new Discount(
            'discount_reason_2',
            12,
        ));

        $this->assertSame(
            $this->order->getGrandTotal(),
            $this->order->getTotal() - 23.5
        );
    }
}
