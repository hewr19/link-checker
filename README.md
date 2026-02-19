# Professional Cashier System (Laravel)

This repository contains a **Laravel-based cashier/POS starter** designed for real-world usage:

- Product catalog with SKU, barcode, stock, and active status.
- Checkout flow with validation, tax calculation, and change calculation.
- Atomic transactions with stock locking to avoid overselling.
- Receipt number generation and sale-item records.

## Core Modules

1. **Products**: inventory and pricing source of truth.
2. **POS / Checkout**: cashier UI and transaction processing.
3. **Sales Ledger**: sales headers + line items.

## Setup (when dependencies are available)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Open `http://127.0.0.1:8000` and process transactions from the cashier page.

## Suggested Next Enhancements

- Authentication + role-based access (admin/cashier/supervisor)
- Discounts & promo engine
- Return/refund workflow
- Daily shift opening/closing balance
- Printable PDF receipts and thermal printer integration
- Sales dashboard and export
