# Choose90 Chapters: The Concept

Since "Choose90" is about rejecting negative algorithms and choosing human goodness, **Chapters are simply the real-world version of that choice.**

Right now, your users are taking a pledge online. That is powerful, but it is solitary. A Chapter turns that solitary pledge into a community force.

## 1. What is a "Chapter"?

Don't think of it like a formal non-profit branch with a board of directors and bank accounts. Think of it like a **Book Club for Positivity.**

* **The Name:** Keep it standard. **"Choose90 [City Name]"**.
  * *Examples:* Choose90 Austin, Choose90 Seattle, Choose90 London.
* **The Vibe:** Informal, safe, and recharging. It is an "Algorithm-Free Zone."

## 2. How it Works (The "Coffee Shop Model")

We want to make this **extremely low friction** for people to start.

* **The Leader:** We call them a "Host." Their only job is to pick a time and place.
* **The Venue:** A local coffee shop, a park, or a library meeting room. Somewhere free and public.
* **The Frequency:** Once a month. (e.g., "The 3rd Saturday morning of every month").

### Sample Meeting Agenda (1 Hour)

We give every Host a simple 1-page PDF guide so they aren't nervous.

1. **0:00 - 0:15:** **Coffee & Connection.** No screens allowed.
2. **0:15 - 0:30:** **The "Good News" Round.** Go around the circle. Everyone must share ONE positive thing that happened to them or that they saw this month.
3. **0:30 - 0:45:** **The Discussion.** The Host reads the "Topic of the Month" from the website (e.g., "How to handle negative coworkers"). The group discusses.
4. **0:45 - 1:00:** **The Mission.** The group decides on one small act of kindness to do before the next meeting (e.g., "Write a thank you note to a teacher").

## 3. Why This Works

* **It solves loneliness.** People are desperate for connection that isn't political or argumentative.
* **It's scalable.** You (John) don't have to manage them. You just provide the "Kit" and the website listing.
* **It creates "Super Users".** A person who hosts a chapter is a Choose90 evangelist for life.

## 4. Technical Strategy: "Seed and Grow"

Since we have zero chapters right now, we shouldn't build a complex directory of empty pages. Instead, we build a **Launchpad**.

### The "Chapters" Page on the Website

Instead of a map of empty pins, the page will say:
> **"Bring Choose90 to Your City."**
> *There are currently no active chapters. Be the first.*

**The Workflow:**

1. **Interest:** User visits `/chapters/`.
2. **Action:** They see a button: **"Apply to Host a Chapter"**.
3. **Form:** They pledge to follow the "Code of Conduct" (No politics, purely positive) and submit their City/Email.
4. **Activation:** You receive the email. You send them the "Host Starter Kit" (PDF).
5. **Listing:** Once they pick a date/venue, YOU create the `chapter` post on the site:
    * **Title:** Choose90 [City]
    * **Status:** Active
    * **Meeting Info:** "Tuesdays @ 7pm at Joe's Coffee"

## Summary Suggestion

**Start small.** Let's build the **Apply to Host** mechanism first. We can add the complex map/directory features later once you actually have 5-10 chapters running.

**Does this "Low Stress / Coffee Shop" model make sense to you?**
