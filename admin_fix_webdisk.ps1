# Run this script as Administrator
# It fixes the WebClient service and registry settings for Web Disk

Write-Host "Configuring WebClient Service..."
Set-Service -Name "WebClient" -StartupType Automatic
Start-Service -Name "WebClient"

Write-Host "Setting Registry for Basic Auth..."
# Allow Basic Auth for SSL (1) and Non-SSL (2). Setting to 2 is most compatible.
Set-ItemProperty -Path "HKLM:\SYSTEM\CurrentControlSet\Services\WebClient\Parameters" -Name "BasicAuthLevel" -Value 2

Write-Host "Restarting WebClient..."
Restart-Service "WebClient"
Write-Host "Done! You can close this window."
