# Chapters Meta Fields & Enhancements - Installation Guide

## Overview
This update adds comprehensive meta fields to chapters, improves the directory display, and adds search/filter functionality.

## Files Created/Updated

### New Files
1. **`hybrid_site/wp-functions-chapters.php`**
   - WordPress functions for chapter meta fields
   - Meta box UI in WordPress admin
   - Helper functions to retrieve chapter data
   - Automatic filtering of only "active" chapters on frontend

### Updated Files
1. **`hybrid_site/page-host-starter-kit.php`**
   - Now saves city, state, leader name, and email when chapter is created
   - Sets status to "pending" for new chapters

2. **`hybrid_site/single-chapter.php`**
   - Displays location (city, state)
   - Shows meeting pattern and location
   - Shows member count
   - Adds "View on Map" link if address is available

3. **`hybrid_site/page-chapters.php`**
   - Enhanced chapter cards with location info
   - Search functionality (by city, name, or location)
   - State filter dropdown
   - Only shows "active" chapters
   - Improved card hover effects
   - Results counter

## Installation Steps

### Step 1: Include WordPress Functions

Add the following to your child theme's `functions.php` file:

```php
// Include Choose90 chapters functions (meta fields, etc.)
require_once get_stylesheet_directory() . '/../hybrid_site/wp-functions-chapters.php';
```

**OR** if the file structure is different, copy the contents of `wp-functions-chapters.php` directly into your `functions.php`.

**OR** update your `setup_child_theme.ps1` script to copy this file to the theme directory.

### Step 2: Update Existing Chapters

For existing chapters in your database:

1. Go to WordPress Admin → Chapters
2. Edit each chapter
3. Fill in the new "Chapter Details" meta box:
   - **Location Information**: City, State
   - **Meeting Information**: Meeting pattern, location name, address
   - **Chapter Leader**: Name and email (internal use)
   - **Status**: Set to "Active" to make it visible on the directory

### Step 3: Deploy Files

Run your deployment script to push the updated templates:

```powershell
.\setup_child_theme.ps1
```

Or manually copy:
- `page-host-starter-kit.php`
- `single-chapter.php`
- `page-chapters.php`

### Step 4: Test

1. **Test Meta Box**: Create or edit a chapter, verify the meta box appears
2. **Test Directory**: Visit the chapters page, verify location info shows in cards
3. **Test Search**: Use the search box to filter chapters
4. **Test State Filter**: Filter chapters by state
5. **Test Single Chapter**: View a chapter detail page, verify all meta fields display

## Meta Fields Reference

### Location Fields
- `_chapter_city` - City name (text)
- `_chapter_state` - State code (e.g., "TX", "CA") or "Other"
- Auto-creates region taxonomy term

### Meeting Fields
- `_chapter_meeting_pattern` - When they meet (e.g., "3rd Tuesday @ 7 PM")
- `_chapter_location_name` - Meeting place name (e.g., "Starbucks on Main St")
- `_chapter_location_address` - Full address (optional, for map link)

### Leader Fields (Internal)
- `_chapter_leader_name` - Leader's name (not displayed publicly)
- `_chapter_leader_email` - Leader's email (not displayed publicly)

### Status Fields
- `_chapter_status` - One of: `active`, `pending`, `paused`, `forming`
  - Only "active" chapters show on public directory

### Other Fields
- `_chapter_member_count` - Approximate number of members (optional)

## Helper Functions

Use these in your templates:

```php
get_chapter_city($post_id);           // Returns city name
get_chapter_state($post_id);          // Returns state code
get_chapter_location($post_id);       // Returns "City, State" or just "State"
get_chapter_meeting_info($post_id);   // Returns array with 'pattern' and 'location'
get_chapter_status($post_id);         // Returns status (defaults to 'active')
```

## Email Distribution Lists

**Important**: When a chapter leader email is set, manually add it to your `chapters@choose90.org` distribution list in your email system. This is a manual step until the CRM system is implemented.

## Next Steps (Future)

- [ ] Contact form for "Join Chapter" (replaces mailto link)
- [ ] Auto-add leader emails to distribution list (via CRM)
- [ ] Chapter leader dashboard
- [ ] Chapter analytics (views, contact form submissions)
- [ ] Email templates for chapter communications

## Troubleshooting

### Meta box doesn't appear
- Ensure `wp-functions-chapters.php` is included in `functions.php`
- Clear WordPress cache if using a caching plugin
- Check that post type is "chapter"

### Chapters don't show on directory
- Check chapter status is set to "active"
- Verify chapter is published (not draft)
- Check `_chapter_status` meta field value

### Search/filter not working
- Check browser console for JavaScript errors
- Ensure jQuery is loaded (if needed)
- Verify data attributes are set on chapter cards



