# Choose90 Browser Extension

**Make your social media posts 90% positive, uplifting, and constructive.**

This browser extension detects when you're writing a post on Twitter/X, Facebook, or LinkedIn and offers to rewrite it in a more positive way while maintaining your original message.

## 🚀 Installation

### For Chrome/Edge (Chromium-based browsers):

**✅ Edge is FULLY SUPPORTED!** Edge uses the same Chromium engine as Chrome, so the extension works identically.

1. **Download the extension:**
   - Clone this repository or download the `browser-extension` folder

2. **Open Extensions page:**
   - **Chrome**: Go to `chrome://extensions/`
   - **Edge**: Go to `edge://extensions/`
   - Or: Menu → Extensions → Manage Extensions

3. **Enable Developer Mode:**
   - Toggle "Developer mode" switch in the top right

4. **Load the extension:**
   - Click "Load unpacked"
   - Select the `browser-extension` folder
   - The extension should now appear in your extensions list

5. **Pin the extension (optional):**
   - **Chrome**: Click the puzzle piece icon in your browser toolbar
   - **Edge**: Click the extensions icon (puzzle piece) in your browser toolbar
   - Find "Choose90" and click the pin icon

### For Firefox:

Firefox uses Manifest V2, so the extension needs to be adapted. This version is currently Chrome/Edge only.

## 📝 How to Use

1. **Go to Twitter/X, Facebook, or LinkedIn**
2. **Start typing a post** (at least 50 characters)
3. **Look for the "✨ Choose90 can help make this more positive" indicator**
4. **Click the indicator** to see your post rewritten
5. **Compare** your original post with the Choose90 version
6. **Choose** to use the rewritten version or keep your original

## ⚙️ Settings

Click the extension icon to access settings:

- **Enable Choose90:** Turn the extension on/off
- **Auto-detect posts:** Automatically detect when you're writing a post
- **Minimum length:** Set the minimum character count before suggestions appear (default: 50)

## 🎨 Features

- **AI-Powered Rewriting:** Uses Choose90's AI to rewrite posts in a positive, uplifting way
- **Platform Support:** Works on Twitter/X, Facebook, and LinkedIn
- **Non-Intrusive:** Only appears when you're writing longer posts
- **Privacy-Focused:** Your posts are sent to Choose90's API for rewriting but not stored
- **One-Click Replace:** Easily replace your post with the rewritten version

## 🔒 Privacy

- Your posts are sent to `https://choose90.org/api/rewrite-post.php` for processing
- Posts are not stored or logged
- No personal information is collected
- All communication is encrypted (HTTPS)

## 🛠️ Development

### File Structure:
```
browser-extension/
├── manifest.json          # Extension configuration
├── background.js          # Service worker
├── content-script.js      # Main content script
├── content-styles.css     # Styles for overlay
├── popup/
│   ├── popup.html        # Settings popup
│   └── popup.js          # Popup logic
└── icons/                # Extension icons (16x16, 48x48, 128x128)
```

### Testing:

1. Make changes to the extension files
2. Go to `chrome://extensions/`
3. Click the refresh icon on the Choose90 extension
4. Test on Twitter/X, Facebook, or LinkedIn

## 📦 Building for Distribution

1. Create icons in `icons/` folder:
   - `icon16.png` (16x16 pixels)
   - `icon48.png` (48x48 pixels)
   - `icon128.png` (128x128 pixels)

2. Zip the `browser-extension` folder (excluding `README.md`)

3. For Chrome Web Store:
   - Go to [Chrome Web Store Developer Dashboard](https://chrome.google.com/webstore/devconsole)
   - Upload the zip file
   - Fill in store listing details

## 🐛 Troubleshooting

**Extension not working:**
- Make sure it's enabled in `chrome://extensions/`
- Check that you're on a supported platform (Twitter/X, Facebook, LinkedIn)
- Try refreshing the page

**No suggestions appearing:**
- Check that your post is at least 50 characters (or your custom minimum)
- Make sure "Auto-detect posts" is enabled in settings
- Try clicking the extension icon to manually trigger

**API errors:**
- Check that `https://choose90.org/api/rewrite-post.php` is accessible
- Check browser console for error messages

## 📄 License

Part of the Choose90.org project.

## 🤝 Support

Visit [https://choose90.org](https://choose90.org) for more information.

---

**Made with ❤️ for a more positive internet**

