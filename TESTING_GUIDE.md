# Choose90 Testing Guide - Current Status & How to Test

## 📍 Where We Are

### ✅ Recently Completed Features

1. **Resources Hub with Category Filtering**
   - Category submenu (All, Tools, Challenges, Guides, Quick Wins)
   - Quick Wins section populated
   - Removed duplicate entries
   - Fixed empty section dividers

2. **Navigation Updates**
   - Tools menu added to Resources dropdown
   - New Year's and Kwanzaa pages linked in multiple locations
   - Top banner below menu (2026 update)

3. **Pledge Wall**
   - Social sharing buttons (Twitter, Facebook, LinkedIn, Instagram)
   - Social handle capture for CRM
   - Newsletter signup forms
   - Seeded counter (3,363+)

4. **Campaign Pages**
   - New Year's Resolution landing page
   - Kwanzaa & Choose90 landing page
   - 7-Day Kwanzaa Challenge
   - Digital Detox Guide in footer campaigns

5. **Analytics Setup**
   - GA4 Measurement ID configured: `G-9M6498Y7W4`
   - Analytics snippet component created
   - Event tracking helper functions
   - Configuration files secured (.gitignore)

6. **Tools**
   - Content Generator (AI-powered)
   - Browser Extension & PWA installation page
   - Accessible via Resources dropdown

### 🚧 Ready for Grok (Prompts Created)

1. **Full Analytics Implementation** (`GROK_ANALYTICS_PROMPT.md`)
   - Event tracking across all pages
   - Integration with existing JavaScript

2. **Analytics Dashboard** (`GROK_DASHBOARD_PROMPT.md`)
   - Real-time metrics
   - Social media performance
   - SEO tracking

3. **Interactive Directory** (`GROK_INTERACTIVE_DIRECTORY_PROMPT.md`)
   - User-contributed directory
   - Chapter integration
   - Community resource building

---

## 🧪 How to Test What We Have

### 1. Resources Hub Testing

**URL**: https://choose90.org/resources-hub.html

**What to Test:**
- [ ] Page loads correctly
- [ ] Category submenu appears at top
- [ ] "All Resources" shows all resources
- [ ] "Tools" filter shows only Tools (Content Generator, Browser Extension)
- [ ] "Challenges" filter shows only Challenges (no duplicate Digital Detox)
- [ ] "Guides" filter shows comprehensive guides
- [ ] "Quick Wins" filter shows 4 resources (Phone Setup, Conversation Starter, Conflict Translator, Small Acts)
- [ ] No empty section dividers appear in "All Resources" view
- [ ] Resource cards are clickable and link correctly
- [ ] Newsletter signup form works at bottom
- [ ] Mobile responsive (test on phone)

**Expected Results:**
- Quick Wins shows 4 resources
- No duplicate Digital Detox in Challenges
- Tools section appears only when "Tools" category selected
- Challenges section appears only when "Challenges" category selected

---

### 2. Navigation & Menu Testing

**URLs**: All pages (homepage, about, resources, etc.)

**What to Test:**
- [ ] Main navigation menu displays correctly
- [ ] "Resources" dropdown shows Tools section
- [ ] "Tools" in Resources dropdown links to Content Generator
- [ ] "Browser Extension & PWA" links to /pwa.html
- [ ] "Pledge" dropdown includes New Year's Resolution
- [ ] Top banner appears below menu on desktop
- [ ] Banner says "Start 2026 with intention!"
- [ ] Banner can be dismissed (checks localStorage)
- [ ] Mobile menu works (hamburger icon)

**Expected Results:**
- Tools accessible via Resources → Tools
- All dropdown menus function correctly
- Banner positioned correctly below menu

---

### 3. Pledge Wall Testing

**URL**: https://choose90.org/pledge-wall.html

**What to Test:**
- [ ] Page loads
- [ ] Pledge counter shows "3,363+" (or higher if updated)
- [ ] Social sharing buttons visible (Twitter/X, Facebook, LinkedIn, Instagram)
- [ ] Clicking social share buttons opens share dialogs
- [ ] Social handle capture form appears below sharing buttons
- [ ] Newsletter signup form visible
- [ ] Can submit pledge (if form functional)
- [ ] Success message shows after pledge submission
- [ ] Newsletter signup form in success message works

**Expected Results:**
- No "Recent Pledges" or "Be the first to share" messages
- Social sharing buttons functional
- Newsletter signup accessible

---

### 4. Tools Testing

**Content Generator**
**URL**: https://choose90.org/tools/content-generator.html

**What to Test:**
- [ ] Page loads
- [ ] Can generate post suggestions
- [ ] Can generate quotes
- [ ] AI responses appear
- [ ] Copy buttons work
- [ ] Form validation works

**Browser Extension/PWA Page**
**URL**: https://choose90.org/pwa.html

**What to Test:**
- [ ] Page loads
- [ ] Download link for extension ZIP works
- [ ] Installation instructions clear
- [ ] Mobile app section visible
- [ ] Links to other pages work

**Expected Results:**
- Content Generator produces AI responses
- Extension download available
- All instructions clear

---

### 5. Campaign Pages Testing

**New Year's Resolution**
**URL**: https://choose90.org/new-years-resolution.html

**What to Test:**
- [ ] Page loads
- [ ] Content displays correctly
- [ ] Links work
- [ ] Shows "2026" not "2025"

**Kwanzaa Pages**
**URLs**: 
- https://choose90.org/kwanzaa-choose90.html
- https://choose90.org/kwanzaa-challenge.html

**What to Test:**
- [ ] Both pages load
- [ ] Content displays correctly
- [ ] Links work
- [ ] Challenge days functional (if interactive)

**Digital Detox Guide**
**URL**: https://choose90.org/digital-detox-guide.html

**What to Test:**
- [ ] Page loads
- [ ] Interactive elements work
- [ ] Progress tracking functional

---

### 6. Footer Testing

**All Pages**

**What to Test:**
- [ ] Footer displays on all pages
- [ ] "Campaigns" section includes:
  - New Year's Resolution
  - Kwanzaa & Choose90
  - 7-Day Kwanzaa Challenge
  - Digital Detox Guide
- [ ] Newsletter signup form in footer works
- [ ] All footer links work

**Expected Results:**
- Digital Detox Guide in Campaigns section
- All links functional

---

### 7. Analytics Testing

**GA4 Configuration**

**What to Test:**
- [ ] Visit any page on choose90.org
- [ ] Open browser DevTools (F12)
- [ ] Go to Network tab
- [ ] Filter for "gtag" or "collect"
- [ ] Should see requests to `google-analytics.com/g/collect`
- [ ] Check GA4 Real-Time reports (https://analytics.google.com)
- [ ] Should see your visit in Real-Time

**Analytics Files**

**What to Check:**
- [ ] `Z:\components\analytics-snippet.html` contains Measurement ID `G-9M6498Y7W4`
- [ ] `Z:\js\analytics.js` contains Measurement ID `G-9M6498Y7W4`
- [ ] Analytics snippet loaded in page source

**How to Verify in GA4:**
1. Go to https://analytics.google.com
2. Select your Choose90 property
3. Go to Reports → Real-time
4. Visit choose90.org in another tab
5. Should see yourself as an active user in Real-time

---

### 8. WordPress Pledge Page Testing

**URL**: https://choose90.org/pledge/

**What to Test:**
- [ ] Page loads (not 404 or broken)
- [ ] Uses correct template (page-pledge.php)
- [ ] Pledge form displays (if not logged in)
- [ ] Welcome message shows (if logged in)
- [ ] Form submission works
- [ ] Navigation menu displays correctly
- [ ] Tools accessible from Resources dropdown

**Troubleshooting:**
- If page shows wrong template: Run `fix-wordpress-pages-now.php` or set template manually in WordPress admin
- If page 404: Check permalinks settings, flush rewrite rules

---

### 9. Mobile Responsiveness Testing

**All Pages**

**What to Test:**
- [ ] Open site on mobile device or resize browser
- [ ] Navigation menu collapses to hamburger icon
- [ ] Dropdown menus work on mobile (tap to open)
- [ ] All content readable (no horizontal scrolling)
- [ ] Buttons large enough to tap
- [ ] Forms usable on mobile
- [ ] Images scale correctly

**Expected Results:**
- No horizontal scrolling
- All functionality works on mobile
- Text readable without zooming

---

### 10. Newsletter Signup Testing

**Locations to Test:**
- Footer (all pages)
- Pledge wall
- Resources page
- Pledge success message

**What to Test:**
- [ ] Form displays
- [ ] Can enter email
- [ ] Submit button works
- [ ] Success message appears
- [ ] Email actually received (check Mailchimp/ConvertKit)
- [ ] Form validation (requires email)

**Expected Results:**
- Forms submit successfully
- Emails added to mailing list
- Success messages display

---

### 11. Cross-Browser Testing

**Browsers to Test:**
- Chrome
- Firefox
- Safari (if Mac available)
- Edge
- Mobile browsers (iOS Safari, Chrome Mobile)

**What to Test:**
- [ ] All pages load
- [ ] JavaScript works (dropdowns, filters, etc.)
- [ ] CSS displays correctly
- [ ] No console errors
- [ ] Analytics tracking works

---

### 12. Performance Testing

**What to Test:**
- [ ] Page load times (< 3 seconds)
- [ ] Images optimized (not too large)
- [ ] No broken links (check console)
- [ ] No JavaScript errors (check console)
- [ ] Analytics doesn't slow down page

**Tools to Use:**
- Google PageSpeed Insights
- Browser DevTools → Network tab
- Lighthouse (Chrome DevTools)

---

## 🐛 Known Issues / Things to Check

1. **WordPress Pledge Page**
   - Verify template is set correctly
   - May need to run fix script

2. **Analytics Event Tracking**
   - Not yet fully implemented (waiting for Grok)
   - Basic page views should work
   - Custom events need implementation

3. **Social Sharing**
   - Verify social handle capture saves to database
   - Check API endpoint: `/api/capture-social-handle.php`

---

## ✅ Testing Checklist Summary

**Quick Smoke Test (5 minutes):**
- [ ] Homepage loads
- [ ] Resources Hub loads and filters work
- [ ] Tools accessible via Resources menu
- [ ] Pledge wall displays correctly
- [ ] Navigation menus work
- [ ] Footer links work
- [ ] Analytics snippet in page source

**Full Test (30 minutes):**
- [ ] All items in Quick Smoke Test
- [ ] Test all campaign pages
- [ ] Test newsletter signups in all locations
- [ ] Test social sharing
- [ ] Test mobile responsiveness
- [ ] Verify analytics in GA4 Real-Time
- [ ] Test WordPress pages (pledge, chapters)
- [ ] Check for console errors
- [ ] Test all dropdown menus

---

## 📊 Analytics Verification Steps

1. **Verify GA4 is Tracking:**
   ```
   Steps:
   1. Visit https://choose90.org
   2. Open DevTools (F12) → Network tab
   3. Filter: "collect" or "gtag"
   4. Reload page
   5. Should see request to google-analytics.com/g/collect
   6. Status should be 200 (success)
   ```

2. **Verify in GA4 Dashboard:**
   ```
   Steps:
   1. Go to https://analytics.google.com
   2. Select "choose90" property
   3. Go to Reports → Real-time
   4. Visit site in another tab
   5. Should see yourself as active user
   ```

3. **Check Measurement ID:**
   - View page source
   - Search for "G-9M6498Y7W4"
   - Should find it in analytics snippet

---

## 🚀 Next Steps After Testing

1. **Document any bugs found**
2. **Fix critical issues immediately**
3. **Hand off analytics implementation to Grok** (prompt ready)
4. **Hand off dashboard to Grok** (prompt ready)
5. **Hand off interactive directory to Grok** (prompt ready)
6. **Continue with testing as features are built**

---

## 📝 Test Results Template

Use this to document your testing:

```
Date: ___________
Tester: ___________

Resources Hub:
[ ] Pass [ ] Fail [ ] Notes: ___________

Navigation:
[ ] Pass [ ] Fail [ ] Notes: ___________

Pledge Wall:
[ ] Pass [ ] Fail [ ] Notes: ___________

Tools:
[ ] Pass [ ] Fail [ ] Notes: ___________

Campaign Pages:
[ ] Pass [ ] Fail [ ] Notes: ___________

Analytics:
[ ] Pass [ ] Fail [ ] Notes: ___________

Issues Found:
1. ___________
2. ___________
3. ___________
```

