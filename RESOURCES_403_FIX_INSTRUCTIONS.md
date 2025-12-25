# Fix 403 Error on /resources/ from Pledge Page

## Problem
Clicking "Resources" from the pledge page (https://choose90.org/pledge/) gives a 403 Forbidden error because an empty `resources/` directory exists on the server, conflicting with the WordPress page.

## Solution

### Option 1: Use cPanel File Manager (Recommended)

1. Log into cPanel
2. Open **File Manager**
3. Navigate to the root directory (`/home/constit2/choose90.org/`)
4. Find the `resources/` folder
5. **Right-click** → **Delete** (or select it and click Delete)
6. Confirm deletion

### Option 2: Use PowerShell Script

Run the script I created:
```powershell
.\remove_empty_resources.ps1
```

If that doesn't work, you can manually delete via cPanel.

### After Removing the Directory

1. Go to **WordPress Admin** → **Settings** → **Permalinks**
2. Click **Save Changes** (this refreshes the rewrite rules)
3. Test: https://choose90.org/resources/

The `/resources/` page should now redirect to `resources-hub.html` correctly.

## Why This Happens

WordPress uses URL rewriting to serve pages. When a physical directory exists with the same name as a WordPress page slug (`/resources/`), Apache tries to serve the directory instead of the WordPress page. If directory listing is disabled, this results in a 403 Forbidden error.

## Prevention

The `deploy_production.ps1` script now automatically deploys resource files to `resources-backup/` instead of `resources/` to prevent this conflict in the future.



