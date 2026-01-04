# Deploy secrets.json to Server

## Problem
The browser extension rewrite feature is failing with "Configuration not found" because `secrets.json` is not on the server.

## Solution

You need to manually upload `secrets.json` to your server. The file is in `.gitignore` for security, so it won't deploy automatically.

### Steps:

1. **Locate your secrets.json file** (should be in project root)

2. **Upload to server** at one of these locations:
   - **Option 1 (Recommended):** Upload to the website root directory (same level as `hybrid_site/`)
     - Path: `/path/to/choose90.org/secrets.json`
     - The API looks for it at: `hybrid_site/api/../../secrets.json`
   
   - **Option 2:** Upload to `hybrid_site/` directory
     - Path: `/path/to/choose90.org/hybrid_site/../secrets.json`
     - (Note: This is still the root, just different reference)

3. **Set proper permissions:**
   - File should be readable by web server (PHP)
   - **Do NOT make it publicly accessible via web**
   - Recommended: `644` or `600` permissions

4. **Verify the file structure** matches `secrets.json.example`:
   ```json
   {
     "api_keys": {
       "deepseek": {
         "api_key": "your_key_here",
         "base_url": "https://api.deepseek.com/v1",
         "model": "deepseek-chat",
         "enabled": true
       }
     }
   }
   ```

5. **Test the API endpoint:**
   - Visit: `https://choose90.org/api/rewrite-post.php`
   - Should NOT show "Configuration not found" error
   - (May show other validation errors if called without proper POST data, which is fine)

## Security Notes

- ✅ `secrets.json` is in `.gitignore` (won't be committed)
- ⚠️ **Make sure `.htaccess` protects it from web access**
- ⚠️ **Don't share the file contents publicly**
- ⚠️ **Keep your API keys secure**

## Check if .htaccess protects secrets.json

Your `.htaccess` should have rules to block access to `secrets.json`. If not, add:

```apache
<Files "secrets.json">
    Order Allow,Deny
    Deny from all
</Files>
```

## Quick Upload via WebDAV

If you have WebDAV access (Z: drive mapped):

```powershell
# Copy secrets.json to server root
Copy-Item "secrets.json" "Z:\secrets.json"
```

Or manually via FTP/File Manager:
- Upload `secrets.json` to your website root directory
- Ensure it's at the same level as `hybrid_site/` folder