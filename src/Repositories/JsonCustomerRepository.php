<?php

declare(strict_types=1);

namespace App\Repositories;

class JsonCustomerRepository implements CustomerRepository
{
    public function __construct(private string $rootDir)
    {
    }

    public function getByCustomerId(int $customerId): array
    {
        $customers = json_decode(file_get_contents($this->rootDir . '/var/customers.json'), true);

        return array_values((array_filter($customers, fn ($customer) => (int) $customer['id'] === $customerId)))[0];
    }
}
