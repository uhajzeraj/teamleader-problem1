<?php

namespace App\Discounts;

use JsonSerializable;
use Webmozart\Assert\Assert;

final class Discount implements JsonSerializable
{
    public function __construct(
        private string $description,
        private float $amount
    ) {
        Assert::greaterThan($amount, 0);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function jsonSerialize()
    {
        return [
            'reason' => $this->description,
            'discount' => $this->amount,
        ];
    }
}
