# Privacy & Security Notes

## GA4 Measurement ID Privacy

### Public Files (OK to Commit)
The following files contain the GA4 Measurement ID and **should be committed** to GitHub:
- `hybrid_site/components/analytics-snippet.html` - Frontend tracking snippet
- `hybrid_site/js/analytics.js` - Frontend JavaScript tracking
- `wp-content/plugins/choose90-crm/templates/analytics-settings.php` - Plugin template

**Why?** GA4 Measurement IDs are **public by design**. They're visible in the browser's JavaScript and are required for tracking to work. This is standard practice and not a security concern.

### Private Files (Excluded from Git)
The following files contain sensitive configuration details and are in `.gitignore`:
- `GA4_CONFIGURATION.md` - Contains Measurement ID, Stream ID, and setup details
- `.env` - Environment variables (if used)

**Why?** While the Measurement ID itself is public, configuration documents may contain:
- Stream IDs
- API keys (if added later)
- Setup instructions with sensitive details
- Internal notes

## Current Configuration

- **Measurement ID**: `G-9M6498Y7W4` (public, in frontend files)
- **Stream ID**: `13204202167` (private, only in GA4_CONFIGURATION.md)
- **Stream Name**: `choose90`
- **Stream URL**: `https://choose90.org`

## Best Practices

1. **Never commit**:
   - Service account JSON keys
   - API secrets
   - Search Console API keys
   - Any credentials or tokens

2. **OK to commit**:
   - GA4 Measurement IDs (public by design)
   - Frontend tracking code
   - Template files with placeholder values

3. **Use templates**:
   - `GA4_CONFIGURATION.template.md` - Template without real values
   - Copy to `GA4_CONFIGURATION.md` locally and fill in actual values
   - Keep actual config file in `.gitignore`

## .gitignore Entries

Current entries protecting sensitive data:
```
.env
secrets.json
GA4_CONFIGURATION.md
wp-config.php
.htaccess
```

---

**Last Updated**: 2025-12-27


