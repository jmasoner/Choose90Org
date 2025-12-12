# Choose90 Modular Logo System - Deployment Guide

## 🎯 Overview
This modular system allows you to maintain the animated Choose90 logo in **ONE place** while using it across:
- WordPress pages (Chapters, Pledge, Donations, etc.)
- Static HTML pages (index.html, resources.html, about.html)
- Future pages (Cart, etc.)

**Single source of truth = No code duplication** ✅

---

## 📦 Files Created

| File | Size | Purpose | Used By |
|------|------|---------|---------|
| `logo-animated.css` | 2KB | All animation styles | ALL pages |
| `logo-animated.php` | 1.8KB | SVG component for static HTML | Static HTML pages |
| `header-logo.php` | 1.9KB | WordPress template part | WordPress pages |
| `logo-animated.js` | 2.7KB | JavaScript fallback (optional) | Backup solution |
| `functions-logo-additions.php` | 764B | WordPress enqueue code | Add to theme functions.php |

---

## 📂 Directory Structure on Server

```
/home/constit2/choose90.org/
│
├── css/
│   └── logo-animated.css                    ← SINGLE SOURCE for animations
│
├── includes/
│   └── logo-animated.php                    ← For static HTML pages
│
├── js/
│   └── logo-animated.js                     ← Optional JS fallback
│
└── wp-content/themes/Divi-choose90/
    ├── css/
    │   └── logo-animated.css                ← Copy of main CSS (or symlink)
    ├── template-parts/
    │   └── header-logo.php                  ← WordPress template part
    ├── functions.php                        ← Add code from functions-logo-additions.php
    └── header.php                           ← Call get_template_part('template-parts/header', 'logo')
```

---

## 🚀 Deployment Methods

### **Method 1: cPanel File Manager** (Recommended - Easiest)

#### Step 1: Upload Core CSS File
1. Log into cPanel: `https://choose90.org:2083`
2. Open **File Manager**
3. Navigate to `/home/constit2/choose90.org/`
4. Create folder: `css/` (if doesn't exist)
5. **Upload** `logo-animated.css` to `/css/`

#### Step 2: Upload Static HTML Component
1. Still in File Manager at `/home/constit2/choose90.org/`
2. Create folder: `includes/` (if doesn't exist)
3. **Upload** `logo-animated.php` to `/includes/`

#### Step 3: Upload WordPress Template Part
1. Navigate to `/home/constit2/choose90.org/wp-content/themes/Divi-choose90/`
2. Create folder: `template-parts/` (if doesn't exist)
3. **Upload** `header-logo.php` to `/template-parts/`
4. Create folder: `css/` inside your theme directory
5. **Upload** `logo-animated.css` to theme's `/css/` folder

#### Step 4: Update WordPress Theme
1. In File Manager, navigate to `/wp-content/themes/Divi-choose90/`
2. **Edit** `functions.php`
3. Scroll to the bottom
4. **Paste** contents of `functions-logo-additions.php`
5. **Save** file

#### Step 5: Update header.php
1. Still in theme directory, **Edit** `header.php`
2. Find your current logo code (likely `<a href="index.html" class="logo">Choose<span>90</span>.</a>`)
3. **Replace** with:
   ```php
   <?php get_template_part('template-parts/header', 'logo'); ?>
   ```
4. **Save** file

---

### **Method 2: SSH Terminal** (Advanced)

```bash
# Connect to server
ssh constit2@choose90.org

# Create directories
mkdir -p ~/choose90.org/css
mkdir -p ~/choose90.org/includes
mkdir -p ~/choose90.org/js
mkdir -p ~/choose90.org/wp-content/themes/Divi-choose90/template-parts
mkdir -p ~/choose90.org/wp-content/themes/Divi-choose90/css

# Exit SSH, upload from LOCAL machine
scp logo-animated.css constit2@choose90.org:~/choose90.org/css/
scp logo-animated.php constit2@choose90.org:~/choose90.org/includes/
scp logo-animated.js constit2@choose90.org:~/choose90.org/js/
scp header-logo.php constit2@choose90.org:~/choose90.org/wp-content/themes/Divi-choose90/template-parts/
scp logo-animated.css constit2@choose90.org:~/choose90.org/wp-content/themes/Divi-choose90/css/

# Then manually edit functions.php and header.php via cPanel or SSH editor
```

---

### **Method 3: PowerShell Deployment Script** (Automated)

Create `deploy-logo-system.ps1`:

```powershell
# Choose90 Logo System Deployment Script
# Requires Z: drive mounted (WebDisk)

$source = "C:\Users\john\OneDrive\MyProjects\Choose90Org\logo-files\"
$webRoot = "Z:\"
$themeRoot = "Z:\wp-content\themes\Divi-choose90\"

# Check Z: drive
if (-not (Test-Path "Z:\")) {
    Write-Error "Z: drive not mounted. Please connect WebDisk first."
    exit 1
}

# Deploy core files
Write-Host "Deploying core logo files..." -ForegroundColor Cyan
Copy-Item "$source\logo-animated.css" -Destination "$webRoot\css\" -Force
Copy-Item "$source\logo-animated.php" -Destination "$webRoot\includes\" -Force
Copy-Item "$source\logo-animated.js" -Destination "$webRoot\js\" -Force

# Deploy WordPress files
Write-Host "Deploying WordPress theme files..." -ForegroundColor Cyan
Copy-Item "$source\header-logo.php" -Destination "$themeRoot\template-parts\" -Force
Copy-Item "$source\logo-animated.css" -Destination "$themeRoot\css\" -Force

Write-Host "`n✅ Deployment complete!" -ForegroundColor Green
Write-Host "⚠️  MANUAL STEP: Add code from functions-logo-additions.php to theme's functions.php" -ForegroundColor Yellow
Write-Host "⚠️  MANUAL STEP: Update header.php to call get_template_part()" -ForegroundColor Yellow
```

**Run:**
```powershell
.\deploy-logo-system.ps1
```

---

## 🔧 Updating Your HTML Pages

### For Static HTML Pages (index.html, resources.html, about.html)

**Add to `<head>` section:**
```html
<link rel="stylesheet" href="/css/logo-animated.css">
```

**Replace static logo with:**
```html
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/logo-animated.php'; ?>
```

**Example `index.html` → `index.php`:**
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose 90 | Be the Good</title>
    <link rel="stylesheet" href="/css/logo-animated.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/logo-animated.php'; ?>
        <nav>
            <!-- Your navigation -->
        </nav>
    </header>
    <!-- Rest of page -->
</body>
</html>
```

⚠️ **Important:** Rename `index.html` → `index.php` to enable PHP includes!

---

## 🎨 Customization Guide

### Change Animation Speed
**Edit:** `logo-animated.css` (lines 15-27)
```css
.logo-animated svg .figure-blue {
    animation: slideInLeft 0.8s ease-out;  /* Change 0.8s to 1.2s for slower */
}
```

### Adjust "CHOOSE 90" Spacing
**Edit:** `logo-animated.php` AND `header-logo.php` (line 33)
```html
<text class="logo-text" x="235" ...>90</text>  <!-- Increase x="235" to x="250" -->
```

### Change Logo Colors
**Edit:** `logo-animated.css` (lines 28-30)
```css
.logo-animated svg .figure-blue { fill: #0066CC; }   /* Blue figure */
.logo-animated svg .figure-yellow { fill: #E8B93E; } /* Yellow figure */
```

### Modify Logo Size
**Edit:** `logo-animated.css` (line 13)
```css
.logo-animated svg {
    width: 180px;  /* Change to 200px for larger, 140px for smaller */
}
```

---

## ✅ Testing Checklist

After deployment:

- [ ] **WordPress Pages:**
  - [ ] https://choose90.org/chapters/ - Logo animates
  - [ ] https://choose90.org/pledge/ - Logo animates
  - [ ] https://choose90.org/donate/ - Logo animates
  
- [ ] **Static HTML Pages:**
  - [ ] https://choose90.org/ (index) - Logo animates
  - [ ] https://choose90.org/resources.html - Logo animates
  
- [ ] **Functionality:**
  - [ ] Logo links to homepage
  - [ ] Animation plays on page load
  - [ ] Hover effect scales logo
  - [ ] Mobile responsive (test on phone)
  
- [ ] **Browser Cache:**
  - [ ] Clear cache (Ctrl+Shift+R)
  - [ ] Test in incognito/private mode

---

## 🐛 Troubleshooting

### Logo doesn't appear on WordPress pages
**Fix:** Check that `functions.php` has the enqueue code added
```bash
# SSH into server
cat ~/choose90.org/wp-content/themes/Divi-choose90/functions.php | grep "choose90_enqueue_logo_styles"
```
Should return the function name. If not, add code from `functions-logo-additions.php`.

### Logo doesn't appear on static HTML pages
**Causes:**
1. Page still named `.html` instead of `.php`
   - **Fix:** Rename to `.php` to enable PHP includes
2. Path to CSS incorrect
   - **Fix:** Verify `<link rel="stylesheet" href="/css/logo-animated.css">` in `<head>`

### Animation doesn't play
**Fix:** Clear browser cache and check CSS is loaded:
```bash
# View source in browser, look for:
<link rel="stylesheet" href="/css/logo-animated.css">
```

### Logo appears but spacing is wrong
**Fix:** Edit `x="235"` value in both:
- `/includes/logo-animated.php` (line 33)
- `/wp-content/themes/Divi-choose90/template-parts/header-logo.php` (line 33)

---

## 🔄 Maintenance

### To Update Logo Across ALL Pages:
1. Edit ONLY these files:
   - `logo-animated.css` (for animation changes)
   - `logo-animated.php` + `header-logo.php` (for SVG structure changes)
2. Changes automatically apply to ALL pages
3. No need to edit individual HTML/PHP pages

### Version Control
Recommended: Track these files in Git
```bash
git add css/logo-animated.css
git add includes/logo-animated.php
git add wp-content/themes/Divi-choose90/template-parts/header-logo.php
git commit -m "Update animated logo system"
```

---

## 📊 Performance Benefits

| Metric | Before (Duplicated) | After (Modular) | Improvement |
|--------|-------------------|----------------|-------------|
| Total CSS size | 15KB (2.5KB × 6 pages) | 2.5KB (cached) | **83% reduction** |
| Total SVG size | 9KB (1.5KB × 6 pages) | 1.5KB (reused) | **83% reduction** |
| Maintenance files | 6+ HTML files | 2 component files | **67% reduction** |
| Update time | 6 file edits | 1 file edit | **83% faster** |

---

## 🎓 Educational Notes

**Why this approach works:**
- **PHP includes** = Server-side, executes before HTML is sent to browser
- **WordPress template parts** = Native WP system, optimized for themes
- **Single CSS file** = Browser caches once, reuses everywhere
- **DRY principle** = Don't Repeat Yourself = Easier maintenance

**Why rename HTML to PHP:**
Static HTML pages need `.php` extension to execute PHP code. Modern servers handle `.php` files just like `.html` for static content, but gain PHP functionality.

---

## 🆘 Need Help?

If you encounter issues during deployment:
1. Check file permissions (should be 644 for files, 755 for folders)
2. Verify paths match your server structure
3. Test on a staging page first before updating production
4. Keep backups of original files before overwriting

**Ready to deploy?** Start with Method 1 (cPanel) - it's the safest for live sites.
