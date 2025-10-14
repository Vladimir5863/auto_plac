<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Auto Plac

Auto Plac is a Laravel web application for listing and selling vehicles. It offers a public vehicle catalog, authenticated user accounts for managing ads, and an admin panel for moderation, revenue, and reporting.

### Features
- Manage ads (standard and featured), with soft deletes across users/ads/vehicles
- Vehicle management with photos (supports data URLs, storage paths, and public assets)
- Payments tracking and period-based reports (snapshot table `izvestaj_oglas`)
- Admin dashboard with real monthly revenue from featured payments (no dummy data)
- Search, filtering, recent activity widgets, and summary statistics

### Tech Stack
- Laravel 12, PHP 8.2, MariaDB/MySQL
- Blade, Bootstrap 5, Font Awesome, Chart.js

### Requirements
- PHP 8.2+
- Composer
- Node.js 18+ and npm
- MariaDB/MySQL

-

Revenue logic: monthly revenue equals the sum of all payments for special ads with grouped by month (last 12 months), with zero-filled gaps.
