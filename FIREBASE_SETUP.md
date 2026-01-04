# Firebase Authentication Setup Guide

Complete guide for setting up Firebase Authentication on Choose90.org.

---

## Table of Contents

1. [Overview](#overview)
2. [Initial Setup](#initial-setup)
3. [Get Firebase Credentials](#get-firebase-credentials)
4. [Configure secrets.json](#configure-secretsjson)
5. [Enable Sign-In Providers](#enable-sign-in-providers)
6. [OAuth Consent Screen Setup](#oauth-consent-screen-setup)
7. [Publish to Production](#publish-to-production)
8. [Add Additional Providers (Optional)](#add-additional-providers-optional)
9. [Quick Reference](#quick-reference)

---

## Overview

Firebase Authentication provides:
- **Social Login**: Google and Facebook one-click sign-in
- **Better UX**: Higher conversion rates (2-3x more signups)
- **Security**: Enterprise-grade authentication handled by Google
- **WordPress Sync**: Automatically creates/updates WordPress user accounts

---

## Initial Setup

### Step 1: Create Firebase Project

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Click **"Add project"** (or **"Create a project"**)
3. **Project name**: Enter `choose90`
4. Click **"Continue"**
5. **Google Analytics**: Enable (recommended) or disable
6. Click **"Create project"**
7. Wait 30-60 seconds for project creation
8. Click **"Continue"** when ready

### Step 2: Enable Authentication

1. In Firebase Console, click **"Authentication"** in the left sidebar
2. Click **"Get started"** (if shown)
3. You should see tabs: "Users", "Sign-in method", etc.

---

## Get Firebase Credentials

### Step 1: Go to Project Settings

1. In Firebase Console, click the **gear icon** ⚙️ next to "Project Overview"
2. Click **"Project settings"** from the dropdown

### Step 2: Register Web App

1. Scroll down to **"Your apps"** section
2. Click the **Web icon** (`</>`)
3. If no web app exists:
   - Click **"Add app"** → Select **Web** icon
   - **App nickname**: Enter `Choose90 Web`
   - Leave "Firebase Hosting" unchecked
   - Click **"Register app"**

### Step 3: Copy Configuration

You'll see a code block like this:

```javascript
const firebaseConfig = {
  apiKey: "AIzaSyAbCdEfGhIjKlMnOpQrStUvWxYz1234567",
  authDomain: "choose90-abc123.firebaseapp.com",
  projectId: "choose90-abc123",
  storageBucket: "choose90-abc123.appspot.com",
  messagingSenderId: "123456789012",
  appId: "1:123456789012:web:abcdef1234567890"
};
```

**Copy all 6 values** - you'll need them in the next step.

---

## Configure secrets.json

### Step 1: Locate secrets.json

Your `secrets.json` file should be at:
- Project root: `C:\Users\john\OneDrive\MyProjects\Choose90Org\secrets.json`
- Server root: On your server at the root directory

If it doesn't exist, copy `secrets.json.example` to `secrets.json`.

### Step 2: Add Firebase Configuration

Open `secrets.json` and add the Firebase config:

```json
{
  "firebase": {
    "api_key": "AIzaSy...",
    "auth_domain": "your-project.firebaseapp.com",
    "project_id": "your-project-id",
    "storage_bucket": "your-project.appspot.com",
    "messaging_sender_id": "123456789",
    "app_id": "1:123456789:web:abcdef"
  }
}
```

**Important**: Use the exact values you copied from Firebase Console (no quotes around values, except for strings).

### Step 3: Deploy secrets.json

Deploy `secrets.json` to your server (make sure it's in the correct location on the server).

---

## Enable Sign-In Providers

In Firebase Console → **Authentication** → **Sign-in method** tab:

### Google (Recommended - Easiest)

1. Click **"Google"**
2. Toggle **"Enable"** to ON
3. Select **"Project support email"** (your email)
4. Click **"Save"**

**No additional setup needed!** Firebase handles everything.

### Email/Password (Recommended)

1. Click **"Email/Password"**
2. Toggle **"Enable"** to ON
3. Leave "Email link (passwordless sign-in)" as OFF (unless you want it)
4. Click **"Save"**

### Facebook (Optional but Recommended)

1. Click **"Facebook"**
2. Toggle **"Enable"** to ON
3. You'll need Facebook App credentials (see [Add Additional Providers](#add-additional-providers-optional) below)
4. Add **App ID** and **App Secret**
5. Click **"Save"**

---

## OAuth Consent Screen Setup

**Required for Google sign-in to work!**

### Step 1: Go to OAuth Consent Screen

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Make sure you're signed in with the **same Google account** as Firebase
3. **Select your project** from the dropdown (should show "choose90")
4. In the left sidebar: **APIs & Services** → **OAuth consent screen**
5. Or go directly to: https://console.cloud.google.com/apis/credentials/consent

### Step 2: Fill Out Required Fields

#### User Type
- Select: **"External"** (unless you have Google Workspace)
- Click **"CREATE"** if first time

#### App Information (Required Fields)

1. **App name** * → Enter: `Choose90` or `Choose90.org`
2. **User support email** * → Select your email from dropdown
3. **Application home page** * → Enter: `https://choose90.org`
4. **Privacy policy link** * → Enter: `https://choose90.org/privacy.html`
5. **Terms of service link** * → Enter: `https://choose90.org/terms-of-service.html`
6. **Authorized domains** → Click **"ADD DOMAIN"** → Enter: `choose90.org` (and `www.choose90.org` if needed)
7. **Developer contact information** * → Enter your email

Click **"SAVE AND CONTINUE"** through all steps.

### Step 3: Add Test Users (For Testing Mode)

If your app is in "Testing" mode:

1. Scroll down to **"Test users"** section
2. Click **"ADD USERS"**
3. Enter email addresses (one per line) of users who should be able to sign in
4. Click **"ADD"**

**Note**: In Testing mode, only test users can sign in. For production, see [Publish to Production](#publish-to-production) below.

---

## Publish to Production

Once you're ready for anyone to sign in (not just test users):

### Step 1: Verify App is Ready

Make sure all required fields are filled:
- ✅ App name
- ✅ User support email
- ✅ Application home page
- ✅ Privacy policy link
- ✅ Terms of service link
- ✅ Developer contact information

### Step 2: Publish Your App

1. On the OAuth consent screen page, look for **"Publishing status"** section
2. You should see: **"Testing"** (yellow/orange badge)
3. Click the **"PUBLISH APP"** button
4. Confirm the action (click **"CONFIRM"** or **"PUBLISH"**)

### Step 3: Domain Verification (If Required)

Google may require domain verification:

**Option A: HTML File Upload (Easiest)**
1. Download the HTML file Google provides
2. Upload it to your website's root directory
3. Make sure it's accessible at: `https://choose90.org/google-verification-file.html`
4. Click **"VERIFY"** in Google Cloud Console

**Option B: DNS Verification**
1. Add the TXT record Google provides to your domain's DNS
2. Wait 24-48 hours for DNS propagation
3. Click **"VERIFY"** in Google Cloud Console

### Step 4: Wait for Processing

- Status will change from "Testing" to "In production" (takes 5-10 minutes)
- Refresh the page to verify status shows **"In production"** (green badge)

### What Changes After Publishing?

**Before (Testing Mode):**
- ❌ Only test users can sign in
- ❌ Must manually add each user's email
- ❌ Limited to 100 test users

**After (Production Mode):**
- ✅ **Anyone** can sign in (no test user list needed)
- ✅ No user limit
- ✅ Your app is publicly available

---

## Add Additional Providers (Optional)

### Facebook

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Click **"My Apps"** → **"Create App"**
3. Select **"Consumer"** as app type
4. **App name**: Enter `Choose90`
5. **App contact email**: Enter your email
6. Click **"Create App"**
7. In left sidebar: **"Settings"** → **"Basic"**
8. Copy **"App ID"** and **"App Secret"**
9. Click **"Add Platform"** → Select **"Website"**
10. **Site URL**: Enter `https://choose90.org`
11. Click **"Save Changes"**
12. In Firebase Console → Authentication → Sign-in method → Facebook:
    - Paste **App ID** and **App Secret**
    - Click **"Save"**

### GitHub, LinkedIn, Twitter/X

Similar process - each requires creating an app on their developer platform and adding credentials to Firebase.

---

## Quick Reference

### Firebase Console Links

- **Firebase Console**: https://console.firebase.google.com/
- **Project Settings**: Firebase Console → Gear icon → Project settings
- **Authentication**: Firebase Console → Authentication → Sign-in method
- **Authorized Domains**: Firebase Console → Authentication → Settings → Authorized domains

### Google Cloud Console Links

- **Google Cloud Console**: https://console.cloud.google.com/
- **OAuth Consent Screen**: https://console.cloud.google.com/apis/credentials/consent
- **Credentials**: https://console.cloud.google.com/apis/credentials

### Required Domains to Authorize

Add these to Firebase **Authorized domains**:
- `choose90.org`
- `www.choose90.org`
- `localhost` (for local development)

### Required URLs for OAuth Consent Screen

- **Application home page**: `https://choose90.org`
- **Privacy policy**: `https://choose90.org/privacy.html`
- **Terms of service**: `https://choose90.org/terms-of-service.html`

### secrets.json Structure

```json
{
  "firebase": {
    "api_key": "AIzaSy...",
    "auth_domain": "your-project.firebaseapp.com",
    "project_id": "your-project-id",
    "storage_bucket": "your-project.appspot.com",
    "messaging_sender_id": "123456789",
    "app_id": "1:123456789:web:abcdef"
  }
}
```

---

## Troubleshooting

For common issues and solutions, see **FIREBASE_TROUBLESHOOTING.md**.

---

## Files Deployed

After setup, these files should be deployed to your server:

- `hybrid_site/api/firebase-auth-callback.php` - Handles Firebase token verification
- `hybrid_site/js/firebase-auth.js` - Firebase Auth JavaScript helper
- `hybrid_site/components/firebase-config.php` - Loads Firebase config
- `hybrid_site/components/pledge-form.php` - Includes Firebase login buttons
- `hybrid_site/page-login.php` - Login page with Firebase integration
- `secrets.json` - Contains Firebase configuration (keep secure!)

---

## Summary Checklist

- [ ] Firebase project created
- [ ] Authentication enabled in Firebase
- [ ] Web app registered in Firebase
- [ ] Firebase credentials copied
- [ ] secrets.json updated with Firebase config
- [ ] secrets.json deployed to server
- [ ] Google sign-in enabled in Firebase
- [ ] Email/Password enabled in Firebase (optional)
- [ ] OAuth consent screen configured
- [ ] All required OAuth fields filled
- [ ] Authorized domains added (`choose90.org`, `www.choose90.org`)
- [ ] OAuth consent screen published to production
- [ ] Test sign-in works

---

**Need Help?** See FIREBASE_TROUBLESHOOTING.md for common issues and solutions.
