# Pledge Form - Where Data Goes

## Current Implementation

When someone submits the pledge form, here's what happens:

### 1. WordPress User Account Creation ✓
- **Location**: `wp_users` table in WordPress database
- **Username**: Email address
- **Password**: User-provided (hashed by WordPress)
- **Role**: `subscriber` (default WordPress role)
- **Status**: Active, logged in automatically

### 2. User Meta Data Storage ✓
- **Location**: `wp_usermeta` table
- **Fields Stored**:
  - `screen_name` - Display name
  - `phone` - Phone number (optional)
  - `location_city` - City
  - `location_state` - State/Province
  - `facebook_url` - Facebook profile
  - `instagram_handle` - Instagram handle
  - `twitter_handle` - Twitter/X handle
  - `tiktok_handle` - TikTok handle
  - `linkedin_url` - LinkedIn profile
  - `reddit_username` - Reddit username
  - `pledge_date` - When they took the pledge
  - `badges_earned` - JSON array of badge IDs
  - `resource_count` - Number of resources completed
  - `last_active` - Last activity timestamp

### 3. Email Notification ✓
- **Recipient**: User's email address
- **Subject**: "Welcome to Choose90! You've earned your first badge 🎯"
- **Content**: Welcome message with badge notification and next steps

### 4. Automatic Login ✓
- User is automatically logged in after pledge
- WordPress authentication cookie is set
- User can immediately access member features

## ❌ NOT Currently Integrated

### CRM System
- **Status**: NOT automatically created
- **Reason**: CRM plugin exists but isn't integrated with pledge form
- **Location**: `wp-content/plugins/choose90-crm/`
- **What it would do**: Create a contact/lead entry in CRM for tracking

## Integration Options

### Option 1: Add CRM Integration (Recommended)
Add to `wp-functions-pledge.php` after user creation:

```php
// Add to CRM if plugin is active
if (class_exists('Choose90_CRM')) {
    $crm = new Choose90_CRM();
    $crm->create_contact(array(
        'name' => $full_name,
        'email' => $email,
        'phone' => $phone,
        'source' => 'pledge_form',
        'status' => 'new_member'
    ));
}
```

### Option 2: Manual CRM Entry
- Admin can manually add users to CRM
- Or export WordPress users and import to CRM

### Option 3: Scheduled Sync
- Create a cron job to sync WordPress users to CRM
- Runs daily/weekly to keep CRM updated

## Data Access

### View Pledge Data in WordPress Admin
1. Go to **Users → All Users**
2. Find user by email
3. Click "Edit"
4. Scroll to "Choose90 Profile Information" section
5. See all stored meta fields

### Export User Data
- Use WordPress export tools
- Or query database directly:
  ```sql
  SELECT * FROM wp_users WHERE ID = [user_id];
  SELECT * FROM wp_usermeta WHERE user_id = [user_id];
  ```

## Privacy & GDPR

- All data stored in WordPress database
- Users can request data export
- Users can request account deletion
- Data is encrypted (passwords) and sanitized (all inputs)

## Next Steps

- [ ] Integrate CRM plugin with pledge form
- [ ] Add data export functionality
- [ ] Create admin dashboard for viewing pledges
- [ ] Set up automated CRM sync

