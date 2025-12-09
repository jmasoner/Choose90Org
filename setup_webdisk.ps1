# choose90.org Secure WebDisk Setup Script
# Converted from VBScript for better PowerShell/CLI compatibility

$strURL = "\\choose90.org@SSL@2078\DavWWWRoot"
$strName = "choose90.org Secure WebDisk"

Write-Host "Configuring WebClient Service..."
$service = Get-Service -Name "WebClient" -ErrorAction SilentlyContinue

if ($service) {
    if ($service.StartType -ne 'Automatic') {
        Write-Host "Setting WebClient service to Automatic start..."
        Set-Service -Name "WebClient" -StartupType Automatic
    }
    
    if ($service.Status -ne 'Running') {
        Write-Host "Starting WebClient service..."
        Start-Service -Name "WebClient"
    }
    Write-Host "WebClient Service is Ready."
} else {
    Write-Error "WebClient service not found on this machine."
    exit 1
}

Write-Host "Creating Desktop Shortcut..."
$WshShell = New-Object -comObject WScript.Shell
$DesktopPath = $WshShell.SpecialFolders.Item("Desktop")
$ShortcutFile = "$DesktopPath\$strName.lnk"
$Shortcut = $WshShell.CreateShortcut($ShortcutFile)
$Shortcut.TargetPath = $strURL
$Shortcut.IconLocation = "%SystemRoot%\system32\SHELL32.dll,9"
$Shortcut.Description = $strName
$Shortcut.Save()

Write-Host "Shortcut created at: $ShortcutFile"
Write-Host "Opening Web Disk location..."

# Open the folder in Explorer
Invoke-Item $strURL
