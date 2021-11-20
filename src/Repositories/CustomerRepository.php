<?php

namespace App\Repositories;

interface CustomerRepository
{
    public function getByCustomerId(int $customerId);
}
