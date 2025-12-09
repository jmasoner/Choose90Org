# map_drive_interactive.ps1
# This script launches a command prompt to help you map the drive interactively.
# It allows you to enter your password if prompted.

Write-Host "Launching interactive mapping window..." -ForegroundColor Cyan

$Target = "\\choose90.org@SSL@2078\DavWWWRoot"

# Launch a new CMD window to run the net use command.
# This ensures any password prompt is visible to you.
Start-Process cmd -ArgumentList "/k", "echo Clearing old Z: mapping... & net use Z: /delete & echo. & echo Connecting to Choose90 Web Disk... & echo NOTE: Use your hosting username (choose90) and password if prompted. & net use Z: `"$Target`" /persistent:yes & echo. & echo If Successful, close this window and run deploy_hybrid_site.ps1"
