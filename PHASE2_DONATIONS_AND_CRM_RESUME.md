## Choose90.org – Phase 2 Resume: Donations & CRM

This document is a quick **resume + restart guide** so you can jump back in after a break. It focuses on your **next two projects**:

- **Project A:** Set up production-ready **Donations**
- **Project B:** Build out a more robust **CRM** on top of the existing work

---

### 1. Where Things Stand (High-Level)

- **Hybrid architecture:** Static HTML front-end + WordPress backend is live and working.
- **Core pages:** Home, Our Story, Chapters, Resources Hub, Pledge, Support, Host Starter Kit are wired up and deployed.
- **Chapters:** Custom post type and templates exist; host starter kit pipeline and chapter meta are in place.
- **Pledge + CRM:** Pledge form creates/updates a CRM contact CPT and user meta; dashboard widget shows key stats.
- **Resources:** Resources hub and all resource HTML pages are live and linked correctly.
- **Security & routing:** `.htaccess` adjusted to prefer `index.html`; resources 403 issues are resolved; link routing is tracked in `LINK_TEST_LOG.md`.

Your **next big wins** are: turning on real money flow (donations) and making communication + follow‑up (CRM) much stronger and easier to manage.

---

### 2. Project A – Donations (Current Status & Next Steps)

**What’s already in place**

- `hybrid_site/components/donation-form.html`: Front-end UI for recurring + one‑time giving.
- `hybrid_site/page-donate.php`: WordPress template **“Donate Page”** that:
  - Renders a hero section.
  - If **WooCommerce is active**, includes the donation form component from the child theme (`components/donation-form.html`).
  - If WooCommerce is **not** active, shows a “WooCommerce Not Configured” instructions box.
- Template and component are deployed to the live theme:
  - `Z:\wp-content\themes\Divi-choose90\page-donate.php`
  - `Z:\wp-content\themes\Divi-choose90\components\donation-form.html`
- All “Donate” buttons/links on the site point to **`/donate/`**.
- `DONATION_PAGE_SETUP.md` contains a detailed step‑by‑step WooCommerce setup guide.

**What still needs to be done (when you’re ready)**

1. **Wire the WordPress Donate page to the template**
   - WP Admin → **Pages → Add New / Edit “Donate”**
   - Title: **Donate**
   - Permalink: `/donate/`
   - Clear the block editor content (leave it empty).
   - In **Page Attributes → Template**, choose **“Donate Page”**.
   - Publish / Update.

2. **Configure WooCommerce for payments**
   - Activate WooCommerce (and WooCommerce Subscriptions if you want recurring).
   - Configure at least one live gateway (Stripe recommended) using your real keys.
   - Create donation products (one‑time + recurring tiers) as outlined in `DONATION_PAGE_SETUP.md`.

3. **End‑to‑end testing**
   - Hit `https://choose90.org/donate/` as a **test user**.
   - Walk through both recurring and one‑time flows.
   - Verify:
     - Successful payment.
     - Thank‑you emails.
     - Orders/donations visible in WooCommerce admin.

**Finished definition for Donations (Phase 2)**

Donations are “done for Phase 2” when:

- `/donate/` loads reliably with the donation form.
- At least one real payment method works in live mode.
- You can see and reconcile donations inside WooCommerce.

---

### 3. Project B – CRM (Current Status & Direction)

**What already exists**

- **Docs and plan**
  - `CRM_SYSTEM_PLAN.md`: Overall architecture and feature set (email routing, distribution lists, dashboards, etc.).
  - `CRM_PROMPT_FOR_GROK.md`: Prompt for letting Grok help design/extend the CRM.
- **WordPress-side implementation (Phase 1 CRM)**
  - Custom post types in PHP:
    - `crm_contact` – base entity for pledge takers / chapter leaders / supporters.
  - Pledge form integration:
    - Enhanced pledge form sends data to WordPress and creates/updates a `crm_contact` record.
    - Screen name, social accounts, and contact info are stored on the WP user + CRM contact.
  - Dashboard widget:
    - `wp-functions-dashboard.php` adds a **“Choose90 Statistics”** widget showing signups and donations summary.

**What’s missing / next for CRM**

- Email‑centric features from `CRM_SYSTEM_PLAN.md`:
  - Inbound email capture for `chapters@choose90.org`.
  - Outgoing email logging / reply status per contact and per chapter.
  - Distribution‑list‑style routing (chapter → leaders).
  - Simple CRM UI in WP Admin: list contacts, filter by chapter, see history.

**Suggested next CRM milestones**

1. **Finalize data model in WordPress**
   - Confirm/extend CPTs: `crm_contact`, `crm_email` (or `crm_message`).
   - Standardize meta keys for:
     - Chapter association(s), status, tags.
     - Primary email(s), phone, social links.

2. **Build a simple “Inbox” view inside WordPress**
   - Admin page listing recent `crm_email` entries (from `chapters@choose90.org`).
   - Filters: by chapter, by contact, “needs reply vs done”.

3. **Hook email into the CRM**
   - Phase 2: Use an IMAP/SMTP bridge or a plugin to read `chapters@choose90.org` and push messages into `crm_email` CPT.
   - Longer term: centralize outbound send through a trusted SMTP (GoSMTP Pro / transactional provider) and log every send.

4. **Leader view / dashboard**
   - Per‑leader dashboard widget: “Your chapters, recent messages, needs reply.”

5. **Reporting**
   - Simple stats: number of contacts, active chapters, open threads, average response time.

You can treat `CRM_SYSTEM_PLAN.md` as the **master spec** and implement it incrementally, starting with storage and admin views before tackling full email routing.

---

### 4. How to Resume Work After Reboot

When you come back and want to keep building **Donations** and **CRM**, this is the quick re‑entry path:

1. **Open the project**
   - Navigate to: `C:\Users\john\OneDrive\MyProjects\Choose90Org\`
   - Open the folder in Cursor.

2. **Review current status**
   - Skim:
     - `PHASE2_DONATIONS_AND_CRM_RESUME.md` (this file)
     - `DONATION_PAGE_SETUP.md`
     - `CRM_SYSTEM_PLAN.md`
     - `LINK_TEST_LOG.md` (for any routing/link history)

3. **Pick a lane for the session**
   - **Donations session:** finish WooCommerce + `/donate/` wiring and testing.
   - **CRM session:** choose a small slice (e.g., `crm_email` CPT and basic admin list) and implement it.

4. **Deployment & testing**
   - Use the existing PowerShell scripts as before:
     - `deploy_production.ps1`
     - `setup_child_theme.ps1`
   - Test on the live domain (`https://choose90.org`) in a browser.

---

### 5. Very Short TL;DR

- **Donations:** Template + UI exist and are deployed; you need to wire the WP Donate page to the “Donate Page” template and finish WooCommerce/payment configuration (see `DONATION_PAGE_SETUP.md`).  
- **CRM:** Core skeleton (contacts + pledge integration + dashboard stats) is in place; next is to implement the email/communication tracking pieces outlined in `CRM_SYSTEM_PLAN.md`.  
- This file + `DONATION_PAGE_SETUP.md` + `CRM_SYSTEM_PLAN.md` give you everything you need to restart quickly after a reboot and keep moving toward a full donations + CRM launch.




