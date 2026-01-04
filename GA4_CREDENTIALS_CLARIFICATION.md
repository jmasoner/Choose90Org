# GA4 API Credentials - Which to Choose?

## ⚠️ Important: You Need a SERVICE ACCOUNT, Not an API Key!

If you're seeing "Which API are you using?" you might be in the wrong credential type.

## What You Actually Need

**Service Account** (NOT API Key, NOT OAuth Client)

## Step-by-Step: Creating the Right Credential

### Step 1: Go to Credentials Page

1. In Google Cloud Console: **APIs & Services** → **Credentials**
2. At the top, click **+ CREATE CREDENTIALS**
3. You'll see a dropdown with options:
   - API key
   - OAuth client ID
   - Service account ⬅️ **SELECT THIS ONE**

### Step 2: Create Service Account

1. Click **Service account**
2. Fill in:
   - **Service account name**: `choose90-analytics`
   - **Service account ID**: Auto-generated (leave as is)
   - **Description**: "For Choose90 Analytics Dashboard API access"
3. Click **CREATE AND CONTINUE**
4. **Skip** the optional steps (Grant access, Grant users access)
5. Click **DONE**

### Step 3: Download JSON Key

1. You'll see your service account in the list
2. Click on the service account **email** (looks like `choose90-analytics@your-project.iam.gserviceaccount.com`)
3. Go to the **KEYS** tab
4. Click **ADD KEY** → **Create new key**
5. Select **JSON**
6. Click **CREATE**
7. **JSON file downloads** - this is what you paste into WordPress!

## If You're Still Seeing "Which API are you using?"

### Option A: You're Creating an API Key (Wrong Type)
- **Don't create an API key**
- Go back and select **Service Account** instead

### Option B: You're in Service Account but Seeing API Selection
- If you see "Which API are you using?" when creating a service account, you can:
  - **Skip it** or select **"Google Analytics Data API"**
  - But actually, you don't need to restrict it - service accounts can access multiple APIs
  - Just proceed with creating the service account

## The Correct Flow

```
Google Cloud Console
  → APIs & Services
    → Credentials
      → + CREATE CREDENTIALS
        → Service account ⬅️ (NOT API key!)
          → Fill in name
          → CREATE AND CONTINUE
          → DONE
          → Click on service account email
          → KEYS tab
          → ADD KEY → Create new key → JSON
          → Download JSON file
```

## What the JSON File Looks Like

The downloaded file will contain something like:
```json
{
  "type": "service_account",
  "project_id": "your-project-id",
  "private_key_id": "...",
  "private_key": "-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n",
  "client_email": "choose90-analytics@your-project.iam.gserviceaccount.com",
  "client_id": "...",
  ...
}
```

**Copy the ENTIRE contents** of this file and paste it into WordPress.

## Quick Answer

**If asked "Which API are you using?":**
- Select **"Google Analytics Data API"** (if that's an option)
- Or just **skip/continue** - service accounts don't need to be restricted to one API
- The important thing is you're creating a **Service Account**, not an API Key

## Still Confused?

Make sure you:
1. ✅ Are in **Google Cloud Console** (not Google Analytics)
2. ✅ Are creating a **Service Account** (not API Key)
3. ✅ Have enabled **Google Analytics Data API** first (APIs & Services → Library)

The service account will work for GA4 Data API access once you:
- Enable the API
- Create the service account
- Add the service account email to GA4 Property access

