# Create a standalone browser extension ZIP
# This creates a clean ZIP with only the extension files, avoiding any repository structure issues

$extensionFolder = "browser-extension"
$zipFile = "choose90-extension-standalone.zip"

Write-Host "Creating standalone browser extension ZIP..." -ForegroundColor Cyan
Write-Host "This ZIP contains ONLY the extension files, no repository structure" -ForegroundColor Yellow

if (-not (Test-Path $extensionFolder)) {
    Write-Host "Error: $extensionFolder folder not found!" -ForegroundColor Red
    exit 1
}

# Remove existing ZIP if it exists
if (Test-Path $zipFile) {
    Remove-Item $zipFile -Force
    Write-Host "Removed existing ZIP file" -ForegroundColor Yellow
}

# Create a temporary clean folder structure
$tempFolder = "temp-ext-clean"
if (Test-Path $tempFolder) {
    Remove-Item $tempFolder -Recurse -Force
}
New-Item -ItemType Directory -Path $tempFolder -Force | Out-Null

Write-Host "Copying extension files to clean structure..." -ForegroundColor Gray

# Copy all files maintaining relative structure but starting from root
$files = Get-ChildItem -Path $extensionFolder -Recurse -File

foreach ($file in $files) {
    # Get relative path from extension folder
    $relativePath = $file.FullName.Substring((Resolve-Path $extensionFolder).Path.Length + 1)
    
    # Destination in temp folder (same relative structure)
    $destPath = Join-Path $tempFolder $relativePath
    $destDir = Split-Path $destPath -Parent
    
    # Create directory if needed
    if ($destDir -and -not (Test-Path $destDir)) {
        New-Item -ItemType Directory -Path $destDir -Force | Out-Null
    }
    
    # Copy file
    Copy-Item $file.FullName $destPath -Force
    
    # Verify path length
    $fullDestPath = (Resolve-Path $destPath).Path
    if ($fullDestPath.Length -gt 200) {
        Write-Host "Warning: Long path detected: $relativePath ($($fullDestPath.Length) chars)" -ForegroundColor Yellow
    }
}

Write-Host "Creating ZIP file..." -ForegroundColor Gray

# Create ZIP from temp folder (this will have clean, short paths)
try {
    Compress-Archive -Path "$tempFolder\*" -DestinationPath $zipFile -Force
    
    # Clean up temp folder
    Remove-Item $tempFolder -Recurse -Force
    
    if (Test-Path $zipFile) {
        $zipSize = (Get-Item $zipFile).Length / 1KB
        Write-Host ""
        Write-Host "✓ Standalone ZIP created successfully!" -ForegroundColor Green
        Write-Host "  File: $zipFile" -ForegroundColor Gray
        Write-Host "  Size: $([math]::Round($zipSize, 2)) KB" -ForegroundColor Gray
        Write-Host ""
        Write-Host "This ZIP can be extracted anywhere without path length issues." -ForegroundColor Cyan
        Write-Host "It contains only the browser-extension files in a clean structure." -ForegroundColor Cyan
    } else {
        Write-Host "Error: ZIP file was not created!" -ForegroundColor Red
        exit 1
    }
} catch {
    Write-Host "Error creating ZIP: $_" -ForegroundColor Red
    if (Test-Path $tempFolder) {
        Remove-Item $tempFolder -Recurse -Force
    }
    exit 1
}


