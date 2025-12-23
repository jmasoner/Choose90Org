# deploy_production.ps1
# Production deployment script - excludes test files

$SourcePath = Join-Path $PSScriptRoot "hybrid_site"
$DestPath = "Z:\"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   Choose90.org PRODUCTION Deployment    " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# Check for source
if (-not (Test-Path $SourcePath)) {
    Write-Error "Source folder '$SourcePath' not found!"
    exit 1
}

# Check for Web Disk
if (-not (Test-Path "Z:\")) {
    Write-Warning "Drive Z: is not accessible."
    Write-Host "Please ensure your Web Disk is connected and mapped to Z:" -ForegroundColor Yellow
    exit 1
}

# Files to EXCLUDE from production
$ExcludeFiles = @(
    "test-api-keys.php",
    "test-api-keys-production.php"
)

Write-Host "`nDeploying production files (excluding test files)..." -ForegroundColor Yellow
Write-Host "Source: $SourcePath" -ForegroundColor Gray
Write-Host "Dest:   $DestPath" -ForegroundColor Gray
Write-Host ""

# Get all files and folders
$Items = Get-ChildItem $SourcePath -Recurse

$DeployedCount = 0
$SkippedCount = 0

foreach ($Item in $Items) {
    $RelativePath = $Item.FullName.Substring($SourcePath.Length + 1)
    $ShouldExclude = $false
    
    foreach ($Exclude in $ExcludeFiles) {
        if ($RelativePath -like "*$Exclude*") {
            $ShouldExclude = $true
            break
        }
    }
    
    if ($ShouldExclude) {
        Write-Host "Skipping (test file): $RelativePath" -ForegroundColor DarkYellow
        $SkippedCount++
        continue
    }
    
    # Special handling: resources/ folder should deploy to resources-backup/ to avoid WordPress conflict
    if ($RelativePath -like "resources\*") {
        $RelativePath = $RelativePath -replace "^resources\\", "resources-backup\"
    }
    
    $DestItemPath = Join-Path $DestPath $RelativePath
    
    try {
        if ($Item.PSIsContainer) {
            # Create directory if it doesn't exist
            if (-not (Test-Path $DestItemPath)) {
                New-Item -ItemType Directory -Path $DestItemPath -Force | Out-Null
            }
        } else {
            # Create parent directory if needed
            $DestParent = Split-Path $DestItemPath -Parent
            if (-not (Test-Path $DestParent)) {
                New-Item -ItemType Directory -Path $DestParent -Force | Out-Null
            }
            
            # Copy file
            Copy-Item -Path $Item.FullName -Destination $DestItemPath -Force
            Write-Host "Deployed: $RelativePath" -ForegroundColor Green
            $DeployedCount++
        }
    }
    catch {
        Write-Host "Failed: $RelativePath" -ForegroundColor Red
        Write-Error $_
    }
}

Write-Host "`n==========================================" -ForegroundColor Cyan
Write-Host "   DEPLOYMENT COMPLETE                     " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "Files deployed: $DeployedCount" -ForegroundColor Green
Write-Host "Files skipped:  $SkippedCount" -ForegroundColor Yellow
Write-Host "`nVisit https://choose90.org to verify." -ForegroundColor Cyan

