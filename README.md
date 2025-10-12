<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Auto Plac

Auto Plac is a Laravel web application for listing and selling vehicles. It offers a public vehicle catalog, authenticated user accounts for managing ads, and an admin panel for moderation, revenue, and reporting.

<<<<<<< HEAD
## Auto Plac

Auto Plac is a Laravel web application for listing and selling vehicles. It offers a public vehicle catalog, authenticated user accounts for managing ads, and an admin panel for moderation, revenue, and reporting.

### Features

-   Manage ads (standard and featured), with soft deletes across users/ads/vehicles
-   Vehicle management with photos (supports data URLs, storage paths, and public assets)
-   Payments tracking and period-based reports (snapshot table `izvestaj_oglas`)
-   Admin dashboard with real monthly revenue from featured payments (no dummy data)
-   Search, filtering, recent activity widgets, and summary statistics

### Features

-   Manage ads (standard and featured), with soft deletes across users/ads/vehicles
-   Vehicle management with photos (supports data URLs, storage paths, and public assets)
-   Payments tracking and period-based reports (snapshot table `izvestaj_oglas`)
-   Admin dashboard with real monthly revenue from featured payments (no dummy data)
-   Search, filtering, recent activity widgets, and summary statistics

### Tech Stack

### Tech Stack

-   Laravel 12, PHP 8.2, MariaDB/MySQL
-   Blade, Bootstrap 5, Font Awesome, Chart.js

### Requirements
=======
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

---

## Setup

1) Clone and install dependencies
```
git clone <your-repo-url> auto_plac
cd auto_plac
composer install
npm install
```

2) Environment
```
cp .env.example .env
php artisan key:generate
```
Edit `.env` and set your database credentials (MariaDB/MySQL).

3) Database migrations
```
php artisan migrate
# or to reset from scratch (DROPS tables)
# php artisan migrate:fresh
```

4) Storage symlink (needed if images are stored under storage/app/public)
```
php artisan storage:link
```

5) Run the app
```
php artisan serve
```

6) Front-end assets (if applicable)
```
npm run dev
# or npm run build for production
```
>>>>>>> 882bbcf (Version 1)

-   PHP 8.2+
-   Composer
-   Node.js 18+ and npm
-   MariaDB/MySQL

<<<<<<< HEAD
-

Revenue logic: monthly revenue equals the sum of all payments for special ads with grouped by month (last 12 months), with zero-filled gaps.
=======
## Troubleshooting
- "Foreign key constraint is incorrectly formed" (errno 150): ensure FK column types match and that columns set to `NULL` on delete are not part of a primary key.
- "Identifier name ... is too long (1059)": shorten index names (e.g., `io_uniq_izv_tip_ogl_kor_del`).
- Images not visible: run `php artisan storage:link` and check that `vozilo.slike` contains valid paths or URLs.
 
---

## Usage (overview)
- Public catalog: browse and search vehicles via `vehicles.index`/`vehicles.search` and `vehicles.show`.
- Users can create and manage their own ads under the Ads menu. Featured ads are charged and reflected in revenue.
- Admin area (`/admin`): dashboard with monthly revenue chart, reports list, users and ads management, and quick actions.

Revenue logic: monthly revenue equals the sum of all payments with `tip = 'featured'` grouped by month (last 12 months), with zero-filled gaps.
>>>>>>> 882bbcf (Version 1)
