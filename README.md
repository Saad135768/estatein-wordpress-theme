# Estatein — Custom WordPress Theme

A custom WordPress theme built from the **Estatein (Dark) real-estate Figma template**. All six pages of the design — Home, About Us, Properties, Property Details, Services and Contact — are built pixel-close at desktop (1920), laptop (1440) and mobile (390), with content managed from the WordPress admin.

**Live demo:** https://estatein-saad.wasmer.app

![Theme screenshot](screenshot.png)

## Features

- **Custom theme, no page builder:** hand-written PHP templates, one CSS file, one small vanilla JS file (no jQuery).
- **Custom post types:** Properties (with a *Property Type* taxonomy), Testimonials, FAQs, Team Members, Clients, Services and Offices.
- **Page templates:** `page-about-us.php`, `page-services.php`, `page-contact.php` (selectable as templates or applied automatically by slug), `archive-property.php` and `single-property.php`.
- **Property search:** keyword search plus Location, Property Type, Price, Size and Build Year filters on the Properties page (`pre_get_posts`, no plugin).
- **Property Details:** gallery from attached images with thumbnails and slider, key facts, features list, inquiry form pre-filled with the property, and a pricing breakdown with computed totals (fees, down payment, mortgage amount).
- **Enquiry forms:** three forms (property request, property inquiry, contact) sharing one handler — nonce, honeypot, validation, stored as private *Enquiries* and emailed to the site admin.
- **ACF-powered content:** every Home section (hero, stats, feature cards, section headings, CTA) plus property and client details. Field groups are registered in PHP (`inc/acf-fields.php`), so they are version-controlled and work with **free ACF**.
- **Graceful fallbacks:** if ACF is inactive or a field is empty, the theme falls back to the design copy, so the site never renders blank.
- **Customizer settings:** announcement banner, Contact URL, copyright and social links.
- **Menus:** Primary, Footer (top-level items become column headings) and Legal.
- **Interactive pieces:**
  - Scroll-snap sliders with arrows, keyboard support and a live "01 of 04" counter.
  - Expandable FAQ answers.
  - Dismissible banner.
  - Accessible mobile menu.
- **Newsletter sign-up:** nonce plus honeypot protected. Subscribers are stored as a private post type.
- **One-click demo import:** a dashboard notice button (or `wp estatein import-demo`) builds the Home page, 6 properties, 6 testimonials, 6 FAQs, the menu and media.
- **SEO:**
  - Meta description and Open Graph / Twitter tags.
  - JSON-LD for `RealEstateAgent`, `RealEstateListing` and `FAQPage`.
  - Automatically disabled when Yoast SEO or Rank Math is active.
- **Performance:**
  - Self-hosted Urbanist WOFF2 fonts, preloaded.
  - Responsive hero image preloaded with `fetchpriority="high"`.
  - WebP images with lazy loading, deferred JS, and block-library CSS dropped.
- **Accessibility:**
  - Skip link, landmarks and visible focus styles.
  - ARIA on the sliders, menu and FAQs.
  - `prefers-reduced-motion` support.

## Quality checks (local, WordPress 7.1.4 / PHP 8.3)

| Check | Result |
| --- | --- |
| Lighthouse mobile (Home, About, Property Details, Contact): Performance / Accessibility / Best Practices / SEO | 95–99 / 100 / 100 / 100 |
| Lighthouse desktop: Performance | 100 |
| axe-core (WCAG 2 A/AA + best practices), all 6 pages at 1440px and 390px | 0 violations |
| Horizontal overflow at 1920 / 1440 / 390 | none |
| PHP notices with `WP_DEBUG` on | none |

## Installation

1. Upload the `estatein` folder to `wp-content/themes/`, or upload `estatein.zip` via **Appearance → Themes → Add New → Upload**.
2. Install and activate **Advanced Custom Fields** (the free version is enough).
3. Activate **Estatein**.
4. On the Dashboard, click **Import demo content**. The Home page is set as the front page automatically.
5. Go to **Settings → Permalinks** and click **Save** once so property URLs work.

## Development

Edit `assets/css/main.css` and `assets/js/main.js`, then run `npm install && npm run build` to regenerate the minified files the theme loads (`main.min.css`, `main.min.js`). Set `SCRIPT_DEBUG` to `true` in `wp-config.php` to load the unminified sources.

## Structure

```
estatein/
├── style.css, functions.php, screenshot.png
├── header.php, footer.php, front-page.php, index.php
├── page-about-us.php, page-services.php, page-contact.php
├── archive-property.php, single-property.php
├── inc/
│   ├── setup.php               theme supports, menus, image sizes, cleanup
│   ├── enqueue.php             assets, font + hero preloading
│   ├── post-types.php          property / testimonial / faq + taxonomy
│   ├── acf-fields.php          ACF field groups in code
│   ├── template-functions.php  helpers, design defaults, Customizer
│   ├── page-content.php        inner-page copy (filterable)
│   ├── property-search.php     archive filters
│   ├── enquiries.php           form fields + handler
│   ├── newsletter.php          sign-up handler
│   ├── seo.php                 meta + JSON-LD
│   └── demo-content.php        one-click / WP-CLI importer
├── template-parts/
│   ├── home/                   hero, features, properties, testimonials, faqs, cta
│   ├── sections/               team, clients, pricing
│   └── components/             section-header, page-hero, card-property, slider-controls, enquiry-form
└── assets/ css, js, fonts, icons (SVG), images (WebP)
```

## Requirements

WordPress 6.5+ (tested up to 7.1), PHP 8.0+, Advanced Custom Fields (recommended).

## License

Proprietary, all rights reserved. Shared only to evaluate a job application; see [LICENSE](LICENSE).

## Credits

Design: Estatein Real Estate Website UI Template — Dark Theme (Produce UI, Figma Community). Font: Urbanist (SIL Open Font License).
