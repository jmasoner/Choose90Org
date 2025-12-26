# remove_empty_resources.ps1
# Removes the empty resources directory that's causing 403 errors

$DestPath = "Z:\"
$ResourcesDir = Join-Path $DestPath "resources"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   Remove Empty Resources Directory      " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

if (-not (Test-Path "Z:\")) {
    Write-Error "Drive Z: is not connected. Please run 'map_drive_interactive.ps1' first."
    exit 1
}

if (-not (Test-Path $ResourcesDir -PathType Container)) {
    Write-Host "No resources directory found. Nothing to remove." -ForegroundColor Green
    exit 0
}

# Check if directory is empty
$items = Get-ChildItem $ResourcesDir -Force
$fileCount = ($items | Where-Object { $_.Name -ne "." -and $_.Name -ne ".." }).Count

if ($fileCount -gt 0) {
    Write-Host "WARNING: Directory is not empty! It contains $fileCount items." -ForegroundColor Yellow
    Write-Host "Contents:" -ForegroundColor Gray
    Get-ChildItem $ResourcesDir -Force | Select-Object -First 10 | ForEach-Object {
        Write-Host "  - $($_.Name)" -ForegroundColor Gray
    }
    Write-Host ""
    $confirm = Read-Host "Do you want to delete it anyway? (yes/no)"
    if ($confirm -notmatch "^yes$") {
        Write-Host "Cancelled." -ForegroundColor Red
        exit 0
    }
} else {
    Write-Host "Directory is empty. Safe to remove." -ForegroundColor Green
}

Write-Host ""
Write-Host "This will DELETE:" -ForegroundColor Cyan
Write-Host "  Z:\resources\" -ForegroundColor White
Write-Host ""
Write-Host "This will allow WordPress to serve the /resources/ page." -ForegroundColor Gray
Write-Host ""

$confirm = Read-Host "Continue? (y/n)"

if ($confirm -notmatch "^y") {
    Write-Host "Cancelled." -ForegroundColor Red
    exit 0
}

try {
    Write-Host ""
    Write-Host "Removing directory..." -ForegroundColor Yellow
    Remove-Item -Path $ResourcesDir -Recurse -Force
    Write-Host "Directory removed successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Next steps:" -ForegroundColor Cyan
    Write-Host "  1. Go to WordPress Admin -> Settings -> Permalinks -> Save" -ForegroundColor White
    Write-Host "  2. Test: https://choose90.org/resources/" -ForegroundColor White
    Write-Host ""
} catch {
    Write-Host "Error removing directory: $_" -ForegroundColor Red
    Write-Host ""
    Write-Host "You may need to remove it manually via cPanel File Manager." -ForegroundColor Yellow
    exit 1
}



