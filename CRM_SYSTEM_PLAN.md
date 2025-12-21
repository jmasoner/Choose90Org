# Choose90 CRM System Plan

## Overview

A Customer Relationship Management (CRM) system to centralize all chapter communications, manage distribution lists, track email conversations, and provide visibility into chapter leader activities.

## Core Requirements

### 1. Email Management
- **Centralized Email:** `chapters@choose90.org` as primary contact
- **Distribution Lists:** Create distribution lists for each chapter
- **Email Routing:** Incoming emails automatically routed to correct distribution list
- **Email Tracking:** See if messages have been replied to
- **Thread Tracking:** Track conversation threads per chapter/contact

### 2. Communication Repository
- **Central Database:** All emails stored in one location
- **Search Functionality:** Search emails by chapter, contact, date, subject
- **Contact History:** View all interactions with a contact
- **Chapter Activity:** See all communications related to a specific chapter

### 3. Chapter Leader Features
- **Dashboard:** View assigned chapters and their communications
- **Reply Status:** See which messages need responses
- **Email Templates:** Pre-written templates for common responses
- **Notifications:** Alerts for new messages requiring attention

### 4. Admin Features
- **Distribution List Management:** Assign/remove leaders from distribution lists
- **Email Monitoring:** View all communications across all chapters
- **Analytics:** Communication volume, response times, etc.
- **User Management:** Manage chapter leader access

## Technical Architecture Options

### Option 1: WordPress-Based CRM (Recommended for Phase 1)
**Pros:**
- Integrates with existing WordPress site
- Uses existing user system
- Can leverage WordPress plugins
- Lower cost to start

**Cons:**
- May have limitations for advanced features
- Requires custom development

**Implementation:**
- Custom post type: `crm_email` or `crm_message`
- Custom post type: `crm_contact`
- WordPress user meta for distribution list assignments
- Plugin or custom code for email capture/storage

### Option 2: Third-Party CRM Integration
**Pros:**
- Feature-rich out of the box
- Professional email management
- May have mobile apps

**Cons:**
- Monthly costs ($10-50+/month)
- Learning curve
- Data lives outside WordPress

**Options:**
- **HubSpot** (Free tier available)
- **Zoho CRM** (Free tier available)
- **Copper** (Google Workspace integration)
- **Monday.com** (Project management + CRM)

### Option 3: Custom-Built CRM (Recommended for Phase 2)
**Pros:**
- Fully tailored to Choose90 needs
- Complete control
- Can integrate deeply with WordPress
- No monthly fees (just hosting)

**Cons:**
- More development time
- Requires ongoing maintenance
- Need to build all features

**Tech Stack:**
- Backend: PHP (WordPress) or Node.js
- Database: MySQL (WordPress) or PostgreSQL
- Frontend: React/Vue.js or WordPress admin
- Email: IMAP/SMTP integration or API (SendGrid, Mailgun)

## Recommended Approach: Hybrid

### Phase 1 (Week 1): WordPress-Based MVP
- Use WordPress custom post types for email storage
- Email forwarding/capture via plugin
- Basic distribution list management
- Simple dashboard for chapter leaders

### Phase 2 (Week 2-3): Enhanced Features
- Email threading
- Reply tracking
- Search functionality
- Better UI/UX

### Phase 3 (Future): Full Custom CRM
- Advanced features
- Mobile app
- Analytics dashboard
- Integration with other systems

## Data Structure (WordPress-Based)

### Custom Post Type: `crm_email`
**Fields:**
- Subject
- From (email address)
- To (email address or distribution list)
- Body (email content)
- Status (unread, read, replied, archived)
- Thread ID (for grouping conversations)
- Chapter ID (relationship to chapter)
- Timestamp
- Attachments (if any)

### Custom Post Type: `crm_contact`
**Fields:**
- Name
- Email
- Phone (optional)
- Chapter(s) they're associated with
- Contact type (host, member, prospect, etc.)
- Notes
- Last contact date

### Distribution Lists
**Structure:**
- Distribution list name (e.g., "Choose90 Austin - Hosts")
- Associated chapter
- Members (WordPress user IDs)
- Email address: `chapters+austin@choose90.org` or routing rules

## Email Routing Strategy

### Method 1: Email Aliases
**Setup:**
- `chapters@choose90.org` = Main inbox
- Create forwarding rules based on subject line or recipient
- Example: Subject contains "Austin" → Forward to Austin distribution list

### Method 2: Plus Addressing
**Setup:**
- `chapters+austin@choose90.org` → Routes to Austin distribution list
- `chapters+join-austin@choose90.org` → Auto-categorizes as "join" request
- All emails go to main inbox, but routing happens via +address

### Method 3: IMAP + Parsing
**Setup:**
- All emails go to `chapters@choose90.org`
- PHP script (cron job) checks inbox via IMAP
- Parses emails and routes to appropriate distribution lists
- Stores emails in WordPress database

## Features Breakdown

### Must-Have (MVP):
1. ✅ Email storage in WordPress
2. ✅ Distribution list management
3. ✅ Basic dashboard for chapter leaders
4. ✅ Email forwarding/capture
5. ✅ View unread/replied status

### Should-Have (Phase 2):
1. ⏳ Email threading
2. ⏳ Search functionality
3. ⏳ Email templates
4. ⏳ Reply tracking
5. ⏳ Contact management

### Nice-to-Have (Phase 3):
1. ⏳ Mobile notifications
2. ⏳ Analytics dashboard
3. ⏳ Email scheduling
4. ⏳ Automated responses
5. ⏳ Integration with pledge system

## Implementation Steps (Week 1)

### Day 1-2: Email Capture Setup
1. Set up email forwarding/capture
2. Create `crm_email` custom post type
3. Build email parser/importer
4. Test email storage

### Day 3-4: Distribution Lists
1. Create distribution list management system
2. Build UI for assigning leaders to lists
3. Set up email routing logic
4. Test routing

### Day 5: Dashboard
1. Create chapter leader dashboard
2. Display emails assigned to their chapters
3. Show reply status
4. Basic reply functionality

### Day 6-7: Testing & Polish
1. Test full workflow
2. Fix bugs
3. Add UI polish
4. Documentation

## Database Schema (WordPress Custom Tables)

If we need custom tables (for better performance):

```sql
-- Emails table
wp_choose90_crm_emails
- id
- from_email
- to_email
- subject
- body
- status (unread/read/replied/archived)
- thread_id
- chapter_id
- created_at
- updated_at

-- Distribution Lists table
wp_choose90_distribution_lists
- id
- name
- email_alias (chapters+austin@choose90.org)
- chapter_id
- created_at

-- Distribution List Members
wp_choose90_distribution_list_members
- id
- distribution_list_id
- user_id (WordPress user)
- role (leader/member)
- added_at

-- Email Threads
wp_choose90_crm_threads
- id
- subject
- chapter_id
- contact_id
- last_message_at
- status
```

## Questions to Answer Before Building

1. **Email Provider:** Which email hosting do you use? (Gmail, Office 365, cPanel, etc.)
   - Determines how we integrate

2. **Distribution List Method:** 
   - Simple forwarding?
   - Plus addressing?
   - Full distribution list system?

3. **Access Control:**
   - Can chapter leaders see ALL chapters' emails?
   - Or only their assigned chapter(s)?

4. **Reply Method:**
   - Reply directly from WordPress dashboard?
   - Reply via email and track it?
   - Both?

5. **Notification System:**
   - Email notifications?
   - Dashboard notifications?
   - Mobile push (later)?

## Next Steps

1. **Decide on approach:** WordPress-based MVP vs. third-party vs. custom
2. **Email setup:** Configure `chapters@choose90.org` and distribution lists
3. **Build MVP:** Start with email capture and basic dashboard
4. **Test with one chapter:** Use one chapter as pilot
5. **Expand:** Roll out to all chapters

---

**Timeline Estimate:**
- Phase 1 MVP: 1 week
- Phase 2 Enhanced: 1-2 weeks  
- Phase 3 Full CRM: 1-2 months

**Recommended Start:** WordPress-based MVP (Phase 1) - fastest to implement and test

