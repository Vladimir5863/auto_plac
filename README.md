<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Auto Plac (project overview)

Auto Plac je Laravel aplikacija za oglašavanje i prodaju vozila sa admin panelom, izveštajima i naplatom isticanja oglasa.

### Glavne funkcionalnosti
- Upravljanje oglasima (standardni i istaknuti)
- Upravljanje vozilima i korisnicima (soft delete podrška)
- Uplate i izveštaji po periodu (snapshot tabela `izvestaj_oglas`)
- Admin dashboard sa realnim mesečnim prihodima (isticanja)

### Tehnologije
- Laravel 12, PHP 8.2, MariaDB/MySQL
- Blade, Bootstrap 5, Font Awesome, Chart.js

---

## Setup i pokretanje

1) Instaliraj zavisnosti
```
composer install
npm install
```

2) Napravi `.env`
```
cp .env.example .env
php artisan key:generate
```
Podesi DB kredencijale (MariaDB/MySQL).

3) Migracije i seed (opciono seed)
```
php artisan migrate
# ili ako želiš sve iz početka (briše tabele)
# php artisan migrate:fresh
```

4) Storage symlink (za prikaz slika ako se čuvaju u storage)
```
php artisan storage:link
```

5) Pokretanje dev servera
```
php artisan serve
```

6) Asseti (ako koristiš Vite/Laravel Mix)
```
npm run dev
# ili npm run build za produkciju
```

---

## Korisne napomene

- Uloge: polje `users.tipKorisnika` određuje ulogu (npr. `admin`). Provera uloga se radi preko tog polja.
- Slike vozila: polje `vozilo.slike` je kastovano u niz i može sadržati:
  - data URL (base64), apsolutne URL-ove, storage putanje (`storage/...`/`public/...`) ili public asset putanje. Za storage putanje obavezno je `php artisan storage:link`.
- Mesečni prihodi (dashboard): endpoint `route('admin.stats.monthly')` vraća zbir svih uplata sa `tip = 'featured'` za poslednjih 12 meseci (prazni meseci = 0).
- Izveštaji: tabela `izvestaj_oglas` ima PK `id` i jedinstveni indeks `io_uniq_izv_tip_ogl_kor_del` preko (`izvestajID`,`tip`,`oglasID`,`korisnikID`,`deleted_at`) kako bi soft-deleted redovi dozvolili reinsert.

### Troubleshooting
- "Foreign key constraint is incorrectly formed" (errno 150): proveri da su FK kolone istog tipa i unsigned, i da kolone koje se `SET NULL` ne učestvuju u primarnom ključu.
- "Identifier name ... is too long (1059)": skrati ime indeksa (npr. `io_uniq_izv_tip_ogl_kor_del`).
- Slike se ne vide: pokreni `php artisan storage:link` i proveri da vrednosti u `vozilo.slike` ukazuju na validne resurse.

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
