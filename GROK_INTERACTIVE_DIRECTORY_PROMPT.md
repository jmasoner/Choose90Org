# Interactive Local Good Directory - Handoff to Grok

## Overview
Transform the static Local Good Directory Template into an interactive, user-contributed directory system that's tied to WordPress user accounts and integrated with the chapter system. When users join or create a chapter, their local directory entries automatically populate their chapter page, creating a community resource that grows organically.

## Business Value
- **Community Engagement**: Encourages users to discover and share local positive resources
- **Chapter Growth**: Incentivizes users to join/start chapters to contribute to their directory
- **Local Impact**: Each chapter gets a curated list of local good things
- **User Retention**: Creates ongoing value and engagement for chapter members
- **Network Effect**: More contributors = more valuable directory = more chapter engagement

## User Flow

### Flow 1: New User Discovers Directory
1. User visits `/resources-backup/local-directory-template.html`
2. Sees sample directory entries and instructions
3. Clicks "Create My Local Directory" or "Add to Directory"
4. Prompted to log in or create account
5. After login, redirected to interactive directory page
6. Can start adding entries immediately

### Flow 2: User Adds Directory Entries
1. User is logged in and on their directory page
2. Clicks "Add Entry" button
3. Selects category (Business, Garden, Volunteer, Gathering Place, etc.)
4. Fills out form (name, description, location, contact, etc.)
5. Submits entry
6. Entry is saved and immediately visible in their directory
7. If user is part of a chapter, entry is also added to chapter's directory

### Flow 3: User Joins/Creates Chapter
1. User has been adding entries to their personal directory
2. System prompts: "Join a chapter to share your directory with your community!"
3. User clicks to join/create chapter
4. After joining/creating chapter:
   - All their directory entries are automatically associated with the chapter
   - Chapter page displays combined directory from all chapter members
   - User can continue adding entries that will appear on chapter page

### Flow 4: Chapter Directory Display
1. Visitor views chapter page (`/chapter/[chapter-slug]/`)
2. Sees "Local Good Directory" section
3. Views all entries contributed by chapter members
4. Can filter by category
5. Can search entries
6. Logged-in chapter members can add/edit entries directly from chapter page

## Technical Architecture

### Database Structure

#### Custom Post Type: `local_directory_entry`
Each directory entry is a custom post type with the following structure:

```php
Post Fields:
- post_title: Entry name (e.g., "Smith's Coffee Shop")
- post_content: Description/details
- post_author: WordPress user ID who created it
- post_status: publish, draft, pending (for moderation)

Post Meta:
- _directory_category: business, garden, volunteer, gathering, person, resource
- _directory_location: Address or location description
- _directory_contact: Contact information (email, phone, etc.)
- _directory_website: Website URL (optional)
- _directory_why_good: Why this is a good thing (text)
- _directory_verified: boolean (has admin verified it)
- _directory_approved: boolean (published/approved status)
- _chapter_id: Associated chapter ID (if user is in a chapter)
- _directory_public: boolean (show in public directory vs chapter-only)
```

#### Taxonomy: `directory_category`
- business
- garden
- volunteer
- gathering_place
- person
- resource

#### User Meta (for tracking):
- `_user_directory_entries_count`: Total entries user has created
- `_user_has_directory`: boolean (has user created directory entries)
- `_user_chapter_directory_enabled`: boolean (is their directory linked to chapter)

### WordPress Integration

#### Custom Post Type Registration
Create in `functions.php` or separate file:

```php
function choose90_register_directory_entry() {
    $args = array(
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-location-alt',
        'supports' => array('title', 'editor', 'author'),
        'has_archive' => false,
        'rewrite' => array('slug' => 'directory-entry'),
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => 'edit_posts', // Allow logged-in users to create
        ),
    );
    register_post_type('local_directory_entry', $args);
}
add_action('init', 'choose90_register_directory_entry');
```

#### Frontend Interface

**Personal Directory Page** (`/my-directory/`):
- Shows user's personal directory entries
- Add/edit/delete buttons for own entries
- "Join Chapter" CTA if not in chapter
- Preview of how entries will look on chapter page

**Chapter Directory Display** (on chapter pages):
- Shows all entries from chapter members
- Filterable by category
- Searchable
- "Add Entry" button for chapter members
- Map view (optional future enhancement)

**Entry Form** (Modal or inline):
- Category selector
- Required fields: Name, Category, Description, Location
- Optional: Contact, Website, Why it's good
- Image upload (optional)
- Submit button

### API Endpoints Needed

#### 1. Create Directory Entry
**Endpoint**: `POST /wp-json/choose90/v1/directory/entries`
**Auth**: Requires logged-in user
**Payload**:
```json
{
  "title": "Smith's Coffee Shop",
  "category": "business",
  "description": "Local coffee shop that pays fair wages...",
  "location": "123 Main St",
  "contact": "info@smithscoffee.com",
  "website": "https://smithscoffee.com",
  "why_good": "Pays employees living wage",
  "chapter_id": 123  // optional, auto-set if user in chapter
}
```
**Response**: Entry object with ID

#### 2. Get User's Directory Entries
**Endpoint**: `GET /wp-json/choose90/v1/directory/entries?user_id={id}`
**Auth**: User can see own, public for others
**Response**: Array of entry objects

#### 3. Get Chapter Directory Entries
**Endpoint**: `GET /wp-json/choose90/v1/directory/chapter/{chapter_id}`
**Auth**: Public
**Response**: Array of entry objects for that chapter

#### 4. Update Directory Entry
**Endpoint**: `PUT /wp-json/choose90/v1/directory/entries/{id}`
**Auth**: Entry owner or chapter admin
**Payload**: Same as create, partial updates allowed

#### 5. Delete Directory Entry
**Endpoint**: `DELETE /wp-json/choose90/v1/directory/entries/{id}`
**Auth**: Entry owner or chapter admin

### User Interface Components

#### 1. Directory Entry Card Component
Reusable card component to display entries:
- Entry name (title)
- Category badge/icon
- Description (truncated with "read more")
- Location
- Contact info (if provided)
- "Learn More" or "Visit Website" button
- Edit/Delete buttons (if user owns entry)

#### 2. Directory Form Component
Add/Edit entry form with:
- Category selector (radio buttons or dropdown)
- Name field (required)
- Description textarea (required)
- Location field (required)
- Contact field (optional)
- Website field (optional)
- "Why it's good" textarea (optional)
- Submit/Cancel buttons

#### 3. Directory Grid/List View
Display entries in:
- Grid layout (cards)
- List layout (compact table)
- Map view (future enhancement)

#### 4. Filter/Search Component
- Category filter (checkboxes or dropdown)
- Search bar (searches name, description, location)
- Sort options (newest, alphabetical, category)

#### 5. Chapter Integration Banner
If user is not in chapter, show:
- "Join a chapter to share your directory with your community!"
- Button: "Find a Chapter" or "Start a Chapter"
- Preview showing how entries will look on chapter page

### Page Structure

#### New Pages to Create:

1. **Interactive Directory Page** (`/my-directory/` or `/local-directory/`)
   - User's personal directory entries
   - Add entry button
   - Chapter integration CTA (if not in chapter)
   - Preview mode showing how it looks on chapter page

2. **Directory Entry Detail Page** (optional, `/directory-entry/[slug]/`)
   - Full entry details
   - Map (if address provided)
   - Share buttons
   - Related entries from same category

3. **Update Existing Template** (`/resources-backup/local-directory-template.html`)
   - Keep as example/template
   - Add "Make This Interactive" button at top
   - Link to `/my-directory/`

#### Update Existing Pages:

1. **Chapter Detail Page** (`single-chapter.php`)
   - Add "Local Good Directory" section
   - Display entries from chapter members
   - "Add Entry" button for logged-in chapter members
   - Filter/search functionality

2. **User Dashboard/Profile** (if exists)
   - Link to "My Directory"
   - Count of entries created
   - Quick add entry form

### Integration with Chapter System

#### When User Joins Chapter:
1. Query all entries where `post_author` = user ID
2. Update `_chapter_id` meta for all those entries
3. Set `_user_chapter_directory_enabled` user meta to true
4. Display notification: "Your directory entries are now on your chapter page!"

#### When User Creates Chapter:
1. Same as joining
2. Additionally, set chapter as "owner" of directory entries
3. Chapter admin can moderate entries

#### Display on Chapter Page:
1. Query entries where `_chapter_id` = chapter ID
2. Display in "Local Good Directory" section
3. Group by category
4. Show contributor names (optional)

### Moderation & Quality Control

#### Entry Approval Options:
- **Option 1: Auto-approve** (for logged-in users)
- **Option 2: Moderate first entries** (first 3 entries need approval)
- **Option 3: Chapter admin moderation** (if user in chapter, chapter admin approves)
- **Option 4: Full moderation** (all entries need admin approval)

#### Flag/Report System:
- "Report Entry" button on each entry
- Admin review process
- Auto-flag duplicates (similar name/location)

### User Experience Enhancements

#### 1. Quick Add
- Floating "Add Entry" button
- Quick-add modal with minimal fields
- Can fill in details later

#### 2. Bulk Import
- CSV import for initial directory population
- Template download
- Validation and error reporting

#### 3. Map Integration (Future)
- Google Maps or OpenStreetMap
- Plot entries on map
- Location-based search

#### 4. Social Sharing
- Share individual entries
- Share chapter directory
- Generate PDF of directory

### Security & Privacy

#### Access Control:
- Only logged-in users can add entries
- Users can edit/delete only their own entries
- Chapter admins can moderate chapter entries
- Site admins can moderate all entries

#### Privacy Considerations:
- Contact information optional (user choice)
- "Public" vs "Chapter-only" toggle
- GDPR compliance (user can delete their data)

#### Data Validation:
- Sanitize all input
- Validate URLs and emails
- Prevent spam/duplicate entries
- Rate limiting (max entries per user per day)

### File Structure

```
wp-content/
  themes/
    Divi-choose90/
      directory/
        single-directory-entry.php (entry detail template)
        archive-directory.php (directory listing)
        includes/
          class-directory-api.php (REST API endpoints)
          class-directory-forms.php (form handling)
          class-directory-chapter.php (chapter integration)
        js/
          directory.js (frontend interactions)
          directory-form.js (form handling)
        css/
          directory.css (directory styles)
  plugins/ (or in theme)
    choose90-directory/
      choose90-directory.php (main plugin file)
      includes/
        class-directory-cpt.php (CPT registration)
        class-directory-api.php (REST API)
        class-directory-admin.php (admin interface)
      templates/
        directory-form.php
        directory-card.php
        directory-list.php
```

## Implementation Phases

### Phase 1: Foundation (Week 1)
1. Register custom post type `local_directory_entry`
2. Create database structure and meta fields
3. Build basic add entry form
4. Create personal directory page
5. Basic CRUD operations (Create, Read, Update, Delete)

### Phase 2: Chapter Integration (Week 2)
1. Link entries to chapters when user joins/creates
2. Display entries on chapter pages
3. Chapter admin moderation
4. "Join Chapter" CTA for users without chapters

### Phase 3: Enhancements (Week 3)
1. Filter and search functionality
2. Category organization
3. Entry moderation system
4. User dashboard integration

### Phase 4: Polish (Week 4)
1. UI/UX improvements
2. Mobile optimization
3. Performance optimization
4. Documentation

## Success Criteria

- Users can create directory entries without friction
- Entries automatically appear on chapter pages
- Chapter pages have engaging directory sections
- Users are encouraged to join/create chapters
- Directory grows organically as community contributes
- No spam or low-quality entries
- Fast page load times
- Mobile-friendly interface

## Questions to Address

1. Should entries be public by default or chapter-only?
2. Do we need moderation for all entries or trust users?
3. Should we allow anonymous entries (with email verification)?
4. Should entries expire/need renewal?
5. Do we want a map view initially or later?
6. Should we allow comments/reviews on entries?
7. Should we limit entries per user?
8. Do we want to allow entries for chapters user isn't in?

## Deliverables

1. **Working Interactive Directory**
   - Add/edit/delete entries
   - Personal directory page
   - Chapter integration
   - Display on chapter pages

2. **REST API**
   - All CRUD endpoints
   - Chapter integration endpoints
   - Proper authentication

3. **Database Schema**
   - Custom post type registered
   - Meta fields defined
   - User meta integration

4. **UI Components**
   - Entry form
   - Entry cards
   - Directory grid/list
   - Filter/search

5. **Documentation**
   - User guide
   - API documentation
   - Admin guide

---

## Example User Journey

**Sarah's Journey:**
1. Sarah visits the Local Good Directory template page
2. She thinks it's a great idea and clicks "Create My Directory"
3. She logs in (or creates account)
4. She's taken to `/my-directory/` where she can add entries
5. She adds 5 local businesses she loves
6. System shows: "Join a chapter to share your directory!"
7. She clicks "Find a Chapter" and joins "Downtown Seattle Chapter"
8. Her 5 entries automatically appear on the chapter page
9. Other chapter members see her entries and add their own
10. The chapter directory grows from 5 to 25 entries
11. The chapter page becomes a valuable local resource
12. More people join the chapter because of the directory

This creates a positive feedback loop: Directory → Chapter Engagement → More Members → More Directory Entries → Better Resource → More Engagement

