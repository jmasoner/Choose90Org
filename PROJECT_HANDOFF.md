# Project Handoff: Choose90.org (Hybrid WordPress Site)

**Current Date:** December 17, 2025
**Project State:** Active Development - Phase 2 (Chapters & SMS)

## 1. Architecture Overview

This is a **Hybrid Site**. We do NOT use the WordPress Block Editor/Divi Builder for the main pages.

* **Source of Truth:** Local folder `c:\Users\john\OneDrive\MyProjects\Choose90Org\hybrid_site\`
* **Deployment:** We use PowerShell scripts to "compile" HTML/PHP files and deploy them to the server (Mounted as `Z:`).
* **Theme:** Child Theme `Divi-choose90`. We inject our custom PHP templates here.

### Key Deployment Scripts

* `setup_child_theme.ps1`: **PRIMARY SCRIPT.** Updates `functions.php`, `style.css`, and **deploys PHP Page Templates** (`page-chapters.php`, `page-resources.php`, etc.) to the remote theme folder (`Z:\wp-content\themes\Divi-choose90\`).
* `deploy_hybrid_site.ps1`: Deploys static assets (images, PDFs, HTML files) to the remote server root (`Z:\`).

## 2. Current Status of Features

### ✅ A. Chapters Section (Completed)

* **Directory Page:** `page-chapters.php` (Deployed). Shows "Start a Chapter" pitch + Grid of active chapters.
* **Host Starter Kit:** `page-host-starter-kit.php` (Deployed).
  * Contains the application form.
  * **Logic:** PHP handles POST request -> Sanitizes -> Creates **Draft 'Chapter' Post** in WP Database -> Emails Admin.
  * **Assets:** Meeting Agenda PDF link is here.
* **Single Chapter Template:** `single-chapter.php` (Deployed). Displays individual chapter details.

### ✅ B. Resources Section (Completed)

* **Template:** `page-resources.php` (Deployed).
* **Fixes:** Resolved a 403 Forbidden error by deleting the conflicting server folder `Z:\resources\` and ensuring the WordPress Page `resources` exists via `functions.php`.
* **Assets:** Files are stored in `Z:\assets\docs\` (e.g., `Choose90-Digital-Detox-Guide.pdf`).

### 🚧 C. Pledge / SMS System (NEXT PRIORITY)

* **Status:** **Pending Implementation.**
* **Requirement:** We need to update the Pledge page to collect **Mobile Numbers** and store them in a custom MySQL table for daily SMS dispatch.
* **Specs:** See `SMS_SYSTEM_REQUIREMENTS.md` in root for the exact database schema and PHP logic needed.
* **Files to Edit:** `hybrid_site/page-pledge.php`.

## 3. Important File Paths

| Local Path | Remote Path (Z:) | Purpose |
| :--- | :--- | :--- |
| `hybrid_site/page-chapters.php` | `wp-content/themes/Divi-choose90/page-chapters.php` | Main Chapters Directory |
| `hybrid_site/page-host-starter-kit.php` | `wp-content/themes/Divi-choose90/page-host-starter-kit.php` | Host App Form |
| `hybrid_site/page-resources.php` | `wp-content/themes/Divi-choose90/page-resources.php` | Resources Grid |
| `setup_child_theme.ps1` | N/A | **Run this to deploy edits.** |

## 4. How to Continue Work

1. **To Edit a Page:** Edit the file in `hybrid_site/`.
2. **To Deploy:** Run `.\setup_child_theme.ps1` in PowerShell.
3. **To Build SMS System:**
    * Read `SMS_SYSTEM_REQUIREMENTS.md`.
    * Modify `page-pledge.php` to add the Phone field.
    * Create a simple `install_db.php` script (or add to `functions.php`) to create the `wp_c90_pledges` table.
    * Create the Cron Job script.

## 5. Known Quirks

* **403 Errors:** If you create a page called `/foo/` in WordPress, make sure you don't have a real folder called `/foo/` on the server default root, or Apache will block it.
* **Caching:** HTML changes usually reflect instantly on `Z:`, but PHP template changes require a page refresh.
