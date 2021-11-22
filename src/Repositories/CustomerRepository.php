<?php

namespace App\Repositories;

use RuntimeException;

interface CustomerRepository
{
    /**
     * @throws RuntimeException
     */
    public function getByCustomerId(int $customerId);
}
