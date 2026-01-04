# Analytics Dashboard Access Guide

## ✅ Fixed Issues

The analytics dashboard had a template path bug that has been fixed. The dashboard should now be accessible.

## 📍 How to Access the Dashboard

### WordPress Admin URL:
```
/wp-admin/admin.php?page=choose90-crm-analytics
```

### Navigation Path:
1. Log in to WordPress Admin
2. Look for **"CRM"** in the left sidebar menu
3. Click **"CRM"** to expand the menu
4. Click **"Analytics"** under the CRM menu

### Required Permissions:
- **Administrator** role (requires `manage_options` capability)
- Regular editors and authors will see "CRM" menu but not the Analytics submenu

## 📋 Available Pages

### 1. Analytics Dashboard
- **URL**: `/wp-admin/admin.php?page=choose90-crm-analytics`
- **Menu**: CRM → Analytics
- **Content**: Real-time metrics, charts, and analytics data

### 2. Analytics Settings
- **URL**: `/wp-admin/admin.php?page=choose90-crm-analytics-settings`
- **Menu**: CRM → Analytics Settings
- **Content**: Configure GA4 Measurement ID, API keys, and other settings

### 3. CRM Dashboard (Chapter Leader)
- **URL**: `/wp-admin/admin.php?page=choose90-crm-dashboard`
- **Menu**: CRM → My Dashboard
- **Content**: Email management for chapter leaders
- **Permissions**: All users with `read` capability

## 🔧 What Was Fixed

1. **Template Path Bug**: Fixed incorrect path calculation in `class-crm-analytics.php`
   - **Before**: `plugin_dir_path(dirname(__FILE__)) . '../templates/analytics-dashboard.php'`
   - **After**: `CHOOSE90_CRM_PLUGIN_DIR . 'templates/analytics-dashboard.php'`

2. **Settings Template Path**: Fixed the same issue for analytics settings template

3. **Script Enqueue Hook**: Updated to include both dashboard and settings pages

## 🚨 Troubleshooting

### If you still can't see the dashboard:

1. **Check User Role**: Make sure you're logged in as an Administrator
   - Go to Users → Your Profile
   - Your role should be "Administrator"

2. **Clear Cache**: 
   - Clear any WordPress caching plugins
   - Clear browser cache

3. **Verify Plugin is Active**:
   - Go to Plugins → Installed Plugins
   - Look for "Choose90 CRM"
   - Make sure it's activated

4. **Check for Errors**:
   - Go to `/wp-admin/admin.php?page=choose90-crm-analytics`
   - Look for error messages in the page
   - Check browser console (F12) for JavaScript errors

5. **Menu Visibility**:
   - If you don't see "CRM" menu at all, check if the plugin is properly loaded
   - Check WordPress debug.log for PHP errors

### Direct Access
If the menu isn't visible but you're an admin, try accessing directly:
```
https://yourdomain.com/wp-admin/admin.php?page=choose90-crm-analytics
```

Replace `yourdomain.com` with your actual domain.

## 📝 Current Status

- ✅ Dashboard code exists and is registered
- ✅ Template files exist at correct location
- ✅ Menu registration fixed
- ✅ Template path bug fixed
- ⏳ Dashboard functionality needs GA4 API integration (most methods are placeholders)

The dashboard structure is in place, but most analytics data methods return placeholder data. Full GA4 API integration is needed to populate real data.

