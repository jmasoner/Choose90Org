# deploy_htaccess_fix.ps1
# Deploys the fixed .htaccess file to prioritize index.html

$DestPath = "Z:\"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   .htaccess Fix Deployment               " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

if (-not (Test-Path "Z:\")) {
    Write-Error "Drive Z: is not connected. Please run 'map_drive_interactive.ps1' first."
    exit 1
}

Write-Host ""
Write-Host "⚠️  WARNING: This will overwrite your root .htaccess file!" -ForegroundColor Yellow
Write-Host "Make sure you have a backup!" -ForegroundColor Yellow
Write-Host ""
$confirm = Read-Host "Continue? (y/n)"

# Accept y, yes, Y, YES, Yes (case-insensitive)
if ($confirm -notmatch "^y" -and $confirm -notmatch "^yes") {
    Write-Host "Cancelled." -ForegroundColor Red
    exit 0
}

# Backup existing .htaccess
$BackupPath = "Z:\.htaccess.backup.$(Get-Date -Format 'yyyyMMdd-HHmmss')"
if (Test-Path "Z:\.htaccess") {
    Write-Host "Creating backup: $BackupPath" -ForegroundColor Gray
    Copy-Item "Z:\.htaccess" $BackupPath
}

# Deploy fixed .htaccess
Write-Host "Deploying fixed .htaccess..." -ForegroundColor Green
Copy-Item ".htaccess" "Z:\.htaccess" -Force

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   DEPLOYMENT COMPLETE                    " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "✓ Backup saved to: $BackupPath" -ForegroundColor Green
Write-Host "✓ Fixed .htaccess deployed" -ForegroundColor Green
Write-Host ""
Write-Host "Please test: https://choose90.org" -ForegroundColor Yellow
Write-Host ""
Write-Host "If there are issues, restore from backup:" -ForegroundColor Gray
Write-Host "  Copy-Item '$BackupPath' 'Z:\.htaccess' -Force" -ForegroundColor Gray

