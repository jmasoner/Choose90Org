# How to Get GA4 API Key (Service Account Setup)

## Overview

The "GA4 API Key" is actually a **Service Account JSON key file** that allows your WordPress dashboard to access GA4 data via the Google Analytics Data API.

## ⚠️ Important: You're in the Right Place Now!

If Google is asking about MySQL, PostgreSQL, BigQuery, etc., **you're in the wrong place!** Those are for data imports, which you don't need.

**You need to be in Google Cloud Console** (not Google Analytics data import section).

## Step-by-Step Instructions

### Step 1: Go to Google Cloud Console

1. Visit [Google Cloud Console](https://console.cloud.google.com/)
2. Sign in with the same Google account that has access to your GA4 property
3. Select or create a project (you can use an existing project or create a new one)
4. **Make sure you're NOT in Google Analytics** - you should be in Cloud Console

### Step 2: Enable the Google Analytics Data API

1. In Google Cloud Console, go to **APIs & Services** → **Library**
2. Search for "Google Analytics Data API"
3. Click on **Google Analytics Data API**
4. Click **Enable**

### Step 3: Create a Service Account

1. Go to **APIs & Services** → **Credentials**
2. Click **+ CREATE CREDENTIALS** at the top
3. **IMPORTANT**: Select **Service account** (NOT "API key" or "OAuth client ID")
4. Fill in the details:
   - **Service account name**: `choose90-analytics` (or any name you prefer)
   - **Service account ID**: Will auto-generate
   - **Description**: "Service account for Choose90 Analytics Dashboard"
5. Click **CREATE AND CONTINUE**
6. **If asked "Which API are you using?"**: 
   - You can select "Google Analytics Data API" or just skip/continue
   - Service accounts don't need to be restricted to one API
7. Skip the optional steps (Grant access, Grant users access) and click **DONE**

### Step 4: Create and Download the JSON Key

1. In **Credentials** page, find your newly created service account
2. Click on the service account email (it will look like `choose90-analytics@your-project.iam.gserviceaccount.com`)
3. Go to the **KEYS** tab
4. Click **ADD KEY** → **Create new key**
5. Select **JSON** as the key type
6. Click **CREATE**
7. A JSON file will automatically download to your computer
8. **IMPORTANT**: Keep this file secure! It contains sensitive credentials.

### Step 5: Grant Access to GA4 Property

1. Go to [Google Analytics](https://analytics.google.com)
2. Click the gear icon (⚙️) → **Admin**
3. Under **Property**, click **Property access management**
4. Click **+** (plus icon) → **Add users**
5. Enter the service account email (from Step 4, e.g., `choose90-analytics@your-project.iam.gserviceaccount.com`)
6. Select role: **Viewer** (this is sufficient for reading analytics data)
7. Click **Add**

### Step 6: Get the JSON Key Content

1. Open the downloaded JSON file in a text editor (Notepad, VS Code, etc.)
2. The file will look like this:
```json
{
  "type": "service_account",
  "project_id": "your-project-id",
  "private_key_id": "...",
  "private_key": "-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n",
  "client_email": "choose90-analytics@your-project.iam.gserviceaccount.com",
  "client_id": "...",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "..."
}
```

3. **Copy the entire contents** of this JSON file

### Step 7: Paste into WordPress

1. Go to your WordPress admin: **CRM → Analytics Settings**
2. In the **GA4 API Key** field, paste the entire JSON content
3. Click **Save Settings**

## Important Notes

### Security
- ⚠️ **Never commit the JSON key to Git** - it's already in `.gitignore`
- ⚠️ **Don't share the JSON key publicly**
- ⚠️ **Keep the downloaded file secure** on your computer
- The key gives access to your GA4 data, so treat it like a password

### Troubleshooting

**If you get "Permission denied" errors:**
- Make sure you added the service account email to GA4 Property access
- Verify the service account has "Viewer" role (or higher)
- Wait a few minutes after adding access - it can take time to propagate

**If the API doesn't work:**
- Verify the Google Analytics Data API is enabled in Cloud Console
- Check that the Property ID is correct (490452975)
- Make sure the JSON key is pasted completely (no missing brackets or quotes)

## Quick Checklist

- [ ] Created Google Cloud project
- [ ] Enabled Google Analytics Data API
- [ ] Created service account
- [ ] Downloaded JSON key file
- [ ] Added service account email to GA4 Property access (Viewer role)
- [ ] Pasted JSON content into WordPress settings
- [ ] Saved settings

## Alternative: Using OAuth (More Complex)

If you prefer OAuth instead of service account, that's possible but more complex. The service account method is recommended for server-to-server API access.

---

**Your Property ID**: `490452975` ✅

Once you complete these steps, your Analytics Dashboard should be able to pull data from GA4!

