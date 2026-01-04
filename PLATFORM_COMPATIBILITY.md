# Choose90 Platform Compatibility Guide

## ✅ PWA (Progressive Web App) - Mobile Solution

### **Android** ✅ Full Support
- **Chrome**: ✅ Full PWA support, installable
- **Firefox**: ✅ Full PWA support, installable
- **Samsung Internet**: ✅ Full PWA support, installable
- **Edge Mobile**: ✅ Full PWA support, installable (Edge on Android also supports PWAs)

**How to Install on Android:**
1. Visit `https://choose90.org/pwa/` in Chrome/Firefox
2. Browser will show "Add to Home Screen" prompt
3. Or: Menu → "Add to Home Screen"
4. App appears like a native app

**Features on Android:**
- ✅ Full screen experience (standalone mode)
- ✅ Offline support (service worker)
- ✅ Push notifications (can be added)
- ✅ App shortcuts
- ✅ Works like native app

---

### **iPhone (iOS)** ✅ Full Support
- **Safari**: ✅ Full PWA support since iOS 11.3
- **Chrome iOS**: ⚠️ Uses Safari engine, PWA works but limited
- **Firefox iOS**: ⚠️ Uses Safari engine, PWA works but limited

**How to Install on iPhone:**
1. Visit `https://choose90.org/pwa/` in Safari
2. Tap the Share button (square with arrow)
3. Scroll down and tap "Add to Home Screen"
4. App appears like a native app

**Features on iPhone:**
- ✅ Full screen experience (standalone mode)
- ✅ Offline support (service worker)
- ✅ App icon on home screen
- ✅ Works like native app
- ⚠️ Push notifications: Limited (requires iOS 16.4+)
- ⚠️ App shortcuts: Limited support

**iOS-Specific Notes:**
- Service workers work on iOS 11.3+
- Offline caching works
- "Add to Home Screen" works perfectly
- Some advanced PWA features are limited compared to Android

---

## 🖥️ Browser Extension - Desktop Solution

### **Desktop Browsers** ✅ Full Support
- **Chrome (Windows/Mac/Linux)**: ✅ Full support
- **Edge (Windows/Mac/Linux)**: ✅ **FULL SUPPORT** - Edge is Chromium-based, works identically to Chrome
- **Brave**: ✅ Full support (Chromium-based)
- **Opera**: ✅ Full support (Chromium-based)

**Edge Installation:**
1. Go to `edge://extensions/`
2. Enable "Developer mode"
3. Click "Load unpacked"
4. Select the `browser-extension` folder
5. Done! Works exactly like Chrome.

**How to Install:**
1. Go to `chrome://extensions/` or `edge://extensions/`
2. Enable "Developer mode"
3. Click "Load unpacked"
4. Select the `browser-extension` folder

**Features:**
- ✅ Detects posts on Twitter/X, Facebook, LinkedIn
- ✅ Shows rewrite suggestions
- ✅ One-click to replace text
- ✅ Settings popup

---

### **Mobile Browsers** ⚠️ Limited Support

**Android Chrome:**
- ⚠️ Extensions work but experience is different
- ⚠️ Not as seamless as desktop
- ✅ Can be installed but limited functionality

**iPhone Safari:**
- ❌ **No extension support** - iOS doesn't allow browser extensions
- ✅ Use PWA instead (recommended)

---

## 📱 Recommended Setup by Platform

### **For iPhone Users:**
✅ **Use the PWA** - It's the perfect solution!
- Install from Safari
- Works like a native app
- Full AI rewriting features
- Offline support

### **For Android Users:**
✅ **Use the PWA** - Best mobile experience!
- Install from Chrome/Firefox
- Works like a native app
- Full AI rewriting features
- Offline support

### **For Desktop Users:**
✅ **Use the Browser Extension** - Best desktop experience!
- Works on Twitter/X, Facebook, LinkedIn
- Automatic detection
- Non-intrusive overlay

---

## 🎯 Cross-Platform Strategy

### **Desktop:**
- Browser Extension (Chrome/Edge)
- Works automatically when typing posts

### **Mobile (Both Android & iPhone):**
- PWA (Progressive Web App)
- Installable from browser
- Works like native app
- Full feature parity

### **Why This Works:**
- **Desktop users** get the extension (seamless integration)
- **Mobile users** get the PWA (native-like experience)
- **Everyone** gets the AI rewriting "muscle" feature
- **No app store approval needed** (PWA installs directly)

---

## 🔧 Technical Details

### PWA Compatibility:
- **Android**: Full PWA support since Chrome 67+ (2018)
- **iOS**: Full PWA support since iOS 11.3 (2018)
- **Service Workers**: Supported on both platforms
- **Offline Support**: Works on both platforms
- **Install Prompts**: Work on both platforms

### Browser Extension Compatibility:
- **Chrome/Edge**: Manifest V3 (current standard)
- **Firefox**: Would need Manifest V2 version (not built yet)
- **Safari**: Not supported (Apple doesn't allow extensions)

---

## ✅ Summary

**PWA works on BOTH Android and iPhone!** ✅

- Android: Full support, installable from Chrome/Firefox
- iPhone: Full support, installable from Safari
- Both: Works like a native app, offline support, full features

**Browser Extension:**
- Desktop: Full support (Chrome/Edge)
- Mobile: Limited (not recommended for mobile)

**Recommendation:**
- **Desktop users**: Use Browser Extension
- **Mobile users (Android & iPhone)**: Use PWA

Both solutions provide the same core "muscle" feature - AI-powered post rewriting!

