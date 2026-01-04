# What to Do With Your GA4 JSON Key

## ✅ You Have the JSON Key!

Great! Now you need to do two things:

## Step 1: Grant Access in Google Analytics

The service account needs permission to access your GA4 property.

1. Go to **Google Analytics**: https://analytics.google.com
2. Make sure you're viewing the correct property (Property ID: `490452975`)
3. Click the **gear icon (⚙️)** in the bottom left → **Admin**
4. Under the **Property** column (middle column), click **Property access management**
5. Click the **+** (plus icon) → **Add users**
6. In the email field, paste: **`choose90-analytics@choose90-dashboard.iam.gserviceaccount.com`**
   - (This is the `client_email` from your JSON file)
7. Select role: **Viewer** (this is sufficient for reading analytics data)
8. Click **Add**

**Important**: Wait 1-2 minutes after adding for permissions to propagate.

## Step 2: Paste JSON into WordPress

1. **Copy the ENTIRE JSON content** you have (all of it, from `{` to `}`)
2. Go to your WordPress admin
3. Navigate to: **CRM → Analytics Settings**
4. Find the **"GA4 API Key"** field
5. **Paste the entire JSON content** into that field
6. Click **Save Settings**

## What the JSON Contains

Your JSON file has:
- **Service account email**: `choose90-analytics@choose90-dashboard.iam.gserviceaccount.com` ⬅️ This needs to be added to GA4
- **Private key**: Used for authentication
- **Project ID**: `choose90-dashboard`

## Verification Checklist

After completing both steps:

- [ ] Service account email added to GA4 Property access (Viewer role)
- [ ] Entire JSON content pasted into WordPress "GA4 API Key" field
- [ ] Settings saved in WordPress
- [ ] Property ID (`490452975`) is also entered in WordPress settings

## Testing

After setup, you can test if it works:
1. Go to **CRM → Analytics** (the dashboard)
2. The dashboard should start pulling data from GA4
3. If you see errors, check:
   - Service account has access in GA4
   - JSON is pasted completely (no missing brackets)
   - Property ID is correct

## Security Reminder

⚠️ **Keep this JSON file secure!**
- Don't share it publicly
- Don't commit it to Git (it's already in `.gitignore`)
- Treat it like a password

## Quick Copy-Paste

**Service Account Email to add to GA4:**
```
choose90-analytics@choose90-dashboard.iam.gserviceaccount.com
```

**Role to assign:** Viewer

---

You're almost done! Just add the service account to GA4 and paste the JSON into WordPress.

