# Fix Analytics Menu Not Showing

## Problem
The "Analytics" submenu is not appearing under the CRM menu in WordPress admin, even though the code looks correct.

## Most Likely Causes

### 1. Plugin Files Not Updated on Server ⚠️ **MOST COMMON**
The code changes were made locally but haven't been uploaded to your WordPress server yet.

**Solution:**
- Upload the updated `class-crm-analytics.php` file to your server
- File location: `wp-content/plugins/choose90-crm/includes/class-crm-analytics.php`

### 2. Plugin Needs Reactivation
WordPress caches menu registrations. Deactivating and reactivating forces a refresh.

**Solution:**
1. Go to Plugins → Installed Plugins
2. Find "Choose90 CRM"
3. Click "Deactivate"
4. Wait 2-3 seconds
5. Click "Activate"

### 3. Caching Issue
WordPress or server caching might be serving old menu structure.

**Solution:**
- Clear WordPress cache (if using caching plugin)
- Clear browser cache (Ctrl+Shift+Delete)
- Hard refresh admin page (Ctrl+F5)

## Code Verification

The code should have:

1. **Priority 11** in constructor:
```php
add_action('admin_menu', array($this, 'add_admin_menu'), 11);
```

2. **Menu registration** in `add_admin_menu()`:
```php
add_submenu_page(
    'choose90-crm',
    __('Analytics Dashboard', 'choose90-crm'),
    __('Analytics', 'choose90-crm'),
    'manage_options',
    'choose90-crm-analytics',
    array($this, 'render_dashboard')
);
```

## Quick Verification Steps

1. **Check if file exists on server:**
   - File: `wp-content/plugins/choose90-crm/includes/class-crm-analytics.php`
   - Verify it has the priority 11 fix

2. **Check plugin is active:**
   - Plugins → Installed Plugins
   - "Choose90 CRM" should be Active (not just installed)

3. **Check user role:**
   - You must be Administrator (not Editor)
   - The menu requires `manage_options` capability

4. **Try direct access:**
   - Go to: `/wp-admin/admin.php?page=choose90-crm-analytics`
   - If this works, menu registration is fine but visibility is the issue
   - If this gives 404/error, menu isn't registered

## If Still Not Working

Enable WordPress debug logging:

1. Edit `wp-config.php`
2. Add before "That's all, stop editing!":
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

3. Check `wp-content/debug.log` for errors
4. Look for lines mentioning "Choose90_CRM_Analytics" or menu registration errors

## Expected Result

After fixes, you should see under CRM menu:
- CRM (main page)
- Distribution Lists
- Settings
- My Dashboard
- **Analytics** ← Should appear here
- **Analytics Settings** ← Should appear here

