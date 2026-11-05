# Estatein Theme: Development Notes

**Scope:** All six pages of the Estatein Figma template (Home, About Us, Properties, Property Details, Services, Contact), built as one custom WordPress theme at desktop, laptop and mobile, with real content management behind every section.

## 1. Process

1. **Design analysis.** I extracted the design tokens from Figma:
   - **Grey scale:** 08 to 60.
   - **Purple:** 60 and 75.
   - **Type:** Urbanist 500, 600 and 700.
   - **Spacing:** 162 / 80 / 16 px gutters at the 1920 / 1440 / 390 frames, with 150 / 120 / 80 px section rhythm.
   - **Assets:** icons were exported as SVG (recoloured to `currentColor` where useful), and photos and decorative patterns as WebP.
2. **Content model first.** Before writing markup I decided what a client would need to edit, and where:
   - **Properties, Testimonials, FAQs, Team Members, Clients, Services and Offices** are repeatable content, so they are custom post types. Property *type* is a taxonomy and drives the Properties filters.
   - **Property facts and pricing** (price, beds, baths, area, build year, fees, taxes, HOA, down payment) are ACF fields; the Property Details page computes totals such as the down payment and mortgage amount.
   - **Inner-page copy** (values, achievements, process steps, service-group intros) lives in `inc/page-content.php` behind `estatein_content_{key}` filters; each page's hero title and text can be overridden with ACF.
   - **Section copy** (hero, stats, feature cards, headings, CTA) lives in ACF fields on the front page, grouped in tabs.
   - **Site-wide elements** (banner, socials, copyright) live in the Customizer because they are not tied to one page.
3. **Templates as components.** `front-page.php` only assembles six section parts. Repeated UI (section header, property card, slider controls) are reusable template parts. The property card is shared by the Home slider and the Properties archive.
4. **Styling.** I wrote one hand-written stylesheet, mobile-first, with CSS custom properties per breakpoint (768 / 1024 / 1200 / 1800). This lets each design frame map to a token set instead of overriding many individual rules.
5. **Verification.**
   - Screenshot comparison against each Figma frame.
   - Interaction tests in a headless browser (sliders, FAQ toggle, banner, menu, newsletter).
   - axe-core accessibility audit and Lighthouse runs.
   - `WP_DEBUG` on, to catch PHP notices.

## 2. Key decisions

- **Free ACF, fields in code.** `acf_add_local_field_group()` keeps fields in Git and avoids database drift between environments. I avoided Pro-only fields (repeaters, options pages) so the theme works on any host. Fixed groups cover the three stats and four feature cards.
- **Design defaults as fallbacks.** `estatein_home()` returns the Figma copy when a field is empty, and `estatein_field()` falls back to post meta when ACF is inactive. The site never breaks if the plugin is missing.
- **No slider library.** The sliders use native CSS scroll-snap with about 60 lines of JS for arrows, the counter and keyboard support. This means no extra requests, it works with touch out of the box, and it degrades to a swipeable row without JS.
- **Security.**
  - All output is escaped (`esc_html`, `esc_url`, `esc_attr`, and `wp_kses` with an SVG allowlist for inline icons).
  - Forms use nonces and capability checks.
  - The newsletter adds a honeypot and `sanitize_email` / `is_email`.
- **Performance.**
  - Self-hosted, preloaded WOFF2 fonts.
  - The LCP hero image is preloaded with a matching `imagesrcset`.
  - Images are WebP with lazy loading below the fold.
  - JS is deferred, and block-library and emoji assets are removed.
- **SEO and accessibility.**
  - Semantic landmarks with a correct heading order (one H1).
  - Meta and Open Graph tags, plus JSON-LD for `RealEstateAgent`, `RealEstateListing` and `FAQPage`. These are disabled automatically when Yoast SEO or Rank Math is present.
  - A skip link, focus styles and ARIA states, and `prefers-reduced-motion` support.

## 3. Enhancements beyond the design

- The hero badge text is set on an SVG `textPath` and rotates slowly. It speeds up on hover, and the motion stops when reduced motion is requested.
- FAQ "Read More" expands the full answer in place instead of navigating away.
- Properties filters submit on change and show the number of matching results; the property gallery thumbnails sync with the slider and dots.
- Office tabs (All / Regional / International) filter cards client-side; "Say Hello" on a team card opens the contact form pre-filled with that person's name.
- One shared enquiry handler powers three forms; submissions are saved in the admin (Enquiries) and emailed.
- The slider counter reflects the real number of scroll positions for the current screen size.
- The announcement banner is dismissible and remembers the choice.
- One-click demo import (dashboard button or `wp estatein import-demo`), so a reviewer or client gets the exact design immediately.

## 4. Plugins and tools

- **Advanced Custom Fields (free):** content fields. This is the only plugin required.
- **No page builder, slider or SEO plugin.**
- **Development and QA tools:**
  - Figma for specs and asset export.
  - WordPress 7.1.4 locally with WP-CLI.
  - Playwright for screenshots and interaction tests.
  - axe-core and Lighthouse for audits.
- **AI tools:** I used AI assistance (Claude) to speed up scaffolding and repetitive markup. I reviewed, tested and refined all the code myself.

## 5. Results

| Metric | Result |
| --- | --- |
| Lighthouse mobile: Performance / Accessibility / Best Practices / SEO | 96 / 100 / 100 / 100 |
| Lighthouse desktop: Performance | 100 |
| axe-core violations | 0 |

## 6. Next steps (outside the 4-hour scope)

- Replace the demo photos (exported from the Figma canvas at screen resolution) with full-resolution originals.
- Add map embeds for offices and saved searches for logged-in users.
- Connect the newsletter to an email service (Mailchimp or Brevo) through the `estatein_subscriber` post type, or a hook.
- Add a block pattern or `theme.json` so editors get the same tokens in the block editor.
