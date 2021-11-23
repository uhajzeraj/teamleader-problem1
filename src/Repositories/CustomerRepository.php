<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Customer;
use RuntimeException;

interface CustomerRepository
{
    /**
     * @throws RuntimeException
     */
    public function getByCustomerId(int $customerId): Customer;
}
