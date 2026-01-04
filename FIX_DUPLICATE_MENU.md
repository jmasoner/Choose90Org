# Fix Duplicate Analytics Menu

## Issues Found

1. **Duplicate Menu Items**: Analytics menu appears twice
2. **Template File Missing**: `analytics-settings.php` template not found on server

## Solutions

### 1. Remove Duplicate Menu Registration

The Analytics class still has menu registration code. I've removed it from the constructor, but we need to make sure the `add_admin_menu()` method is completely removed or commented out.

### 2. Upload Template Files

Make sure these files exist on your server:
- `wp-content/plugins/choose90-crm/templates/analytics-dashboard.php`
- `wp-content/plugins/choose90-crm/templates/analytics-settings.php`

## Files to Upload

1. **`class-crm-analytics.php`** - Updated (menu registration removed)
2. **`templates/analytics-settings.php`** - Make sure this exists on server
3. **`templates/analytics-dashboard.php`** - Make sure this exists on server

## After Upload

1. Reactivate plugin
2. Clear cache
3. Refresh admin page
4. Check - menu should appear once, and template should load

