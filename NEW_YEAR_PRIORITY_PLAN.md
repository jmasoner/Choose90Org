# Choose90 New Year's Priority Plan - AI Rewriting Focus
**Timeline:** December 26 - January 1, 2026 (6 days)
**Priority:** Browser Extension + PWA with AI Rewriting

## 🎯 THE KILLER FEATURES (Highest Impact)

### Feature 6: Browser Extension with AI Rewriting
**The "Muscle" Feature - Shows immediate Choose90 impact**

**What it does:**
- Detects when user is typing a post on Twitter/X, Facebook, LinkedIn, etc.
- Analyzes the text for negativity, complaints, or conflict
- Offers to rewrite it in a positive, uplifting way
- Shows side-by-side comparison: "Your post" vs "Choose90 version"
- One-click to replace or keep original

**Technical Stack:**
- Chrome/Edge Extension (Manifest V3)
- Content scripts to detect text inputs
- AI API endpoint (using existing DeepSeek integration)
- Beautiful UI overlay that doesn't interfere with typing

**Timeline:** 2-3 days
- Day 1: Extension structure, content script, basic detection
- Day 2: AI API integration, rewriting logic, UI overlay
- Day 3: Polish, testing on all platforms, user experience

---

### Feature 7: PWA Mobile App with AI Rewriting
**Mobile-first experience for on-the-go posting**

**What it does:**
- Installable Progressive Web App
- Quick post composer with Choose90 reminder
- AI rewriting feature (same as extension)
- Daily inspiration notifications
- Works offline (cached content)

**Technical Stack:**
- PWA (Service Worker, Web App Manifest)
- Responsive design (mobile-first)
- Same AI API as extension
- Push notifications (optional)

**Timeline:** 1-2 days
- Day 1: PWA structure, post composer, AI integration
- Day 2: Offline support, notifications, polish

---

## 🚀 Revised Timeline (6 Days)

### **Days 1-2: Browser Extension MVP**
- ✅ Extension manifest and structure
- ✅ Content script for Twitter/X, Facebook, LinkedIn
- ✅ Text detection and capture
- ✅ AI API endpoint for rewriting
- ✅ UI overlay with side-by-side comparison
- ✅ One-click replace functionality

### **Days 3-4: PWA Mobile App**
- ✅ PWA manifest and service worker
- ✅ Mobile-optimized post composer
- ✅ AI rewriting integration
- ✅ Daily inspiration feature
- ✅ Install prompt and offline support

### **Days 5-6: Polish & Launch**
- ✅ Cross-platform testing
- ✅ User onboarding flow
- ✅ Documentation and help
- ✅ Social sharing of the tools
- ✅ Analytics tracking

---

## 💡 Why This Approach Works

**The "Muscle" Concept:**
- Users SEE the difference immediately
- Not theoretical - they experience it
- Creates "aha!" moment that converts
- Builds habit through repeated use

**The Extension is the Hook:**
- People use social media daily
- Extension is always there, always helpful
- Creates brand awareness every time they post
- Word-of-mouth: "You have to try this extension!"

**The PWA is the Companion:**
- Mobile users can use it anywhere
- Quick access from home screen
- Complements the extension perfectly

---

## 🛠️ Technical Architecture

### AI Rewriting API Endpoint
**File:** `hybrid_site/api/rewrite-post.php`

**Function:**
- Takes original text
- Analyzes tone and content
- Rewrites in positive, uplifting way
- Maintains original intent
- Returns both versions for comparison

**AI Prompt Strategy:**
- "Rewrite this social media post to be 90% positive and uplifting while maintaining the original message and intent"
- Preserve facts, remove negativity
- Add constructive framing
- Keep it authentic, not fake

### Browser Extension Structure
```
browser-extension/
├── manifest.json          # Extension config
├── background.js          # Service worker
├── content/
│   ├── twitter.js         # Twitter/X detection
│   ├── facebook.js        # Facebook detection
│   ├── linkedin.js        # LinkedIn detection
│   └── common.js          # Shared logic
├── popup/
│   ├── popup.html         # Extension popup UI
│   ├── popup.js
│   └── popup.css
├── content-script.js      # Main content script
├── rewrite-overlay.html   # UI overlay for suggestions
├── rewrite-overlay.css
└── rewrite-overlay.js
```

### PWA Structure
```
hybrid_site/pwa/
├── index.html             # Main app
├── manifest.json          # PWA manifest
├── service-worker.js      # Offline support
├── app.js                 # Main app logic
├── composer.html          # Post composer
└── styles.css
```

---

## 🎨 User Experience Flow

### Browser Extension:
1. User starts typing a post on Twitter/X
2. Extension detects text input (after 50+ characters)
3. Shows subtle indicator: "✨ Choose90 can help make this more positive"
4. User clicks indicator or extension icon
5. Extension sends text to AI API
6. Shows overlay with:
   - Original post (left)
   - Rewritten version (right)
   - "Use this version" button
   - "Keep original" button
7. User chooses → text is replaced or kept

### PWA Mobile App:
1. User opens PWA from home screen
2. Sees daily Choose90 inspiration quote
3. Taps "Compose Post"
4. Types their post
5. Taps "Make it Choose90" button
6. AI rewrites it
7. User can edit, share, or save

---

## 🔒 Privacy & Security

- **No data storage:** Text is sent to API, rewritten, returned - not stored
- **User control:** Always opt-in, user chooses to use rewrite
- **Transparency:** Clear about what AI is doing
- **Rate limiting:** Prevent abuse of API
- **CORS:** Secure API endpoints

---

## 📊 Success Metrics

- Extension installs
- Rewrites used per day
- User retention (daily active users)
- Conversion to Choose90 pledge
- Social shares mentioning Choose90

---

**Ready to build? Let's start with the Browser Extension - it's the game-changer!**


