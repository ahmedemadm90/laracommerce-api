# LaraCommerce API

A working Laravel 13 REST API for an e-commerce back end. The repository contains real database migrations, Eloquent models, Sanctum authentication, product search, stock-aware carts, transactional checkout, seed data, and feature tests.

## What is implemented

| Area | Working capabilities |
|---|---|
| Authentication | Registration, login, token revocation, logout with Laravel Sanctum |
| Catalog | Active-product listing, search, category filtering, price range filtering, pagination |
| Cart | Add items, merge quantities, update quantities, delete items, subtotal calculation |
| Checkout | Validated shipping address, stock checks, atomic order creation, stock decrement, cart clearing |
| Quality | SQLite-friendly migrations, demo seed data, automated feature tests |

## Stack

- PHP 8.3+
- Laravel 13
- Laravel Sanctum
- Eloquent ORM
- SQLite for local development; MySQL/PostgreSQL-compatible schema
- PHPUnit feature tests

## Quick start

```bash
git clone https://github.com/ahmedemadm90/laracommerce-api.git
cd laracommerce-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

The seeded demo account is `demo@laracommerce.test` with password `password`.

## API examples

Register:

```bash
curl -X POST http://127.0.0.1:8000/api/v1/auth/register \
  -H 'Accept: application/json' -H 'Content-Type: application/json' \
  -d '{"name":"Ada Lovelace","email":"ada@example.com","password":"password123","password_confirmation":"password123"}'
```

Browse the catalog:

```bash
curl 'http://127.0.0.1:8000/api/v1/products?search=keyboard&per_page=10'
```

Add an item to the cart and checkout using the bearer token returned from login:

```bash
curl -X POST http://127.0.0.1:8000/api/v1/cart \
  -H 'Authorization: Bearer YOUR_TOKEN' -H 'Content-Type: application/json' \
  -d '{"product_id":1,"quantity":2}'

curl -X POST http://127.0.0.1:8000/api/v1/orders \
  -H 'Authorization: Bearer YOUR_TOKEN' -H 'Content-Type: application/json' \
  -d '{"shipping_address":{"name":"Ada Lovelace","phone":"+201000000000","line1":"1 Main Street","city":"Cairo","country":"Egypt"}}'
```

## Test suite

```bash
php artisan test --compact
```

The test suite covers token creation, catalog search, transactional checkout, inventory decrement, and cart clearing.

## Project structure

```text
app/Http/Controllers/   Auth, catalog, cart, and order endpoints
app/Models/             User, category, product, cart, order, and order-item models
database/migrations/    Commerce schema and Sanctum token schema
database/seeders/       Demo customer and catalog
routes/api.php          Versioned `/api/v1` route map
tests/Feature/          End-to-end HTTP tests
```

## Author

Ahmed Emad — Backend, Mobile, and Automation Developer.
