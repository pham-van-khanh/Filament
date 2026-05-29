# LockByMemories

LockByMemories is a Laravel visual memory album site for `chuaminh.vn`.
It combines mobile-first public album pages, a Filament admin panel, and a
custom visual editor for memories built from reusable media blocks.

## Highlights

- Public pages for home, memory listing, memory detail, gallery, timeline,
  map, search, categories, tags, archive, about, and featured memories.
- Filament admin resources for memories, templates, section types, categories,
  tags, media, comments, reactions, private messages, and users.
- Custom memory editor at `/admin/memories/{post}/editor` with template
  selection, block controls, media upload, drag ordering, and preview modes.
- Typed editor storage using `post_sections` and `post_section_items`; per-post
  content should not be stored as raw JSON.
- Image-first album rendering with support for uploaded images, uploaded
  videos, quotes, stats, music, galleries, and timeline sections.

## Stack

- Laravel 12 and PHP 8.2+
- Filament 4
- Blade, Tailwind CSS 4, Alpine.js
- Vite, Swiper, PhotoSwipe, SortableJS
- SQLite for local development by default
- Docker Compose with MySQL 8.0 as an optional runtime

## Requirements

- PHP 8.2 or newer
- Composer 2
- Node.js 20 or newer and npm
- SQLite extension for local development
- Docker Desktop if using the Docker workflow

## Quick Start

The Composer `setup` script installs dependencies, creates `.env`, creates the
SQLite database file, generates the app key, runs migrations and seeders, and
builds Vite assets.

```bash
composer run setup
php artisan storage:link
composer run dev
```

Open:

- Frontend: `http://localhost:8000`
- Admin: `http://localhost:8000/admin`
- Custom editor: `http://localhost:8000/admin/memories/{id}/editor`

Demo admin accounts:

```txt
admin@chuaminh.vn / password
love@chuaminh.vn / password
```

## Manual Local Setup

```bash
composer install
cp .env.example .env
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

For frontend development, run Vite in another terminal:

```bash
npm run dev
```

## Docker

The Docker app service is `filament_app`; Nginx serves the app on port `8000`.

```bash
docker compose up -d --build
docker compose exec -T filament_app php artisan migrate --seed
docker compose exec -T filament_app php artisan storage:link
docker compose exec -T filament_app npm install
docker compose exec -T filament_app npm run build
```

Open `http://localhost:8000`.

Useful Docker commands:

```bash
docker compose exec -T filament_app php artisan test
docker compose exec -T filament_app php artisan migrate:fresh --seed
docker compose exec -T filament_app npm run build
```

## Useful Commands

```bash
composer test
php artisan test
vendor/bin/pint
npm run build
npm run dev
php artisan migrate:fresh --seed
```

Feature tests run against an in-memory SQLite database through `phpunit.xml`.

## Project Map

- `app/Filament/Resources` - Filament admin resources and pages.
- `app/Http/Controllers/Frontend` - Public page controllers.
- `app/Http/Controllers/Admin/MemoryEditorController.php` - Custom visual
  editor read/write flow.
- `app/Models` - Eloquent models for posts, media, sections, interactions, and
  taxonomy.
- `app/Services/Rendering` - Section rendering, media collection, and design
  token merging.
- `resources/views/frontend/pages` - Public page views.
- `resources/views/frontend/sections` - Rendered memory section components.
- `resources/views/admin/memories/editor.blade.php` - Custom editor UI.
- `database/seeders` - Demo templates, section types, and sample memories.
- `docs/CURRENT_PROJECT_SPEC.md` - Current product and editor specification.

## Editor Model

Posts are memories. Each memory stores identity, category, template, visibility,
publishing state, dates, SEO fields, and cover media in `posts`.

Editor details that are not part of the main post identity live in
`post_details`, including display date range and background music fields.

Blocks live in `post_sections`. Repeating block content lives in
`post_section_items`. The editor currently supports these addable block types:

- `hero_image`
- `stats`
- `single_image`
- `gallery_grid`
- `gallery_slider`
- `video_embed`
- `quote`
- `music`
- `timeline`

`video_embed` is kept as the technical section type name for compatibility, but
new editor content should use uploaded video media. URL-based video embeds are
legacy fallback only.

Templates and section types may still use JSON for system configuration and UI
schema. Individual post content should use the typed columns above.

## Notes For Contributors

- Keep public pages mobile-first and image-led.
- Prefer existing Laravel, Filament, Blade, Tailwind, and Alpine patterns.
- Keep editor changes aligned with `post_sections` and `post_section_items`.
- Add or update feature tests for editor save behavior, media upload, public
  rendering, and visibility/auth changes.
- Do not commit generated runtime files from `public/storage`, `storage`, cache
  folders, or local build artifacts.
