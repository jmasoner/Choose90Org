# Diagnose Resources 403 Error

## Current Status
- ✓ `resources/` directory does not exist (you deleted it)
- ✓ `page-resources.php` template exists on server
- ✗ Still getting 403 errors

## Possible Causes

### 1. WordPress Page Doesn't Exist or Isn't Published
The template exists, but WordPress needs a page to use it.

**Check:**
1. Go to WordPress Admin → Pages → All Pages
2. Look for a page titled "Resources"
3. If it doesn't exist, create it:
   - Click "Add New"
   - Title: "Resources"
   - Template: Select "Resources Page" (or "Default Template")
   - Permalink: Should be `/resources/`
   - Status: **Published** (not Draft)
   - Click "Publish"

### 2. Permalinks Need Refresh
WordPress rewrite rules might be stale.

**Fix:**
1. WordPress Admin → Settings → Permalinks
2. Click "Save Changes" (don't change anything, just save)
3. This refreshes the rewrite rules

### 3. Template Not Assigned
The page exists but isn't using the correct template.

**Fix:**
1. Edit the "Resources" page
2. In "Page Attributes" → Template, select "Resources Page"
3. Update the page

### 4. .htaccess Blocking Access
There might be a security rule blocking the path.

**Check:**
- Look for any rules in `.htaccess` that might block `/resources/`
- Check if Bulletproof Security or similar plugin has rules blocking it

### 5. WordPress Page Content Override
If the page editor has content, it might be trying to load something that causes 403.

**Fix:**
- Edit the "Resources" page
- Delete all content from the editor (leave it empty)
- The template provides all content via redirect

## Quick Test

Try accessing these URLs directly:
- https://choose90.org/resources/ (should redirect to resources-hub.html)
- https://choose90.org/resources-hub.html (should work directly)

If `resources-hub.html` works but `/resources/` gives 403, it's definitely a WordPress page configuration issue.

