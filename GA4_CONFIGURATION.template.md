# Google Analytics 4 Configuration Template

## Configuration Details

- **Measurement ID**: `G-XXXXXXXXXX` (Replace with your actual ID)
- **Stream ID**: `XXXXXXXXXX` (Replace with your actual Stream ID)
- **Stream Name**: `your-stream-name`
- **Stream URL**: `https://your-domain.com`

## Implementation Status

### ✅ Completed
- Analytics snippet updated with Measurement ID
- Event tracking configured
- All tracking functions using correct ID
- Analytics dashboard settings pre-configured

### Files Updated
1. `hybrid_site/components/analytics-snippet.html` - Main GA4 snippet
2. `hybrid_site/js/analytics.js` - Page view tracking
3. `wp-content/plugins/choose90-crm/templates/analytics-settings.php` - Dashboard settings

## Enhanced Measurement

Your GA4 property should have **Enhanced Measurement** enabled, which automatically tracks:
- Page views
- Scrolls
- Outbound clicks
- Site search
- Video engagement
- File downloads

## Next Steps for Analytics Dashboard

To enable full Analytics Dashboard functionality, you'll need:

1. **GA4 Property ID** (for API access)
   - Found in GA4 Admin → Property Settings
   - Format: `123456789` (numeric)

2. **Service Account JSON Key** (for API authentication)
   - Create in Google Cloud Console
   - Enable Google Analytics Data API
   - Add service account to GA4 property with "Viewer" role

3. **Search Console Integration** (optional)
   - Verify property in Search Console
   - Use same service account for API access

## Testing

To verify tracking is working:

1. Visit any page on your site
2. Open browser DevTools → Network tab
3. Filter for "gtag" or "collect"
4. You should see requests to `google-analytics.com`
5. Check GA4 Real-Time reports to see your visit

## Event Tracking

The following events are configured and tracking:
- `pledge_submitted` - When users submit pledge
- `newsletter_signup` - Newsletter signups
- `social_share` - Social media shares
- `resource_viewed` - Resource page views
- `category_filtered` - Resource category filters
- `content_generator_used` - Content generator usage
- `campaign_viewed` - Campaign page views
- `campaign_cta_clicked` - Campaign CTA clicks
- `challenge_started` - Challenge starts

## Privacy & Compliance

- IP anonymization enabled
- Cookie flags configured for cross-site tracking
- GDPR considerations: Users can opt-out via browser settings

## Support

For issues or questions:
- Check GA4 Real-Time reports for immediate verification
- Review browser console for JavaScript errors
- Verify Measurement ID matches in all files
- Check network requests in DevTools

---

**Note**: Copy this template to `GA4_CONFIGURATION.md` and fill in your actual values.
Keep `GA4_CONFIGURATION.md` in `.gitignore` to protect sensitive information.


