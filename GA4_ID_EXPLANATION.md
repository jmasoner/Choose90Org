# Google Analytics 4 IDs Explained

## The Three Different IDs

### 1. **Measurement ID** (G-XXXXXXXXXX)
- **Format**: Starts with "G-" followed by alphanumeric characters
- **Example**: `G-9M6498Y7W4`
- **Used for**: Frontend tracking code (what goes in your website)
- **Where to find**: GA4 Admin → Data Streams → Select your stream → Measurement ID is shown at top
- **Purpose**: Links your website to a specific data stream in GA4

### 2. **Stream ID** (Numeric)
- **Format**: Numeric only (e.g., `13204202167`)
- **Example**: `13204202167`
- **Used for**: Internal GA4 reference for a specific data stream
- **Where to find**: GA4 Admin → Data Streams → Select your stream → Stream ID is in the URL or stream details
- **Purpose**: Identifies a specific data stream (web, iOS app, Android app) within a property
- **Note**: A property can have multiple streams, each with its own Stream ID

### 3. **Property ID** (Numeric) ⬅️ **This is what you need for API access**
- **Format**: Numeric only (e.g., `123456789`)
- **Example**: `123456789`
- **Used for**: GA4 Data API access (for the dashboard)
- **Where to find**: 
  1. GA4 Admin → Property Settings → Property ID is shown at the top
  2. Or in the URL: `analytics.google.com/analytics/web/#/p123456789/`
- **Purpose**: Identifies the entire GA4 property for API calls

## Hierarchy

```
GA4 Account
  └── Property (Property ID: 123456789)
      ├── Web Stream (Stream ID: 13204202167, Measurement ID: G-9M6498Y7W4)
      ├── iOS App Stream (Stream ID: 987654321, Measurement ID: G-XXXXXXXXXX)
      └── Android App Stream (Stream ID: 456789123, Measurement ID: G-YYYYYYYYYY)
```

## For Your CRM Plugin

In the Analytics Settings page, you need:

1. **GA4 Measurement ID**: `G-9M6498Y7W4` ✅ (Already configured)
   - Used for frontend tracking
   - Already set up in your website

2. **GA4 Property ID**: `123456789` ⬅️ **You need to find this**
   - Used for API access to pull data into the dashboard
   - This is what goes in the "GA4 Property ID" field

3. **GA4 API Key**: Service account JSON key
   - For authenticating API requests
   - Created in Google Cloud Console

## How to Find Your Property ID

1. Go to [Google Analytics](https://analytics.google.com)
2. Select your GA4 property
3. Click the gear icon (⚙️) in the bottom left → **Admin**
4. Under **Property** column, click **Property Settings**
5. The **Property ID** is displayed at the top (numeric, like `123456789`)

OR

Look at the URL when you're in GA4:
- `https://analytics.google.com/analytics/web/#/p123456789/`
- The number after `/p` is your Property ID

## Summary

- **Measurement ID** = For tracking (frontend) ✅ You have this
- **Stream ID** = Internal stream identifier (not needed for API)
- **Property ID** = For API access (dashboard) ⬅️ **You need this**

**Answer**: No, Property ID and Stream ID are NOT the same. You need the **Property ID** for the dashboard API access.

