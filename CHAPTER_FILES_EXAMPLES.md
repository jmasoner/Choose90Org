# Chapter Files - Examples & Overview

## File Structure

### 1. **`single-chapter.php`** - Individual Chapter Display Page
**Purpose:** Shows a single chapter's details when someone clicks on it from the directory.

**Current Features:**
- Hero header with chapter name and region
- Main content area (chapter description)
- Community Agreement section
- Sidebar with chapter details and contact buttons

**Current Issues:**
- Line 47-48: Comment says "We will add dynamic meta fields here later"
- Hardcoded email: `john@choose90.org` (should use chapter leader's email from meta)
- Generic "Meeting Details" text instead of actual data

**Example Output:**
```
[Blue Header]
Choose90 SODO
Seattle, WA

[Main Content]
[Chapter description from WordPress editor]

🤝 Community Agreement
By attending this chapter, members agree to:
- Focus on the 90% good in humanity
- Listen to understand, not to argue
- Leave politics and divisiveness at the door

[Sidebar Card]
Chapter Details
Meeting Details: Check the description for upcoming dates.
[Contact Host Button] [Join This Chapter Button]
```

---

### 2. **`page-chapters.php`** - Chapters Directory Page
**Purpose:** Main landing page listing all active chapters in a grid layout.

**Current Features:**
- Hero section with "Stop Waiting for Community. Build It."
- 3 benefits grid
- "How It Works" section
- Dynamic chapter grid (pulls from WordPress CPT)
- Empty state message if no chapters exist

**Current Issues:**
- No search/filter functionality
- Grid cards only show title, image, excerpt, and "View Chapter" button
- No location info shown on cards (city/state)
- No way to filter by location

**Example Output:**
```
[Top Section]
Stop Waiting for Community. Build It.
Be the spark that starts the movement in your neighborhood.
[Get the Host Starter Kit] [Find a Chapter]

[3 Benefits Cards]
1. Cure Your Own Loneliness
2. Be a Beacon of Light  
3. Do Something Real

[How It Works]
Step 1: Apply (5 mins)
Step 2: Get Your Kit (Free)
Step 3: Host (1 hr/month)

[Chapter Grid]
┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│ Choose90    │  │ Choose90    │  │ Choose90    │
│ SODO        │  │ Capitol Hill│  │ West Austin │
│             │  │             │  │             │
│ [Image]     │  │ [Image]     │  │ [Image]     │
│             │  │             │  │             │
│ Description │  │ Description │  │ Description │
│             │  │             │  │             │
│ [View]      │  │ [View]      │  │ [View]      │
└─────────────┘  └─────────────┘  └─────────────┘

OR

"No active chapters yet. Start one today!"
```

---

### 3. **`page-host-starter-kit.php`** - Host Application Page
**Purpose:** Guides potential hosts and collects applications to start new chapters.

**Current Features:**
- Host Starter Kit content (Pages 1-2 of the kit)
- Application form with validation
- Math CAPTCHA for spam protection
- Automatically creates draft chapter post on submission
- Sends email notification to admin

**Form Fields:**
- Your Name (text)
- City & State / Chapter Name (text)
- Email Address (email)
- Initial Meeting Date (text)
- Security Check (math CAPTCHA)

**Form Processing:**
```php
// When submitted:
1. Validates CAPTCHA
2. Sanitizes inputs
3. Creates draft chapter post with:
   - Title = Chapter Name
   - Post Type = 'chapter'
   - Status = 'draft' (pending review)
   - Content = Host info as text
4. Sends email to admin
5. Shows success message
```

**Current Issues:**
- Line 37: Comment says meta fields aren't saved yet
- Form only captures basic info (missing: meeting pattern, location name, etc.)
- No automatic region taxonomy assignment

**Example Form:**
```
Ready to Make it Official?

Once you've read the kit and are ready to schedule your 
first meeting, fill out this form so we can list your 
chapter on the website.

[Form]
Your Name: [John Doe____________]
City & State (Chapter Name): [Choose90 Austin________]
Email Address: [john@example.com_____]
Initial Meeting Date: [3rd Tuesday of next month___]
Security Check: What is 7 + 3? [10___]

[Submit Application]
```

---

### 4. **WordPress Functions** (in `functions.php` or child theme)
**Purpose:** Registers the Custom Post Type and Taxonomies.

**Registered:**
- Custom Post Type: `chapter`
- Taxonomy: `chapter_region` (for grouping by location)

**What's Missing:**
- Custom meta fields registration
- Meta box UI for chapter details
- Functions to save/retrieve meta fields

---

## Data Flow

### Creating a New Chapter:

1. **User visits:** `/host-starter-kit/`
2. **Fills out form** → Submits
3. **PHP processes form:**
   ```php
   wp_insert_post([
       'post_title' => 'Choose90 Austin',
       'post_type' => 'chapter',
       'post_status' => 'draft',  // Pending review
       'post_content' => 'Host Name: John...'  // Basic info
   ])
   ```
4. **Admin gets email notification**
5. **Admin reviews in WordPress dashboard**
6. **Admin edits chapter, adds details, publishes**
7. **Chapter appears on** `/chapters/` directory

### Displaying Chapters:

1. **User visits:** `/chapters/`
2. **WordPress queries:**
   ```php
   WP_Query([
       'post_type' => 'chapter',
       'post_status' => 'publish',  // Only active ones
       'posts_per_page' => 12
   ])
   ```
3. **Template loops through chapters** → Displays grid
4. **User clicks chapter** → Goes to `single-chapter.php`

---

## What Needs to Be Added

### Priority 1: Meta Fields
Currently, chapter data is stored in post content as plain text. Need to add:

**Fields to Add:**
```php
_chapter_city          // "Austin"
_chapter_state         // "Texas" 
_chapter_leader_name   // "John Doe" (hidden)
_chapter_leader_email  // "john@example.com" (hidden)
_chapter_meeting_pattern  // "3rd Tuesday @ 7 PM"
_chapter_location_name    // "Starbucks on Main St"
_chapter_location_address // "123 Main St" (optional)
_chapter_status           // "Active", "Pending", "Paused"
```

### Priority 2: Display Updates
**In `single-chapter.php`:**
- Replace line 48 generic text with actual meta field data
- Replace hardcoded email with `_chapter_leader_email`
- Display meeting pattern prominently
- Show location with map link (if address provided)

**In `page-chapters.php`:**
- Add city/state to grid cards
- Add search/filter by location
- Show meeting time on cards

### Priority 3: Form Enhancement
**In `page-host-starter-kit.php`:**
- Add more fields (meeting pattern, location)
- Save data to meta fields, not just post content
- Auto-assign region taxonomy based on state

---

## Visual Structure

### Single Chapter Page Layout:
```
┌─────────────────────────────────────┐
│  [Blue Hero Header]                 │
│  Choose90 SODO                      │
│  Seattle, WA                        │
└─────────────────────────────────────┘

┌─────────────────────┬───────────────┐
│ Main Content        │ Sidebar       │
│                     │               │
│ Chapter description │ Chapter       │
│ about the group...  │ Details:      │
│                     │               │
│ 🤝 Community        │ Meeting:      │
│ Agreement           │ 3rd Tue @ 7PM│
│ - Focus on 90%      │               │
│ - Listen to         │ Location:     │
│   understand        │ Starbucks     │
│ - No politics       │               │
│                     │ [Contact]     │
│                     │ [Join]        │
└─────────────────────┴───────────────┘
```

### Directory Page Layout:
```
┌─────────────────────────────────────┐
│  Stop Waiting for Community.        │
│  Build It.                          │
│  [Get Kit] [Find Chapter]           │
└─────────────────────────────────────┘

┌──────────┬──────────┬──────────┐
│ Benefit 1│ Benefit 2│ Benefit 3│
└──────────┴──────────┴──────────┘

┌─────────────────────────────────────┐
│  How It Works                       │
│  Step 1 | Step 2 | Step 3          │
└─────────────────────────────────────┘

┌─────────┐ ┌─────────┐ ┌─────────┐
│ Chapter │ │ Chapter │ │ Chapter │
│ Card    │ │ Card    │ │ Card    │
│         │ │         │ │         │
│ [Image] │ │ [Image] │ │ [Image] │
│ Title   │ │ Title   │ │ Title   │
│ Desc... │ │ Desc... │ │ Desc... │
│ [View]  │ │ [View]  │ │ [View]  │
└─────────┘ └─────────┘ └─────────┘
```

---

## Current Status Summary

✅ **Working:**
- Custom Post Type registered
- Templates exist and are functional
- Form creates draft chapters
- Basic display works
- Email notifications sent

❌ **Missing:**
- Custom meta fields implementation
- Meta field display in templates
- Location-based search/filter
- Proper contact form (currently mailto links)
- Enhanced form fields
- Sample data for testing

---

## Next Steps

See `CHAPTERS_ANALYSIS_AND_RECOMMENDATIONS.md` for detailed implementation plan.

**Quick Start:**
1. Implement custom meta fields
2. Update templates to display meta fields
3. Add search/filter functionality
4. Create sample chapters for testing

