# BulletProof Security Configuration Guide
## Preventing BPS from Creating/Modifying Files and Directories

If BPS turns out to be the culprit, here's how to configure it to stop interfering with your site structure.

## Key BPS Features That Could Cause Issues

1. **AutoRestore|Quarantine (ARQ IDPS)** - Real-time File Monitor that watches for changes
2. **.htaccess Auto-Updates** - BPS can automatically modify .htaccess files
3. **Quarantine System** - Moves "suspicious" files to quarantine folders
4. **File Monitoring** - Scans and may recreate deleted directories

## Configuration Steps

### 1. Exclude `/resources/` Directory from File Monitoring

**Location:** WordPress Admin → BulletProof Security → AutoRestore|Quarantine (ARQ) → Exclude Options

**Steps:**
1. Go to **BPS Pro → AutoRestore|Quarantine (ARQ) → Exclude Options**
2. In the "Exclude Folders/Files" section, add:
   ```
   /resources/
   /resources-backup/
   ```
3. Also add to "Hard Exclude" list if available
4. Save changes

**Alternative:** If using IDPS (Intrusion Detection/Prevention System):
- Go to **BPS Pro → Monitor → IDPS Settings**
- Add to "Exclude Paths":
  ```
  /resources/
  /resources-backup/
  ```

### 2. Disable Auto-Restore for Specific Paths

**Location:** WordPress Admin → BulletProof Security → AutoRestore|Quarantine (ARQ)

**Steps:**
1. Go to **BPS Pro → AutoRestore|Quarantine (ARQ)**
2. Look for **"AutoRestore Exclusions"** or **"Skip AutoRestore For"**
3. Add these paths:
   ```
   /resources/
   /resources-backup/
   ```
4. This prevents BPS from automatically restoring deleted files in these directories

### 3. Prevent .htaccess Auto-Modification

**Location:** WordPress Admin → BulletProof Security → .htaccess

**Steps:**
1. Go to **BPS Pro → .htaccess**
2. Look for **"Auto Lock .htaccess Files"** or **".htaccess Protection"**
3. **Disable** automatic .htaccess modifications if enabled
4. **OR** add your root `.htaccess` to a "Do Not Modify" list

**Manual Protection:**
- In BPS settings, find **".htaccess File Lock"** or **"Protect .htaccess"**
- Disable auto-updates for root `.htaccess`
- Manually manage .htaccess outside of BPS

### 4. Whitelist Static HTML Files

**Location:** WordPress Admin → BulletProof Security → MScan or Security Log

**Steps:**
1. Go to **BPS Pro → MScan** (Malware Scanner)
2. Find **"Exclude Files"** or **"Whitelist"**
3. Add file patterns:
   ```
   *.html
   resources-hub.html
   resources-backup/*
   ```

### 5. Disable Quarantine for Specific Files

**Location:** WordPress Admin → BulletProof Security → Quarantine

**Steps:**
1. Go to **BPS Pro → Quarantine**
2. Look for **"Quarantine Exclusions"**
3. Add:
   ```
   /resources/
   /resources-backup/
   resources-hub.html
   ```

### 6. File Monitoring Settings (Most Important)

**Location:** WordPress Admin → BulletProof Security → Monitor → File Monitor

**Steps:**
1. Go to **BPS Pro → Monitor → File Monitor Settings**
2. Find **"Monitor Exclusions"** or **"Skip Monitoring"**
3. Add these paths (one per line):
   ```
   /resources/
   /resources-backup/
   /resources-hub.html
   ```
4. **Disable** "Auto-Restore Deleted Files" if present
5. **Disable** "Auto-Create Missing Directories" if present

### 7. IDPS (Intrusion Detection) Settings

**Location:** WordPress Admin → BulletProof Security → Monitor → IDPS

**Steps:**
1. Go to **BPS Pro → Monitor → IDPS Settings**
2. In **"Exclude Paths from Monitoring"**, add:
   ```
   /resources/
   /resources-backup/
   ```
3. **Disable** "Auto-Restore" for root directory changes

## Quick Fix: Disable Specific BPS Features

If you can't find the exact settings, try disabling these features temporarily:

1. **Disable ARQ (AutoRestore|Quarantine):**
   - Go to **BPS Pro → AutoRestore|Quarantine (ARQ)**
   - Turn OFF "Enable ARQ" or "Enable File Monitor"

2. **Disable File Monitoring:**
   - Go to **BPS Pro → Monitor**
   - Turn OFF "Enable File Monitor" or "IDPS File Monitor"

3. **Disable .htaccess Auto-Lock:**
   - Go to **BPS Pro → .htaccess**
   - Turn OFF "Auto Lock" or "Protect .htaccess"

## Testing After Configuration

After making these changes:

1. **Delete** `/resources/` directory if it exists
2. **Wait 24 hours** or check BPS logs
3. **Monitor** if `/resources/` gets recreated
4. **Check BPS Security Log** for any actions taken:
   - Go to **BPS Pro → Security Log**
   - Filter for "resources" or "directory creation"

## BPS Log Files to Check

If BPS is creating directories, check these log files:

```
/wp-content/bps-backup/logs/security_log.txt
/wp-content/bps-backup/logs/arq_log.txt
/wp-content/bps-backup/logs/idps_log.txt
```

Look for entries like:
- "Directory created"
- "File restored"
- "Quarantine action"
- "/resources/"

## Nuclear Option: Create a BPS Exclusion File

If the above doesn't work, BPS may support a custom exclusion file. Check:

1. Look for `bps-custom-exclusions.php` or similar
2. Create exclusion rules for `/resources/` and `/resources-backup/`
3. Contact BPS support for advanced exclusion methods

## Contact BPS Support

If none of these work, contact BPS Pro support:
- Forum: https://forum.ait-pro.com/
- Explain: "BPS is automatically creating a /resources/ directory that conflicts with our static site structure. How do I permanently exclude this path from all BPS monitoring/restore features?"

## Recommended Settings Summary

**For Choose90.org hybrid site, configure BPS to:**

✅ **EXCLUDE from all monitoring:**
- `/resources/`
- `/resources-backup/`
- `resources-hub.html`

✅ **DISABLE for root directory:**
- Auto-Restore deleted files
- Auto-Create missing directories
- .htaccess auto-lock

✅ **WHITELIST:**
- All `*.html` files in root
- `/resources-backup/*` directory and contents

✅ **MONITOR:**
- Only WordPress core files
- Only `/wp-content/plugins/` and `/wp-content/themes/` (not root)

## Alternative: Selective BPS Deactivation

If BPS continues to interfere, consider:

1. **Keep BPS active** for WordPress core protection
2. **Disable BPS** for root directory monitoring only
3. **Use BPS** only for `/wp-admin/` and `/wp-content/` protection
4. **Manual .htaccess management** for root directory

