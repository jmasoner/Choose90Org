# Handoff: Chapters Section Next Steps

This document outlines the current state of the "Chapters" section migration and exactly what needs to be done next.

---

## 1. Current Technical Status

- **Custom Post Type (CPT):** `chapter` is fully registered in the child theme's `functions.php`.
- **Taxonomies:** `chapter_region` is registered for grouping chapters by location.
- **Templates:**
  - `page-chapters.php` (The directory grid) is active.
  - `single-chapter.php` (The individual group page) is active.
  - `page-host-starter-kit.php` (The landing page for new hosts) is active.
- **Auto-Provisioning:** The theme automatically creates the `/host-starter-kit/` and `/resources/` slugs on first run.

---

## 2. Immediate Next Tasks (Priority Order)

### Task A: Form Integration (Gravity Forms)

1. **Create the "Host Application" Form:**
    - Use Gravity Forms in WordPress admin.
    - Fields: Name, Email, City, Region (State/Province), and "Why do you want to start a chapter?"
2. **Automation:**
    - Set the form to automatically create a "Pending" post of type `chapter` when submitted.
    - Alternatively, send a notification to John (`john@masoner.us`) for manual creation.
3. **Landing Page:** Embed this form on the `/host-starter-kit/` page.

### Task B: Content Seeding (The "Starters")

1. **Generate Sample Data:**
    - Create 3-5 sample chapters in the WordPress admin to verify the grid layout works.
2. **Meta Field Cleanup:**
    - We planned custom fields for: `_chapter_city`, `_chapter_leader_name`, `_chapter_meeting_time`.
    - Ensure these fields are being output correctly in `single-chapter.php`.

### Task C: Visual Polish

1. **Grid Styling:** Test the responsive layout of `page-chapters.php` on mobile.
2. **Map Integration (Optional Long Term):**
    - If the directory grows, consider a plugin like "WP Google Maps" to display chapters geographically.

---

## 3. Related Files

- **Logic:** `setup_child_theme.ps1` (Updates `functions.php`).
- **Templates:** `/hybrid_site/` (Source code for the PHP templates).
- **Prompts:** `CHAPTERS_CONTENT_PROMPTS.md` (Use these to generate copy for the site).
