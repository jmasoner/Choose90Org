# Resources Page Deployment Guide

## 📦 What's Included

You now have **three related files** for your Resources page:

1. **`resources.html`** (22KB) - **Standalone HTML page** for your hybrid site
2. **`digital-detox-guide-PRODUCTION.html`** (39KB) - Interactive detox guide
3. **`Choose90-Digital-Detox-Guide.pdf`** (21KB) - Downloadable PDF version

---

## 🎯 What Does `resources.html` Do?

Your new Resources page:

✅ **Matches the Digital Detox Guide design** - Same blue/yellow colors, animated logo, navigation  
✅ **Displays two resource cards**:
   - 📄 Digital Detox Guide (PDF download)
   - 🌐 Interactive Digital Detox Guide (web version)
✅ **Includes a "Coming Soon" card** for future resources  
✅ **CTA section** - Encourages users to take the pledge or find chapters  
✅ **Responsive design** - Looks great on desktop, tablet, mobile  
✅ **Scroll animations** - Cards fade in as you scroll  

---

## 🚀 Deployment Options

### **Option A: Quick Upload via cPanel (Recommended)**

#### Step 1: Download Files
1. [Download resources.html](computer:///mnt/user-data/outputs/resources.html) (22KB)
2. [Download digital-detox-guide-PRODUCTION.html](computer:///mnt/user-data/outputs/digital-detox-guide-PRODUCTION.html) (39KB)
3. [Download Choose90-Digital-Detox-Guide.pdf](computer:///mnt/user-data/outputs/Choose90-Digital-Detox-Guide.pdf) (21KB)

#### Step 2: Log into cPanel
- Go to your hosting control panel
- Find **File Manager** icon
- Navigate to `/home/constit2/choose90.org`

#### Step 3: Upload HTML Files
1. In the main directory (`/home/constit2/choose90.org`):
   - Upload `resources.html`
   - Upload `digital-detox-guide-PRODUCTION.html`
   - **Rename** `digital-detox-guide-PRODUCTION.html` to `digital-detox-guide.html`

#### Step 4: Upload PDF
1. Check if `resources/` folder exists:
   - If not, click **+ Folder** → Name it `resources` → Create
2. Open the `resources/` folder
3. Upload `Choose90-Digital-Detox-Guide.pdf`

#### Step 5: Test
Visit these URLs to verify:
- `https://choose90.org/resources.html` (Resources page)
- `https://choose90.org/digital-detox-guide.html` (Interactive guide)
- `https://choose90.org/resources/Choose90-Digital-Detox-Guide.pdf` (PDF)

---

### **Option B: Using SSH Terminal**

```bash
# Step 1: Connect to server
ssh constit2@mi3-ts7.hosting.com

# Step 2: Navigate to website directory
cd ~/choose90.org

# Step 3: Create resources directory (if needed)
mkdir -p resources

# Step 4: Upload files from local machine (run from YOUR computer)
scp resources.html constit2@mi3-ts7.hosting.com:/home/constit2/choose90.org/
scp digital-detox-guide-PRODUCTION.html constit2@mi3-ts7.hosting.com:/home/constit2/choose90.org/digital-detox-guide.html
scp Choose90-Digital-Detox-Guide.pdf constit2@mi3-ts7.hosting.com:/home/constit2/choose90.org/resources/

# Step 5: Set permissions (back in SSH on server)
chmod 644 resources.html
chmod 644 digital-detox-guide.html
chmod 644 resources/Choose90-Digital-Detox-Guide.pdf
```

---

### **Option C: Using PowerShell Deployment Script**

#### Step 1: Download files to your local project folder
Place downloaded files in: `C:\Users\john\OneDrive\MyProjects\Choose90Org\hybrid_site\`

#### Step 2: Update your deployment script
Edit `deploy_hybrid_site.ps1` and add these lines:

```powershell
# Deploy Resources Page
Write-Host "Deploying Resources page..." -NoNewline
Copy-Item -Path ".\resources.html" -Destination "Z:\resources.html" -Force
Write-Host " [OK]" -ForegroundColor Green

# Deploy Interactive Detox Guide
Write-Host "Deploying Interactive Detox Guide..." -NoNewline
Copy-Item -Path ".\digital-detox-guide.html" -Destination "Z:\digital-detox-guide.html" -Force
Write-Host " [OK]" -ForegroundColor Green

# Create resources folder if needed
if (!(Test-Path "Z:\resources")) {
    New-Item -Path "Z:\resources" -ItemType Directory
}

# Deploy PDF
Write-Host "Deploying PDF..." -NoNewline
Copy-Item -Path ".\resources\Choose90-Digital-Detox-Guide.pdf" -Destination "Z:\resources\Choose90-Digital-Detox-Guide.pdf" -Force
Write-Host " [OK]" -ForegroundColor Green
```

#### Step 3: Run deployment
```powershell
.\deploy_hybrid_site.ps1
```

---

## 📋 Post-Deployment Checklist

### Desktop Testing:
- [ ] Resources page loads at `https://choose90.org/resources.html`
- [ ] Animated logo plays on page load
- [ ] Both resource cards display correctly
- [ ] PDF download button works
- [ ] Interactive guide link goes to `/digital-detox-guide.html`
- [ ] "Coming Soon" card displays properly
- [ ] CTA buttons work (Take the Pledge, Find Your Chapter)
- [ ] Footer links work

### Mobile Testing:
- [ ] Logo scales properly
- [ ] Resource cards stack vertically
- [ ] Text is readable
- [ ] Buttons are tappable
- [ ] Navigation works

### Link Testing:
- [ ] PDF downloads: `https://choose90.org/resources/Choose90-Digital-Detox-Guide.pdf`
- [ ] Interactive guide loads: `https://choose90.org/digital-detox-guide.html`
- [ ] Navigation links work from Resources page

---

## 🔗 Update Your Navigation

Now that you have a Resources page, update your site navigation:

### Update `index.html`:
Find the navigation section and change:
```html
<!-- OLD: -->
<li><a href="/resources/">Resources</a></li>

<!-- NEW: -->
<li><a href="resources.html">Resources</a></li>
```

### Update `about.html` (if you have one):
Same change - link to `resources.html` instead of `/resources/`

---

## 🎨 Design Features

Your new Resources page includes:

### **Visual Design:**
- 🎨 Gradient hero section (blue #0066CC)
- 💎 Glass-effect resource cards with hover animations
- 🌈 Color-coded icons (blue for PDF, yellow for interactive)
- 📏 Top gradient border appears on hover
- ✨ Icon rotation effect on hover

### **Interactive Elements:**
- 📜 Fade-in animations as you scroll
- 🎯 Card lift effect on hover
- 📱 Fully responsive grid layout
- 🔗 Call-to-action section at bottom

### **Brand Consistency:**
- ✅ Uses your exact brand colors (#0066CC blue, #E8B93E yellow)
- ✅ Matches Digital Detox Guide design language
- ✅ Includes your animated Choose90 logo
- ✅ Same typography (Outfit + Inter fonts)

---

## 🔧 Troubleshooting

### Problem: Resources page shows as plain text
**Solution:** Verify filename is `resources.html` (not `.txt`) and it's in the root directory.

### Problem: PDF download gives 404 error
**Solution:** 
1. Check that PDF is at: `/home/constit2/choose90.org/resources/Choose90-Digital-Detox-Guide.pdf`
2. Verify folder name is exactly `resources` (lowercase, no spaces)
3. Check file permissions: `chmod 644 Choose90-Digital-Detox-Guide.pdf`

### Problem: Interactive guide link broken
**Solution:**
1. Verify `digital-detox-guide.html` exists in root directory
2. Check that you renamed `digital-detox-guide-PRODUCTION.html` correctly
3. Test URL directly: `https://choose90.org/digital-detox-guide.html`

### Problem: Logo doesn't animate
**Solution:**
1. Check that `style.css` is in the same directory
2. Clear browser cache (Ctrl+Shift+Delete)
3. Check browser console (F12) for errors

### Problem: Cards don't fade in on scroll
**Solution:**
1. Verify `script.js` is accessible
2. Check browser console for JavaScript errors
3. Test in different browsers (Chrome, Firefox, Safari)

---

## 📝 Next Steps

After deploying the Resources page:

### 1. Update Homepage
Add a Resources section or link to `resources.html`:
```html
<section class="resources-preview">
    <div class="container">
        <h2>Resources</h2>
        <p>Free guides and tools to support your journey</p>
        <a href="resources.html" class="btn btn-primary">View All Resources</a>
    </div>
</section>
```

### 2. Create Resources Navigation Menu
Update your main navigation to highlight Resources:
```html
<li><a href="resources.html">Resources</a></li>
```

### 3. Add More Resources (Future)
To add new resources, edit `resources.html`:
1. Copy an existing `.resource-card` div
2. Update the icon, title, description, and link
3. Add the actual file to your server
4. Re-deploy

### 4. Set Up Analytics
Track resource downloads and page views:
- Resources page visits
- PDF downloads
- Interactive guide engagement

### 5. Promote Your Resources
- Share on social media using your graphics kit
- Email your community
- Add to newsletter/blog posts

---

## 📂 File Structure

After deployment, your server should look like this:

```
/home/constit2/choose90.org/
├── index.html
├── about.html
├── resources.html ← NEW
├── digital-detox-guide.html ← NEW
├── style.css
├── script.js
├── resources/ ← NEW FOLDER
│   └── Choose90-Digital-Detox-Guide.pdf ← NEW
└── (other files...)
```

---

## ✅ Success Criteria

You'll know deployment succeeded when:

- ✅ `https://choose90.org/resources.html` loads correctly
- ✅ Both resource cards display with proper styling
- ✅ PDF downloads when clicking "Download PDF Guide"
- ✅ Interactive guide opens when clicking "Start Interactive Guide"
- ✅ Logo animates on page load
- ✅ Cards fade in as you scroll down
- ✅ CTA buttons link to correct pages
- ✅ Mobile responsive design works (resize browser to test)
- ✅ No console errors in browser dev tools (F12)

---

## 🆘 Need Help?

If you run into issues:

1. **Check file paths** - Are files in the correct directories?
2. **Verify permissions** - Files should be 644, folders 755
3. **Clear cache** - Browser cache can cause old versions to display
4. **Test in incognito** - Eliminates cache/cookie issues
5. **Check error logs** - cPanel → Error Log for server errors

---

## 🎉 What You've Accomplished

With this deployment, you now have:

✅ A **professional Resources hub** for your movement  
✅ A **downloadable PDF guide** for offline reading  
✅ An **interactive web guide** with progress tracking  
✅ A **scalable structure** for adding more resources in the future  
✅ **Full brand consistency** across all pages  
✅ **Mobile-responsive design** that works everywhere  

**Congratulations!** Your Choose90 Resources page is production-ready. 🚀

---

**Version**: 1.0  
**Created**: 2025-12-12  
**For**: Choose90.org Hybrid Site Deployment

**Questions?** Let me know which deployment method you're using, and I'll walk you through it step-by-step!