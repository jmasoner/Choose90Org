# Choose90 User Profile Fields

## Required Fields (Pledge Form)
- **Email** (username, unique)
- **Full Name**
- **Screen Name** (display name, changeable, not unique)
- **Password**

## Optional but Encouraged
- **Phone Number** - "We use this to personalize your experience and connect you with local chapters"
- **Location** (City, State) - "Connect with local chapters and community"

## Social Media (Optional)
- **Facebook** - "Share your Choose90 journey"
- **Twitter/X** - "Share your Choose90 journey"
- **LinkedIn** - "Share your Choose90 journey"
- **Instagram** - "Share your Choose90 journey"

## Progressive Profiling
Capture as user engages:
- **Phone Type** (from phone setup guide)
- **Interests** (which resources they use)
- **Chapter Membership**
- **Badges Earned**
- **Donation History**
- **Engagement Score**
- **Last Active Date**

## WordPress User Meta Structure

```php
// Basic Info
screen_name
phone
location_city
location_state

// Social Media
facebook_url
twitter_handle
linkedin_url
instagram_handle

// Engagement
pledge_date
last_active
engagement_score
resource_count
badges_earned (JSON array)
preferences (JSON)

// Donations
donation_total
donation_type (one-time/recurring)
donation_amount
donation_date
supporter_level
```

