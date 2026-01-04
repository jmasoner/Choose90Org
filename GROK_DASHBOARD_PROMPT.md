# Analytics Dashboard & Admin Panel - Handoff to Grok

## Overview
Create a comprehensive admin dashboard for Choose90.org that provides real-time analytics, trends, social media insights, SEO metrics, and actionable data visualizations. This will be a WordPress admin page accessible to administrators and editors.

## Business Value
- **Real-time visibility** into what's working and what isn't
- **Data-driven decisions** for content strategy and marketing
- **Social media performance tracking** across platforms
- **SEO monitoring** to improve discoverability
- **Conversion funnel analysis** (pledges, signups, engagement)
- **Resource popularity tracking** to guide content creation

## Dashboard Location & Access
- **WordPress Admin URL**: `/wp-admin/admin.php?page=choose90-dashboard`
- **Access Level**: Administrators and Editors
- **Navigation**: Add to WordPress admin menu: "Choose90 Dashboard"

## Core Dashboard Sections

### 1. **Overview/Summary Panel** (Top Section)
**Real-time key metrics in cards:**
- Total Pledges (with trend arrow)
- Active Users (last 24 hours)
- Newsletter Signups (today, this week, this month)
- Most Popular Resource (by views/downloads)
- Social Shares (total, last 7 days)
- Content Generator Usage (today, this week)

**Visual Elements:**
- Trend indicators (↑↓) with percentage change
- Color coding (green for positive, red for negative)
- Quick comparison to previous period

### 2. **Pledge Analytics**
**Metrics to Display:**
- Total pledges over time (line chart)
- Daily/weekly/monthly pledge submissions
- Pledge source tracking (where users came from)
- Conversion rate (visitors → pledges)
- Pledge counter value (3,363+ base)

**Visualizations:**
- Line chart: Pledges over time (last 30 days)
- Bar chart: Pledges by source (referral, direct, social, etc.)
- Conversion funnel: Views → Pledge Page → Submission

### 3. **Social Media Performance**
**Platforms to Track:**
- Twitter/X
- Facebook
- LinkedIn
- Instagram
- TikTok (if applicable)

**Metrics Per Platform:**
- Hashtag performance (#Choose90, #90PercentPositive, etc.)
- Share counts
- Engagement rates
- Click-through rates
- Best performing content
- Peak engagement times

**Social Media APIs Needed:**
- Twitter/X API (for hashtag tracking, share counts)
- Facebook Graph API (for hashtag insights, engagement)
- Instagram Basic Display API (for hashtag performance)
- LinkedIn API (for share tracking)

**Visualizations:**
- Platform comparison chart
- Hashtag performance table/sorted list
- Time series: Shares over time by platform
- Top performing posts/content

### 4. **Resource Engagement**
**Metrics:**
- Most viewed resources (with view counts)
- Most downloaded resources
- Category performance (Tools, Challenges, Guides, Quick Wins)
- Average time on resource pages
- Bounce rate by resource type

**Visualizations:**
- Top 10 resources (bar chart)
- Category distribution (pie chart)
- Resource engagement over time (line chart)
- Heat map: Popular resources by time of day

### 5. **Tool Usage Analytics**
**Content Generator:**
- Total generations (post suggestions, quotes)
- Usage over time
- Most popular generation types
- Average session duration

**Browser Extension/PWA:**
- Download counts
- Installation rate
- Active users
- Usage frequency

**Visualizations:**
- Tool usage comparison
- Generation types breakdown
- Installation funnel (views → download → install)

### 6. **Newsletter & Email Metrics**
**Metrics:**
- Total subscribers
- New signups (today, week, month)
- Signup locations (footer, pledge wall, resources, etc.)
- Open rates (if integrated with email service)
- Click-through rates
- Unsubscribe rate

**Visualizations:**
- Subscriber growth over time
- Signup source breakdown
- Email performance metrics (if available)

### 7. **SEO Performance**
**Metrics:**
- Search visibility score
- Organic traffic (Google Analytics integration)
- Top landing pages
- Top search queries
- Click-through rate from search
- Average position in search results
- Backlinks count
- Domain authority (if using SEO tool)

**Visualizations:**
- Organic traffic over time
- Top 10 search queries
- Page performance ranking
- SEO health score

**Integration Needed:**
- Google Search Console API (for search queries, positions, clicks)
- Google Analytics API (for organic traffic)
- Optional: SEO tools API (SEMrush, Ahrefs, Moz)

### 8. **Campaign Performance**
**Campaigns to Track:**
- New Year's Resolution
- Kwanzaa & Choose90
- Digital Detox Guide
- 30-Day Challenge

**Metrics Per Campaign:**
- Page views
- Conversions (pledges, signups)
- Social shares
- Engagement rate
- Conversion rate
- ROI (if applicable)

**Visualizations:**
- Campaign comparison chart
- Campaign funnel (views → engagement → conversion)
- Campaign timeline with key events

### 9. **User Behavior & Journey**
**Metrics:**
- Most common navigation paths
- Drop-off points
- Session duration
- Pages per session
- Bounce rate
- Returning vs new visitors

**Visualizations:**
- User flow diagram
- Funnel visualization
- Session duration distribution
- Visitor type breakdown

### 10. **Content Performance**
**Blog/Content Pages:**
- Most viewed pages
- Average time on page
- Scroll depth
- Exit rate
- Social shares per page

**Visualizations:**
- Top 20 pages (table with metrics)
- Content performance heat map

### 11. **Traffic Sources**
**Breakdown:**
- Direct traffic
- Organic search
- Social media (by platform)
- Referrals
- Email campaigns
- Paid ads (if applicable)

**Visualizations:**
- Traffic source pie chart
- Traffic source trends over time
- Top referring sites

### 12. **Geographic Data**
**If Available:**
- Traffic by country/region
- Pledges by location
- Engagement by geography

**Visualizations:**
- World map with traffic/engagement heat map
- Top countries/regions table

## Technical Implementation

### Backend Architecture

#### Option 1: WordPress Custom Dashboard (Recommended)
- Create custom WordPress admin page
- Use WordPress hooks and filters
- Store analytics data in WordPress database
- Leverage WordPress REST API for data retrieval

#### Option 2: Standalone Dashboard
- Separate PHP page (if needed for complex visualizations)
- Fetch data from WordPress database
- Use API endpoints for real-time updates

### Data Storage

#### WordPress Database Tables:
```
wp_choose90_analytics
- id
- event_type (pledge_submitted, newsletter_signup, etc.)
- event_data (JSON)
- user_id (if logged in)
- ip_address (anonymized)
- timestamp
- page_url
- referrer
- user_agent

wp_choose90_social_metrics
- id
- platform (twitter, facebook, etc.)
- metric_type (shares, hashtag_mentions, etc.)
- metric_value
- content_url
- hashtag
- timestamp
```

#### Caching Strategy:
- Cache API responses (30 seconds to 5 minutes for real-time feel)
- Store aggregated daily/weekly/monthly summaries
- Use WordPress transients for temporary data

### API Integrations Required

1. **Google Analytics 4 API**
   - User metrics
   - Page views
   - Traffic sources
   - User behavior

2. **Google Search Console API**
   - Search queries
   - Click-through rates
   - Average position
   - Impressions

3. **Social Media APIs**
   - Twitter/X API v2 (hashtag tracking, share counts)
   - Facebook Graph API (engagement, shares)
   - Instagram Basic Display API (hashtag performance)
   - LinkedIn API (share tracking)

4. **WordPress REST API**
   - Internal site data
   - Pledge counts
   - User data

### Frontend Technologies

#### Recommended:
- **Chart.js** or **Chartist.js** (lightweight, no jQuery dependency)
- **ApexCharts** (more features, modern)
- **D3.js** (for advanced visualizations, if needed)

#### UI Framework:
- WordPress admin styles (native look and feel)
- Custom CSS for dashboard layout
- Responsive design for mobile admin access

### Data Refresh Strategy

**Real-time Data (updated every 30-60 seconds):**
- Active users
- Recent events (last hour)
- Current page views

**Near Real-time (updated every 5 minutes):**
- Social media metrics
- Pledge submissions
- Tool usage

**Periodic Updates (hourly/daily):**
- SEO metrics
- Aggregated analytics
- Trend calculations

**Background Jobs:**
- Use WordPress Cron (wp_schedule_event) for scheduled updates
- Fetch social media data periodically
- Aggregate and summarize data daily

## Dashboard Layout Design

### Header Section
- Dashboard title
- Date range selector (Today, Last 7 days, Last 30 days, Custom)
- Refresh button
- Export data button (CSV/PDF)

### Main Dashboard Grid
Use WordPress admin-style grid layout:
- Cards for key metrics (2-3 columns wide)
- Charts (full width or 2 columns)
- Tables for detailed data
- Responsive: Stacks on mobile

### Section Organization
1. **Overview Cards** (top row, 3-4 cards)
2. **Main Charts** (pledges, traffic, engagement)
3. **Social Media Performance** (platform comparison)
4. **Resource Performance** (tables and charts)
5. **SEO Metrics** (search console data)
6. **Detailed Tables** (top pages, top resources, etc.)

## File Structure

```
wp-content/
  themes/
    Divi-choose90/
      admin/
        dashboard/
          dashboard.php (main dashboard page)
          dashboard.js (frontend JavaScript)
          dashboard.css (dashboard styles)
          includes/
            api-handlers.php (API integration functions)
            data-processors.php (data aggregation)
            chart-renderers.php (chart generation helpers)
          templates/
            overview-section.php
            pledges-section.php
            social-section.php
            resources-section.php
            seo-section.php
            tools-section.php
```

Or create as WordPress plugin:
```
wp-content/
  plugins/
    choose90-dashboard/
      choose90-dashboard.php (main plugin file)
      includes/
        class-dashboard-admin.php
        class-analytics-api.php
        class-social-api.php
        class-data-aggregator.php
      admin/
        css/dashboard.css
        js/dashboard.js
      templates/
        (dashboard templates)
```

## Implementation Steps

### Phase 1: Foundation (Week 1)
1. Create WordPress admin page structure
2. Set up database tables for analytics storage
3. Create basic dashboard layout
4. Integrate Google Analytics 4 API
5. Display basic metrics (pledges, page views, signups)

### Phase 2: Core Analytics (Week 2)
1. Implement pledge analytics section
2. Add resource engagement tracking
3. Create tool usage analytics
4. Build newsletter metrics
5. Add traffic source breakdown

### Phase 3: Social Media Integration (Week 3)
1. Integrate Twitter/X API
2. Integrate Facebook Graph API
3. Integrate Instagram API
4. Create social media dashboard section
5. Implement hashtag tracking

### Phase 4: SEO & Advanced (Week 4)
1. Integrate Google Search Console API
2. Add SEO metrics section
3. Create campaign performance tracking
4. Build user behavior analytics
5. Add geographic data (if available)

### Phase 5: Polish & Optimization (Week 5)
1. Improve visualizations
2. Add data export functionality
3. Optimize performance (caching, aggregation)
4. Add alerts/notifications for key metrics
5. Documentation and user guide

## Key Features to Implement

### 1. Real-time Updates
- AJAX polling for live metrics
- WebSocket connection (optional, for true real-time)
- Auto-refresh toggle

### 2. Date Range Filtering
- Preset ranges (Today, 7 days, 30 days, 90 days, Year)
- Custom date picker
- Comparison to previous period

### 3. Export Functionality
- Export charts as images (PNG, SVG)
- Export data as CSV
- Generate PDF reports (weekly, monthly)

### 4. Alerts & Notifications
- Email alerts for significant changes
- Dashboard notifications
- Threshold alerts (e.g., "Pledges dropped 20%")

### 5. Drill-down Capabilities
- Click charts to see detailed data
- Filter and sort tables
- Link to source pages/data

### 6. Responsive Design
- Mobile-friendly dashboard
- Collapsible sections
- Touch-friendly charts

## Security Considerations

1. **Access Control**
   - WordPress capabilities check (`manage_options` or custom capability)
   - Nonce verification for all AJAX requests
   - Sanitize all user inputs

2. **API Key Storage**
   - Store API keys securely (wp-config.php or encrypted in database)
   - Never expose keys in frontend code
   - Rotate keys periodically

3. **Data Privacy**
   - Anonymize IP addresses
   - Follow GDPR/privacy regulations
   - Option to purge old data

4. **Rate Limiting**
   - Limit API calls to prevent abuse
   - Cache responses appropriately
   - Handle API failures gracefully

## Testing Requirements

1. **Functionality Testing**
   - All sections load correctly
   - Charts render properly
   - Data refreshes as expected
   - Export functions work

2. **Performance Testing**
   - Dashboard loads in < 3 seconds
   - Charts render smoothly
   - API calls don't timeout
   - Caching works correctly

3. **Browser Compatibility**
   - Test in Chrome, Firefox, Safari, Edge
   - Test on mobile devices
   - Ensure responsive layout works

4. **Data Accuracy**
   - Verify numbers match source data
   - Check calculations are correct
   - Validate date ranges work properly

## Success Criteria

- Dashboard loads in < 3 seconds
- All key metrics are visible at a glance
- Real-time data updates every 30-60 seconds
- Social media metrics refresh every 5 minutes
- Charts are interactive and informative
- Export functionality works reliably
- Mobile responsive design
- No security vulnerabilities
- Documentation is complete

## Questions to Address

1. What is the GA4 Measurement ID? (needed for API integration)
2. Do we have API credentials for social media platforms?
3. What date range should be default? (suggest: Last 30 days)
4. How frequently should we update social media data? (suggest: Every 5-15 minutes)
5. Should we store historical data or rely on API calls? (recommend: Store aggregated data)
6. What user roles should have access? (suggest: Administrators and Editors)
7. Do we need email alerts? (recommend: Yes, for key metrics)
8. Should we create a public-facing analytics page? (probably not, keep admin-only)

## Deliverables

1. **Working Dashboard**
   - All sections functional
   - Data displaying correctly
   - Charts rendering properly

2. **API Integration Code**
   - Google Analytics 4 integration
   - Google Search Console integration
   - Social media API integrations

3. **Database Schema**
   - Tables for analytics storage
   - Migration scripts if needed

4. **Documentation**
   - Setup instructions
   - API key configuration guide
   - User guide for dashboard
   - Troubleshooting guide

5. **Testing Documentation**
   - Test cases
   - Test results
   - Known issues/limitations

---

## Next Steps After Implementation

1. Set up API credentials
2. Configure data refresh schedules
3. Customize dashboard layout if needed
4. Set up alerts and notifications
5. Train team on using dashboard
6. Schedule regular dashboard reviews
7. Iterate based on usage feedback


