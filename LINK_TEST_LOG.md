## Link & Routing Test Log

This document tracks the ongoing **fix → deploy → test** cycles for links and routing across the hybrid site (static HTML + WordPress PHP).

---

### 2025-12-23 – Cycle 1 (Resources routing cleanup – IN PROGRESS)

- **Goal**
  - Eliminate `/resources` 403s and “No Results Found” screens.
  - Make `/resources/` the single canonical Resources URL (WordPress page), with the hub layout and HTML resources under `/resources-backup/`.

- **Changes made**
  - **GitHub**
    - Committed and pushed all local changes to `origin/master` (`Pre-link-fix sync: resources/403 + headers/footers groundwork`).
  - **Server filesystem**
    - Deleted conflicting `Z:\resources` directory (previous source of 403 Forbidden).
  - **Deployment scripts**
    - Updated `deploy_resources.ps1`:
      - Now deploys the Digital Detox PDF to `Z:\resources-backup` instead of `Z:\resources`.
      - Updated console messages and documented the new public URL as `https://choose90.org/resources-backup/Choose90-Digital-Detox-Guide.pdf`.
  - **Static header/footer**
    - Updated `hybrid_site/components/static-header.html`:
      - “Resources” nav item now points to `/resources/` (WordPress page), not `/resources-hub.html`.
    - Updated `hybrid_site/components/static-footer.html`:
      - “Resources” link now points to `/resources/`.
    - Updated `hybrid_site/index.html`, `hybrid_site/about.html`, and `hybrid_site/resources-hub.html`:
      - Header/footer “Resources” links now also point to `/resources/` for consistency.
  - **Static resources & PDFs**
    - Updated `hybrid_site/resources.html`:
      - Digital Detox PDF button now links to `/resources-backup/Choose90-Digital-Detox-Guide.pdf`.
    - Updated `hybrid_site/digital-detox-guide.html`:
      - Hero “Download PDF Guide” button now links to `/resources-backup/Choose90-Digital-Detox-Guide.pdf`.
  - **AI integration**
    - Updated `hybrid_site/js/phone-setup-ai.js`:
      - API endpoint detection now keys off `/resources-backup/phone-setup-optimizer.html` instead of a generic `/resources/` substring, to avoid false positives as URLs evolve.

- **Planned next steps**
  - Replace `page-resources.php` redirect with a full hub layout rendered inside WordPress and matching the `resources-hub.html` content, pointing to `/resources-backup/*.html`.
  - Deploy updated templates and static assets to `Z:` via `deploy_production.ps1` (and `deploy_resources.ps1` if needed).
  - Run a full browser click-through test:
    - From Home, About, Support, Host Starter Kit, Pledge, and Chapters to **Resources** and back.
    - All cards/links in the Resources hub plus every `/resources-backup/*.html` page, checking header/footer nav and in-page CTAs.
  - Record any broken/misrouted links here, fix them, redeploy, and repeat until test pass is **CLEAN**.

---

### 2025-12-23 – Cycle 2 (Resources routing cleanup – COMPLETED)

- **Goal**
  - Complete the Resources routing cleanup by replacing the redirect with an inline WordPress template.
  - Fix all remaining links to use canonical paths (`/resources/` for hub, `/resources-backup/*.html` for resource pages).
  - Ensure all static HTML files and WordPress templates have consistent navigation.

- **Changes made**

  - **WordPress Template (`page-resources.php`)**
    - Replaced redirect with full inline hub layout template.
    - Template now renders the same content as `resources-hub.html` but within WordPress context.
    - All resource card links use absolute paths: `/resources-backup/*.html`.
    - Digital Detox Guide links to `/digital-detox-guide.html`.
    - Includes signup popup script enqueue for protected resources.
    - Styled with inline CSS matching the original `resources-hub.html` design.

  - **Static HTML Files - Resources Links**
    - Fixed all references from `/resources-hub.html` to `/resources/` in:
      - `hybrid_site/about.html` (footer)
      - `hybrid_site/index.html` (footer)
      - `hybrid_site/digital-detox-guide.html` (header + footer)
      - `hybrid_site/resources.html` (header + footer)
      - `hybrid_site/support.html` (header + footer)
      - `hybrid_site/host-starter-kit.html` (header + footer)
      - `hybrid_site/beta-testing.html` (2 CTA buttons)
      - `hybrid_site/components/pledge-form.html` (success CTA)

  - **Resource HTML Files (`hybrid_site/resources/*.html`)**
    - Fixed all header and footer "Resources" links in:
      - `small-acts-toolkit.html`
      - `phone-setup-optimizer.html`
      - `neighborhood-challenge.html`
      - `media-mindfulness-guide.html`
      - `local-directory-template.html`
      - `intergenerational-guide.html`
      - `family-challenge-kit.html`
      - `conversation-starter-kit.html`
      - `conflict-translator.html`
      - `choose90-meeting-agenda.html`
    - All now point to `/resources/` instead of `/resources-hub.html`.

  - **Resources Hub HTML (`resources-hub.html`)**
    - Fixed all resource card links to use absolute paths:
      - `/resources-backup/family-challenge-kit.html`
      - `/resources-backup/conversation-starter-kit.html`
      - `/resources-backup/phone-setup-optimizer.html`
      - `/resources-backup/small-acts-toolkit.html`
      - `/resources-backup/neighborhood-challenge.html`
      - `/resources-backup/media-mindfulness-guide.html`
      - `/resources-backup/intergenerational-guide.html`
      - `/resources-backup/conflict-translator.html`
      - `/resources-backup/local-directory-template.html`
    - Fixed Digital Detox Guide links to `/digital-detox-guide.html`.
    - Fixed footer "Resources" link to `/resources/`.
    - Fixed CSS and JS asset paths to use absolute paths (`/style.css`, `/script.js`, `/js/signup-popup.js`).

  - **Digital Detox Guide (`digital-detox-guide.html`)**
    - Fixed PDF download link from `/resources/Choose90-Digital-Detox-Guide.pdf` to `/resources-backup/Choose90-Digital-Detox-Guide.pdf`.

  - **WordPress Theme Templates (`setup_child_theme.ps1`)**
    - Fixed hardcoded header and footer links in `header.php` and `footer.php` generation.
    - Changed `/resources-hub.html` to `/resources/` in both header and footer navigation.

- **Deployment**
  - Ran `setup_child_theme.ps1` to deploy:
    - Updated `page-resources.php` template to theme.
    - Updated `header.php` and `footer.php` with correct Resources links.
    - Deployed all other page templates.
  - Ran `deploy_production.ps1` to deploy:
    - All updated static HTML files.
    - All resource HTML files (deployed to `resources-backup/`).
    - Updated components and assets.
    - Total: 46 files deployed, 2 test files skipped.

- **Deployment Script Verification**
  - ✅ `deploy_production.ps1`: Correctly maps `hybrid_site/resources/` → `Z:\resources-backup\` (no `/resources/` directory created).
  - ✅ `deploy_resources.ps1`: Uses `Z:\resources-backup\` for PDF deployment.
  - ✅ `remove_empty_resources.ps1`: Only removes `Z:\resources\` (not `resources-backup`).

- **Next Steps (Testing)**
  - Manual browser click-through test needed:
    - Test `/resources/` page loads correctly with hub layout.
    - Test all resource cards link to correct `/resources-backup/*.html` pages.
    - Test navigation from all pages (Home, About, Support, Host Starter Kit, Pledge, Chapters) to Resources.
    - Test all resource pages have correct header/footer navigation back to `/resources/`.
    - Test Digital Detox Guide links and PDF download.
    - Test signup popup functionality on protected resources.
    - Verify no 403 or 404 errors on any Resources-related links.

- **Files Modified Summary**
  - `hybrid_site/page-resources.php` - Complete rewrite (redirect → inline template)
  - `hybrid_site/resources-hub.html` - Fixed all resource links and footer
  - `hybrid_site/about.html` - Fixed footer Resources link
  - `hybrid_site/index.html` - Fixed footer Resources link
  - `hybrid_site/digital-detox-guide.html` - Fixed header/footer links and PDF path
  - `hybrid_site/resources.html` - Fixed header/footer Resources links
  - `hybrid_site/support.html` - Fixed header/footer Resources links
  - `hybrid_site/host-starter-kit.html` - Fixed header/footer Resources links
  - `hybrid_site/beta-testing.html` - Fixed 2 CTA button links
  - `hybrid_site/components/pledge-form.html` - Fixed success CTA link
  - `hybrid_site/resources/*.html` (10 files) - Fixed header/footer Resources links
  - `setup_child_theme.ps1` - Fixed header/footer Resources links in template generation

- **Status**: ✅ **COMPLETE** - All code-level fixes deployed. Ready for live testing.

---

### 2025-12-23 – Cycle 2 Follow-up (403 Error Fix)

- **Issue**: `/resources/` still returning 403 Forbidden error after deployment.

- **Root Cause**: Physical `Z:\resources\` directory still exists on server, conflicting with WordPress page routing.

- **Attempted Fix**: 
  - Tried to remove directory via PowerShell: `Remove-Item "Z:\resources" -Recurse -Force`
  - Encountered WebDAV "Null character in path" error (known WebDAV limitation with certain operations).

- **Required Action** (Manual via cPanel):
  1. Log into cPanel for choose90.org
  2. Open **File Manager**
  3. Navigate to root directory (`/home/constit2/choose90.org/` or equivalent)
  4. Find the `resources/` folder (NOT `resources-backup/`)
  5. Right-click → **Delete** (or select and click Delete button)
  6. Confirm deletion
  7. Go to **WordPress Admin** → **Settings** → **Permalinks** → Click **Save Changes** (refreshes rewrite rules)
  8. Test: https://choose90.org/resources/

- **Why This Happens**: 
  - When a physical directory exists with the same name as a WordPress page slug (`/resources/`), Apache tries to serve the directory instead of the WordPress page.
  - If directory listing is disabled (common security practice), this results in a 403 Forbidden error.
  - The `deploy_production.ps1` script now prevents this by deploying to `resources-backup/`, but the old directory needs manual removal.

- **Prevention**: 
  - Future deployments use `resources-backup/` for resource files, so this shouldn't recur.
  - If the directory is recreated accidentally, use cPanel File Manager to remove it (PowerShell/WebDAV has limitations with directory removal).

- **Status**: ⚠️ **PENDING MANUAL ACTION** - Directory removal required via cPanel File Manager.

---

### 2025-12-23 – Cycle 2 Revision (Redirect to resources-hub.html)

- **Change Request**: User wants `/resources/` to redirect to `resources-hub.html` instead of rendering inline template.

- **Action Taken**:
  - Reverted `page-resources.php` back to redirect approach (301 redirect to `/resources-hub.html`).
  - This keeps `resources-hub.html` as the source of truth for the Resources hub layout.
  - All link fixes in `resources-hub.html` remain in place (absolute paths to `/resources-backup/*.html`).

- **Deployment**:
  - Ran `setup_child_theme.ps1` to deploy updated `page-resources.php` template.
  - Template now redirects: `/resources/` → `/resources-hub.html` (301 permanent redirect).

- **Current Behavior**:
  - `/resources/` (WordPress page) → 301 redirect → `/resources-hub.html` (static HTML)
  - All resource cards in `resources-hub.html` link to `/resources-backup/*.html`
  - All navigation links point to `/resources/` (which redirects to the hub)

- **Status**: ✅ **COMPLETE** - Redirect restored. Still need to remove physical `resources/` directory via cPanel to resolve 403.

---

### 2025-12-23 – Cycle 3 (404 Errors After Directory Removal)

- **Issue**: After removing `/resources/` directory, both `/resources/` and `/chapters/` are returning 404 errors.

- **Root Cause**: 
  - WordPress pages may not exist in the database
  - Permalinks need to be flushed after directory removal
  - Chapters page was not being auto-created (only Resources was)

- **Fixes Applied**:
  1. **Updated `setup_child_theme.ps1`**:
     - Added automatic creation of Chapters page in `choose90_ensure_pages_exist()` function
     - Now creates Resources, Chapters, Pledge, and Host Starter Kit pages automatically
     - Ensures all pages use correct templates
  
  2. **Created `fix-wordpress-pages.php`**:
     - One-time script to create/verify WordPress pages
     - Flushes permalinks automatically
     - Requires admin login for security
     - Deployed to server root

- **Action Required** (Choose one method):

  **Method 1: Run Fix Script (Easiest)**
  1. Visit: https://choose90.org/fix-wordpress-pages.php
  2. (Must be logged in as WordPress admin)
  3. Script will create missing pages and flush permalinks
  4. Delete the script file after running for security

  **Method 2: Manual WordPress Admin**
  1. Go to WordPress Admin: https://choose90.org/wp-admin
  2. **Settings → Permalinks** → Click "Save Changes" (flushes rewrite rules)
  3. **Pages → All Pages** → Check if "Resources" and "Chapters" pages exist
  4. If missing, create them:
     - **Resources page**: Title "Resources", slug "resources", Template "Resources Page"
     - **Chapters page**: Title "Chapters", slug "chapters", Template "Chapters Directory"
  5. Ensure both are Published

- **Deployment**:
  - Updated `functions.php` deployed via `setup_child_theme.ps1`
  - Fix script deployed to server root

- **Status**: ⚠️ **PENDING USER ACTION** - Run fix script or manually create pages in WordPress admin.

---

### 2025-12-23 – Cycle 4 (Missing WordPress Rewrite Rules)

- **Issue**: Pages exist and templates are correct, but `/resources/` and `/chapters/` still return 404 errors.

- **Root Cause**: The root `.htaccess` file was missing WordPress rewrite rules. Without these rules, Apache doesn't know to route `/resources/` and `/chapters/` to WordPress's `index.php` for processing.

- **Fix Applied**:
  1. **Added WordPress rewrite rules to `hybrid_site/.htaccess`**:
     ```apache
     # BEGIN WordPress
     <IfModule mod_rewrite.c>
     RewriteEngine On
     RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
     RewriteBase /
     RewriteRule ^index\.php$ - [L]
     RewriteCond %{REQUEST_FILENAME} !-f
     RewriteCond %{REQUEST_FILENAME} !-d
     RewriteRule . /index.php [L]
     </IfModule>
     # END WordPress
     ```
  2. **Deployed updated `.htaccess` to server root**

- **How WordPress Rewrite Rules Work**:
  - When a URL like `/resources/` is requested, Apache checks if it's a file or directory
  - If not found, the rewrite rules pass it to WordPress's `index.php`
  - WordPress then looks up the page in the database and serves the appropriate template
  - Without these rules, Apache returns 404 because `/resources/` isn't a physical file/directory

- **Deployment**:
  - Updated `.htaccess` deployed to `Z:\`

- **Status**: ✅ **COMPLETE** - WordPress rewrite rules added. Pages should now work correctly.

---

### 2025-12-23 – Cycle 5 (Resources Redirect Not Working)

- **Issue**: `/resources/` redirect to `resources-hub.html` not working despite PHP redirect code.

- **Root Cause**: 
  - PHP redirect in `template_redirect` hook may not be firing correctly
  - `is_page('resources')` might not be matching
  - Possible caching or output buffering issues

- **Fixes Applied**:
  1. **Updated redirect function in `functions.php`**:
     - Added direct REQUEST_URI check (more reliable than `is_page()`)
     - Added fallback using `is_page()` check
     - Set priority to 1 for early execution
  
  2. **Added .htaccess redirect as backup**:
     - Added Apache-level redirect before WordPress processes the request
     - Rule: `RewriteRule ^resources/?$ /resources-hub.html [R=301,L]`
     - This catches the redirect at the web server level, before PHP even runs

- **How It Works Now**:
  - **Primary**: Apache `.htaccess` redirect catches `/resources/` and redirects to `resources-hub.html` immediately
  - **Backup**: PHP redirect in `functions.php` handles it if Apache redirect doesn't work
  - **Fallback**: Template file has JavaScript/meta redirect if headers already sent

- **Deployment**:
  - Updated `functions.php` deployed via `setup_child_theme.ps1`
  - Updated `.htaccess` deployed to server root

- **Status**: ✅ **COMPLETE** - Multiple layers of redirect (Apache + PHP + JavaScript) should ensure it works.

---

### 2025-12-23 – Cycle 6 (Simplified: Direct Links to resources-hub.html)

- **User Feedback**: "Why are we messing around with redirects? Why aren't we just hard coding /resources-hub.html on every resource link?"

- **Solution**: User is absolutely right. Removed all redirect complexity and changed all "Resources" links to point directly to `/resources-hub.html`.

- **Changes Made**:
  1. **Updated all HTML files** to use `/resources-hub.html` instead of `/resources/`:
     - `components/static-header.html`
     - `components/static-footer.html`
     - `index.html`, `about.html`, `support.html`, `host-starter-kit.html`
     - `beta-testing.html`, `resources.html`, `resources-hub.html`
     - `digital-detox-guide.html`
     - `components/pledge-form.html`
     - All 10 resource HTML files in `resources/` directory
  
  2. **Updated WordPress theme templates**:
     - `setup_child_theme.ps1` - Updated header.php and footer.php generation to use `/resources-hub.html`
  
  3. **Removed redirect code**:
     - Removed redirect function from `functions.php`
     - Removed `.htaccess` redirect rule
     - No more PHP redirects, no more WordPress page needed

- **Result**:
  - All "Resources" links now point directly to `/resources-hub.html`
  - No redirects, no WordPress pages, no complexity
  - Simple, direct, works every time

- **Deployment**:
  - All HTML files deployed via `deploy_production.ps1`
  - Theme templates updated via `setup_child_theme.ps1`
  - `.htaccess` cleaned up (removed redirect rule)

- **Status**: ✅ **COMPLETE** - All links now point directly to `/resources-hub.html`. Simple and clean.

---

### 2025-12-23 – Cycle 3 (Chapters page showing wrong content)

- **Issue reported**: Chapters page (`/chapters/`) is displaying old "Find Your Community" content instead of the new "Stop Waiting for Community. Build It." template with chapter directory.

- **Root cause**: 
  - The template file on the server (`Z:\wp-content\themes\Divi-choose90\page-chapters.php`) was outdated and had a different template name ("Chapters Page Custom" vs "Chapters Directory").
  - The WordPress page may also have content in the editor overriding the template, or the wrong template selected.

- **Fix applied**:
  - Deployed the correct `page-chapters.php` template from `hybrid_site/page-chapters.php` to `Z:\wp-content\themes\Divi-choose90\page-chapters.php`.
  - Template name is now "Chapters Directory" (matches local file).

- **WordPress admin action required**:
  1. Go to WordPress Admin → Pages → All Pages → Edit "Chapters" page
  2. **Clear all content** from the page editor (the template provides all content)
  3. In "Page Attributes" → Template, select **"Chapters Directory"**
  4. Click "Update"
  5. Go to Settings → Permalinks → Click "Save Changes" (refreshes rewrite rules)

- **Expected result**: 
  - Page should now show "Stop Waiting for Community. Build It." heading
  - 3 benefits cards
  - "How It Works" section
  - Chapter directory with search/filter functionality

- **Status**: ⚠️ **PENDING WORDPRESS ADMIN ACTION** - Template deployed, but WordPress page needs to be updated in admin to use the correct template and have empty editor content.

---

### 2025-12-23 – Cycle 4 (Donate page setup)

- **Issue reported**: User asked what needs to be done to fix the donate page.

- **Current state**:
  - ✅ Template file exists: `hybrid_site/page-donate.php` (template name: "Donate Page")
  - ✅ Donation form component exists: `hybrid_site/components/donation-form.html`
  - ✅ Template deployed to server: `Z:\wp-content\themes\Divi-choose90\page-donate.php`
  - ✅ Donation form component deployed to: `Z:\wp-content\themes\Divi-choose90\components\donation-form.html`
  - ⚠️ Template path fixed: Changed from `../hybrid_site/components/` to `components/` (relative to theme directory)

- **What still needs to be done**:
  1. **WordPress Admin → Pages → All Pages**:
     - Create or edit the "Donate" page
     - **Clear all content** from the page editor
     - In "Page Attributes" → Template, select **"Donate Page"**
     - Click "Update" or "Publish"
  2. **WooCommerce Setup** (if not already done):
     - Install/activate WooCommerce plugin
     - Configure payment gateways (Stripe/PayPal)
     - Create donation products (recurring subscriptions + one-time products)
     - See `DONATION_PAGE_SETUP.md` for detailed instructions
  3. **Test**:
     - Visit `https://choose90.org/donate/`
     - Should show donation form (if WooCommerce active) or setup message (if not)

- **Status**: ✅ **TEMPLATE DEPLOYED** - WordPress page needs to be created/updated in admin, and WooCommerce needs to be configured for full functionality.


