# Refined Chapters Strategy

## 1. Naming & Geography: "The Neighborhood Model"

You are absolutely right—"Seattle" is too big. We should encourage hyper-local naming. Connection happens in neighborhoods, not in sprawling metropolises.

* **Structure:** We will use **Parent/Child** organization for searchability, but **Free-form Titles** for identity.
* **The Title:** "Choose90 [Neighborhood/Town]"
  * *Example:* Choose90 SODO (Seattle)
  * *Example:* Choose90 Capitol Hill (Seattle)
  * *Example:* Choose90 West Austin
* **The Taxonomy (Backend):** We will tag them by **State/Province** and **City**.
  * *User Search:* "Show me chapters in **Seattle**."
  * *Result:* Shows both "SODO" and "Capitol Hill".

## 2. What Information is Public?

Safety and privacy are key. We do **not** publish the Host's home address or personal cell phone.

**Publicly Visible:**

* **Chapter Name:** Choose90 SODO
* **Meeting Rhythms:** "3rd Tuesdays @ 7:00 PM"
* **Venue:** "Starbucks on 1st Ave" (or "Contact Host for Address" if meeting in a private clubhouse).
* **About:** "A group of young professionals focusing on positivity..."
* **Contact:** A **"Join / Contact" button**.
  * *Mechanism:* This opens a form. The message is sent to the Host via the system. The Host's email is never exposed directly.

## 3. The "Database" (It's already there!)

We don't need a separate SQL database. WordPress **custom post types (CPT)** are perfect for this.

* Every Chapter is just a "Post" of type `chapter`.
* **Tracking:** You can see all chapters in your WordPress Admin sidebar under a new menu called **"Chapters"**.
* **Status:** We will use WP post statuses:
  * `Publish` = Active Chapter (Visible on map/list).
  * `Draft` = Pending Application / Setting Up (Not visible yet).
  * `Private` = Paused/Full.

## 4. Admin Dashboard?

**Not yet.**

* **Phase 1 (The Rush):** You are the Admin. You use the standard WordPress Dashboard. It gives you a list, search, and edit capability out of the box.
* **Phase 2 (Nice to Have):** Later, we can build a frontend "Host Portal" where leaders login to update their own meeting times. For now, they just email you updates.

---

## 5. Immediate Action Plan (The Backbone)

I will now build the modular backbone to support this.

**Step 1: The "Chapter" Object**
I will add code to `functions.php` to register the `chapter` type.

* It will have its own menu icon in your WP Dashboard.
* It will support: Title, Content (Description), Featured Image (Group photo).

**Step 2: Chapter Data Fields**
I will add code to save specific data points for each chapter:

* `Location Name` (e.g. "SODO Public Library")
* `Meeting Pattern` (e.g. "2nd Thursdays")
* `Leader Name` (Internal use mostly)
* `Contact Email` (Hidden from public)

**Step 3: The Display (Templates)**

* `page-chapters.php`: If 0 chapters exist, it shows the "Start a Chapter" pitch. If >0 exist, it shows a list/grid filtered by State/City.
* `single-chapter.php`: The beautiful "Home Page" for that specific chapter.

**Ready to build this backbone?**
