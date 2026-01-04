# Remove Duplicate Analytics Menu

## Issue
Analytics menu items appear twice in the CRM menu.

## Root Cause
The old version of `class-crm-analytics.php` on the server still has menu registration code that's being executed.

## Solution Applied

I've added a check in `class-crm-admin.php` to prevent duplicate menu registration. It now checks if the menu already exists before adding it.

## What You Need to Do

### 1. Verify Server File
On your server, check that `class-crm-analytics.php` has this in the constructor (around line 24-30):

```php
private function __construct() {
    // Menu registration is now handled in class-crm-admin.php to avoid duplicates
    // Only register settings and AJAX handlers here
    add_action('admin_init', array($this, 'register_settings'));
    add_action('wp_ajax_choose90_analytics_refresh', array($this, 'ajax_refresh_data'));
    add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
}
```

**It should NOT have:**
- `add_action('admin_menu', ...)` for menu registration
- `add_admin_menu()` method

### 2. Upload Updated Files

Upload these files to ensure no duplicates:
- `wp-content/plugins/choose90-crm/includes/class-crm-admin.php` (with duplicate check)
- `wp-content/plugins/choose90-crm/includes/class-crm-analytics.php` (without menu registration)

### 3. Reactivate Plugin

- Deactivate "Choose90 CRM"
- Activate "Choose90 CRM"
- Clear cache
- Refresh admin page

### 4. Verify

After these steps, you should see:
- "Analytics" appears ONCE
- "Analytics Settings" appears ONCE
- No duplicates

## If Still Duplicated

If duplicates persist after uploading, the old file on the server might be cached. Try:
1. Delete the plugin folder completely
2. Re-upload the entire plugin
3. Reactivate

