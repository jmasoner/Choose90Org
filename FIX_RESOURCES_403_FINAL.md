# Fix 403 Error on /resources/ - FINAL SOLUTION

## Problem
Multiple users getting 403 Forbidden error when clicking "Resources" from the home page. This happens because a physical `resources/` directory exists on the server, conflicting with the WordPress page.

## Root Cause
When a physical directory exists with the same name as a WordPress page slug (`/resources/`), Apache tries to serve the directory instead of the WordPress page. If directory listing is disabled, this results in a 403 Forbidden error.

## Solution: Remove the Directory via cPanel

### Step 1: Log into cPanel
1. Go to your hosting control panel (cPanel)
2. Log in with your credentials

### Step 2: Open File Manager
1. Find and click the **"File Manager"** icon
2. Navigate to the root directory: `/home/constit2/choose90.org/` (or your root directory)

### Step 3: Delete the resources Directory
1. Look for a folder named **`resources`**
2. **Right-click** on the `resources` folder
3. Select **"Delete"** (or select it and click the Delete button)
4. Confirm the deletion

**IMPORTANT:** Make sure you're deleting the empty `resources/` folder, NOT `resources-backup/` (which contains all your resource HTML files).

### Step 4: Refresh WordPress Permalinks
1. Go to **WordPress Admin:** https://choose90.org/wp-admin
2. Navigate to **Settings → Permalinks**
3. Click **"Save Changes"** (this refreshes the rewrite rules)

### Step 5: Test
Visit: https://choose90.org/resources/

You should now be redirected to `resources-hub.html` instead of getting a 403 error.

## Why This Keeps Happening

The `resources/` directory may have been recreated during deployment or may have been there from before. The `deploy_production.ps1` script now deploys resource files to `resources-backup/` to prevent this conflict, but the old directory needs to be manually removed.

## Prevention

After removing the directory, future deployments will use `resources-backup/` for resource files, so this shouldn't happen again.

## Alternative: If You Can't Access cPanel

If you can't access cPanel, you can try using FTP or SSH to remove the directory, but cPanel File Manager is the easiest method.



