# Price Tracker

This project is designed to track the prices of products from various online retailers. It allows users to monitor price
changes over time, receiving notifications for price drops.

## Requirements

This is a laravel project that can be hosted on any shared hosting service supporting PHP 8.4+

## Getting started

1. Clone the project on your server
2. Initialize the project using `make install`
3. Login and start adding products

## Worker

The project use laravel jobs, to ensure all task are handled add this as a cronjob

```bash
php artisan queue:work --stop-when-empty
```
