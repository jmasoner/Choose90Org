# Firebase Authentication Troubleshooting Guide

Quick reference for common Firebase authentication issues and solutions.

---

## Quick Diagnosis

**First step**: Check the browser console (F12 → Console tab) for error messages. The console will have the most detailed error information.

---

## Common Errors and Solutions

### "This domain is not authorized for OAuth operations"

**Error Message:**
```
Firebase: This domain is not authorized for OAuth operations for your Firebase project.
Edit the list of authorized domains from the Firebase console. (auth/unauthorized-domain)
```

**Solution:**
1. Go to [Firebase Console](https://console.firebase.google.com/) → Authentication → Settings
2. Scroll down to **"Authorized domains"**
3. Click **"Add domain"**
4. Add: `choose90.org`
5. Add: `www.choose90.org` (if needed)
6. Click **"Add"**
7. Wait 1-2 minutes for changes to propagate

---

### "OAuth configuration issue. Please check OAuth consent screen"

**Error Message:**
```
Sign-in failed: OAuth configuration issue. Please check OAuth consent screen in Google Cloud Console.
```

**Solution:**
1. Go to [OAuth Consent Screen](https://console.cloud.google.com/apis/credentials/consent)
2. Make sure you're in the correct project (select "choose90" from dropdown)
3. Verify all required fields are filled:
   - App name
   - User support email
   - Application home page (`https://choose90.org`)
   - Privacy policy link (`https://choose90.org/privacy.html`)
   - Terms of service link (`https://choose90.org/terms-of-service.html`)
   - Developer contact information
4. If in Testing mode, add test users in the "Test users" section
5. Click **"SAVE"** if you made changes
6. If ready for production, click **"PUBLISH APP"** (see FIREBASE_SETUP.md for details)

---

### "Error (auth/internal-error)"

**Error Message:**
```
Sign-in failed: Firebase: Error (auth/internal-error)
```

**Common Causes:**
1. **OAuth consent screen not configured** - See error above
2. **Missing required fields in OAuth consent screen**
3. **App not published and user not in test users list**

**Solution:**
1. Check OAuth consent screen is configured (see error above)
2. If in Testing mode, make sure your email is in the "Test users" list
3. Check browser console for more detailed error
4. Try clearing browser cache and cookies
5. Try in incognito/private browsing mode

---

### "Configuration not found" or Firebase not initializing

**Error Message:**
```
Configuration not found
```
Or Firebase scripts not loading.

**Solution:**
1. Check that `secrets.json` exists and is in the correct location
2. Verify `secrets.json` has valid JSON syntax (use a JSON validator)
3. Verify Firebase config values are correct in `secrets.json`
4. Check browser console for file loading errors
5. Verify `hybrid_site/components/firebase-config.php` is being included correctly
6. Check server file permissions (files should be readable)

---

### Content Security Policy (CSP) blocking Firebase scripts

**Error Message:**
```
Content Security Policy: The page's settings blocked the loading of a resource at 'https://apis.google.com/...'
```

**Solution:**
1. Check `.htaccess` file or BulletProof Security Pro settings
2. Add CSP headers to allow Firebase domains (see `BPS_CUSTOM_CODE_FIREBASE_CSP.txt`)
3. Required domains to allow:
   - `https://www.gstatic.com`
   - `https://apis.google.com`
   - `https://identitytoolkit.googleapis.com`
   - `https://securetoken.googleapis.com`
   - `https://*.firebaseapp.com`
   - `https://*.firebase.com`

**For BulletProof Security Pro:**
1. Go to WordPress Admin → Security → BulletProof Security
2. Go to **Custom Code** tab
3. Add the CSP code from `BPS_CUSTOM_CODE_FIREBASE_CSP.txt`
4. Save changes

---

### Google sign-in completes but user not signed in

**Symptom:**
- Google sign-in popup appears and completes successfully
- User verifies with Google (even phone verification)
- But user is not signed into the website
- Console shows: "Redirect result: {credential: null, user: null}" or "User signed out"

**Common Causes:**
1. **Firebase auth persistence not set correctly**
2. **Redirect result not being handled properly**
3. **Browser tracking/bounce protection** (especially in Edge)
4. **Auth state listener not firing**

**Solutions:**
1. **Check Firebase initialization** - Make sure `firebase-auth.js` is loading
2. **Check browser console** - Look for Firebase initialization errors
3. **Try a different browser** - Test in Chrome, Firefox, and Edge
4. **Clear browser cache and cookies** - Old cached code might be interfering
5. **Check network tab** - Verify Firebase API calls are completing successfully
6. **Check auth state listener** - Verify `onAuthStateChanged` is being called

**Note**: This is usually resolved by using `signInWithPopup` instead of `signInWithRedirect` (which is the current implementation).

---

### "User signed out" after successful sign-in

**Symptom:**
- User signs in successfully
- Gets redirected back to the site
- But immediately shows as "signed out"

**Solution:**
1. Check that `firebase-auth.js` is setting auth persistence: `setPersistence(auth, browserLocalPersistence)`
2. Verify WordPress session is being created in `firebase-auth-callback.php`
3. Check browser cookies - WordPress auth cookies should be set
4. Check server logs for PHP errors in `firebase-auth-callback.php`
5. Verify user is being created/updated in WordPress database

---

### Button does nothing when clicked

**Symptom:**
- Click "Sign up with Google" or "Sign up with Facebook"
- Nothing happens (no popup, no redirect, no error)

**Solution:**
1. **Check browser console** for JavaScript errors
2. **Verify Firebase scripts are loading** - Check Network tab for `firebase-app.js`, `firebase-auth.js`
3. **Check that buttons have event listeners** - Verify `firebase-auth.js` is initializing
4. **Check for JavaScript errors** - Look for syntax errors or undefined variables
5. **Verify `firebase-config.php` is included** - Check page source for Firebase config script tag

---

### 403 Error when clicking login button

**Symptom:**
- Click login button
- Get 403 Forbidden error instead of Firebase popup

**Solution:**
1. **Check CSP settings** - Content Security Policy might be blocking Firebase
2. **Check server permissions** - Verify files are readable
3. **Check .htaccess rules** - Make sure Firebase domains aren't blocked
4. **Check BulletProof Security Pro** - May need to whitelist Firebase domains

---

## Testing Checklist

When troubleshooting, check these in order:

- [ ] Browser console (F12 → Console) - Check for error messages
- [ ] Network tab (F12 → Network) - Check if Firebase scripts are loading
- [ ] Firebase Console - Verify authentication is enabled
- [ ] Firebase Console - Verify authorized domains include `choose90.org`
- [ ] Google Cloud Console - Verify OAuth consent screen is configured
- [ ] Google Cloud Console - Verify OAuth consent screen is published (or test users added)
- [ ] secrets.json - Verify Firebase config is correct
- [ ] Server files - Verify all Firebase files are deployed
- [ ] Browser cache - Try clearing cache or using incognito mode
- [ ] Different browser - Test in Chrome, Firefox, Edge

---

## Getting More Information

### Check Browser Console

1. Press **F12** (or Right-click → Inspect)
2. Click **Console** tab
3. **Clear console** (click 🚫 icon or Ctrl+L)
4. Try the action that's failing
5. **Copy any error messages** (they're usually in red)

### Check Network Tab

1. Press **F12** (or Right-click → Inspect)
2. Click **Network** tab
3. **Clear network log** (click 🚫 icon)
4. Try the action that's failing
5. Look for:
   - Failed requests (shown in red)
   - Firebase scripts loading (`firebase-app.js`, `firebase-auth.js`)
   - Firebase API calls (`identitytoolkit.googleapis.com`, `securetoken.googleapis.com`)

### Check Firebase Console

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Select your project
3. Go to **Authentication** → **Users** tab
4. Check if users are being created (even if sign-in fails, user creation might succeed)

### Check Server Logs

1. Check your server error logs (PHP error log, Apache error log, etc.)
2. Look for errors in:
   - `firebase-auth-callback.php`
   - `firebase-config.php`
   - Any PHP files related to authentication

---

## Common Configuration Mistakes

1. **Missing authorized domains** - Must add `choose90.org` and `www.choose90.org` to Firebase
2. **OAuth consent screen not published** - App must be published or test users added
3. **Incorrect secrets.json format** - Must be valid JSON with correct structure
4. **Wrong project selected** - Make sure you're working in the correct Firebase/Google Cloud project
5. **CSP blocking Firebase** - Content Security Policy must allow Firebase domains
6. **Files not deployed** - Make sure all Firebase files are deployed to the server

---

## Still Having Issues?

If you've checked everything above and it's still not working:

1. **Check the exact error message** from browser console
2. **Verify all setup steps** in FIREBASE_SETUP.md
3. **Test in multiple browsers** (Chrome, Firefox, Edge)
4. **Test in incognito/private mode** (rules out cache/cookie issues)
5. **Check server logs** for PHP errors
6. **Verify all files are deployed** correctly

---

## Quick Reference Links

- **Firebase Console**: https://console.firebase.google.com/
- **Google Cloud Console**: https://console.cloud.google.com/
- **OAuth Consent Screen**: https://console.cloud.google.com/apis/credentials/consent
- **Firebase Auth Docs**: https://firebase.google.com/docs/auth
