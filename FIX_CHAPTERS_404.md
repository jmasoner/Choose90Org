# Fix 404 Error on /chapters/ Link

## Problem
Clicking "Chapters" from resources-hub.html gives a 404 error because the WordPress page doesn't exist or isn't published.

## Quick Fix (2 minutes)

### Step 1: Create/Verify the Chapters Page in WordPress

1. **Go to WordPress Admin:** https://choose90.org/wp-admin
2. **Navigate to:** Pages → All Pages
3. **Check if "Chapters" page exists:**
   - If it exists: Click "Edit"
   - If it doesn't exist: Click "Add New"

4. **Set up the page:**
   - **Title:** "Chapters"
   - **Permalink:** Should be `/chapters/` (WordPress sets this automatically)
   - **Template:** In "Page Attributes" → Template, select **"Chapters Directory"** (or "Default Template")
   - **Content:** Leave the editor EMPTY (the template provides all content)
   - **Status:** Make sure it's **Published** (not Draft)

5. **Click "Publish" or "Update"**

### Step 2: Refresh Permalinks

1. Go to **Settings → Permalinks**
2. Click **"Save Changes"** (this refreshes the rewrite rules)
3. This is important - it makes WordPress recognize the `/chapters/` URL

### Step 3: Test

Visit: https://choose90.org/chapters/

You should now see the chapters directory page instead of a 404.

## Why This Happens

WordPress pages need to be created in the admin before the URL works. The template file (`page-chapters.php`) exists, but WordPress doesn't know to use it until you create a page and assign the template.

## Alternative: Temporary Fallback

If you can't access WordPress admin right now, I can update the link to point to a different URL temporarily, but the proper fix is to create the page in WordPress.

