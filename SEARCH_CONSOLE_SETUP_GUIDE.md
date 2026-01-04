# Google Search Console Setup Guide

## Overview

Google Search Console provides search performance data (queries, clicks, impressions, rankings). You can use the **same service account JSON key** you created for GA4!

## What You Need

1. **Search Console Site** - Your verified property URL
2. **Search Console API Key** - Same JSON key as GA4 (or create a new one)

## Step 1: Find Your Search Console Site URL

1. Go to [Google Search Console](https://search.google.com/search-console)
2. Sign in with the same Google account
3. You'll see your verified properties listed
4. **Copy the exact URL** shown (it will be one of these formats):
   - `https://choose90.org` (domain property)
   - `https://www.choose90.org` (URL prefix property)
   - `sc-domain:choose90.org` (domain property)

**Important**: Use the exact format shown in Search Console.

## Step 2: Add Service Account to Search Console

You need to give the service account access to Search Console:

1. In Search Console, click on your property
2. Click the **gear icon (⚙️)** → **Users and permissions**
3. Click **ADD USER**
4. Paste the service account email: **`choose90-analytics@choose90-dashboard.iam.gserviceaccount.com`**
5. Select role: **Owner** or **Full** (needed for API access)
6. Click **ADD**

**Note**: Search Console requires "Owner" or "Full" role for API access (unlike GA4 which only needs "Viewer").

## Step 3: Enable Search Console API

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Make sure you're in the same project (`choose90-dashboard`)
3. Go to **APIs & Services** → **Library**
4. Search for: **"Google Search Console API"**
5. Click on it and click **Enable**

## Step 4: Use the Same JSON Key

**Good news**: You can use the **same JSON key file** you created for GA4!

1. The JSON key you already have works for both GA4 and Search Console
2. Just paste it in the **"Search Console API Key"** field in WordPress
3. It's the same service account, so same credentials

## WordPress Settings

In **CRM → Analytics Settings**, fill in:

1. **Search Console Site**: 
   - Use the exact URL from Search Console
   - Examples: `https://choose90.org` or `sc-domain:choose90.org`

2. **Search Console API Key**: 
   - Paste the **same JSON content** you used for GA4 API Key
   - It's the same service account, so same JSON file

## Quick Setup Checklist

- [ ] Property verified in Search Console
- [ ] Service account email added to Search Console (Owner/Full role)
- [ ] Google Search Console API enabled in Cloud Console
- [ ] Search Console Site URL entered in WordPress
- [ ] Same JSON key pasted in Search Console API Key field

## Finding Your Search Console Site URL

**Method 1: From Search Console Home**
- Look at the property list - the URL shown is what you need

**Method 2: From Property Settings**
- Click on your property
- Gear icon → Settings → Your property URL is shown at top

**Method 3: Check the URL**
- When viewing Search Console, the URL shows: `search.google.com/search-console/.../property/SC-...`
- The property identifier in the URL

## Common Formats

- **URL Prefix**: `https://choose90.org` or `https://www.choose90.org`
- **Domain**: `sc-domain:choose90.org` or just `choose90.org`

Use the **exact format** shown in Search Console.

## Optional: Create Separate Service Account

If you prefer separate credentials:
- You can create a second service account just for Search Console
- But it's not necessary - one service account can access both GA4 and Search Console

## Troubleshooting

**"Permission denied" errors:**
- Make sure service account has **Owner** or **Full** role (not just Viewer)
- Wait a few minutes after adding for permissions to propagate

**"Property not found" errors:**
- Verify the Site URL matches exactly what's in Search Console
- Check that the property is verified
- Make sure you're using the correct format (URL prefix vs domain)

---

**Your Service Account Email:**
```
choose90-analytics@choose90-dashboard.iam.gserviceaccount.com
```

**Same JSON Key**: Use the one you already have for GA4!

