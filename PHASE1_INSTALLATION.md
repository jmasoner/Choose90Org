# Phase 1 Installation Guide: Enhanced Pledge Form + Personalization

## Files Created

### 1. WordPress Functions Files
- `hybrid_site/wp-functions-pledge.php` - Pledge form handler
- `hybrid_site/wp-functions-personalization.php` - Personalization functions

### 2. Form Components
- `hybrid_site/components/pledge-form.php` - Enhanced pledge form
- `hybrid_site/components/pledge-form.html` - HTML version (backup)

### 3. JavaScript & CSS
- `hybrid_site/js/badge-system.js` - Badge system
- `hybrid_site/js/social-sharing.js` - Social sharing
- `hybrid_site/js/personalization.js` - Personalization
- `hybrid_site/css/pledge-form.css` - Form styles
- `hybrid_site/css/badges-sharing.css` - Badge & sharing styles

### 4. Updated Files
- `hybrid_site/page-pledge.php` - Updated to include new form

## Installation Steps

### Step 1: Add WordPress Functions

Add the following to your child theme's `functions.php`:

```php
// Include Choose90 pledge functions
require_once get_stylesheet_directory() . '/../hybrid_site/wp-functions-pledge.php';

// Include Choose90 personalization functions
require_once get_stylesheet_directory() . '/../hybrid_site/wp-functions-personalization.php';
```

**OR** copy the contents of:
- `wp-functions-pledge.php`
- `wp-functions-personalization.php`

Directly into your child theme's `functions.php` file.

### Step 2: Deploy Files

Run your deployment script:
```powershell
.\setup_child_theme.ps1
```

This will deploy:
- `page-pledge.php` (updated)
- All CSS and JS files

### Step 3: Update Pledge Form Path

In `page-pledge.php`, the form path needs to match your server structure. 

**If files are in:** `wp-content/themes/Divi-choose90/components/`
```php
include(get_stylesheet_directory() . '/components/pledge-form.php');
```

**If files are in:** `hybrid_site/components/` (relative to theme)
```php
include(get_stylesheet_directory() . '/../hybrid_site/components/pledge-form.php');
```

### Step 4: Enqueue Styles & Scripts

Make sure your theme's `functions.php` enqueues:
- `pledge-form.css`
- `badge-system.js`
- `social-sharing.js`
- `badges-sharing.css`

Or add to `wp-functions-pledge.php`:
```php
function choose90_enqueue_assets() {
    wp_enqueue_style('choose90-pledge-form', get_stylesheet_directory_uri() . '/../hybrid_site/css/pledge-form.css');
    wp_enqueue_style('choose90-badges-sharing', get_stylesheet_directory_uri() . '/../hybrid_site/css/badges-sharing.css');
    wp_enqueue_script('choose90-badge-system', get_stylesheet_directory_uri() . '/../hybrid_site/js/badge-system.js', array('jquery'), '1.0', true);
    wp_enqueue_script('choose90-social-sharing', get_stylesheet_directory_uri() . '/../hybrid_site/js/social-sharing.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'choose90_enqueue_assets');
```

### Step 5: Test the Form

1. Visit `/pledge/` page
2. Fill out the form
3. Submit
4. Check WordPress admin → Users to see new user created
5. Check user meta to see all fields saved

## What Happens When User Submits

1. **User Account Created** - WordPress user with email as username
2. **User Meta Saved** - All profile fields stored
3. **Badge Awarded** - "I Choose 90" badge added to user meta
4. **User Logged In** - Automatically logged in after pledge
5. **Welcome Email Sent** - Personalized welcome email
6. **Success Message** - Shows badge notification + share buttons

## Database Structure

User meta fields created:
- `screen_name` - Display name
- `phone` - Phone number
- `location_city` - City
- `location_state` - State/Province
- `facebook_url` - Facebook URL
- `instagram_handle` - Instagram handle
- `twitter_handle` - Twitter/X handle
- `tiktok_handle` - TikTok handle
- `linkedin_url` - LinkedIn URL
- `reddit_username` - Reddit username
- `pledge_date` - Date pledge was taken
- `badges_earned` - JSON array of badge IDs
- `resource_count` - Number of resources completed
- `last_active` - Last active timestamp

## Troubleshooting

### Form doesn't submit
- Check browser console for errors
- Verify `admin-post.php` is accessible
- Check nonce verification

### User not created
- Check WordPress error logs
- Verify email isn't already registered
- Check password meets requirements (8+ characters)

### Styles not loading
- Verify file paths in `wp_enqueue_style/script`
- Check file permissions
- Clear browser cache

### Badge not awarded
- Check user meta for `badges_earned`
- Verify JavaScript files are loading
- Check browser console for errors

## Next Steps

After Phase 1 is working:
1. Test form submission
2. Verify user creation
3. Test badge system
4. Test social sharing
5. Move to Phase 2 (AI phone setup, etc.)

