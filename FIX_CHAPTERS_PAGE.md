# Fix Chapters Page - Showing Wrong Content

## Problem
The `/chapters/` page is showing "Find Your Community" with three cards instead of the new "Stop Waiting for Community. Build It." template with the chapter directory.

## Solution

### Step 1: Deploy the Correct Template
Run the deployment script to ensure the latest template is on the server:

```powershell
.\setup_child_theme.ps1
```

### Step 2: Update WordPress Page Settings

1. **Go to WordPress Admin:** https://choose90.org/wp-admin
2. **Navigate to:** Pages → All Pages
3. **Find and Edit the "Chapters" page**
4. **Clear the page content:**
   - In the WordPress editor, **delete all content** from the page
   - The template will provide all the content, so the editor should be empty
5. **Select the correct template:**
   - Look for **"Page Attributes"** box (right sidebar)
   - Under **"Template"**, select **"Chapters Directory"**
   - If you don't see this option, select **"Default Template"**
6. **Save/Update** the page

### Step 3: Clear Cache
- Clear your browser cache (Ctrl+F5 or Cmd+Shift+R)
- If using a caching plugin, clear that cache too

### Step 4: Verify
Visit: https://choose90.org/chapters/

You should now see:
- ✅ "Stop Waiting for Community. Build It." heading
- ✅ 3 benefits cards
- ✅ "How It Works" section
- ✅ Chapter directory with search/filter

## Why This Happens

WordPress pages can have content in two places:
1. **Page Template** (PHP file) - This is what we want to use
2. **Page Editor Content** - This can override the template

If the page editor has content, it will display instead of the template. That's why we need to clear the editor content and select the correct template.

## Alternative: If Template Doesn't Appear

If "Chapters Directory" template doesn't appear in the dropdown:

1. **Check file exists:** The template should be at:
   `Z:\wp-content\themes\Divi-choose90\page-chapters.php`

2. **Verify template name:** The file should have:
   ```php
   /* Template Name: Chapters Directory */
   ```

3. **Re-deploy:** Run `.\setup_child_theme.ps1` again

4. **Check theme:** Make sure you're using the "Divi-choose90" child theme, not the parent Divi theme

