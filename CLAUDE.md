# CLAUDE.md

This file gives Claude Code and its agents the project context needed to work
on LockByMemories without rediscovering the same constraints each session.

## Project Context

LockByMemories is a Laravel 12 + Filament 4 application for `chuaminh.vn`.
The product is a visual memory album site: photos and short emotional text are
the primary public experience, while admins manage memories through Filament and
a custom visual editor.

Core stack:

- PHP 8.2+, Laravel 12, Filament 4
- Blade, Tailwind CSS 4, Alpine.js
- Vite, Swiper, PhotoSwipe, SortableJS
- SQLite locally by default; Docker Compose uses MySQL 8.0

## First Steps For Agents

1. Run `git status --short` before editing and preserve user changes.
2. Read nearby code before proposing abstractions.
3. Keep edits scoped to the requested behavior.
4. Prefer existing Laravel, Filament, Blade, Tailwind, and Alpine patterns.
5. Update tests when behavior changes.

## Commands

Local setup:

```bash
composer run setup
php artisan storage:link
composer run dev
```

Common checks:

```bash
composer test
php artisan test
vendor/bin/pint
npm run build
```

Docker workflow:

```bash
docker compose up -d --build
docker compose exec -T filament_app php artisan migrate --seed
docker compose exec -T filament_app php artisan test
docker compose exec -T filament_app npm run build
```

The Docker app service is `filament_app`.

## Architecture Map

- `routes/web.php` defines public routes, API interaction routes, and custom
  admin editor routes.
- `app/Filament/Resources` contains Filament resources for admin CRUD.
- `app/Http/Controllers/Admin/MemoryEditorController.php` owns the custom memory
  editor form load and save flow.
- `app/Http/Controllers/Admin/MediaUploadController.php` owns editor media
  uploads.
- `app/Http/Controllers/Frontend` contains public page controllers.
- `app/Models/Post.php`, `PostDetail.php`, `PostSection.php`,
  `PostSectionItem.php`, `Media.php`, `Template.php`, and `SectionType.php`
  are the central content models.
- `app/Services/Rendering` renders section blocks and resolves design tokens.
- `resources/views/admin/memories/editor.blade.php` is the custom editor UI.
- `resources/views/frontend/sections` contains public section partials.
- `database/seeders` contains demo templates, section types, media, and posts.
- `docs/CURRENT_PROJECT_SPEC.md` captures the current product/editor spec.

## Editor Invariants

- A memory is a `posts` row with optional `post_details`.
- Blocks are rows in `post_sections`.
- Repeating block children are rows in `post_section_items`.
- Do not reintroduce raw per-post content JSON for editor-managed content.
- JSON in `templates` and `section_types` is allowed because it is system
  configuration/schema, not per-post content.
- Addable section types currently are `hero_image`, `stats`, `single_image`,
  `gallery_grid`, `gallery_slider`, `video_embed`, `quote`, `music`, and
  `timeline`.
- `video_embed` is still the technical block name, but editor-created videos
  must use uploaded media with `media.type = video`.
- URL-based video embeds are legacy rendering fallback only.
- Gallery layouts currently include `mosaic`, `grid_2`, `grid_3`,
  `featured_left`, `masonry`, `film_strip`, and `polaroid`.

## Testing Expectations

Use focused feature tests for:

- Public page rendering and visible copy.
- Custom editor page loading and save behavior.
- Media upload auth and media type detection.
- Video block validation.
- Visibility, password, unlisted, and preview behavior when touched.

Tests use in-memory SQLite in `phpunit.xml`. Avoid relying on the local
`database/database.sqlite` file in tests.

## Agent Guidance

When Claude Code agents are available, split work by ownership:

- Laravel backend agent: controllers, models, requests, migrations, policies,
  actions, and feature tests.
- Filament admin agent: resources, forms, tables, admin pages, and admin UX.
- Frontend Blade agent: public pages, section partials, Tailwind styling, and
  Alpine interactions.
- Editor agent: `MemoryEditorController`, `editor.blade.php`, section typing,
  media selection, ordering, and preview behavior.
- QA agent: test coverage, regression checks, build/test commands, and risky
  edge cases.

Give agents explicit file boundaries and ask them to report changed files,
behavioral risks, and verification commands. Merge their output through the
existing architecture instead of adding parallel systems.

## Style Notes

- Keep UI mobile-first and image-led.
- Public copy can be emotional and concise; admin UX should stay practical.
- Prefer Eloquent relationships and Laravel validation over ad hoc parsing.
- Keep Blade partials small and aligned with existing section components.
- Use Tailwind utility patterns already present in the app.
- Keep comments rare and useful.
