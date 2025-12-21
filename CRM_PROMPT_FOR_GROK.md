# CRM Development Prompt for Grok

If you want to use Grok (or another AI) to work on the CRM, you can share this prompt:

---

## Prompt for Grok:

"I need you to design and implement a CRM system for Choose90.org, a nonprofit movement focused on positive community building. 

**Context:**
- We use WordPress for our main website
- We have chapters (local groups) that need communication management
- We want to use `chapters@choose90.org` as a centralized email with distribution lists
- Each chapter has leaders who need to see and respond to emails
- We need to track if messages have been replied to

**Requirements:**
1. **Email Management:**
   - Centralized email: `chapters@choose90.org`
   - Distribution lists for each chapter (e.g., chapters+austin@choose90.org routes to Austin chapter leaders)
   - Store all emails in a central database
   - Track email status (unread, read, replied, archived)
   - Email threading (group related emails)

2. **Chapter Leader Dashboard:**
   - View emails for their assigned chapters
   - See reply status
   - Reply to emails (either from dashboard or track when they reply via email)
   - View contact history

3. **Admin Features:**
   - Manage distribution lists
   - Assign/remove chapter leaders
   - View all communications
   - Search emails by chapter, contact, date

4. **Technical Constraints:**
   - Should integrate with existing WordPress site
   - Use WordPress user system if possible
   - Prefer WordPress-based solution for Phase 1 (MVP)
   - Can consider custom database tables for performance

**Phase 1 MVP (Week 1):**
- Email capture/storage
- Basic distribution list management
- Simple dashboard showing emails
- Reply status tracking

**Please provide:**
1. Technical architecture recommendation
2. Database schema design
3. Implementation plan
4. Code structure outline
5. WordPress integration approach

**Current Tech Stack:**
- WordPress (hybrid site - static HTML frontend, WordPress backend)
- PHP 7.4+
- MySQL database
- cPanel hosting environment

Start with Phase 1 MVP design and we'll iterate from there."

---

## Alternative: Use Me (Current Assistant)

I can work on this CRM project right now! I can:
- Design the database schema
- Create the WordPress custom post types
- Build the email capture system
- Create the dashboard interface
- Implement distribution list management

Would you like me to start building it, or would you prefer to open a separate session with Grok/another AI?

