# Choose90 Testimonials Widget Setup

## Overview
This widget replaces the "Recent Comments" sidebar widget with a rotating testimonials display that shows testimonials from Choose90 members.

## Installation

### Step 1: Deploy the Widget File
The widget file `wp-widget-testimonials.php` needs to be in your WordPress theme's directory or loaded via functions.php.

**Option A: Load via functions.php (Recommended)**
Add this line to your theme's `functions.php` file:
```php
require_once get_template_directory() . '/wp-widget-testimonials.php';
```

**Option B: Place in Theme Directory**
Copy `wp-widget-testimonials.php` to your active WordPress theme directory, then add to `functions.php`:
```php
require_once get_stylesheet_directory() . '/wp-widget-testimonials.php';
```

### Step 2: Add Widget to Sidebar
1. Go to WordPress Admin → Appearance → Widgets
2. Find the "Choose90 Testimonials" widget
3. Drag it to your sidebar (usually "Primary Sidebar" or "Main Sidebar")
4. Optionally set a custom title (default is "What Others Are Saying")
5. Click "Save"

### Step 3: Remove Recent Comments Widget (Optional)
1. In WordPress Admin → Appearance → Widgets
2. Find the "Recent Comments" widget in your sidebar
3. Click the three-dot menu (⋮) on the widget
4. Select "Delete" to remove it

## Features
- ✅ Rotating testimonials from the same database as the pledge page
- ✅ Auto-rotates every 5 seconds
- ✅ Navigation dots for manual browsing
- ✅ Styled with yellow/gold gradient to match Choose90 branding
- ✅ Responsive design
- ✅ Customizable title

## API Requirements
The widget uses the `/api/get-testimonials.php` endpoint, which should already be deployed with the testimonials system.

## Customization
You can customize the widget title in WordPress Admin → Appearance → Widgets → Choose90 Testimonials → Title field.

The widget automatically loads testimonials from the same JSON file (`/data/testimonials.json`) used by the pledge page, so all testimonials are synced across the site.
