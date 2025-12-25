# Project Status Resume: Choose90.org

**Date:** December 9, 2025
**Current State:** Live Hybrid Deployment (HTML Homepage + WordPress Backend)

## 🛑 Stop / Start Context

**We are pivoting to a "Hybrid" architecture.**

- **Frontend**: High-performance HTML/CSS for `index.html` (Home) and `about.html` (Story).
- **Backend**: WordPress for Forms (`/pledge/`), Donations (`/donate/`), and content updates.

## ✅ Accomplished This Session

1. **Hybrid Site Built**: Created `hybrid_site` folder with premium HTML/CSS assets.
2. **Deployment Scripts**:
    - `map_drive_interactive.ps1`: Fixes Z: drive connection.
    - `deploy_hybrid_site.ps1`: Deploys local HTML files to live server.
3. **Content**:
    - "Our Story" page built with your personal narrative.
    - Pledge Page fixed using a custom template (`page-pledge.php`) to force the "I Choose 90" message to appear.
4. **Backend**: Added "Pledge Amount" field to Gravity Form ID 1.

## ⚠️ Outstanding / Next Steps

1. **Pledge Amount Field Visibility**:
    - The field was added in the backend but was not showing on the live site yet (likely caching).
    - **Action**: Check `https://choose90.org/pledge/` after 30-60 minutes. It should appear automatically.
2. **Web Disk**:
    - You may need to run `map_drive_interactive.ps1` again after restarting your computer to reconnect the Z: drive if you want to deploy future changes.

## 📝 How to Resume

1. **Open VS Code** to this folder.
2. **Check Site**: Visit [Choose90.org](https://choose90.org).
3. **Editing Content**:
    - **To edit the Homepage/About**: Edit `hybrid_site/index.html` or `about.html`, then run `deploy_hybrid_site.ps1`.
    - **To edit Forms**: Log into WordPress Admin.

**Git Status**: All code, scripts, and hybrid site assets have been pushed to GitHub.
