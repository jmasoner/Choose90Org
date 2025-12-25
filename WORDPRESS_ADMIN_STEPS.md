# WordPress Admin Steps (After Deployment)

## ✅ Files Are Deployed
All chapter files have been pushed to Z: drive. They're automatically active (no plugin activation needed).

## What You Need to Do in WordPress Admin

### Step 1: Verify the Templates Are Available
1. Go to: **https://choose90.org/wp-admin**
2. Navigate to: **Pages → Add New** (or edit existing "Chapters" page)
3. Look for **"Page Attributes"** box (on the right sidebar)
4. Check the **"Template"** dropdown - you should see:
   - "Chapters Page" (or "Default Template" using page-chapters.php)
   - "Host Starter Kit" (for page-host-starter-kit.php)

### Step 2: Create/Update the Chapters Directory Page
1. Go to: **Pages → All Pages**
2. **If "Chapters" page exists:**
   - Click "Edit"
   - In "Page Attributes" → Template, select **"Chapters Page"** (or default)
   - Click **"Update"**
   
3. **If "Chapters" page doesn't exist:**
   - Click **"Add New"**
   - Title: **"Chapters"**
   - Permalink: Should be `/chapters/` (WordPress will set this automatically)
   - In "Page Attributes" → Template, select **"Chapters Page"**
   - Click **"Publish"**

### Step 3: Create a Test Chapter (To See It Work)
1. Go to: **Chapters → Add New** (in WordPress admin menu)
2. You'll now see the **"Chapter Details"** meta box with fields:
   - City: Enter "Austin"
   - State: Select "TX"
   - Meeting Pattern: Enter "3rd Tuesday @ 7 PM"
   - Location Name: Enter "Starbucks on Main St"
   - Status: Select **"Active"** (important!)
3. Add a title: **"Choose90 Austin"**
4. Click **"Publish"**

### Step 4: View Your Work!
1. Visit: **https://choose90.org/chapters/**
   - You should see the directory with search/filter
   - Your test chapter should appear in the grid
   
2. Click on your test chapter to see the single chapter page

## Quick Troubleshooting

### "I don't see the Chapter Details meta box"
- The `wp-functions-chapters.php` file should have been included in `functions.php`
- Try refreshing the page (Ctrl+F5)
- Check that you're editing a "Chapter" post type (not a regular Page)

### "No chapters showing on /chapters/"
- Make sure at least one chapter has status set to **"Active"**
- Make sure chapters are **Published** (not Draft)
- Clear WordPress cache if you use a caching plugin

### "Template dropdown doesn't show Chapters Page"
- The files are deployed, WordPress should detect them automatically
- Try going to **Settings → Permalinks → Save** (refreshes templates)
- Check that the page is using the correct template

## What You'll See

✅ **On `/chapters/` page:**
- Search box
- State filter dropdown
- Grid of chapter cards with location info
- Results counter

✅ **On single chapter page:**
- Chapter details sidebar
- Location, meeting info, member count
- Contact Host / Join Chapter buttons
- Map link (if address provided)

---

**That's it!** No plugin activation needed - these are part of your theme.



