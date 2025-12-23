# fix_resources_403.ps1
# Fixes 403 error by renaming conflicting resources directory

$DestPath = "Z:\"
$ResourcesDir = Join-Path $DestPath "resources"
$BackupDir = Join-Path $DestPath "resources-backup"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   Fix Resources 403 Error                " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

if (-not (Test-Path "Z:\")) {
    Write-Error "Drive Z: is not connected. Please run 'map_drive_interactive.ps1' first."
    exit 1
}

if (-not (Test-Path $ResourcesDir -PathType Container)) {
    Write-Host "No conflicting directory found. The 403 might be from another cause." -ForegroundColor Green
    Write-Host ""
    Write-Host "Please check:" -ForegroundColor Yellow
    Write-Host "  1. WordPress page exists: Pages -> All Pages -> Resources" -ForegroundColor White
    Write-Host "  2. Page is published (not Draft)" -ForegroundColor White
    Write-Host "  3. Permalink is set to /resources/" -ForegroundColor White
    Write-Host "  4. Go to Settings -> Permalinks -> Save" -ForegroundColor White
    exit 0
}

Write-Host "Found conflicting directory: Z:\resources\" -ForegroundColor Yellow
Write-Host ""
Write-Host "This directory contains resource HTML files." -ForegroundColor Gray
Write-Host "It needs to be renamed so WordPress can serve the /resources/ page." -ForegroundColor Gray
Write-Host ""

# Check if backup already exists
if (Test-Path $BackupDir) {
    Write-Host "Backup directory already exists: resources-backup" -ForegroundColor Yellow
    $backupName = "resources-backup-" + (Get-Date -Format "yyyyMMdd-HHmmss")
    $BackupDir = Join-Path $DestPath $backupName
    Write-Host "   Will use: $backupName" -ForegroundColor Gray
}

Write-Host "This will rename:" -ForegroundColor Cyan
Write-Host "  Z:\resources\ -> Z:\resources-backup\" -ForegroundColor White
Write-Host ""
Write-Host "The files will still be accessible, just at a different path." -ForegroundColor Gray
Write-Host ""

$confirm = Read-Host "Continue? (y/n)"

if ($confirm -notmatch "^y") {
    Write-Host "Cancelled." -ForegroundColor Red
    exit 0
}

try {
    Write-Host ""
    Write-Host "Renaming directory..." -ForegroundColor Yellow
    Rename-Item -Path $ResourcesDir -NewName (Split-Path -Leaf $BackupDir) -Force
    Write-Host "Directory renamed successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Next steps:" -ForegroundColor Cyan
    Write-Host "  1. Go to WordPress Admin -> Settings -> Permalinks -> Save" -ForegroundColor White
    Write-Host "  2. Test: https://choose90.org/resources/" -ForegroundColor White
    Write-Host ""
    Write-Host "Note: Resource files are now at:" -ForegroundColor Gray
    Write-Host "  Z:\resources-backup\" -ForegroundColor Gray
    Write-Host "  (Update links if needed)" -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "Error renaming directory: $_" -ForegroundColor Red
    Write-Host ""
    Write-Host "You may need to rename it manually via cPanel File Manager." -ForegroundColor Yellow
    exit 1
}
