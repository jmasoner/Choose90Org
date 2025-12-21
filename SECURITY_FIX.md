# Security Fix: Removed Hardcoded Password

## Issue
GitGuardian detected a hardcoded password in `setup_webdisk2.ps1` that was committed to the repository.

## Actions Taken

1. ✅ **Removed password from file**: Updated `setup_webdisk2.ps1` to read credentials from `secrets.json` instead
2. ✅ **Added to .gitignore**: Added `setup_webdisk*.ps1` to prevent future commits
3. ✅ **Removed from git tracking**: Removed the file from git index
4. ✅ **Committed fix**: New commit removes the sensitive data

## Important: Password Still in Git History

⚠️ **CRITICAL**: The password is still visible in git history (commit 655608e). 

### To completely remove it, you have two options:

#### Option 1: Rotate the Password (RECOMMENDED)
Since the password has been exposed, **change your WebDAV password immediately**:
1. Log into your hosting control panel
2. Change the WebDAV password
3. Update `secrets.json` with the new password
4. This is the safest approach - the old password becomes invalid

#### Option 2: Remove from Git History (Advanced)
If you want to remove it from history entirely (requires force push):

```powershell
# WARNING: This rewrites history and requires force push
# Only do this if you're the only one working on this repo

# Install git-filter-repo (if not installed)
# pip install git-filter-repo

# Remove the password from all history
git filter-repo --path setup_webdisk2.ps1 --invert-paths

# Force push (WARNING: This rewrites history)
git push origin --force --all
```

**Note**: Force pushing rewrites history and can cause issues for collaborators.

## Current Status

- ✅ Password removed from current code
- ✅ File added to .gitignore
- ⚠️ Password still visible in git history (commit 655608e)
- ⚠️ **ACTION REQUIRED**: Rotate your WebDAV password

## Prevention

Going forward:
- ✅ `secrets.json` is in `.gitignore` (never commit it)
- ✅ `setup_webdisk*.ps1` is in `.gitignore` (won't be committed)
- ✅ All scripts now read from `secrets.json` instead of hardcoding

## Next Steps

1. **IMMEDIATELY**: Change your WebDAV password in your hosting control panel
2. Update `secrets.json` with the new password
3. Test that WebDAV connection still works
4. Consider using GitGuardian to monitor for future secrets

