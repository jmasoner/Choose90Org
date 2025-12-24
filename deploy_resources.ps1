# deploy_resources.ps1
# Deploys resources (PDFs, guides, etc.) to the live server

$SourcePDF = Join-Path $PSScriptRoot "Choose90-Digital-Detox-Guide.pdf"
# IMPORTANT: Use resources-backup to avoid conflicting with the WordPress /resources/ page
$DestResourcesDir = "Z:\resources-backup"
$ThemePath = "Z:\wp-content\themes\Divi-choose90"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   Choose90.org Resources Deployment     " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# 1. Check for Web Disk (Z:)
if (-not (Test-Path "Z:\")) {
    Write-Error "Drive Z: is not connected. Please run 'map_drive_interactive.ps1' first."
    exit 1
}

# 2. Create resources-backup directory if it doesn't exist
if (-not (Test-Path $DestResourcesDir)) {
    Write-Host "Creating resources-backup directory..." -ForegroundColor Cyan
    New-Item -ItemType Directory -Force -Path $DestResourcesDir | Out-Null
    Write-Host "resources-backup directory created." -ForegroundColor Green
}

# 3. Deploy PDF
if (Test-Path $SourcePDF) {
    Write-Host "Deploying Digital Detox Guide..." -NoNewline
    try {
        Copy-Item -Path $SourcePDF -Destination $DestResourcesDir -Force
        Write-Host " [OK]" -ForegroundColor Green
    }
    catch {
        Write-Host " [FAILED]" -ForegroundColor Red
        Write-Error $_
    }
}
else {
    Write-Warning "Source PDF not found at: $SourcePDF"
}

# 4. Deploy page-resources.php template to theme
$SourceTemplate = Join-Path $PSScriptRoot "page-resources.php"
if (Test-Path $SourceTemplate) {
    # Create theme directory if it doesn't exist
    if (-not (Test-Path $ThemePath)) {
        Write-Host "Creating theme directory..." -ForegroundColor Cyan
        New-Item -ItemType Directory -Force -Path $ThemePath | Out-Null
    }
    
    Write-Host "Deploying Resources page template..." -NoNewline
    try {
        Copy-Item -Path $SourceTemplate -Destination $ThemePath -Force
        Write-Host " [OK]" -ForegroundColor Green
    }
    catch {
        Write-Host " [FAILED]" -ForegroundColor Red
        Write-Error $_
    }
}
else {
    Write-Warning "Resources template not found at: $SourceTemplate"
}

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   RESOURCES DEPLOYMENT COMPLETE         " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next Steps:" -ForegroundColor Yellow
Write-Host "1. Go to WordPress Admin > Pages" -ForegroundColor White
Write-Host "2. Create a new page called 'Resources'" -ForegroundColor White
Write-Host "3. In Page Attributes, select 'Resources Page' as the template" -ForegroundColor White
Write-Host "4. Set the permalink to '/resources/'" -ForegroundColor White
Write-Host "5. Publish the page" -ForegroundColor White
Write-Host ""
Write-Host "The Digital Detox Guide PDF will be available at:" -ForegroundColor Cyan
Write-Host "https://choose90.org/resources-backup/Choose90-Digital-Detox-Guide.pdf" -ForegroundColor Green

Start-Sleep -Seconds 5
