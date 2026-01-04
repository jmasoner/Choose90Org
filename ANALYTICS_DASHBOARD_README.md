# Choose90 Analytics Dashboard

## Overview

A comprehensive analytics dashboard that provides real-time visibility into Choose90.org's performance, integrating data from Google Analytics 4, Google Search Console, Social Media APIs, and WordPress.

## Features

### Real-Time Visibility
- **60-second refresh interval** for up-to-date metrics
- **Caching system** for performance (60-second cache expiry)
- **Manual refresh button** for instant updates
- **Last updated timestamp** display

### Dashboard Sections

#### 1. Overview Cards
- Visitors (today vs last week)
- Pledge conversions (7 days)
- Newsletter signups (7 days)
- Bounce rate

#### 2. Pledge Analytics
- Conversion trends (30-day chart)
- Conversion sources breakdown
- Total pledges, 30-day count, conversion rate
- Source attribution

#### 3. Social Media Performance
- Platform breakdown (Facebook, Twitter, LinkedIn, etc.)
- Hashtag performance (#Choose90 tracking)
- Social engagement trends
- Share counts by platform

#### 4. Resource Engagement
- Most popular resources (top 10)
- Category performance (tools, challenges, guides, quick wins)
- Resource view trends
- Category filtering analytics

#### 5. Tool Usage
- Content Generator usage statistics
- Generation type breakdown (post_suggestion vs quote)
- Browser Extension downloads
- PWA installs
- Usage trends

#### 6. Newsletter Metrics
- Signup locations (footer, pledge_wall, pledge_success, resources)
- Signup trends (30-day chart)
- Total signups and weekly counts
- Location performance

#### 7. SEO Performance (Search Console)
- Top search queries
- Total clicks and impressions
- Average CTR and position
- Top performing pages
- Search trend data

#### 8. Campaign Performance
- **Kwanzaa Campaign**: Views, CTA clicks, conversion rate
- **New Year's Campaign**: Views, CTA clicks, conversion rate
- **Digital Detox Campaign**: Views, CTA clicks, conversion rate
- Campaign trend data

#### 9. Traffic Sources
- Organic search
- Direct traffic
- Social media
- Referral traffic
- Email campaigns
- Paid advertising
- Visual breakdown chart

## Installation

### 1. Files Created

**Core Files:**
- `wp-content/plugins/choose90-crm/includes/class-crm-analytics.php` - Main analytics class
- `wp-content/plugins/choose90-crm/templates/analytics-dashboard.php` - Dashboard template
- `wp-content/plugins/choose90-crm/templates/analytics-settings.php` - Settings page
- `wp-content/plugins/choose90-crm/assets/css/analytics-dashboard.css` - Dashboard styles
- `wp-content/plugins/choose90-crm/assets/js/analytics-dashboard.js` - Dashboard JavaScript

**Database:**
- Newsletter signups table created automatically on plugin activation

### 2. Access the Dashboard

1. Go to **WordPress Admin → CRM → Analytics**
2. The dashboard will load with cached data
3. Click "Refresh Data" to get latest metrics

### 3. Configure API Keys

1. Go to **WordPress Admin → CRM → Analytics Settings**
2. Enter your API credentials:
   - **GA4 Measurement ID** (required for basic tracking)
   - **GA4 Property ID** (for API access)
   - **GA4 API Key** (JSON service account key)
   - **Search Console** credentials (optional)
   - **Social Media API keys** (optional)

## API Setup Instructions

### Google Analytics 4

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing
3. Enable **Google Analytics Data API**
4. Create a **Service Account**:
   - Go to IAM & Admin → Service Accounts
   - Click "Create Service Account"
   - Name it (e.g., "Choose90 Analytics")
   - Grant "Viewer" role
5. Create and download JSON key:
   - Click on the service account
   - Go to "Keys" tab
   - Click "Add Key" → "Create new key" → JSON
   - Download the JSON file
6. Add service account to GA4:
   - Go to GA4 Admin → Property Access Management
   - Add the service account email
   - Grant "Viewer" permissions
7. Copy the JSON key content and paste in Analytics Settings

### Google Search Console

1. Use the same service account from GA4
2. Go to Search Console → Settings → Users and permissions
3. Add the service account email as an **Owner**
4. Use the same JSON key in Analytics Settings

### Social Media APIs (Optional)

**Twitter/X:**
- Apply for API access at [developer.twitter.com](https://developer.twitter.com/)
- Create an app and generate Bearer Token
- Paste in Analytics Settings

**Facebook/Instagram:**
- Create app at [developers.facebook.com](https://developers.facebook.com/)
- Generate access token
- Paste in Analytics Settings

**LinkedIn:**
- Create app at [developer.linkedin.com](https://developer.linkedin.com/)
- Generate access token
- Paste in Analytics Settings

## Data Sources

### Primary: Google Analytics 4
- All event tracking data
- Traffic sources
- User behavior metrics
- Conversion funnels

### Secondary: WordPress Database
- Newsletter signups (local storage)
- Pledge submissions (if stored locally)
- Internal metrics

### Optional: Search Console
- Search query data
- Click-through rates
- Average positions
- Top pages

### Optional: Social Media APIs
- Hashtag mentions
- Engagement metrics
- Platform-specific insights

## Caching & Performance

- **Cache Duration**: 60 seconds
- **Cache Group**: `choose90_analytics`
- **Auto-Refresh**: Every 60 seconds
- **Manual Refresh**: Available via button
- **Cache Clearing**: Automatic on manual refresh

## Security

- All API keys stored securely in WordPress options
- Settings page requires `manage_options` capability
- AJAX requests protected with nonces
- API keys masked in password fields

## Customization

### Adding New Metrics

1. Add method to `class-crm-analytics.php`:
```php
public function get_custom_metric() {
    // Your logic here
    return $data;
}
```

2. Call in `render_dashboard()`:
```php
$custom_data = $this->get_custom_metric();
```

3. Pass to template and display in dashboard

### Adding New Charts

1. Add canvas element to `analytics-dashboard.php`:
```html
<canvas id="custom-chart"></canvas>
```

2. Initialize in `analytics-dashboard.js`:
```javascript
charts.customChart = createLineChart('custom-chart', {
    labels: [...],
    datasets: [...]
});
```

## Troubleshooting

### Dashboard Shows No Data
1. Verify GA4 Measurement ID is correct
2. Check that events are firing (use GA4 Real-Time reports)
3. Ensure API keys are valid
4. Check browser console for JavaScript errors

### Charts Not Displaying
1. Verify Chart.js is loading (check Network tab)
2. Check browser console for errors
3. Ensure data is being passed to JavaScript
4. Verify canvas elements exist in DOM

### API Errors
1. Verify service account has correct permissions
2. Check API keys are valid JSON
3. Ensure APIs are enabled in Google Cloud Console
4. Check WordPress error logs

### Cache Issues
1. Click "Refresh Data" to clear cache
2. Check cache is working: `wp_cache_get()` should return data
3. Verify cache group is correct

## Future Enhancements

- [ ] User behavior funnels
- [ ] Custom date range selection
- [ ] Export data to CSV
- [ ] Email reports
- [ ] Custom alerts for key metrics
- [ ] Comparison periods (week-over-week, month-over-month)
- [ ] Goal tracking
- [ ] Enhanced ecommerce tracking (for donations)

## Support

For issues or questions:
1. Check WordPress error logs
2. Verify API credentials
3. Test in GA4 Real-Time reports
4. Review browser console for JavaScript errors

---

**Status:** ✅ Core dashboard complete
**Next:** Configure API keys and test with real data


