## Second Look Review – Choose90.org

## 1. High-Priority Security Findings

- **[High] `hybrid_site/api/phone-setup-ai.php:172–210` – Fallback disables SSL verification for AI API calls**  
  - **Why it matters**: The cURL fallback path explicitly sets `CURLOPT_SSL_VERIFYPEER => false` and `CURLOPT_SSL_VERIFYHOST => false`. If this code ever runs in production (e.g., due to a certificate issue), requests to the AI provider are vulnerable to MITM attacks and certificate spoofing.  
  - **Recommended fix (high-level)**: Restrict the insecure fallback to clearly-marked non-production environments (e.g., only when a `CHOOSE90_ENV=local` flag is set) or remove it entirely. In production, fail hard on SSL errors and surface a clear 5xx with logging instead of silently retrying with SSL disabled.

- **[High] `hybrid_site/test-api-keys.php:199–201, 291–292` – Test script forces SSL verification off**  
  - **Why it matters**: Even though this is a test tool, it normalizes using `CURLOPT_SSL_VERIFYPEER => false` for external APIs, encouraging copying insecure patterns into production. If this script is ever exposed publicly, an attacker on-path could tamper with responses.  
  - **Recommended fix (high-level)**: Mirror the stronger pattern in `hybrid_site/test-api-keys-production.php`: prefer system CA bundles, and only allow disabling SSL via an explicit, non-default flag for strictly local environments. Add a loud warning banner in the HTML output when SSL verification is off.

- **[High] `remove_empty_resources.ps1:56–60` and related destructive PowerShell commands**  
  - **Why it matters**: `Remove-Item -Recurse -Force` on `Z:\resources` is powerful and easy to misfire. A typo in `$ResourcesDir` or an unexpected mapping of `Z:` could wipe the wrong directory on the WebDAV drive. Similar risk exists in `deploy_crm_plugin.ps1` and any future scripts that recursively delete/overwrite trees.  
  - **Recommended fix (high-level)**: Add explicit safety checks: verify the resolved path starts with `Z:\resources` (or the intended root) exactly, require a “type the folder name to confirm” prompt, and support a dry-run (`-WhatIf`) mode. Log deletions and renames to a timestamped log file on disk for recovery/audit.

- **[High] `setup_child_theme.ps1:84–87` – `flush_rewrite_rules()` on every request via `init`**  
  - **Why it matters**: `choose90_register_chapters()` (injected into the theme via this script) calls `flush_rewrite_rules()` inside an `init` hook. This is a known WordPress anti-pattern: it recalculates rewrite rules on every page load, which can hurt performance and under load can lead to subtle routing issues.  
  - **Recommended fix (high-level)**: Move rewrite flushing into a one-time activation path (e.g., `after_switch_theme` or a plugin activation hook). Remove `flush_rewrite_rules()` from `init` and add a small “Permalinks need refresh” admin notice if rules are ever out of sync.

- **[High] `hybrid_site/page-host-starter-kit.php:7–70` – Host application form lacks CSRF protection**  
  - **Why it matters**: The host application form posts directly to the same template and only uses a math CAPTCHA. Without a WordPress nonce, any third-party site could CSRF a logged-in admin into creating bogus draft chapters.  
  - **Recommended fix (high-level)**: Add `wp_nonce_field()` to the form and verify it before processing. Ideally move the processing logic into a dedicated `admin_post_*` handler (similar to the pledge form) and keep the template purely presentational.

## 2. Medium / Low-Priority Issues

- **[Medium] `hybrid_site/about.html:23–31, 107–113` – Hard-coded `/resources/` links**  
  - Header and footer both link to `/resources/` instead of `/resources-hub.html`, which conflicts with the new resources hub and the WordPress `/resources/` page/redirect. This can reintroduce the legacy 403 confusion.  
  - Suggest updating these to `/resources-hub.html` or, better, replacing the bespoke header/footer in `about.html` with the shared static header/footer components.

- **[Medium] `hybrid_site/resources.html:452–487` and `hybrid_site/digital-detox-guide.html:662, 976` – PDF links under `/resources/`**  
  - Links like `/resources/Choose90-Digital-Detox-Guide.pdf` still assume a physical `/resources/` directory, while deployment tools now move HTML resources to `/resources-backup/` and route the page itself through WordPress. This mixes concerns and can collide with the `/resources/` WP route.  
  - Move PDFs to a clearly non-conflicting path (`/assets/` or `/resources-backup/`), update all links, and ensure the `/resources/` URL is controlled exclusively by WordPress.

- **[Medium] `hybrid_site/js/phone-setup-ai.js:10–11` – Endpoint selection keying off `/resources/`**  
  - The script infers whether to call `../api/phone-setup-ai.php` or `/api/phone-setup-ai.php` based on `window.location.pathname.includes('/resources/')`. With the new hub at `/resources-hub.html` and HTML resources under `/resources-backup/...`, this heuristic is brittle and may select the wrong path if URLs change.  
  - Prefer deriving the API base from the script tag’s `src` or from a `data-api-endpoint` attribute injected into the page, or at least check for the specific resource path (`/resources-backup/phone-setup-optimizer.html`) instead of a generic `/resources/` substring.

- **[Medium] `hybrid_site/components/chapter-contact-form.php:19–69` – No rate limiting or spam defenses**  
  - The chapter contact/“Join” forms validate and sanitize input and use nonces, but there is no honeypot, timeout, or IP throttling. A bot can still spam `chapters@choose90.org` with valid-looking requests.  
  - Add a hidden honeypot field, basic per-IP throttling using transients, and/or reCAPTCHA for higher-risk forms (join/contact) to protect the inbox.

- **[Medium] `wp-content/plugins/choose90-crm/includes/class-crm-email-handler.php:51–77` – File-based rate limiting only on phone AI, none on CRM contact creation**  
  - Pledge submissions auto-create CRM contacts (`hybrid_site/wp-functions-pledge.php:214–266`), but that path lacks any explicit rate limiting or anomaly detection on user creation/contact creation. A scripted attack against the pledge endpoint could create a large number of low-quality users/contacts.  
  - Consider adding per-IP and per-email throttling around user creation (e.g., limit N new accounts per IP per hour) and logging anomalies.

- **[Low] Drive-letter assumptions across scripts** (`deploy_production.ps1`, `setup_child_theme.ps1`, `fix_resources_403.ps1`, `remove_empty_resources.ps1`, `deploy_crm_plugin.ps1`)  
  - All scripts assume `Z:\` is the mapped WebDAV root. This is fine today but brittle if the mapping or environment changes.  
  - Centralize the WebDAV root into a single configurable variable (or `secrets.json` entry), and add a sanity check that the target contains expected files (`wp-config.php`, `wp-content/`) before any destructive operations.

- **[Low] `hybrid_site/wp-functions-personalization.php:16–21` – Minor fullname formatting**  
  - `fullName` is built as `$user->first_name . ' ' . $user->last_name` without trimming, which can produce extra spaces for users missing first/last names.  
  - Use `trim($user->first_name . ' ' . $user->last_name)` to avoid cosmetic issues.

- **[Low] `hybrid_site/components/pledge-form.php:193–219` – Error reporting via `alert()`**  
  - Failed pledge submissions surface errors through `alert()`, which is jarring UX and easy to block.  
  - Prefer in-form error banners (like those used in the chapter contact form) so messaging stays within the UI.

## 3. Architecture & Maintainability

- **Shared header/footer templating is good, but legacy scripts linger**  
  - `hybrid_site/components/static-header.html` and `static-footer.html` define a clean canonical nav/footer, and `update_static_header_footer.ps1` applies them across `hybrid_site`. However, `add_headers_footers_to_resources.ps1` still injects hard-coded nav/footer (including `/resources/` links and `../index.html`) into HTML under `hybrid_site/resources/`.  
  - Recommend fully migrating to the static component + updater script and deleting or archiving `add_headers_footers_to_resources.ps1`. Ensure *every* HTML file under `hybrid_site/` is on the component pipeline so header/footer changes propagate consistently.

- **Static vs WordPress duplication for resources**  
  - The resources experience exists as:  
    - Static `hybrid_site/resources-hub.html` (primary hub, links to `resources-backup/...`);  
    - Static legacy `hybrid_site/resources.html` (grid-style page, still present);  
    - WordPress `page-resources.php` (grid template in root) and redirect template in `hybrid_site/page-resources.php`.  
  - This is workable but easy to confuse. Long-term, keep only the static hub plus a WP “shell” page that permanently redirects `/resources/` → `/resources-hub.html`, and mark the older grid template in the root as deprecated to avoid future edits.

- **Theme-coupled logic vs site-specific plugins**  
  - The pledge system, personalization, chapters meta, and dashboard stats are all bundled in `wp-functions-*.php` files and included from the child theme’s `functions.php` (see `setup_child_theme.ps1:39–88, 147–175`). This is clear and modular, but theme-dependent.  
  - As the site matures, consider promoting major subsystems (pledge/registration, chapters, CRM bridge) into small, dedicated plugins. This decouples behavior from theme styling and will make future theme refreshes safer and simpler.

- **CRM and pledge coupling**  
  - `hybrid_site/wp-functions-pledge.php:214–266` creates or updates CRM contacts if the `Choose90_CRM_Post_Types` class exists. This conditional coupling is good, but errors in the CRM path are silent.  
  - Add basic logging (e.g., `error_log` with a specific tag) or a future admin dashboard widget summarizing “pledges without CRM contact” so operational issues are detectable.

- **JS organization is generally solid**  
  - `hybrid_site/script.js` handles only core UI behaviors (mobile menu, smooth scroll, counters). Feature-specific scripts live in their own files (`js/phone-setup-ai.js`, `js/signup-popup.js`, `js/social-sharing.js`, `js/badge-system.js`), which is a good separation of concerns.  
  - Over time, consider a small module loader or bundler (even ES modules) so features can declare dependencies explicitly and avoid relying on global objects.

## 4. Correctness / Behavior Checks

- **Pledge form flow**  
  - **Logged-out**: `page-pledge.php:36–48` includes `components/pledge-form.php` (or its HTML fallback), which submits via AJAX to `admin-post.php` with action `choose90_pledge_submit`. Server-side handler `choose90_handle_pledge_submission()` (`wp-functions-pledge.php:114–211`) verifies a nonce, validates fields, enforces password length, creates a user, sets meta, logs the user in, and returns JSON via `wp_send_json_success/error`. This appears correct and robust.  
  - **Logged-in**: `page-pledge.php:19–35` bypasses the form and shows a “Welcome back / thank you for your pledge” panel with CTAs to `resources-hub.html` and `/chapters/`, matching the intended behavior.

- **Chapters creation and display**  
  - `page-host-starter-kit.php:7–70` creates a draft `chapter` post with basic host info on POST; meta fields and taxonomy wiring are handled by `wp-functions-chapters.php:206–252`. On the front-end, `page-chapters.php:168–183` queries only `_chapter_status = 'active'` chapters for the directory, and `single-chapter.php:47–98` renders details with sensible fallbacks.  
  - The JS filters (`page-chapters.php:292–343`) update visibility and counts client-side only, which is fine for the current scale; if chapters grow large, you may want server-side filtering or at least pagination-aware filters.

- **Resources routing and 403 correctness**  
  - `deploy_production.ps1:58–61` rewrites `resources\*` paths to `resources-backup\*` at deploy time, and `hybrid_site/resources-hub.html` links into `resources-backup/*.html`. `hybrid_site/page-resources.php` simply `wp_redirect`s to `/resources-hub.html`. This architecture correctly avoids the `/resources` directory conflict that caused 403s and centralizes the hub at a single URL.  
  - Remaining `/resources/...` PDF links are the main correctness risk; once aligned, the routing story is clean.

- **Phone Setup AI behavior**  
  - Front-end (`js/phone-setup-ai.js`) injects AI buttons into `.step-box` elements, lazily collects device info via a modal, and calls `api/phone-setup-ai.php`. It explicitly detects `file://` access and explains that a web server is required, which is a nice DX touch for local viewing.  
  - Back-end (`api/phone-setup-ai.php`) enforces POST, validates/sanitizes, rate-limits by IP using a temp file keyed on IP+window, and supports DeepSeek via `secrets.json`. Overall behavior is correct; the main concern is the SSL fallback already noted.

## 5. Style / Consistency

- **Header/footer consistency**  
  - Most static pages (index, resources-hub, support, resources guides) now show the shared header/footer with consistent `/chapters/`, `/resources-hub.html`, `/pledge/`, `/donate/`, `/support.html` links. `about.html` stands out as still using its own header/footer with older paths and relative `index.html` links.  
  - Bringing `about.html` onto the shared header/footer would eliminate this inconsistency and simplify future navigation changes.

- **Inline styles in templates**  
  - PHP templates (especially `page-donate.php`, `page-chapters.php`, `page-host-starter-kit.php`, and `single-chapter.php`) contain extensive inline style attributes. While they render well, they make global restyling harder and mix structure with presentation.  
  - Over time, migrate recurring style blocks into named CSS classes in `style.css` or the child theme CSS and replace inline styles with class names.

- **Error messaging UX**  
  - Forms such as the chapter contact and host starter kit show inline success/error banners with clear copy, which is good. The pledge form, by contrast, still uses JavaScript `alert()` for error messages (`components/pledge-form.php:214–217`), leading to a rougher experience.  
  - Align on the banner pattern everywhere for consistency and better accessibility.

## 6. Praise / Things Done Well

- **Strong use of WordPress security primitives**  
  - Critical flows (pledge, chapters meta, chapter contact forms) all use `wp_nonce_field` + `wp_verify_nonce`, check capabilities (`current_user_can`), and sanitize inputs via `sanitize_text_field`, `sanitize_email`, and `sanitize_textarea_field`. This is exactly how WordPress custom code should be written.  
  - The pledge handler also correctly uses `wp_create_user`, `wp_update_user`, and sets roles explicitly, avoiding direct SQL.

- **Secrets and API handling are thoughtfully designed**  
  - API keys and donation credentials live in `secrets.json`, with `secrets.json.example` checked in and `secrets.json` itself ignored via `.gitignore` (`.gitignore:12`). The `SECURITY_FIX.md` file documents the previous hard-coded password issue and remediation, which shows good security hygiene and institutional memory.

- **Hybrid architecture is well-supported by tooling**  
  - Scripts like `deploy_production.ps1`, `setup_child_theme.ps1`, `fix_resources_403.ps1`, `check_resources_403.ps1`, and `deploy_crm_plugin.ps1` encode the deployment story clearly, including guardrails (e.g., checking `Z:\` mapping, excluding test files from production). The documentation (`FIX_RESOURCES_403*.md`, `DONATION_PAGE_SETUP.md`, `PHONE_SETUP_AI_DOCUMENTATION.md`) is detailed and actionable.

- **User-centric flows and messaging**  
  - The pledge page behavior (different for logged-in vs logged-out users), the rich copy in the digital detox guide, and the host starter kit all show a strong emphasis on user understanding and encouragement. Forms provide helpful helper text, and emails (e.g., `wp-functions-pledge.php:276–320`) are well-crafted and on-brand.  
  - The chapters directory and single-chapter pages balance privacy (no public host emails by default) with usability (chapter contact form + mailto fallback), which is a good security/usability compromise.

Overall, the codebase is in good shape: security fundamentals are mostly solid, the hybrid architecture is clear, and key flows (pledge, chapters, resources, AI integration) are thoughtfully implemented. The main follow-up work for the next assistant is to harden SSL usage, add a bit more CSRF/rate limiting around the host application & contact flows, and finish standardizing navigation/asset URLs around the new resources hub.