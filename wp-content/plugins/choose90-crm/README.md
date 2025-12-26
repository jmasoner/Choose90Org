# Choose90 CRM Plugin

A comprehensive Customer Relationship Management system for Choose90 chapters, designed to centralize email communications, manage distribution lists, and provide chapter leaders with a dashboard to track and respond to communications.

## Features

### Core Functionality

- **Email Management**: Centralized email storage using WordPress custom post types
- **Distribution Lists**: Create and manage distribution lists for each chapter
- **Email Routing**: Automatic routing of emails to appropriate distribution lists based on email aliases or subject lines
- **Email Tracking**: Track email status (unread, read, replied, archived)
- **Thread Tracking**: Group related emails into conversation threads
- **Contact Management**: Automatically create and manage contacts from incoming emails

### Chapter Leader Features

- **Dashboard**: Personalized dashboard showing emails for assigned chapters
- **Status Management**: Mark emails as read or replied
- **Statistics**: View unread, replied, and total email counts
- **Filtering**: Filter emails by status, chapter, and search terms

### Admin Features

- **Distribution List Management**: Create, edit, and delete distribution lists
- **Member Management**: Add/remove chapter leaders from distribution lists
- **Email Monitoring**: View all communications across all chapters
- **Settings**: Configure email capture method (IMAP, forwarding, or API)

## Installation

1. Upload the `choose90-crm` folder to `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to CRM > Settings to configure email capture settings

## Configuration

### Email Capture Setup

The plugin supports three methods for capturing emails:

#### 1. IMAP (Recommended)
- Configure IMAP settings in CRM > Settings
- Plugin will check for new emails hourly via WordPress cron
- Requires PHP IMAP extension

#### 2. Email Forwarding
- Set up email forwarding rules on your email server
- Emails can be manually imported or captured via webhook (future feature)

#### 3. API (Future)
- Integration with email service APIs (SendGrid, Mailgun, etc.)

### Distribution Lists

1. Go to CRM > Distribution Lists
2. Create a new distribution list
3. Optionally set an email alias (e.g., `chapters+austin@choose90.org`)
4. Associate with a chapter (optional)
5. Add members (WordPress users) to the list

### Email Routing

Emails are automatically routed to distribution lists based on:

- **Plus Addressing**: If email is sent to `chapters+austin@choose90.org`, it routes to the Austin distribution list
- **Subject Line**: If subject contains a chapter name, it routes to that chapter's list
- **Manual Assignment**: Admins can manually assign emails to chapters

## Usage

### For Chapter Leaders

1. Log in to WordPress
2. Go to CRM > My Dashboard
3. View emails for your assigned chapters
4. Mark emails as read or replied
5. Click on email subject to view full details

### For Administrators

1. Go to CRM to view overall statistics
2. Manage distribution lists in CRM > Distribution Lists
3. Configure settings in CRM > Settings
4. View all emails in CRM > CRM Emails
5. Manage contacts in CRM > CRM Contacts

## Database Structure

The plugin creates the following custom database tables:

- `wp_choose90_distribution_lists`: Stores distribution list information
- `wp_choose90_distribution_list_members`: Links users to distribution lists
- `wp_choose90_crm_threads`: Tracks email conversation threads

Custom post types:
- `crm_email`: Stores individual emails
- `crm_contact`: Stores contact information

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- PHP IMAP extension (for IMAP email capture)

## Development

### File Structure

```
choose90-crm/
├── choose90-crm.php          # Main plugin file
├── includes/
│   ├── class-crm-post-types.php
│   ├── class-crm-distribution-lists.php
│   ├── class-crm-email-handler.php
│   ├── class-crm-dashboard.php
│   └── class-crm-admin.php
├── assets/
│   ├── css/
│   │   └── admin.css
│   └── js/
│       └── admin.js
└── README.md
```

## Future Enhancements

- Email templates for common responses
- Reply functionality directly from dashboard
- Mobile notifications
- Analytics dashboard
- Integration with pledge system
- Advanced search functionality
- Email scheduling
- Automated responses

## Support

For issues or questions, please contact the Choose90 development team.

## License

GPL v2 or later






