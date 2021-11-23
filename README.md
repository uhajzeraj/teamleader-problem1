# Teamleader Problem 1 (Discounts) implementation

## General information

PHP 8 is required.  
The codebase uses the **Slim framework** and **PHP-DI** container.  
The container configuration can be found under `app/dependencies.php`.  
There are PHPUnit tests provided under the `tests/` directory.


## Setup

Install composer dependencies:
```shell
$ composer install
```

Start the local development server:
```shell
$ php -S localhost:8000 -t public 
```

**Optional**: Run PHPUnit tests
```shell
$ ./vendor/bin/phpunit
```

**Optional**: Run Psalm static analysis
```shell
$ ./vendor/bin/psalm
```

## API

There is one endpoint available:
```
POST /orders
```

With the following JSON payload example:  
```json
{
  "id": "3",
  "customer-id": "2",
  "items": [
    {
      "product-id": "A101",
      "quantity": "2",
      "unit-price": "9.75",
      "total": "19.50"
    },
    {
      "product-id": "A102",
      "quantity": "6",
      "unit-price": "49.5",
      "total": "297"
    },
    {
      "product-id": "B101",
      "quantity": "10",
      "unit-price": "4.99",
      "total": "49.9"
    },
    {
      "product-id": "B102",
      "quantity": "1",
      "unit-price": "4.99",
      "total": "4.99"
    },
    {
      "product-id": "B103",
      "quantity": "5",
      "unit-price": "12.95",
      "total": "64.75"
    }
  ],
    "total": "436.14"
}
```
**Note**: The fields `total` and `unit-price` for each item and the overall `total` field are not taken into account as they're retrieved & calculated on the backend.

The response would contain the following payload:  
```json
{
  "id": 3,
  "customer-id": 2,
  "items": [
    {
      "id": "A101",
      "quantity": 2,
      "unit-price": 9.75,
      "total": 19.5
    },
    {
      "id": "A102",
      "quantity": 6,
      "unit-price": 49.5,
      "total": 297
    },
    {
      "id": "B101",
      "quantity": 12,
      "unit-price": 4.99,
      "total": 59.88
    },
    {
      "id": "B102",
      "quantity": 1,
      "unit-price": 4.99,
      "total": 4.99
    },
    {
      "id": "B103",
      "quantity": 6,
      "unit-price": 12.95,
      "total": 77.7
    }
  ],
  "total": 459.07,
  "grand_total": 390.58,
  "discounts": [
    {
      "reason": "customer_overall_total_spent_over_1000",
      "discount": 43.61
    },
    {
      "reason": "five_switch_products_bought_B101",
      "discount": 9.98
    },
    {
      "reason": "five_switch_products_bought_B103",
      "discount": 12.95
    },
    {
      "reason": "more_than_two_category_tools_20_percent_discount_A101",
      "discount": 1.95
    }
  ]
}
```
This example payload contains all the different available discounts as described on the instructions.

## Adding more discount handlers

Under `app/config/discounts.php` you can find the list of discounts in use.  
In order to add more discounts, do the following:

1. Create a new discount handler class under `src/Discounts/Handlers` (This new class **must** extend the **DiscountHandler** interface).
2. Add the FQCN of the newly created class on the `app/config/discounts.php` array.

That's it. The container will take care of autowiring.

## Possible improvements
- Add form validation for the submitted JSON data
- Add error handling middleware (with proper logging)
- Increase the test coverage (possibly add feature tests)
- Increase psalm's strictness level
