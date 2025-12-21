# How to View Chapter Pages in Your Browser

## The Problem
The files `page-chapters.php`, `single-chapter.php`, etc. are **WordPress PHP templates**, not standalone HTML files. They need to run through WordPress to work.

## Solution: Two Ways to View Them

### Option 1: View on Live Site (Recommended)

#### Step 1: Deploy the Files
Deploy your updated templates to the WordPress theme:

```powershell
# Make sure Z: drive is connected
.\map_drive_interactive.ps1

# Deploy the chapter templates
.\setup_child_theme.ps1
```

#### Step 2: Create/Access the WordPress Page
1. **Go to WordPress Admin:** https://choose90.org/wp-admin
2. **Navigate to:** Pages → All Pages
3. **Find or Create "Chapters" page:**
   - If it exists, edit it
   - If not, create new page titled "Chapters"
4. **Select Template:**
   - In the page editor, look for "Page Attributes" → Template
   - Select "Chapters Page" (or "Default Template" if available)
5. **Set Permalink:** Make sure the URL is `/chapters/`
6. **Publish** the page

#### Step 3: View the Page
Visit: **https://choose90.org/chapters/**

You should now see:
- The chapters directory with search/filter
- All active chapters displayed
- The enhanced cards with location info

#### Step 4: View a Single Chapter
1. **Create a Sample Chapter:**
   - Go to WordPress Admin → Chapters → Add New
   - Fill in the "Chapter Details" meta box:
     - City: Austin
     - State: TX
     - Meeting Pattern: 3rd Tuesday @ 7 PM
     - Location Name: Starbucks on Main St
     - Status: Active
   - Add a title like "Choose90 Austin"
   - Publish
2. **View the Chapter:**
   - Visit: https://choose90.org/chapter/choose90-austin/ (or click the chapter from the directory)

---

### Option 2: Set Up Local WordPress (For Development)

If you want to test locally before deploying:

#### Quick Setup Using Local by Flywheel or XAMPP

1. **Install Local by Flywheel** (easiest): https://localwp.com/
2. **Create a new site:**
   - Site name: Choose90 Local
   - WordPress version: Latest
   - PHP version: 8.0+
3. **Copy your files:**
   - Copy `hybrid_site/wp-functions-chapters.php` to `wp-content/themes/[your-theme]/`
   - Copy templates to the theme directory
4. **Import database** from live site (optional, or create test data)
5. **Access:** http://choose90.local or http://localhost:1000

---

## What You Should See

### On `/chapters/` page:
- ✅ Search box at top
- ✅ State filter dropdown
- ✅ Grid of chapter cards
- ✅ Each card shows: Location (📍), Meeting info (📅), Member count (👥)
- ✅ "View Chapter" button on each card

### On Single Chapter page:
- ✅ Chapter title
- ✅ Sidebar with:
  - Location (City, State)
  - Meeting details (when & where)
  - Member count
  - Contact Host button
  - Join Chapter button
  - View on Map link (if address provided)

---

## Troubleshooting

### "Page Not Found" Error
- **Check:** Is the WordPress page created and published?
- **Check:** Is the permalink set correctly?
- **Fix:** Go to Settings → Permalinks → Save (this refreshes rewrite rules)

### Template Not Showing
- **Check:** Did you select the correct template in the page editor?
- **Check:** Is the template file deployed to `Z:\wp-content\themes\Divi-choose90\`?
- **Fix:** Re-run `.\setup_child_theme.ps1`

### No Chapters Showing
- **Check:** Are chapters published?
- **Check:** Is chapter status set to "Active"?
- **Fix:** Edit chapters in WordPress Admin → Chapters, set status to "Active"

### Search/Filter Not Working
- **Check:** Is JavaScript enabled in your browser?
- **Check:** Are there browser console errors? (F12 → Console)
- **Fix:** Clear browser cache (Ctrl+F5)

### Meta Box Not Appearing
- **Check:** Is `wp-functions-chapters.php` included in `functions.php`?
- **Check:** Is the file deployed to the theme directory?
- **Fix:** Re-deploy or manually add the include statement

---

## Quick Test Checklist

- [ ] Z: drive is connected
- [ ] Files deployed via `setup_child_theme.ps1`
- [ ] WordPress page created/updated
- [ ] Template selected in page editor
- [ ] At least one chapter exists with status "Active"
- [ ] Visiting https://choose90.org/chapters/ shows the directory
- [ ] Clicking a chapter shows the single chapter page
- [ ] Search box filters chapters
- [ ] State dropdown filters chapters

---

## Still Having Issues?

1. **Check WordPress Admin:**
   - Verify the page exists
   - Verify template is selected
   - Check for PHP errors in WordPress → Tools → Site Health

2. **Check File Deployment:**
   ```powershell
   # Verify files are on server
   ls Z:\wp-content\themes\Divi-choose90\page-chapters.php
   ls Z:\wp-content\themes\Divi-choose90\wp-functions-chapters.php
   ```

3. **Check Browser:**
   - Clear cache (Ctrl+F5)
   - Try incognito/private mode
   - Check browser console for errors (F12)

4. **Check Server Logs:**
   - Contact hosting provider for PHP error logs
   - Or check cPanel error logs

