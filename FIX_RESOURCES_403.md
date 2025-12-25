# Fix 403 Forbidden Error on /resources/

## Problem
Getting 403 Forbidden error when accessing https://choose90.org/resources/

## Common Causes

### 1. Physical Directory Conflict
If there's a physical `/resources/` directory on the server, Apache will try to serve it instead of the WordPress page, causing a 403 if directory listing is disabled.

### 2. WordPress Page Not Created
The WordPress page might not exist or isn't published.

### 3. Permissions Issue
The directory might have incorrect file permissions.

## Solutions

### Solution 1: Remove/rename physical directory (if exists)
1. **Check if directory exists:**
   - Via cPanel File Manager
   - Or via FTP/SSH
   - Look for: `/home/constit2/choose90.org/resources/`

2. **If it exists:**
   - **Option A**: Rename it to `resources-old/` or `resources-backup/`
   - **Option B**: Delete it if it's not needed
   - **Option C**: Move contents elsewhere

3. **Then:**
   - Go to Settings → Permalinks → Save (refreshes rewrite rules)
   - Test: https://choose90.org/resources/

### Solution 2: Verify WordPress Page
1. Go to WordPress Admin → Pages → All Pages
2. Find "Resources" page
3. Check:
   - Is it published? (not Draft)
   - Permalink is `/resources/`
   - Template is selected (if using custom template)

### Solution 3: Check .htaccess
Make sure `.htaccess` doesn't have rules blocking `/resources/`

### Solution 4: Use different URL
If you can't remove the directory, use a different permalink:
- `/resources-hub/` instead of `/resources/`
- Update all navigation links

## Quick Fix Script

I can create a script to check and fix this automatically.



