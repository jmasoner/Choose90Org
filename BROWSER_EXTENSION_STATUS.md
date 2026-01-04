# Browser Extension Status - Current State

**Date:** December 28, 2025  
**Status:** ⚠️ **PENDING TESTING** - Extension needs to be reinstalled and tested after computer restart

## Current Issues

### 1. Button Click Handlers Not Working
- **Problem:** Close button (X), "Keep Original", and "Use This Version" buttons don't respond to clicks
- **Status:** Multiple fixes attempted, latest approach uses global functions + data attributes
- **Location:** `browser-extension/content-script.js` - `showRewriteComparison()` function

### 2. API Connection Error
- **Problem:** "Failed to connect to Choose90" error
- **Status:** 
  - ✅ `secrets.json` exists at server root (`Z:\secrets.json`)
  - ✅ `rewrite-post.php` exists at `Z:\api\rewrite-post.php`
  - ✅ Path resolution code updated to check multiple paths
  - ⚠️ Still getting "Configuration not found" or connection errors

## Latest Changes Made

### Button Handler Fix (Latest Attempt)
**File:** `browser-extension/content-script.js`

**Approach:**
- Using global functions on `window` object (`window.choose90Overlays`, `window.choose90HandleButtonClick`)
- Data attributes on buttons (`data-action`, `data-overlay`)
- Event delegation on overlay container
- Both `click` and `mousedown` event listeners
- Maximum z-index (999999) and pointer-events styling

**Code Location:** Lines ~228-360 in `content-script.js`

### API Path Fix
**File:** `hybrid_site/api/rewrite-post.php`

**Changes:**
- Checks multiple possible paths for `secrets.json`:
  1. `../secrets.json` (from api/ to server root - PRIMARY)
  2. `../../secrets.json` (for local dev)
  3. `DOCUMENT_ROOT/secrets.json` (fallback)
- Uses `realpath()` to resolve actual file paths

**Status:** ✅ Deployed to server

### Error Popup Fix
**File:** `browser-extension/content-script.js`

**Changes:**
- `showError()` function now creates button element separately
- Event listener attached before appending to DOM
- Added Escape key handler
- Added click-outside-to-close

### Link Update
**File:** `browser-extension/content-script.js`

**Change:** "Learn more about Choose90" link changed from `/pledge/` to homepage `/`

## Next Steps After Restart

### 1. Test Extension Buttons
1. **Completely remove extension:**
   - Go to `chrome://extensions/` (or `edge://extensions/`)
   - Find "Choose90 - Make Your Posts Positive"
   - Click **REMOVE** (trash icon)

2. **Close browser completely** (not just tabs)

3. **Reopen browser and reinstall:**
   - Go to `chrome://extensions/`
   - Enable "Developer mode"
   - Click "Load unpacked"
   - Select: `C:\Users\john\OneDrive\MyProjects\Choose90Org\browser-extension`

4. **Test on Facebook:**
   - Go to Facebook
   - Start typing a post (50+ characters)
   - Click the Choose90 indicator
   - Test all buttons:
     - Close button (X)
     - Keep Original button
     - Use This Version button
     - Click outside overlay
     - Press Escape key

### 2. Debug API Connection Error
If still getting "Failed to connect" or "Configuration not found":

1. **Check browser console:**
   - Press F12
   - Go to Console tab
   - Look for error messages

2. **Check Network tab:**
   - Press F12
   - Go to Network tab
   - Try rewrite feature
   - Look for request to `rewrite-post.php`
   - Check response/error

3. **Test API directly:**
   - Visit: `https://choose90.org/api/test-secrets-path.php`
   - This will show which paths exist and which one to use
   - **DELETE this file after testing!**

4. **Verify secrets.json on server:**
   - Check that `secrets.json` is at server root
   - Verify it contains DeepSeek API key
   - Check file permissions (should be readable by PHP)

## Files Modified

1. ✅ `browser-extension/content-script.js` - Button handlers, error popup, link update
2. ✅ `hybrid_site/api/rewrite-post.php` - Path resolution for secrets.json
3. ✅ `hybrid_site/api/test-secrets-path.php` - Debug tool (DELETE after testing)

## Known Working Features

- ✅ Extension detects text inputs on Facebook
- ✅ Shows "Choose90 can help" indicator
- ✅ API successfully rewrites posts (when connection works)
- ✅ Rewrite comparison overlay displays correctly
- ✅ "Learn more" link works (goes to homepage)

## Known Issues

- ❌ Buttons don't respond to clicks (multiple fix attempts made)
- ❌ API connection sometimes fails with "Configuration not found"
- ⚠️ Extension needs complete reinstall to pick up changes

## Testing Checklist

After restart and reinstall:

- [ ] Extension loads without errors
- [ ] Indicator appears when typing 50+ characters
- [ ] Rewrite request succeeds (no "Failed to connect" error)
- [ ] Comparison overlay displays correctly
- [ ] Close button (X) works
- [ ] Keep Original button works
- [ ] Use This Version button works and inserts text
- [ ] Click outside overlay closes it
- [ ] Escape key closes overlay
- [ ] Learn more link goes to homepage

## Debug Commands

If buttons still don't work, check console for:
```javascript
// In browser console, try:
window.choose90Overlays
window.choose90HandleButtonClick
```

If these are undefined, the global functions aren't being created.

## Notes

- Extension uses Manifest V3
- Content Security Policy blocks inline event handlers
- Facebook may be intercepting click events
- Latest approach uses global functions to work around CSP restrictions
- Event delegation with capture phase should catch events before Facebook handlers