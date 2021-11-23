<?php

declare(strict_types=1);

namespace App\Discounts;

use JsonSerializable;
use Webmozart\Assert\Assert;

final class Discount implements JsonSerializable
{
    public function __construct(
        private string $description,
        private int $amount
    ) {
        Assert::greaterThan($amount, 0);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function jsonSerialize()
    {
        return [
            'reason' => $this->description,
            'discount' => round($this->amount / 100, 2),
        ];
    }
}
