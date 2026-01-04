# Debugging Analytics Dashboard Menu Issue

## Changes Made

1. ✅ **Added priority 11** to analytics menu registration (same as dashboard menu)
   - Ensures main CRM menu exists before adding submenu

2. ✅ **Added menu existence check** (same pattern as dashboard menu)
   - Checks if 'choose90-crm' menu exists before adding submenu

3. ✅ **Fixed template paths** to use plugin constant

## Current Menu Registration Order

All menus use `admin_menu` hook:

1. **Choose90_CRM_Admin** (priority 10 - default)
   - Registers main "CRM" menu with slug `choose90-crm`
   - Capability: `read`

2. **Choose90_CRM_Dashboard** (priority 11)
   - Adds "My Dashboard" submenu
   - Capability: `read`

3. **Choose90_CRM_Analytics** (priority 11) ⬅️ **FIXED**
   - Adds "Analytics" submenu
   - Capability: `manage_options` (Administrator only)

## If Menu Still Doesn't Appear

### 1. Verify User Role
- Go to Users → Your Profile
- Role must be **Administrator**

### 2. Clear Cache
- WordPress caching plugins
- Browser cache (Ctrl+F5)

### 3. Check WordPress Debug Log
Enable WP_DEBUG in wp-config.php:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

Check `wp-content/debug.log` for errors.

### 4. Test Direct Access
Try accessing directly:
```
/wp-admin/admin.php?page=choose90-crm-analytics
```

If it works directly but menu doesn't show, it's a menu registration issue.

### 5. Check Plugin Activation
- Go to Plugins → Installed Plugins
- Verify "Choose90 CRM" is **Active**
- Try deactivating and reactivating

### 6. Check PHP Errors
Add this temporarily to test:
```php
// Add to class-crm-analytics.php add_admin_menu() method
error_log('Analytics menu registration called');
error_log('Menu exists: ' . (isset($submenu['choose90-crm']) ? 'yes' : 'no'));
```

### 7. Manual Menu Test
Add this to functions.php temporarily to test menu registration:
```php
add_action('admin_menu', function() {
    global $submenu;
    error_log('CRM submenu items: ' . print_r($submenu['choose90-crm'] ?? 'NOT FOUND', true));
}, 999);
```

## Expected Behavior

**For Administrators:**
- Should see "CRM" menu in sidebar
- Should see "Analytics" submenu under CRM
- Should see "Analytics Settings" submenu under CRM

**For Non-Administrators:**
- May see "CRM" menu (if they have `read` capability)
- Will NOT see "Analytics" submenu (requires `manage_options`)
- Will see "My Dashboard" submenu (requires `read`)

## Next Steps

If menu still doesn't appear after these fixes:
1. Check browser console for JavaScript errors
2. Verify plugin files are loaded correctly
3. Check if other CRM submenus are visible (Distribution Lists, Settings)
4. Try adding a simple test submenu to verify the pattern works

