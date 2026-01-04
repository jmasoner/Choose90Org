# Analytics & Tracking Implementation - Handoff to Grok

## Context
Choose90.org is a hybrid WordPress/static HTML site with multiple features that need comprehensive analytics tracking. We need to implement Google Analytics 4 (GA4) with custom event tracking for key user interactions.

## Current Site Structure

### Key Pages to Track
- **Homepage**: `/index.html`
- **Pledge Page**: `/pledge/` (WordPress)
- **Pledge Wall**: `/pledge-wall.html`
- **Resources Hub**: `/resources-hub.html`
- **Content Generator**: `/tools/content-generator.html`
- **Browser Extension/PWA**: `/pwa.html`
- **New Year's Resolution**: `/new-years-resolution.html`
- **Kwanzaa Pages**: `/kwanzaa-choose90.html`, `/kwanzaa-challenge.html`
- **Digital Detox Guide**: `/digital-detox-guide.html`
- **30-Day Challenge**: `/resources/30-day-choose90-challenge.html`

### Key User Actions to Track

#### 1. Pledge Actions
- **Event**: `pledge_submitted`
  - User submits the pledge form
  - Track on: `/pledge/` page
  - Parameters: `pledge_type` (optional)

- **Event**: `pledge_wall_viewed`
  - User views the pledge wall
  - Track on: `/pledge-wall.html`

- **Event**: `pledge_counter_viewed`
  - User sees the pledge counter (3,363+)
  - Track on: `/pledge-wall.html`

#### 2. Newsletter Signup
- **Event**: `newsletter_signup`
  - User subscribes to newsletter
  - Track on: Multiple locations (footer, pledge wall, resources page, success messages)
  - Parameters: `signup_location` (footer, pledge_wall, resources, etc.)

#### 3. Tool Usage
- **Event**: `content_generator_used`
  - User generates content using the AI tool
  - Track on: `/tools/content-generator.html`
  - Parameters: `generation_type` (post_suggestion, quote)

- **Event**: `browser_extension_downloaded`
  - User downloads the browser extension
  - Track on: `/pwa.html`
  - Parameters: `platform` (chrome, edge, etc.)

- **Event**: `pwa_installed`
  - User installs the PWA
  - Track on: `/pwa.html`

#### 4. Social Sharing
- **Event**: `social_share`
  - User shares content on social media
  - Track on: Multiple locations
  - Parameters: `platform` (twitter, facebook, linkedin, instagram), `share_type` (pledge, content, resource), `content_url`

- **Event**: `social_handle_captured`
  - User provides social media handle
  - Track on: `/pledge-wall.html`
  - Parameters: `platform` (twitter, facebook, linkedin, instagram)

#### 5. Resource Engagement
- **Event**: `resource_viewed`
  - User views a resource page
  - Track on: Resources hub and individual resource pages
  - Parameters: `resource_name`, `resource_category` (tools, challenges, guides, quick_wins)

- **Event**: `resource_downloaded`
  - User clicks to view/download a resource
  - Track on: Resources hub
  - Parameters: `resource_name`, `resource_category`

- **Event**: `category_filtered`
  - User filters resources by category
  - Track on: `/resources-hub.html`
  - Parameters: `category` (all, tools, challenges, guides, quick_wins)

#### 6. Campaign Engagement
- **Event**: `campaign_viewed`
  - User views a campaign page
  - Track on: Campaign landing pages
  - Parameters: `campaign_name` (new_years, kwanzaa, digital_detox)

- **Event**: `campaign_cta_clicked`
  - User clicks campaign CTA button
  - Track on: Campaign pages
  - Parameters: `campaign_name`, `cta_text`

#### 7. Challenge Engagement
- **Event**: `challenge_started`
  - User starts a challenge
  - Track on: Challenge pages
  - Parameters: `challenge_name` (30_day, 7_day_kwanzaa, digital_detox)

- **Event**: `challenge_day_completed`
  - User completes a day in a challenge
  - Track on: Challenge pages
  - Parameters: `challenge_name`, `day_number`

#### 8. Navigation
- **Event**: `menu_clicked`
  - User clicks main navigation item
  - Track on: All pages
  - Parameters: `menu_item` (home, pledge, resources, chapters, etc.)

- **Event**: `dropdown_opened`
  - User opens a dropdown menu
  - Track on: Header navigation
  - Parameters: `dropdown_name` (pledge, resources)

## Implementation Requirements

### 1. Google Analytics 4 Setup
- Add GA4 tracking code to all pages
- Use Measurement ID: `G-XXXXXXXXXX` (get from GA4_CONFIGURATION.md or .env - keep actual ID private)
- Implement via Google Tag Manager (recommended) OR direct GA4 implementation
- Ensure tracking works on both WordPress and static HTML pages

### 2. Event Tracking Implementation

#### For Static HTML Pages:
- Add event tracking JavaScript to appropriate pages
- Use `gtag('event', 'event_name', { parameters })` syntax
- Include event tracking in existing JavaScript files where possible

#### For WordPress Pages:
- Add event tracking via theme `functions.php` or custom plugin
- Ensure events fire on WordPress-powered pages (`/pledge/`, `/chapters/`, etc.)

### 3. File Structure
All analytics code should be organized as:
```
hybrid_site/
  js/
    analytics.js (main analytics configuration and helpers)
    analytics-events.js (event tracking functions)
  components/
    analytics-snippet.html (GA4/GTM snippet for inclusion in headers)
```

### 4. Implementation Files to Update/Create

#### Create:
- `hybrid_site/js/analytics.js` - Main analytics configuration
- `hybrid_site/js/analytics-events.js` - Event tracking helpers
- `hybrid_site/components/analytics-snippet.html` - GA4 snippet component

#### Update:
- `hybrid_site/components/static-header.html` - Add analytics snippet
- `hybrid_site/index.html` - Add analytics snippet
- `hybrid_site/pledge-wall.html` - Add event tracking for pledge actions
- `hybrid_site/resources-hub.html` - Add event tracking for resource engagement
- `hybrid_site/tools/content-generator.html` - Add event tracking for tool usage
- `hybrid_site/pwa.html` - Add event tracking for extension/PWA
- All campaign pages - Add event tracking
- WordPress theme `header.php` - Add analytics snippet
- WordPress theme `functions.php` - Add event tracking helpers

### 5. Specific Implementation Details

#### Newsletter Signup Tracking
Track signups from these locations:
- Footer newsletter form
- Pledge wall newsletter section
- Pledge success message newsletter form
- Resources page newsletter section
- Any other newsletter signup forms

Each should include `signup_location` parameter.

#### Social Sharing Tracking
Track shares from:
- Pledge wall social sharing buttons
- Resource cards
- Campaign pages
- Any "Share this" buttons

Include platform and content being shared.

#### Resource Category Filtering
Track when users click category filters on resources page:
- All Resources
- Tools
- Challenges
- Guides
- Quick Wins

#### Tool Usage Tracking
Track:
- Content Generator: Each generation (post/quote)
- Browser Extension: Download initiation, actual installation (if detectable)
- PWA: Installation attempts and completions

### 6. Testing Requirements
- Verify all events fire correctly
- Test on both static HTML and WordPress pages
- Verify event parameters are captured correctly
- Test in GA4 Real-Time reports
- Verify tracking works with ad blockers (graceful degradation)

### 7. Code Quality Standards
- Use consistent event naming (snake_case)
- Include all relevant parameters for each event
- Add comments explaining what each event tracks
- Ensure code doesn't break if GA4 fails to load
- Use async/defer for analytics scripts where possible
- Follow GA4 best practices for event naming and parameters

### 8. Privacy Considerations
- Ensure compliance with privacy policies
- Consider cookie consent if needed
- Make tracking opt-in if required by jurisdiction
- Document what data is collected

## Expected Deliverables

1. **GA4 Configuration Code**
   - Snippet to include in site headers
   - Properly configured Measurement ID placeholder

2. **Event Tracking Implementation**
   - All events listed above implemented
   - Helper functions for common tracking patterns
   - Documentation of event structure

3. **Updated Files**
   - All pages updated with appropriate tracking
   - WordPress integration complete
   - Clean, maintainable code

4. **Testing Checklist**
   - List of all events to verify
   - Testing instructions
   - Expected behavior documentation

5. **Documentation**
   - Event tracking reference guide
   - How to add new events
   - Troubleshooting guide

## Questions to Address

1. What is the GA4 Measurement ID? (If not available, use placeholder and document)
2. Should we use Google Tag Manager or direct GA4 implementation? (Recommend GTM for flexibility)
3. Are there any additional events needed beyond those listed?
4. Do we need enhanced ecommerce tracking for donations? (Future consideration)
5. Should we track user journeys/funnels? (Pledge submission funnel, resource discovery funnel)

## Success Criteria

- All key user actions are tracked
- Events appear in GA4 Real-Time reports
- Code is clean and maintainable
- Documentation is complete
- No performance impact from tracking
- Works across all browsers and devices
- Gracefully handles tracking failures

---

## Next Steps After Implementation

1. Verify tracking in GA4 Real-Time
2. Set up custom reports/dashboards
3. Create conversion goals
4. Set up alerts for key metrics
5. Schedule regular analytics reviews
6. Document insights and recommendations

