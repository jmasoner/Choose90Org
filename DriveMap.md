# WebDisk (WebDAV) Drive Mapping Guide

Use this guide to connect your hosted server files directly to your Windows computer as a "Live" network drive.

---

## 1. Preparation (One-Time Setup)

Windows often disables the services needed for WebDisk by default. If your drives won't connect, run the following steps one time:

1. **Run PowerShell as Administrator.**
2. **Paste and run this code:**

    ```powershell
    # Enable WebClient Service
    Set-Service -Name "WebClient" -StartupType Automatic
    Start-Service -Name "WebClient"

    # Allow Basic Authentication (Most compatible setting)
    Set-ItemProperty -Path "HKLM:\SYSTEM\CurrentControlSet\Services\WebClient\Parameters" -Name "BasicAuthLevel" -Value 2

    Restart-Service "WebClient"
    ```

---

## 2. Mapping Your Primary Drive (Z: Choose90)

To map your first drive, it is best to use the **Interactive Command** method. This ensures that the password prompt is visible and doesn't get stuck in the background.

1. **Target URL:** `\\choose90.org@SSL@2078\DavWWWRoot`
2. **Run this Command in PowerShell:**

    ```powershell
    $Target = "\\choose90.org@SSL@2078\DavWWWRoot"
    Start-Process cmd -ArgumentList "/k", "net use Z: /delete & net use Z: `"$Target`" /persistent:yes"
    ```

3. **When prompted:** Enter your hosting username (`choose90`) and your hosting password.

---

## 3. Mapping Additional Drives (Y: Combrokers)

You can map as many drives as you like (Y:, X:, W:, etc.). Each one follows the same logic.

1. **Target URL:** `\\combrokers.com@SSL@2078\DavWWWRoot`
2. **Run this Command in PowerShell:**

    ```powershell
    $Target = "\\combrokers.com@SSL@2078\DavWWWRoot"
    Start-Process cmd -ArgumentList "/k", "net use Y: /delete & net use Y: `"$Target`" /persistent:yes"
    ```

3. **Note on Credentials:** If you are using a different account for Combrokers, click **"Use a different account"** in the Windows login box to ensure the correct username is used.

---

## 4. Creating Desktop Shortcuts

If you don't want to use drive letters, you can create a direct desktop shortcut to the server folder:

```powershell
# Change $strURL for each different site/account
$strURL = "\\choose90.org@SSL@2078\DavWWWRoot"
$WshShell = New-Object -comObject WScript.Shell
$DesktopPath = $WshShell.SpecialFolders.Item("Desktop")
$Shortcut = $WshShell.CreateShortcut("$DesktopPath\Choose90_WebDisk.lnk")
$Shortcut.TargetPath = $strURL
$Shortcut.Save()
```

---

## 5. Troubleshooting Common Issues

* **"System Error 67" or "Network Path not found":** This usually means the **WebClient** service is not running. Re-run the fix in Step 1.
* **Login Box Keeps Appearing:** Double-check your username (e.g., `choose90` or `constit2`). Ensure you are using the password specifically assigned to your Web Disk or Hosting account.
* **Drive Disconnects After Restart:** The `/persistent:yes` flag in the commands above should prevent this, but if it happens, just re-run the mapping command.
