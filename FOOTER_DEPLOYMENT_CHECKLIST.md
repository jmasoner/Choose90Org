# Footer Update - Deployment Checklist

## ✅ What's Been Updated (Locally)

### Files Updated:
1. ✅ `hybrid_site/components/static-footer.html` - Added Legal section
2. ✅ `hybrid_site/index.html` - Footer updated
3. ✅ `hybrid_site/resources-hub.html` - Footer updated  
4. ✅ `hybrid_site/about.html` - Footer updated
5. ✅ `hybrid_site/support.html` - Footer updated
6. ✅ `hybrid_site/host-starter-kit.html` - Footer updated
7. ✅ `hybrid_site/resources/*.html` - All resource pages updated
8. ✅ `setup_child_theme.ps1` - WordPress footer template updated
9. ✅ All HTML files - Updated via `update_static_header_footer.ps1`

### New Pages Created:
1. ✅ `hybrid_site/terms-of-service.html`
2. ✅ `hybrid_site/delete-account.html`

## 🚀 Deployment Steps

### Step 1: Map Web Disk (if not already mapped)
```powershell
.\map_drive_interactive.ps1
```

### Step 2: Deploy Hybrid Site Files
```powershell
.\deploy_hybrid_site.ps1
```
This will upload:
- All updated HTML files with new footer
- `terms-of-service.html`
- `delete-account.html`
- `components/static-footer.html`

### Step 3: Update WordPress Footer
```powershell
.\setup_child_theme.ps1
```
This will regenerate `footer.php` in the WordPress child theme with the new Legal section.

### Step 4: Clear Browser Cache
- Press `Ctrl + Shift + Delete`
- Clear cached images and files
- Or use Incognito/Private browsing mode

### Step 5: Verify
Visit `https://choose90.org` and check the footer:
- Should see "Legal" column with 3 links
- Footer bottom should have: Privacy Policy | Terms of Service | Delete Account

## 📋 Files to Deploy

### Critical Files (Must Deploy):
```
hybrid_site/terms-of-service.html → /terms-of-service.html
hybrid_site/delete-account.html → /delete-account.html
hybrid_site/components/static-footer.html → /components/static-footer.html
hybrid_site/index.html → /index.html
hybrid_site/resources-hub.html → /resources-hub.html
hybrid_site/about.html → /about.html
hybrid_site/support.html → /support.html
```

### All Other HTML Files:
All HTML files in `hybrid_site/` have been updated with the new footer and should be deployed.

## 🔍 Troubleshooting

### If footer still shows old version:

1. **Check if files were deployed:**
   - Verify files exist on server at correct paths
   - Check file modification dates

2. **Clear browser cache:**
   - Hard refresh: `Ctrl + F5`
   - Or use Incognito mode

3. **Check WordPress footer:**
   - If viewing a WordPress page, the footer comes from `footer.php`
   - Run `setup_child_theme.ps1` to update it

4. **Verify static-footer.html on server:**
   - Check `/components/static-footer.html` on server
   - Should have "Legal" section (lines 28-35)

## ✅ Expected Result

After deployment, footer should show:

**Footer Columns:**
- Explore
- Action  
- **Legal** ← NEW!
  - Privacy Policy
  - Terms of Service
  - Delete Account
- Campaigns

**Footer Bottom:**
© 2025 Choose90.org. All rights reserved. | Privacy Policy | Terms of Service | Delete Account

---

**Last Updated:** December 28, 2025
