# Choose90 SMS System Requirements & Answers

Use these details to configure the SMS Pledge System with Genspark/Deepseek.

## 1. Current WordPress Setup

* **Data Storage:** Currently standard WordPress tables (`wp_posts`). We want to move to a **Custom SQL Table** (within the existing WP database) for efficient SMS handling.
* **User DB:** No existing subscriber database. We will build one from scratch with this system.
* **Capabilities:** **YES**, we can run custom PHP. We are using a Child Theme with custom templates (e.g., `page-pledge.php`) and can create custom REST endpoints or form handlers.
* **Environment:** Shared Hosting / cPanel. Not a restricted managed environment.

## 2. Database Infrastructure

* **Engine:** **MySQL** (Use the existing WordPress database).
* **Architecture:** Create a new custom table (e.g., `wp_c90_pledges`) to store:
  * Phone Number (Primary Key/Unique)
  * First Name
  * Timezone / Zip
  * Start Date
  * Current Day (1-90)
  * Status (Active/Stopped)
* **Why:** Easier and cheaper than spinning up a separate PostgreSQL instance.

## 3. Automation Platform

* **Platform:** **Custom PHP Scripts via Cron Jobs.**
* **Why:** We have cPanel hosting which includes generic Cron Jobs. This avoids external SaaS costs (Zapier/n8n) and keeps user data on our own server.

## 4. Twilio Setup

* **Status:** No account yet.
* **Need:** Please provide full setup steps for:
  * Creating Twilio Account.
  * Buying a number (or using Alphanumeric Sender ID).
  * Setting up the Messaging Service SID.
* **Volume:** Low initial volume (<500 msgs/day), scaling up.

## 5. Pledge Form Integration

* **Current Data:** Form collects Name & Email.
* **New Requirements:** We need to modify `page-pledge.php` to add:
  * **Mobile Number** field.
  * **Zip Code** (for Timezone lookup) OR a Timezone dropdown.
  * **Legal Opt-in Checkbox** ("I agree to receive messages...").
* **Onboarding:** Immediate "Welcome" SMS upon successful form submission.

## 6. Server Environment

* **Hosting:** GreenGeeks (Shared cPanel).
* **Access:** FTP, Web Disk, WP Admin, Cron Jobs.
* **Constraint:** Scripts must be efficient (batch processing) to avoid PHP timeouts on shared hosting.

## 7. Timezone Strategy

* **Requirement:** **Local Time Delivery.**
* **Logic:** The Cron Job should run hourly. It checks the DB for users whose local time is currently 7:00 AM (or target send time) and sends the message only to them.

## 8. Development Request

**"We are running a Hybrid WordPress site using custom PHP templates (`page-pledge.php`). Please provide:**

1. **The MySQL CREATE TABLE statement** for the subscriber database.
2. **The PHP Form Handler logic** to validate phone numbers and insert into this table.
3. **The Cron Script logic** to query this table and send Twilio messages."
