# API Keys Test Instructions

## Quick Test

### Option 1: Browser Test (Easiest)

1. **Deploy the test file:**
   ```powershell
   # Make sure test-api-keys.php is in hybrid_site folder
   .\deploy_hybrid_site.ps1
   ```

2. **Visit in browser:**
   ```
   https://choose90.org/test-api-keys.php?password=choose90_test_2025
   ```
   
   **OR** if accessing from localhost:
   ```
   http://localhost/test-api-keys.php
   ```

3. **Review results:**
   - ✅ Green = API key works
   - ❌ Red = API key invalid or error
   - ⚠️ Yellow = Unexpected response

### Option 2: Command Line Test

1. **Navigate to project:**
   ```powershell
   cd C:\Users\john\OneDrive\MyProjects\Choose90Org\hybrid_site
   ```

2. **Run test:**
   ```powershell
   php test-api-keys.php
   ```

## What the Test Checks

1. **File Exists** - Can it find secrets.json?
2. **Valid JSON** - Is the JSON format correct?
3. **Keys Present** - Are the API keys filled in?
4. **API Connection** - Can it connect to the API?
5. **Authentication** - Do the keys work?
6. **Response** - Does the API return a valid response?

## Expected Results

### ✅ Success
- HTTP Code: 200
- Response contains AI-generated text
- Shows "API test successful" message

### ❌ Common Errors

**401 Unauthorized:**
- API key is invalid or expired
- Check that you copied the full key
- Verify key is active in your API account

**Connection Error:**
- Server can't reach API endpoint
- Check firewall/network settings
- Verify base URL is correct

**No Key:**
- Key field is empty in secrets.json
- Check JSON structure
- Ensure keys are inside quotes

## Security

**⚠️ IMPORTANT:** Delete `test-api-keys.php` after testing!

This file exposes API key information and should NOT be on your live server.

To delete:
```powershell
# After testing, remove the file
Remove-Item hybrid_site\test-api-keys.php
```

## Troubleshooting

### "Access denied"
- Add `?password=choose90_test_2025` to URL
- Or access from localhost
- Or run from command line

### "File not found"
- Check that secrets.json is in project root
- Verify file path in test script matches your structure

### "Invalid JSON"
- Use a JSON validator online
- Check for missing commas, brackets
- Ensure all strings are in quotes

### API returns error
- Verify API keys are correct
- Check API account status
- Ensure you have API credits/quota

