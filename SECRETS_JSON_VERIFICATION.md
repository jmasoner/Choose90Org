# secrets.json Verification Checklist

## Quick Verification Steps

### 1. File Exists
- [ ] `secrets.json` file exists in your project root
- [ ] File is NOT in `.gitignore` (it should be ignored for security)

### 2. JSON Format is Valid
- [ ] File opens without errors
- [ ] No syntax errors (check with a JSON validator: https://jsonlint.com/)
- [ ] All opening `{` have closing `}`
- [ ] All property names are in quotes: `"api_key"` not `api_key`
- [ ] All string values are in quotes: `"value"` not `value`
- [ ] Commas between properties (but NOT after the last one)
- [ ] No trailing commas

### 3. Required Structure

Your `secrets.json` should have this structure:

```json
{
  "firebase": {
    "api_key": "AIza...",
    "auth_domain": "your-project.firebaseapp.com",
    "project_id": "your-project-id",
    "storage_bucket": "your-project.appspot.com",
    "messaging_sender_id": "123456789012",
    "app_id": "1:123456789012:web:abcdef..."
  }
}
```

### 4. Firebase Configuration Fields

Check that ALL these fields exist and have REAL values (not placeholders):

- [ ] **api_key**: Should start with `AIza` (not `AIzaSyAbCdEf...`)
- [ ] **auth_domain**: Should be `your-project.firebaseapp.com` (not `your-project.firebaseapp.com`)
- [ ] **project_id**: Should be your actual project ID (not `your-project-id`)
- [ ] **storage_bucket**: Should be `your-project.appspot.com` (not `your-project.appspot.com`)
- [ ] **messaging_sender_id**: Should be a long number (not `123456789`)
- [ ] **app_id**: Should be `1:numbers:web:letters` format (not `1:123456789012:web:abcdef1234567890`)

### 5. Common Issues to Check

#### ❌ Placeholder Values Still Present
If you see any of these, you need to replace them:
- `"your_firebase_api_key"`
- `"your-project.firebaseapp.com"`
- `"your-project-id"`
- `"123456789"`
- `"abcdef1234567890"`
- `"AIzaSyAbCdEfGhIjKlMnOpQrStUvWxYz1234567"` (example placeholder)

#### ❌ Missing Fields
Make sure all 6 Firebase fields are present:
1. api_key
2. auth_domain
3. project_id
4. storage_bucket
5. messaging_sender_id
6. app_id

#### ❌ Wrong Data Types
- `api_key` should be a string (in quotes)
- `auth_domain` should be a string (in quotes)
- `project_id` should be a string (in quotes)
- `storage_bucket` should be a string (in quotes)
- `messaging_sender_id` should be a string (in quotes) - even though it's a number
- `app_id` should be a string (in quotes)

### 6. Example of CORRECT Firebase Config

```json
{
  "firebase": {
    "api_key": "AIzaSyC_RealKeyFromFirebase_1234567890",
    "auth_domain": "choose90-abc123.firebaseapp.com",
    "project_id": "choose90-abc123",
    "storage_bucket": "choose90-abc123.appspot.com",
    "messaging_sender_id": "987654321098",
    "app_id": "1:987654321098:web:abc123def456ghi789"
  }
}
```

### 7. Where to Get These Values

From Firebase Console:
1. Go to: https://console.firebase.google.com/
2. Select your project
3. Click the gear icon ⚙️ → **Project settings**
4. Scroll down to **"Your apps"** section
5. Click on your web app (or create one)
6. You'll see **"Firebase SDK snippet"** → **"Config"**
7. Copy the values from there

### 8. Quick Test

After verifying, you can test if the file is readable by your PHP code:

1. The file should be at: `/secrets.json` (server root)
2. PHP should be able to read it: `file_get_contents('secrets.json')`
3. JSON should parse correctly: `json_decode($content, true)`

---

## Still Having Issues?

If you're not sure about a specific field, you can:
1. Open `secrets.json` in a text editor
2. Compare it to `secrets.json.example`
3. Make sure all placeholder values are replaced
4. Validate JSON at: https://jsonlint.com/

---

## Security Reminder

⚠️ **NEVER commit `secrets.json` to Git!**
- It should be in `.gitignore`
- It contains sensitive API keys
- Only upload to your server via secure methods (SFTP, SSH)
