# Chapters Section Implementation Plan

## 1. Technical Backbone (Architecture)

To build a robust, searchable, and manageable directory of chapters, we will use WordPress's native **Custom Post Type (CPT)** system. This allows "Chapters" to be stored separately from blog posts, with their own specific data fields.

### A. Data Structure

**Custom Post Type:** `chapter`

* **Supports:** Title (Chapter Name), Editor (Description/Bio), Thumbnail (Chapter Photo)
* **Taxonomies:** `Region` (e.g., State/Province), `Country`

**Custom Fields (Metadata):**
We will store structured data for each chapter.

* `_chapter_city` (Text)
* `_chapter_leader_name` (Text)
* `_chapter_email` (Email - protected/hidden behind contact form usually)
* `_chapter_meeting_time` (Text, e.g., "3rd Tuesday at 7 PM")
* `_chapter_location_name` (Text, e.g., "Main Public Library")
* `_chapter_status` (Select: Active, Pending, Forming)

### B. Required Pages & Templates

1. **Mothership Page (`/chapters/`)**
    * **File:** `page-chapters.php`
    * **Function:** Displays a searchable map (if we add a map plugin) or a clean grid/list of chapters.
    * **Features:**
        * "Find a Chapter" Search Bar (Zip/State).
        * "Start a Chapter" CTA.
        * Dynamic grid of Active chapters.

2. **Individual Chapter Page**
    * **File:** `single-chapter.php`
    * **Function:** The "Home Base" for a specific local group.
    * **Features:**
        * Chapter Name & Photo.
        * Leader Bio.
        * Upcoming Meeting Info.
        * "Join This Chapter" Button.

3. **Registration Workflow**
    * **Tool:** Gravity Forms or Standard WP Form.
    * **Process:** User fills form -> System creates a "Pending" `chapter` post -> Admin approves -> Chapter goes live.

---

## 2. Implementation Steps (My Tasks)

I will perform the following technical setup:

1. **Modify `functions.php` (or create a site-specific plugin):**
    * Register the `chapter` Custom Post Type.
    * Register the `region` Taxonomy.
    * Add code to handle the specific meta fields (City, Leader, etc.).

2. **Build Templates:**
    * Code the `page-chapters.php` to fetch and query `chapter` posts.
    * Code the `single-chapter.php` to display chapter details beautifully.

---

## 3. Content Prompts (For Grok / Deepseek / Genspark)

Since you want to leverage other AIs for the creative content, here are the specific prompts you should use.

### Prompt 1: The "Start a Chapter" Pitch

**Use this for the /chapters/ page intro text.**

> "Act as a community organizer for 'Choose90', a movement dedicated to finding the 90% good in humanity. I need 300 words of compelling copy for a 'Start a Chapter' landing page. The goal is to inspire individuals to step up as leaders in their local town.
>
> **Key points to hit:**
>
> * Loneliness is cured by connection, not likes.
> * We need offline spaces to practice the 90/10 philosophy.
> * It’s easier than they think (we provide the guides).
> * Call to Action: 'Be the Lighthouse in Your Community'."

### Prompt 2: Chapter Leader Guidelines (The "Playbook")

**Use this to create a downloadable PDF resource for leaders.**

> "Create a 'Choose90 Chapter Leader Playbook' outline. This will be a guide for volunteers running monthly 1-hour meetings.
>
> **Requirements:**
>
> * **Tone:** Encouraging, structured, low-pressure.
> * **Meeting Format (60 mins):**
>   * 10 mins: Good News Round (Everyone shares one good thing).
>   * 15 mins: The 90/10 Discussion (Topic of the month).
>   * 25 mins: Action Step (Planning a local act of kindness).
>   * 10 mins: Commitments & Closing.
> * **Include:** A 'Code of Conduct' focusing on no politics, no negativity loops, just focusing on the good."

### Prompt 3: The "Chapter Charter" (Code of Conduct)

**Use this for the text on `single-chapter.php` pages.**

> "Write a short, 5-bullet point 'Community Agreement' for members of a Choose90 local chapter. Members must agree to this to join. Focus on:
>
> 1. Listening to understand, not to reply.
> 2. Checking politics and divisiveness at the door.
> 3. Confidentiality (what happens in the room stays in the room).
> 4. Encouragement over critique.
> 5. Commitment to the 90% positive focus."

---

## 4. Next Steps

1. **Approve Plan:** If this technical structure (CPT + Meta Fields) looks good to you, say "Go".
2. **I will execute Phase 1:** I will write the PHP code to register the post types and fields on your server.
3. **You generate content:** While I code, you can run the prompts above and paste the results into a text file for me to insert.
