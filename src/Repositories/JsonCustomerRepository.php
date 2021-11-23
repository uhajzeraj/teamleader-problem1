<?php

declare(strict_types=1);

namespace App\Repositories;

use RuntimeException;

class JsonCustomerRepository implements CustomerRepository
{
    public function __construct(private string $rootDir)
    {
    }

    /**
     * @throws RuntimeException
     */
    public function getByCustomerId(int $customerId): array
    {
        $customers = json_decode(file_get_contents($this->rootDir . '/var/customers.json'), true);

        $customers = array_values((array_filter($customers, fn (array $customer) => (int) $customer['id'] === $customerId)));

        if ($customers === []) {
            throw new RuntimeException("Customer with id $customerId was not found");
        }

        return $customers[0];
    }
}
