# LockByMemories

Laravel visual memory website for `chuaminh.vn`: mobile-first public album pages, template-based memories, block sections, Filament admin, and a custom visual editor.

## Stack

- Laravel 12, PHP 8.2+
- Filament 4 admin panel
- Blade, Tailwind CSS 4, Alpine.js
- Swiper, PhotoSwipe, SortableJS
- SQLite by default, Docker MySQL optional

## Run Locally

```bash
composer install
cp .env.example .env
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Open:

- Frontend: `http://localhost:8000`
- Admin: `http://localhost:8000/admin`
- Custom editor: `/admin/memories/{id}/editor`

Demo admin accounts:

```txt
admin@chuaminh.vn / password
love@chuaminh.vn / password
```

## MVP Included

- Public routes: home, memories, memory detail, gallery, timeline, map, about/search/archive foundations.
- Admin resources: memories, templates, categories, media, comments, reactions, private messages, users.
- Seeded categories, 8 visual templates, demo memories for Sapa, Valentine, birthday, Japan, and Hoi An.
- Section renderer for hero, stats, featured slider, quote, mosaic gallery, video/timeline foundations.
- Public reactions, pending comments, and private messages.
- Custom visual editor with template panel, block panel, media panel, mobile/desktop preview, block JSON editing, duplicate/delete, and drag ordering.

## Docker

```bash
docker compose up -d --build
docker compose exec lockbymemories_app php artisan migrate --seed
docker compose exec lockbymemories_app npm install
docker compose exec lockbymemories_app npm run build
```
