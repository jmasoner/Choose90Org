# Search Console Quick Start - Step by Step

## What You Need (2 Things)

1. **Search Console Site URL** - Your property URL from Search Console
2. **Search Console API Key** - Use the SAME JSON key as GA4 (you already have this!)

---

## Step 1: Find Your Search Console Site URL

### Option A: From Search Console Homepage

1. Go to: **https://search.google.com/search-console**
2. Sign in with your Google account
3. Look at the list of properties - you'll see your site listed
4. **Copy the exact URL shown** - it might be:
   - `https://choose90.org`
   - `https://www.choose90.org`
   - `sc-domain:choose90.org`

### Option B: From Property Settings

1. Click on your property in Search Console
2. Click the **gear icon (⚙️)** in the bottom left
3. Click **Settings**
4. The property URL is shown at the top of the page

**Important**: Copy the EXACT format shown (don't change it!)

---

## Step 2: Add Service Account to Search Console

1. In Search Console, click on your property
2. Click the **gear icon (⚙️)** → **Users and permissions**
3. Click **ADD USER** (blue button)
4. Paste this email: **`choose90-analytics@choose90-dashboard.iam.gserviceaccount.com`**
5. Select role: **Owner** (or "Full" - both work)
   - ⚠️ **Important**: "Viewer" won't work - you need Owner or Full
6. Click **ADD**
7. Wait 1-2 minutes for permissions to activate

---

## Step 3: Enable Search Console API

1. Go to: **https://console.cloud.google.com/**
2. Make sure you're in the project: **`choose90-dashboard`** (check top dropdown)
3. In the left menu, click **APIs & Services** → **Library**
4. In the search box, type: **"Google Search Console API"**
5. Click on **Google Search Console API** in the results
6. Click the blue **ENABLE** button
7. Wait for it to enable (usually instant)

---

## Step 4: Get Your JSON Key (You Already Have This!)

**Good news**: You already have the JSON key from GA4 setup!

If you need it again:
1. Go to: **https://console.cloud.google.com/**
2. Project: **`choose90-dashboard`**
3. **APIs & Services** → **Credentials**
4. Find: **`choose90-analytics@choose90-dashboard.iam.gserviceaccount.com`**
5. Click on it
6. Go to **KEYS** tab
7. If you see a key listed, you can download it again
8. If not, click **ADD KEY** → **Create new key** → **JSON** → **CREATE**

**Or**: Just use the same JSON you already pasted in WordPress for GA4!

---

## Step 5: Enter in WordPress

1. Go to WordPress Admin
2. Click **CRM** → **Analytics Settings**
3. Scroll to **"Google Search Console"** section
4. **Search Console Site**: Paste the URL from Step 1
   - Example: `https://choose90.org`
5. **Search Console API Key**: Paste the SAME JSON key you used for GA4
   - It's the entire JSON content from `{` to `}`
6. Click **Save Settings**

---

## Quick Checklist

- [ ] Found Search Console Site URL
- [ ] Added service account email to Search Console (Owner/Full role)
- [ ] Enabled Google Search Console API in Cloud Console
- [ ] Pasted Site URL in WordPress
- [ ] Pasted JSON key in WordPress (same as GA4)
- [ ] Saved settings

---

## Troubleshooting

### "I can't find my Search Console property"
- Make sure you're signed in with the correct Google account
- Check if the property is verified (it should show a checkmark)
- If you don't have a property, you need to add and verify your site first

### "Permission denied" errors
- Make sure service account has **Owner** or **Full** role (not Viewer)
- Wait 2-3 minutes after adding the user
- Try refreshing the page

### "Property not found" errors
- Double-check the Site URL matches EXACTLY what's in Search Console
- Make sure you're using the correct format (URL prefix vs domain property)

### "API not enabled" errors
- Go back to Cloud Console → APIs & Services → Library
- Search for "Google Search Console API"
- Make sure it shows "Enabled" (green checkmark)

---

## Your Service Account Email

```
choose90-analytics@choose90-dashboard.iam.gserviceaccount.com
```

Use this email when adding to Search Console.

---

## Need More Help?

Tell me which step you're stuck on:
- Finding the Search Console Site URL?
- Adding the service account?
- Enabling the API?
- Something else?
