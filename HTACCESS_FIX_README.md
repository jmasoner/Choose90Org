# .htaccess Fix for Homepage Loading

## Problem
The homepage wasn't loading because `.htaccess` was configured to prioritize `index.php` over `index.html`.

## Solution
Updated the `DirectoryIndex` directive in both:
1. Root `.htaccess` (line 27) - Changed from `index.php index.html` to `index.html index.php`
2. `hybrid_site/.htaccess` - Added `DirectoryIndex index.html index.php`

## Deployment

### Option 1: Manual Update (Recommended)
The root `.htaccess` is managed by Bulletproof Security plugin. To update it:

1. **Via Bulletproof Security Plugin:**
   - Go to WordPress Admin → Bulletproof Security → Custom Code
   - Add to "CUSTOM CODE DIRECTORY INDEX":
   ```
   DirectoryIndex index.html index.php /index.php
   ```
   - Save

2. **OR directly on server:**
   - SSH into server
   - Edit `/home/constit2/choose90.org/.htaccess`
   - Change line 27 from:
     ```
     DirectoryIndex index.php index.html /index.php
     ```
   - To:
     ```
     DirectoryIndex index.html index.php /index.php
     ```

### Option 2: Deploy hybrid_site/.htaccess
If you want to merge rules, you can copy the updated `hybrid_site/.htaccess` to the root, but be careful not to overwrite Bulletproof Security rules.

```powershell
# Copy hybrid_site/.htaccess to root (BACKUP FIRST!)
Copy-Item "hybrid_site\.htaccess" "Z:\.htaccess" -Force
```

**⚠️ WARNING:** This will overwrite the Bulletproof Security `.htaccess`. Better to update the DirectoryIndex line manually.

## Verification
After updating, visit:
- https://choose90.org - Should load `index.html`
- https://choose90.org/wp-admin - Should still work (WordPress)
- https://choose90.org/chapters/ - Should still work (WordPress pages)

## What Changed

**Before:**
```
DirectoryIndex index.php index.html /index.php
```
(WordPress index.php loads first)

**After:**
```
DirectoryIndex index.html index.php /index.php
```
(Static HTML homepage loads first, WordPress routes still work)



