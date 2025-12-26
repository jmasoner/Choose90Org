# check_resources_403.ps1
# Checks for resources directory conflict and provides fix instructions

$DestPath = "Z:\"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   Resources 403 Error Checker            " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

if (-not (Test-Path "Z:\")) {
    Write-Error "Drive Z: is not connected. Please run 'map_drive_interactive.ps1' first."
    exit 1
}

# Check for physical resources directory
$ResourcesDir = Join-Path $DestPath "resources"
$ResourcesFile = Join-Path $DestPath "resources.html"

Write-Host "Checking for conflicts..." -ForegroundColor Yellow
Write-Host ""

# Check 1: Physical directory
if (Test-Path $ResourcesDir -PathType Container) {
    Write-Host "⚠️  FOUND: Physical directory at Z:\resources\" -ForegroundColor Red
    Write-Host "   This is likely causing the 403 error!" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "   Contents:" -ForegroundColor Gray
    $contents = Get-ChildItem $ResourcesDir -ErrorAction SilentlyContinue
    if ($contents) {
        foreach ($item in $contents) {
            Write-Host "     - $($item.Name)" -ForegroundColor Gray
        }
    } else {
        Write-Host "     (empty)" -ForegroundColor Gray
    }
    Write-Host ""
    Write-Host "   SOLUTION:" -ForegroundColor Green
    Write-Host "   1. Rename the directory:" -ForegroundColor White
    Write-Host "      Rename-Item '$ResourcesDir' 'resources-backup'" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "   2. OR delete it if not needed:" -ForegroundColor White
    Write-Host "      Remove-Item '$ResourcesDir' -Recurse -Force" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "   3. Then refresh WordPress permalinks:" -ForegroundColor White
    Write-Host "      Go to WordPress Admin → Settings → Permalinks → Save" -ForegroundColor Cyan
    Write-Host ""
} else {
    Write-Host "✓ No physical resources directory found" -ForegroundColor Green
}

# Check 2: resources.html file
if (Test-Path $ResourcesFile) {
    Write-Host "✓ Found resources.html file (this is OK)" -ForegroundColor Green
} else {
    Write-Host "ℹ️  No resources.html in root (this is OK if using WordPress page)" -ForegroundColor Gray
}

# Check 3: WordPress page template
$ThemePath = "Z:\wp-content\themes\Divi-choose90"
$TemplateFile = Join-Path $ThemePath "page-resources.php"

if (Test-Path $TemplateFile) {
    Write-Host "✓ Resources page template exists" -ForegroundColor Green
} else {
    Write-Host "⚠️  Resources page template NOT found" -ForegroundColor Yellow
    Write-Host "   Run: .\setup_child_theme.ps1" -ForegroundColor Cyan
}

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   Next Steps                            " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. If physical directory exists, rename or delete it" -ForegroundColor White
Write-Host "2. Verify WordPress page exists: Pages → All Pages → 'Resources'" -ForegroundColor White
Write-Host "3. Go to Settings → Permalinks → Save" -ForegroundColor White
Write-Host "4. Test: https://choose90.org/resources/" -ForegroundColor White
Write-Host ""



