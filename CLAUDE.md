# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

# Proyecto HolaSalta WordPress

## Project overview

**holasalta-child** is a WordPress child theme for **HolaSalta.com**, a Spanish-language local news site based in Salta, Argentina.

The parent theme is **Blocksy**:

```txt
wp-content/themes/blocksy
```

The child theme is:

```txt
wp-content/themes/holasalta-child
```

All custom development must live inside `holasalta-child/`.

Local site runs via XAMPP at:

```txt
http://localhost/wp
```

PHP errors may appear in:

```txt
C:\xampp\apache\logs\error.log
```

or in:

```txt
wp-content/debug.log
```

if `WP_DEBUG_LOG` is enabled.

---

## Mandatory rules

These rules are strict.

- Never modify the `blocksy/` parent theme folder.
- Use `blocksy/` only as reference.
- All edits must be made inside `holasalta-child/`.
- Do not use Elementor.
- Do not use page builders.
- Do not add CSS frameworks.
- Do not add heavy external libraries.
- Do not touch `wp-config.php`.
- Do not edit the database directly.
- Do not break WordPress core behavior.
- Do not break Rank Math.
- Do not break LiteSpeed Cache.
- Do not break SEO or cache plugins.
- Keep normal WordPress posts.
- Keep normal WordPress categories.
- Maintain compatibility with the WordPress template hierarchy.
- Do not upload anything to hosting unless explicitly instructed.
- Do not make destructive changes without explaining the risk first.

---

## Expected workflow

Before editing code:

1. Inspect the existing files.
2. Diagnose UI/UX, layout, structure, or technical problems.
3. Propose a clear implementation plan.
4. Wait for approval before modifying files.

After editing code:

1. List all modified files.
2. Explain exactly how to test the changes locally.
3. Mention possible risks or edge cases.
4. Do not upload anything to hosting.

---

## No build step

There is no Webpack, Gulp, Vite, PostCSS, Tailwind, Sass, or build pipeline.

Edit CSS and JS directly:

```txt
assets/css/holasalta.css
assets/js/holasalta.js
```

Both files are enqueued with `filemtime()` cache-busting, so a hard-refresh is enough after saving:

```txt
Ctrl + Shift + R
```

Do not add a build step unless explicitly requested.

---

## Visual objective

Improve the UI/UX of HolaSalta so it feels like a professional, elegant, editorial news website.

Conceptual inspiration: sober, premium newspapers such as **La Nación**.

Important:

- Do not copy La Nación’s exact design.
- Do not copy its brand identity.
- Do not copy its typography exactly.
- Do not copy its layout exactly.
- Use it only as editorial inspiration.

The final design should feel:

- Clean.
- Fast.
- Serious.
- Modern.
- Local-news oriented.
- Easy to read.
- Optimized for mobile.
- Optimized for SEO and discoverability.

---

## Brand palette

Use this palette consistently:

```css
--hs-primary: #ec1566;
--hs-secondary: #545353;
--hs-bg: #ffffff;
--hs-bg-soft: #f6f6f6;
--hs-text: #1f1f1f;
--hs-border: #e5e5e5;
```

Primary color:

```txt
#EC1566
```

Secondary color:

```txt
#545353
```

Use the primary color for accents, active states, labels, buttons, links, section markers, and selected UI elements.

Use the secondary color for supporting text, subtle labels, footer areas, and neutral interface elements.

---

## Header requirements

The header should be editorial and professional.

Desired structure:

- Social media links/icons on the left.
- Logo or icon centered.
- Search on the right.
- Sandwich/hamburger menu for categories.
- Institutional pages must not be placed in the header.
- Institutional pages must go in the footer.

Header behavior should be:

- Clean on desktop.
- Usable on mobile.
- Accessible by keyboard.
- Not overloaded with links.
- Optimized for a news site.

---

## Main categories

The main editorial categories are:

- Salta
- Policiales
- Política
- Sociedad
- Deportes
- Nacionales
- Economía
- Cultura

These categories are part of the editorial navigation and can appear in the hamburger menu, section grids, archive pages, and category navigation.

---

## Institutional pages

These pages should go in the footer, not in the header:

- Quiénes somos
- Contacto
- Publicidad
- Política editorial
- Política de privacidad
- Términos y condiciones

They are institutional/footer navigation pages, not primary editorial categories.

---

## Advertising system

The site must support ad/banner placements for:

- `.gif`
- `.webp`
- `.jpg`
- `.png`
- HTML snippets

Advertising areas should be prepared for these placements:

- Home arriba
- Home medio
- Home abajo
- Category arriba
- Single sidebar arriba
- Single sidebar medio
- Single dentro del contenido

Do not hardcode final ad images unless explicitly requested.

Prefer reusable widget areas and template parts.

---

## Architecture

### File map

```txt
holasalta-child/
├── functions.php              # Bootstrap: requires inc/, registers image sizes, menus, widget areas, inline-ad filter
├── inc/
│   ├── template-tags.php      # Reusable helper functions (hs_*)
│   └── setup-site.php         # One-time scaffold: pages, categories, menus on theme activation
├── template-parts/
│   ├── card-post.php          # Single post card
│   ├── section-post-grid.php  # Grid of cards for a section
│   ├── ad-slot.php            # Advertising banner renderer
│   ├── related-posts.php      # Related articles row
│   └── sidebar-news.php       # Sidebar widget area
├── assets/
│   ├── css/holasalta.css      # Custom styles
│   └── js/holasalta.js        # Custom scripts
└── *.php                      # Top-level templates: single.php, category.php, etc.
```

---

## Helper functions

Reusable helper functions live in:

```txt
inc/template-tags.php
```

Reuse these functions instead of duplicating logic elsewhere.

| Function                                           | Purpose                                                   |
| -------------------------------------------------- | --------------------------------------------------------- |
| `hs_get_editorial_sections()`                      | Canonical ordered list of news categories: slugs → labels |
| `hs_get_section_url( $slug )`                      | Category URL with fallback before DB is populated         |
| `hs_get_social_links()`                            | Social platform labels, short names, and URLs             |
| `hs_get_posts_by_category( $slug, $count, $args )` | Thin `WP_Query` wrapper with sensible news defaults       |
| `hs_render_post_card( $args )`                     | Loads `template-parts/card-post.php`                      |
| `hs_render_ad_slot( $slot, $size )`                | Loads `template-parts/ad-slot.php` for a given placement  |
| `hs_get_ad_sidebar_id( $slot )`                    | Maps slot name to widget area ID                          |
| `hs_get_primary_category( $post_id )`              | Best category for a post, skipping "Destacadas"           |
| `hs_get_related_posts( $post_id, $count )`         | Related posts by shared categories                        |
| `hs_render_pagination()`                           | Accessible numbered archive pagination                    |
| `hs_footer_menu_fallback()`                        | Hardcoded footer links before a menu is assigned          |

---

## Ad slot system

Ad placements use a three-layer system:

1. Widget areas are registered in `functions.php` through `hs_register_widget_areas()`.
2. `hs_render_ad_slot( $slot, $size )` maps the slot name to a sidebar ID through `hs_get_ad_sidebar_id()`.
3. `template-parts/ad-slot.php` renders the final ad container.
4. Inline ads use `hs_insert_inline_ad()` through the `the_content` filter and inject `hs-ad-single-inline` after the third `</p>` of every single post.

To add a new ad placement:

1. Add the widget area in `hs_register_widget_areas()`.
2. Add the slot mapping in `hs_get_ad_sidebar_id()`.
3. Call `hs_render_ad_slot()` in the correct template location.
4. Test from **WP Admin → Appearance → Widgets**.

---

## One-time site scaffold

The file:

```txt
inc/setup-site.php
```

contains `hs_run_initial_setup()`.

It runs on:

```php
after_switch_theme
```

It creates initial pages, categories, and menus if they do not exist.

It is gated by the option:

```txt
hs_initial_setup_done
```

so it does not run twice.

To re-run it in local development on a fresh database, delete the option manually using a safe one-off snippet, then remove the snippet immediately:

```php
delete_option( 'hs_initial_setup_done' );
```

Do not place this permanently in production code.

---

## Adding a new editorial section

To add a new editorial section:

1. Add the slug and label to `hs_get_editorial_sections()` in `inc/template-tags.php`.
2. Add the corresponding `hs_setup_category()` call in `inc/setup-site.php`.
3. Add the corresponding `hs_setup_menu_item()` call in `inc/setup-site.php`.
4. Delete `hs_initial_setup_done` only in local development if the setup must run again.
5. Reactivate the child theme or trigger the setup manually.

---

## Image sizes

| Handle    |           Dimensions | Usage                                    |
| --------- | -------------------: | ---------------------------------------- |
| `hs-card` |  720 × 405 hard crop | Post cards in grids and lists            |
| `hs-hero` | 1280 × 720 hard crop | Hero images on single posts and homepage |

Use these image sizes with:

```php
the_post_thumbnail()
```

or:

```php
get_the_post_thumbnail()
```

Do not load unnecessarily large images for cards.

---

## UI/UX principles

When improving the theme, prioritize:

- Readability.
- Clear hierarchy.
- Mobile-first layout.
- Fast load time.
- Clean spacing.
- Strong editorial structure.
- Clear category labels.
- Accessible navigation.
- Consistent card design.
- Good contrast.
- SEO-friendly markup.
- Compatibility with WordPress templates.

Avoid:

- Visual clutter.
- Overly decorative effects.
- Heavy animations.
- Large JavaScript dependencies.
- Hardcoded content that should come from WordPress.
- Breaking archive, single, search, or category templates.

---

## SEO and Agentic View principles

The site should be easy for search engines and AI systems to understand.

Prioritize:

- Semantic HTML.
- Proper heading hierarchy.
- Clean article structure.
- Clear category and date metadata.
- Descriptive links.
- Accessible navigation.
- Fast-loading templates.
- No hidden important content behind heavy JavaScript.
- Normal WordPress posts and categories.
- Compatibility with Rank Math.

Do not create a design that looks good visually but damages crawlability, structured content, or article readability.

---

## Verification

After any template or logic change:

1. Load the affected page locally:

```txt
http://localhost/wp
```

2. Test at least:

```txt
Home
Single post
Category page
Search page
Mobile width
```

3. For ad slots:

Go to:

```txt
WP Admin → Appearance → Widgets
```

Confirm the widget area exists, assign a test text widget, and verify that it renders.

4. For PHP errors:

Check:

```txt
C:\xampp\apache\logs\error.log
```

or:

```txt
wp-content/debug.log
```

if debug logging is enabled.

5. For CSS and JS:

Hard-refresh:

```txt
Ctrl + Shift + R
```

Because `filemtime()` cache-busting is active, no manual version bump is needed.

---

## Final response format after making changes

After completing approved code edits, respond with:

1. Summary of what changed.
2. List of modified files.
3. How to test locally.
4. Risks or things to review.
5. Suggested next step.

Do not say that something was tested in the browser unless it was actually tested.
Do not claim that PHP has no errors unless the logs were actually checked.
