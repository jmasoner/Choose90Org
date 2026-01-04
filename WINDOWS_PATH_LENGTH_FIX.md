# Windows Path Length Limit Fix

## The Problem
Windows has a 260 character path length limit. When extracting ZIP files with deep folder structures, you may see:
- **Error 0x80010135: Path too long**
- Files fail to extract
- Copy operations fail

## Solutions

### Option 1: Extract to Short Path (Easiest)
1. **Copy the ZIP file to a short location:**
   - Example: `C:\temp\browser-extension.zip`
   - Or: `C:\ext\browser-extension.zip`

2. **Extract there:**
   - Right-click → Extract All
   - Or use 7-Zip/WinRAR

3. **Move the extracted folder** to your desired location after extraction

### Option 2: Enable Long Path Support (Windows 10/11)
1. Open **Group Policy Editor** (gpedit.msc)
   - Or Registry Editor (regedit.exe)

2. Navigate to:
   ```
   Computer Configuration → Administrative Templates → System → Filesystem
   ```

3. Enable: **"Enable Win32 long paths"**

4. Restart your computer

### Option 3: Use 7-Zip
7-Zip handles long paths better than Windows built-in extractor:
1. Download 7-Zip: https://www.7-zip.org/
2. Right-click ZIP → 7-Zip → Extract Here
3. Usually works even with long paths

### Option 4: Use PowerShell (For Developers)
```powershell
# Extract to short path first
Expand-Archive -Path "browser-extension.zip" -DestinationPath "C:\temp\extract"

# Then move to final location
Move-Item "C:\temp\extract\browser-extension" "C:\Your\Final\Location"
```

## For Choose90 Extension Installation

**Recommended workflow:**
1. Download `browser-extension.zip`
2. Copy it to `C:\temp\` (short path)
3. Extract there
4. Load the extension from `C:\temp\browser-extension\` folder
5. Or move it to your preferred location after extraction

## Quick Fix Script

I've created `create-extension-zip-simple.ps1` which creates the ZIP in the current directory to minimize path length issues.


