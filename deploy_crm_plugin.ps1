# deploy_crm_plugin.ps1
# Deploys the Choose90 CRM plugin to the live server via Web Disk (Z:)

$SourcePath = Join-Path $PSScriptRoot "wp-content\plugins\choose90-crm"
$DestPath = "Z:\wp-content\plugins\choose90-crm"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   Choose90 CRM Plugin Deployment         " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# 1. Check for Plugin Source
if (-not (Test-Path $SourcePath)) {
    Write-Error "Source folder '$SourcePath' not found!"
    Write-Host "Make sure you're running this from the project root directory." -ForegroundColor Yellow
    exit 1
}

# 2. Check for Web Disk (Z:)
if (-not (Test-Path "Z:\")) {
    Write-Warning "Drive Z: is not accessible."
    Write-Host "Please ensure your Web Disk is connected and mapped to Z:" -ForegroundColor Yellow
    Write-Host "You can run 'map_drive_interactive.ps1' or 'launch_workspace.ps1' to map it." -ForegroundColor Yellow
    exit 1
}

# 3. Check for WordPress plugins directory on server
$PluginsDir = "Z:\wp-content\plugins"
if (-not (Test-Path $PluginsDir)) {
    Write-Error "WordPress plugins directory not found at '$PluginsDir'"
    Write-Host "Please verify your Z: drive is mapped to the correct WordPress root." -ForegroundColor Yellow
    exit 1
}

# 4. Remove existing plugin if it exists
if (Test-Path $DestPath) {
    Write-Host "Removing existing plugin..." -ForegroundColor Yellow
    try {
        Remove-Item -Path $DestPath -Recurse -Force
        Write-Host "  [OK] Existing plugin removed" -ForegroundColor Green
    }
    catch {
        Write-Error "Failed to remove existing plugin: $_"
        exit 1
    }
}

# 5. Copy Plugin Files
Write-Host ""
Write-Host "Copying plugin files..." -ForegroundColor Cyan
Write-Host "  Source: $SourcePath" -ForegroundColor Gray
Write-Host "  Dest:   $DestPath" -ForegroundColor Gray
Write-Host ""

try {
    # Create destination directory
    New-Item -ItemType Directory -Force -Path $DestPath | Out-Null
    
    # Copy all files recursively
    Copy-Item -Path "$SourcePath\*" -Destination $DestPath -Recurse -Force
    
    Write-Host "==========================================" -ForegroundColor Cyan
    Write-Host "   DEPLOYMENT COMPLETE                    " -ForegroundColor Green
    Write-Host "==========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Next Steps:" -ForegroundColor Yellow
    Write-Host "1. Log into WordPress Admin: https://choose90.org/wp-admin" -ForegroundColor White
    Write-Host "2. Go to Plugins → Installed Plugins" -ForegroundColor White
    Write-Host "3. Find 'Choose90 CRM' and click 'Activate'" -ForegroundColor White
    Write-Host "4. Go to CRM → Settings to configure" -ForegroundColor White
    Write-Host ""
}
catch {
    Write-Error "Deployment failed: $_"
    exit 1
}

