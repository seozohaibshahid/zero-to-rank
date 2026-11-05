# Zero to Rank — WordPress landing template

A custom, conversion-focused WordPress page template for **Zero to Rank** (SEO · AEO · GEO).
PHP, HTML, CSS and JavaScript only — no page builder, no jQuery, no external requests.

> **Be the answer. Not just another search result.**

---

## What's in the box

| # | Section | Interaction |
|---|---------|-------------|
| 01 | Navigation | Floating glass pill: logo left, section links in the middle (sliding indicator on hover and on the section in view), "Free audit" button right. Hides on scroll down, returns on scroll up. Full-screen dark menu on mobile (Esc to close) |
| 02 | Hero | The blue gradient panel with the three-word headline (Found. Answered. Cited.), captions, a glass card of two real results and a bold audit CTA. Words rise out of a mask on load (CSS, first paint), the light drifts and follows the pointer, the card figures count up, and the words spread apart as the hero scrolls away. Edit `$c['hero']['words']` |
| 03 | Ticker | A blue band of the platforms we optimise for. It drifts on its own and speeds up or reverses with scroll speed and direction. **No client logos** |
| — | Results strip | Four real client figures (`$c['hero']['proof']`) that count up and link to their case study, with the working principles underneath |
| — | Statement | A point-of-view line that fills in word by word as it scrolls (plain text without JS or with reduced motion). Edit `$c['statement']` |
| 04 | Services | Bento grid: a dark feature card ("Visibility for the AI era" with SEO / AEO / GEO tiles), then one card per service with its deliverables. Cards tilt and catch a spotlight under the pointer; each CTA pre-selects the audit focus. Edit `$c['services']` (incl. `feature`) |
| 05 | Case studies | Dark band. Three real engagements as tabs: headline metrics, AI-assistant sources from GA4 (bars grow in), and the original Search Console / GA4 screenshots as evidence. Edit `$c['results']['cases']`; images live in `assets/images/cases/` |
| 06 | The visibility shift | Traditional search (light card) vs AI-driven discovery (dark card) |
| 07 | Process | Dark band with five step cards and a progress line that fills as you scroll |
| 08 | Why Zero to Rank | The argument and platform chips on the left, the differentiators as a ruled list on the right |
| 09 | Who we help | Industry tabs (arrow-key navigation): the challenge on a dark panel, the approach beside it |
| — | Editor content | Anything typed in the page editor renders here (only if not empty) |
| 10 | FAQs | Intro on the left, ruled questions on the right (native `<details>`, smooth height); also output as FAQPage schema |
| 11 | Free visibility audit | A dark CTA box that grows into place on scroll, holding the copy and the form. Inline validation, async submit, no-JS fallback, spam protection, leads stored in WP admin |
| 12 | Footer | Dark: brand + audit button, services / explore / contact columns, and a giant "Zero to Rank" wordmark whose letters rise as the page ends |

**Type** (all self-hosted, Latin only): Bricolage Grotesque for display, Manrope for body text, Instrument Serif italic for emphasis inside headings (`<em>`), JetBrains Mono for small labels.

**Palette:** Blue `#0593C6` (brand) and White `#FCFFFF`, on a cool off-white page `#F1F6F8`. Dark sections are a deep night blue `#020B12` lit by navy `#04263A` and indigo `#141A52` glows, with ice blue `#6FD6F7` as the signal colour (buttons, emphasis, figures). Blue-violet `#4A62E0` is used only inside gradients (the ticker band). Text and links on light backgrounds use `#0477A1` (`--ztr-blue-ink`) for contrast. The hero keeps its own blue sky gradient. Legacy token names (`--ztr-teal`, `--ztr-pearl`, `--ztr-lime`) still resolve.

Motion follows one rule: **it must explain, guide or confirm**. Everything respects
`prefers-reduced-motion` (state changes stay, movement goes) and works without JavaScript.

---

## File structure

```
zero-to-rank/
├── style.css                     Theme header (child-theme or standalone)
├── functions.php                 Bootstrap: constants + includes
├── index.php                     Fallback for standalone mode only
│
├── page-templates/
│   └── template-zero-to-rank.php "Zero to Rank — Landing" (selectable in the page editor)
│
├── template-parts/sections/      One file per section (modular, reorderable)
│   ├── header.php  hero.php  trust.php  stats.php  statement.php  services.php  results.php
│   ├── shift.php  process.php  why.php  industries.php  editor.php  faq.php  audit.php
│   └── footer.php
│
├── inc/
│   ├── helpers.php      Template detection, SVG icons, tracking attributes
│   ├── content.php      ALL copy, filterable via `ztr_content`
│   ├── customizer.php   Contact details, audit recipient, social profiles
│   ├── setup.php        Enqueueing (template-only), dequeue theme CSS, font preload
│   ├── seo.php          Title, meta description, canonical, Open Graph + editor meta box
│   ├── schema.php       JSON-LD: Organization, WebSite, WebPage, Service ×3, FAQPage
│   └── form.php         Audit form: REST + admin-post handlers, validation, spam, storage, email
│
└── assets/
    ├── css/  main.css · animations.css · responsive.css
    ├── js/   main.js · interactions.js · animations.js · vendor/ (GSAP 3.13 + ScrollTrigger, Lenis 1.1.13)
    ├── images/  logo-mark.svg · logo-mark.png
    └── fonts/   Bricolage Grotesque · Manrope · JetBrains Mono (variable) · Instrument Serif Italic — all OFL, self-hosted
```

---

## Installation

The template ships as a theme folder so it can sit **alongside your existing theme without replacing it**.

### Option A — Child theme of your current theme (recommended)

1. Find your current theme's folder name (Appearance → Themes → Theme details, or `wp-content/themes/<folder>`).
2. Open `style.css` and add a header line inside the comment block:
   ```
   Template: your-parent-theme-folder
   ```
3. Upload the `zero-to-rank` folder to `wp-content/themes/` and activate **Zero to Rank**.
   Every other page keeps using your parent theme's templates and styling.

### Option B — Standalone theme

Upload and activate as-is. Non-landing pages use the minimal `index.php` fallback, so this option suits a site where the landing page is the main page.

### Then, for either option

1. **Pages → Add New** (or edit your home page) → **Template: Zero to Rank — Landing** → Publish.
2. *(Optional)* **Settings → Reading → Your homepage displays → A static page** → choose that page.
3. **Appearance → Customize → Zero to Rank**: public email, phone, location, area served,
   the address audit requests go to (defaults to the admin email) and social profiles.
4. *(Optional)* **Appearance → Menus**: assign menus to *Zero to Rank — landing header* / *footer*.
   Without menus the in-page anchor navigation is used.
5. *(Optional)* Set a **Site Icon** (Customize → Site Identity) — used for the favicon and Open Graph image.

---

## Compatibility & isolation

- **Assets load only on the landing template.** No CSS/JS is added anywhere else.
- **Full-canvas template:** renders its own header/footer, but still calls `wp_head()`, `wp_body_open()` and `wp_footer()`, so analytics, consent, caching and SEO plugins keep working.
- **Scoped styles:** every selector lives under `.ztr` / `ztr-*`; every PHP function is prefixed `ztr_`; every asset handle `zero-to-rank-*`.
- On the landing page only, the active theme's own stylesheets, block-library CSS and emoji script are dequeued (plugins untouched). Turn this off with:
  ```php
  add_filter( 'ztr_dequeue_theme_styles', '__return_false' );
  add_filter( 'ztr_disable_emoji', '__return_false' );
  ```
- **SEO plugins** (Yoast, Rank Math, AIOSEO, SEOPress, The SEO Framework) are detected; the template then stops printing its own title, meta description, social tags and Organization schema and lets the plugin own them. Service + FAQPage schema are still added.

---

## Editing content

All copy is in `inc/content.php`. Change it there, or — upgrade-safe — from a small plugin / `functions.php`:

```php
add_filter( 'ztr_content', function ( $c ) {
    $c['hero']['primary_cta'] = 'Book your free audit';
    $c['faq']['items'][] = array( 'q' => 'New question?', 'a' => 'Answer.' );
    return $c;
} );
```

**Add real case studies** (only with client permission) and they appear above the reporting dashboard:

```php
add_filter( 'ztr_content', function ( $c ) {
    $c['results']['case_studies'][] = array(
        'client'  => 'Client name',
        'sector'  => 'Professional services',
        'summary' => 'What we did, in one or two sentences.',
        'metrics' => array( array( 'value' => '+84%', 'label' => 'non-branded clicks, 6 months' ) ),
        'url'     => '/case-studies/client-name/',
    );
    return $c;
} );
```

**First activation:** on a site that still shows its latest posts, activating the theme creates a "Home" page with the landing template and sets it as the front page. An existing front page is never replaced; turn this off with `add_filter( 'ztr_auto_setup', '__return_false' );`.

**Reorder or remove sections:** `add_filter( 'ztr_sections', fn( $s ) => array_diff( $s, array( 'trust' ) ) );`

**SEO title & description:** without an SEO plugin, use the *Zero to Rank — SEO* box in the page editor sidebar (falls back to the page excerpt, then to sensible defaults).

### ⚠️ Confirm before launch

The brief rules out fabricated claims. The copy avoids them, but these statements describe how the agency works and must be true for you:

- The four figures in the results strip and hero card (`$c['hero']['proof']`) must match the case-study evidence.
- The *Statement* line (`$c['statement']`) — opinion, not a claim, but it should sound like you.
- Trust principles, FAQ answers on timelines, pricing and access, and the audit promise ("reviewed by a person").
- Case studies: every figure must match its evidence screenshot; client names stay withheld unless you have written permission.

---

## Audit form

- **JS path:** `POST /wp-json/ztr/v1/audit` → inline success state, no reload.
  **No-JS path:** `admin-post.php?action=ztr_audit` → redirect back to `#audit` with a status message.
- **Validation:** client-side (accessible inline errors, `aria-invalid`, focus moves to the first error) and server-side (authoritative).
- **Spam protection:** honeypot field, HMAC-signed render timestamp with a minimum fill time, 5 requests/hour per anonymised client, link-stuffing filter. Bots get a fake success.
  Signed timestamps are used instead of nonces because page caches (LiteSpeed on Hostinger) serve stale nonces.
  Add Cloudflare Turnstile or reCAPTCHA through the `ztr_audit_spam_check` filter.
- **Storage:** every valid request is saved as a private **Audit requests** item in wp-admin (so leads are never lost), then emailed to the Customizer recipient with `Reply-To` set to the lead. Raw IPs are never stored.
- **Email deliverability:** install an SMTP plugin (e.g. WP Mail SMTP with your domain mailbox). Each lead records whether the email was sent.
- **CRM hook:** `do_action( 'ztr_audit_submitted', $data, $lead_id )`.

---

## Analytics & conversion events

Events are pushed to `window.dataLayer` (GTM) and sent with `gtag()` when GA4 is installed. Nothing is loaded by the theme — use your existing GA4/GTM setup.

| Event | When | Parameters |
|-------|------|-----------|
| `cta_click` | Any CTA | `cta_location`, `cta_label`, `link_url` |
| `contact_click` | Email / phone links | `cta_location`, `cta_label` |
| `section_view` | Section ≥35% visible (once) | `section_name` |
| `tab_select` | Case studies, shift, industries | `component`, `tab_name` |
| `service_expand` | Service row opened | `item_name` |
| `evidence_open` | Case-study screenshot opened | `cta_location`, `cta_label` |
| `faq_open` | FAQ opened | `faq_question` |
| `form_start` | First keystroke in the form | `form_name` |
| `form_error` | Validation / server / network error | `form_name`, `error_type` |
| `generate_lead` | Successful audit request | `form_name`, `audit_focus` |

Mark `generate_lead` as a **key event** in GA4.

---

## Performance & accessibility

- No images on the critical path — all visuals are HTML/CSS/SVG. The LCP element is the hero headline, animated with CSS only (no JS wait).
- Fonts self-hosted, `font-display: swap`, display and body fonts preloaded. Only Latin subsets ship.
- All scripts load with `defer`. GSAP (~45 KB gzipped incl. ScrollTrigger) drives the scroll-linked and pointer effects; disable it with `add_filter( 'ztr_use_gsap', '__return_false' )` and reveals, count-ups, the ticker and the statement fill fall back to Intersection Observer / CSS. Lenis smooth scrolling (mouse wheel and trackpad; touch keeps native scrolling) can be turned off with `add_filter( 'ztr_use_lenis', '__return_false' )`.
- Pointer effects (magnetic buttons, card tilt, custom cursor) only run on fine pointers.
- Animations use `transform`/`opacity`; marquee and hero autoplay pause off-screen. No layout shift (CLS 0).
- Semantic HTML5 landmarks, one `h1`, ordered `h2`/`h3` hierarchy, skip link, visible focus rings, WAI-ARIA tabs/radios with arrow-key support, `aria-live` status messages.

Local test results (WordPress 7.1, PHP 8.3, Lighthouse 12, mobile): **Accessibility 100 · SEO 100 · Best practices 100**, CLS 0, TBT ~100 ms. Performance was measured on PHP's single-threaded dev server without gzip — expect better FCP/LCP on Hostinger with LiteSpeed Cache and compression on.

---

## Deploying to Hostinger

1. Back up the site (hPanel → Backups) before changing themes.
2. Upload this folder to `public_html/wp-content/themes/zero-to-rank/` (File Manager, SFTP, or Git deploy from this repository).
3. Follow *Installation* above (child-theme line first if keeping your current theme).
4. LiteSpeed Cache: purge all after activation. The form needs no cache exclusions.
5. Verify: page loads, form submits and appears under **Audit requests**, email arrives, GA4 DebugView shows `generate_lead`, Rich Results Test sees the FAQ/Service schema.

---

## Requirements

WordPress 6.3+ (uses the script `strategy` argument) · PHP 7.4+.

## Credits & licences

- Theme code: GPL-2.0-or-later.
- [Bricolage Grotesque](https://fonts.google.com/specimen/Bricolage+Grotesque), [Manrope](https://fonts.google.com/specimen/Manrope), [JetBrains Mono](https://fonts.google.com/specimen/JetBrains+Mono) and [Instrument Serif](https://fonts.google.com/specimen/Instrument+Serif): SIL Open Font License 1.1.
- [GSAP 3.13](https://gsap.com) with ScrollTrigger: free under the GSAP Standard License.
- [Lenis 1.1.13](https://github.com/darkroomengineering/lenis): MIT.
