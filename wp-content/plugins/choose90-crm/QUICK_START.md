# Choose90 CRM - Quick Start Guide

## Installation

1. The plugin is located at: `wp-content/plugins/choose90-crm/`
2. Activate the plugin in WordPress Admin → Plugins
3. The plugin will automatically create necessary database tables on activation

## Initial Setup

### Step 1: Configure Email Settings

1. Go to **CRM → Settings**
2. Set your **CRM Email Address** (e.g., `chapters@choose90.org`)
3. Choose your **Email Capture Method**:
   - **IMAP**: For automatic email checking (requires PHP IMAP extension)
   - **Forwarding**: For manual email import
   - **API**: For future API integrations

4. If using IMAP, configure:
   - IMAP Host (e.g., `imap.choose90.org`)
   - IMAP Username
   - IMAP Password
   - IMAP Port (default: 993)

### Step 2: Create Distribution Lists

1. Go to **CRM → Distribution Lists**
2. Click "Create New Distribution List"
3. Fill in:
   - **List Name**: e.g., "Choose90 Austin - Hosts"
   - **Email Alias** (optional): e.g., `chapters+austin@choose90.org`
   - **Chapter** (optional): Select associated chapter
4. Click "Create List"

### Step 3: Add Members to Distribution Lists

1. In the distribution list table, click "Manage Members"
2. Select a user from the dropdown
3. Click "Add Member"
4. Users will receive notifications for emails routed to their lists

## How Email Routing Works

### Method 1: Plus Addressing
- Email sent to `chapters+austin@choose90.org` → Routes to Austin distribution list
- Email sent to `chapters+seattle@choose90.org` → Routes to Seattle distribution list

### Method 2: Subject Line Detection
- If subject contains a chapter name, email routes to that chapter's list
- Example: Subject "Question about Choose90 Austin" → Routes to Austin list

### Method 3: Manual Assignment
- Admins can manually assign emails to chapters via the email edit screen

## Using the Dashboard

### For Chapter Leaders

1. Go to **CRM → My Dashboard**
2. View statistics:
   - Unread emails
   - Replied emails
   - Total emails
   - Distribution lists
3. Filter emails by:
   - Status (unread, read, replied, archived)
   - Chapter
   - Search terms
4. Actions:
   - Click email subject to view full details
   - Mark as Read
   - Mark as Replied

### For Administrators

1. Go to **CRM** (main page) for overview statistics
2. **CRM → Distribution Lists**: Manage lists and members
3. **CRM → Settings**: Configure email capture and notifications
4. **CRM → CRM Emails**: View all emails
5. **CRM → CRM Contacts**: Manage contacts

## Email Status Workflow

1. **Unread**: New email, not yet viewed
2. **Read**: Email has been viewed
3. **Replied**: Response has been sent
4. **Archived**: Email is archived (can be set manually)

## Email Threading

- Emails with the same subject and chapter are automatically grouped into threads
- Threads track conversation history
- Last message time is updated automatically

## Contact Management

- Contacts are automatically created from incoming emails
- Contact information includes:
  - Name
  - Email
  - Phone (if provided)
  - Associated chapters
  - Last contact date
- View all contacts in **CRM → CRM Contacts**

## Troubleshooting

### Emails Not Being Captured

1. Check IMAP settings in **CRM → Settings**
2. Verify PHP IMAP extension is installed: `php -m | grep imap`
3. Check WordPress cron is running (emails checked hourly)
4. Manually trigger email check: Use WP-CLI or a cron testing plugin

### Distribution Lists Not Receiving Emails

1. Verify list members are added correctly
2. Check email routing (plus addressing or subject line)
3. Ensure notifications are enabled in settings

### Permissions Issues

- Chapter leaders need `read` capability
- Administrators need `manage_options` capability
- Check user roles if dashboard is not accessible

## Next Steps

1. **Test with one chapter**: Create a test distribution list and send a test email
2. **Configure email forwarding**: Set up email forwarding rules on your email server
3. **Train chapter leaders**: Show them how to use the dashboard
4. **Monitor**: Check CRM statistics regularly

## Support

For issues or questions, refer to the main README.md file or contact the development team.








