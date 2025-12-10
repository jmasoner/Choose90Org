# Choose90.org - Hybrid WordPress Site

A modular web application combining a high-performance static HTML frontend with a robust WordPress backend for dynamic content, community features, and donations.

## 🏗️ Architecture

This project uses a **Hybrid Architecture**:

1. **Static Frontend (`/hybrid_site/`)**:
    * Contains `index.html`, `about.html`, and global asset files.
    * Serves as the "Source of Truth" for the visual design (Header, Footer, CSS).
    * Files are deployed to the root of the web server.

2. **WordPress Backend (`/wp-admin/`)**:
    * theme: `Divi` (Parent) + `Divi-choose90` (Child Theme).
    * The Child Theme is **automatically generated** to match the static site's design.
    * Custom Page Templates:
        * `page.php`: Full-width default.
        * `page-donate.php`: Donate page with Hero Card.
        * `page-chapters.php`: Chapters grid.
        * `page-resources.php`: Resources grid.

## 🚀 Deployment

We use a mapped Web Disk drive (`Z:`) to deploy files to the live server.

### Key Scripts

* `map_drive_interactive.ps1`:
  * **Usage**: Run this if `Z:` is disconnected.
  * Prompts for the Web Disk password and maps the drive.

* `deploy_hybrid_site.ps1`:
  * **Usage**: Run this to push changes from `hybrid_site/` (HTML/CSS) to the live server.
  * *Safe to run repeatedly.*

* `setup_child_theme.ps1`:
  * **Usage**: Run this to update the WordPress Child Theme (PHP templates, `style.css`).
  * **Crucial**: This script injects the static header/footer HTML into the WordPress theme to ensure they are identical.

## 🛠️ Development Workflow

1. **Edit Visuals**:
    * Modify `hybrid_site/style.css` for global styling.
    * Modify `hybrid_site/index.html` for Header/Footer structure.
2. **Deploy Static**:
    * Run `.\deploy_hybrid_site.ps1`.
3. **Update WordPress Theme**:
    * Run `.\setup_child_theme.ps1`.
    * *Note*: This reads the updated HTML/CSS and packages it into the theme.
4. **Verify**:
    * Check `https://choose90.org`.

## 📁 Project Structure

```
/Choose90Org
├── hybrid_site/         # Static Source Files
│   ├── index.html
│   ├── about.html
│   └── style.css        # GLOBAL CSS file
├── deploy_hybrid_site.ps1
├── setup_child_theme.ps1
├── map_drive_interactive.ps1
├── CONTEXT.md           # Project Context & Roadmap
└── README.md            # This file
```

## 🔐 Credentials & Secrets

* **Secrets**: Stored in `.env` (not committed).
* **Web Disk**: Credentials managed via `secrets.json` (if applicable) or interactive prompt.
