# AI Frontend Skill Guideline

## Project Context

This project is a Laravel Blade + Tailwind CSS memories/blog website.

The goal is to create a visual storytelling website for personal memories, travel blogs, relationship memories, photos, music, maps, quotes, and timeline-based stories.

The frontend should feel emotional, image-heavy, cinematic, romantic, mobile-first, and polished.

This is not a dashboard-first project. Public-facing pages are more important than admin pages.

---

## Core Design Direction

Use the spirit of taste-skill / anti-slop frontend design.

Design dials:

- DESIGN_VARIANCE: 8
- MOTION_INTENSITY: 5
- VISUAL_DENSITY: 3

Meaning:

- Layout should be visually interesting, not too symmetrical.
- Motion should be smooth and emotional, but not excessive.
- Content should feel spacious, image-first, and not text-heavy.

---

## Tech Stack

Use the existing Laravel stack:

- Laravel Blade
- Tailwind CSS
- Alpine.js for lightweight interactions
- AOS or GSAP only when useful
- Vite for assets
- Existing Laravel routes, controllers, models, migrations should not be changed unless required

Do not convert the project to React, Next.js, Vue, or another framework.

---

## Frontend Priorities

Prioritize:

1. Mobile-first responsive design
2. Beautiful image composition
3. Gallery, slider, hero image, timeline, quote, map, music sections
4. Strong typography
5. Good spacing and visual rhythm
6. Emotional storytelling
7. Clean Blade components
8. Reusable section templates
9. Accessibility
10. Performance

---

## Avoid Generic AI UI

Do not use:

- Generic 3-card equal columns everywhere
- Purple/blue AI gradients
- Centered hero with boring subtitle and two buttons
- Too many glassmorphism cards
- Lorem ipsum
- Fake placeholder names
- Random decorative dots
- Overused badges like "New", "Beta", "Premium"
- Dashboard-like layout for public memory pages
- Too much text
- Same layout repeated for every section

---

## Layout Rules

Use varied layouts:

- Full-width hero image
- Masonry gallery
- Horizontal scroll gallery on mobile
- Timeline sections
- Paper/card style memories
- Split image/text section
- Large quote block
- Map section
- Music section at the bottom
- Featured memory card
- Year/month archive layout

For mobile:

- Images should be large and clear
- Text should be short
- Buttons should be easy to tap
- Avoid cramped layouts
- Avoid tiny captions
- Keep strong vertical rhythm

---

## Typography Rules

Use typography intentionally.

Recommended fonts:

- Playfair Display for romantic/editorial headings
- Cormorant Garamond for elegant emotional titles
- Dancing Script for small romantic accents only
- Inter for body text and admin UI

Rules:

- Headings should feel emotional and premium
- Body text should be readable
- Do not use too many font families in one page
- Avoid all-caps everywhere
- Use `text-wrap: balance` or Tailwind equivalent where helpful
- Keep paragraph width readable

---

## Color and Surface Rules

The design should feel warm, romantic, and cinematic.

Prefer:

- Soft cream
- Warm white
- Rose
- Dusty pink
- Deep burgundy
- Soft brown
- Muted gold
- Paper texture
- Subtle shadows
- Soft borders

Avoid:

- Pure black background unless intentionally cinematic
- Harsh neon colors
- Random accent colors
- Too many gradients
- Cold corporate blue
- Default Tailwind gray-only UI

---

## Animation Rules

Use animation only when it improves the story.

Good uses:

- Image fade-in
- Gentle scroll reveal
- Music button animation
- Gallery transition
- Timeline reveal
- Soft hover state
- Parallax only if smooth

Avoid:

- Heavy animation on every element
- Animation that hurts mobile performance
- Scroll hijacking
- Flashy effects
- Moving text that distracts from photos

Always respect reduced motion when possible.

---

## Blade / Tailwind Code Rules

When editing code:

- Keep Blade readable
- Use semantic HTML: `section`, `article`, `figure`, `figcaption`, `nav`, `main`
- Extract repeated UI into Blade partials/components
- Do not create div soup
- Do not hardcode unnecessary inline styles
- Prefer Tailwind utility classes
- Keep class names organized
- Avoid arbitrary `z-[9999]`
- Add meaningful `alt` text for images
- Keep responsive classes clean

---

## Redesign Workflow

Before changing UI, follow this workflow:

1. Scan the current files
2. Identify current framework and styling approach
3. Audit weak UI points
4. Explain what should be improved
5. Modify only necessary files
6. Keep backend logic safe
7. Preserve existing data structure unless change is required
8. Test responsive behavior mentally
9. Remove placeholder code
10. Output complete code, not partial snippets

---

## Public Page Priority

Public pages should receive the most visual attention:

- Home page
- Archive page
- Post detail page
- Memory detail page
- Gallery page
- Timeline page

Admin pages can be clean and usable, but public pages should feel special.

---

## Section Requirements

The project should support beautiful sections such as:

- Hero image
- Blog text
- Gallery grid
- Gallery slider
- Timeline
- Quote
- Map
- Music
- Video
- Memory card
- Featured image
- Paper note
- Polaroid style image
- Travel location block
- Year/month archive

Each section should have its own visual identity but still belong to the same design system.

---

## Music Section

The music section should usually be placed near the bottom of the public post/detail page.

It can include:

- Song title
- Artist
- Play/pause button
- Small animated equalizer
- Optional background music
- Clear user control

Do not autoplay aggressively if it creates poor UX. If autoplay is used, ensure the user can easily pause/mute.

---

## Final Check Before Finishing

Before final output, check:

- Is the page mobile-friendly?
- Are images the main focus?
- Is the UI emotional and memorable?
- Did we avoid generic AI layout?
- Is the Blade code readable?
- Did we preserve backend logic?
- Are sections reusable?
- Are placeholders removed?
- Are hover/focus states present?
- Are images accessible with proper alt text?