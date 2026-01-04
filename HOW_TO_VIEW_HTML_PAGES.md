# How to View HTML Pages in Cursor

## 🚀 Quick Method: Open in Browser

### Option 1: Use the Preview Script
I've created a PowerShell script that opens all pages at once:

```powershell
.\preview-pages.ps1
```

This will open all HTML pages in your default browser.

### Option 2: Open Individual Files
Right-click any HTML file in Cursor's file explorer → **"Reveal in File Explorer"** → Double-click to open in browser.

### Option 3: Use Cursor's Built-in Preview
1. Open an HTML file in Cursor
2. Right-click in the editor
3. Look for **"Open Preview"** or **"Open in Browser"** option
4. Or use keyboard shortcut (varies by OS)

## 🌐 View on Server (Full Functionality)

For pages with API calls (Content Generator, Pledge Wall), you'll need to view them on the server:

1. **Deploy files:**
   ```powershell
   .\deploy_hybrid_site.ps1
   ```

2. **Visit URLs:**
   - New Year's Resolution: `https://choose90.org/new-years-resolution.html`
   - Pledge Wall: `https://choose90.org/pledge-wall.html`
   - Content Generator: `https://choose90.org/tools/content-generator.html`
   - 30-Day Challenge: `https://choose90.org/resources/30-day-choose90-challenge.html`
   - Installation Guide: `https://choose90.org/pwa.html`

## 📋 All Available Pages

### Main Pages:
- `hybrid_site/new-years-resolution.html` - New Year's Resolution landing page
- `hybrid_site/pledge-wall.html` - Interactive pledge wall with counter
- `hybrid_site/pwa.html` - Installation guide for extension & PWA

### Tools:
- `hybrid_site/tools/content-generator.html` - AI content generator

### Resources:
- `hybrid_site/resources/30-day-choose90-challenge.html` - 30-day challenge

### Kwanzaa Pages:
- `hybrid_site/kwanzaa-choose90.html` - Kwanzaa landing page
- `hybrid_site/kwanzaa-challenge.html` - 7-day Kwanzaa challenge

## 💡 Tips

1. **Local Preview:** Works for static content, but API features won't work
2. **Server Preview:** Full functionality, but requires deployment
3. **Browser Dev Tools:** Press F12 to inspect and debug
4. **Mobile Preview:** Use browser's device emulation (F12 → Device Toolbar)

## 🔧 If Pages Don't Load Properly

**Missing CSS/JS:**
- Make sure `style.css` is in the same directory or adjust paths
- Check browser console (F12) for errors

**API Errors:**
- These will only work on the server where API endpoints exist
- Local preview will show errors - that's normal!

**Images Not Loading:**
- Check image paths are correct
- Some images may need to be deployed to server


