# deploy_hybrid_site.ps1
# Deploys the Hybrid Site files to the live server via Web Disk (Z:)

$SourcePath = Join-Path $PSScriptRoot "hybrid_site"
$DestPath = "Z:\"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   Choose90.org Hybrid Site Deployment    " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# 1. Check for Hybrid Site Source
if (-not (Test-Path $SourcePath)) {
    Write-Error "Source folder '$SourcePath' not found!"
    exit 1
}

# 2. Check for Web Disk (Z:)
if (-not (Test-Path "Z:\")) {
    Write-Warning "Drive Z: is not accessible."
    Write-Host "Please ensure your Web Disk is connected and mapped to Z:" -ForegroundColor Yellow
    Write-Host "You can run 'setup_webdisk.ps1' or map it manually."
    exit 1
}

# 3. Check for Destination Folder
if (-not (Test-Path $DestPath)) {
    Write-Host "Destination folder '$DestPath' does not exist." -ForegroundColor Yellow
    Write-Host "Creating it now..."
    New-Item -ItemType Directory -Force -Path $DestPath | Out-Null
}

# 4. Copy Files
Write-Host "Copying files from:" -ForegroundColor Gray
Write-Host "  Source: $SourcePath" -ForegroundColor Green
Write-Host "  Dest:   $DestPath" -ForegroundColor Green
Write-Host ""

# Copy files recursively to preserve folder structure
Write-Host "Deploying files recursively..." -ForegroundColor Yellow
try {
    # Copy all files and folders recursively
    Copy-Item -Path "$SourcePath\*" -Destination $DestPath -Recurse -Force
    Write-Host " [OK] All files deployed" -ForegroundColor Green
}
catch {
    Write-Host " [FAILED]" -ForegroundColor Red
    Write-Error $_
    exit 1
}

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   DEPLOYMENT COMPLETE                    " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "Please visit https://choose90.org to verify."
Start-Sleep -Seconds 3
