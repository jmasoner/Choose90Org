# Create ZIP file for browser extension download
# This script creates a ZIP file of the browser-extension folder for distribution

$extensionFolder = "browser-extension"
$zipFile = "hybrid_site\browser-extension.zip"

Write-Host "Creating browser extension ZIP file..." -ForegroundColor Cyan

if (-not (Test-Path $extensionFolder)) {
    Write-Host "Error: $extensionFolder folder not found!" -ForegroundColor Red
    exit 1
}

# Remove existing ZIP if it exists
if (Test-Path $zipFile) {
    Remove-Item $zipFile -Force
    Write-Host "Removed existing ZIP file" -ForegroundColor Yellow
}

# Create ZIP file
Compress-Archive -Path "$extensionFolder\*" -DestinationPath $zipFile -Force

if (Test-Path $zipFile) {
    $zipSize = (Get-Item $zipFile).Length / 1KB
    Write-Host "✓ ZIP file created successfully: $zipFile" -ForegroundColor Green
    Write-Host "  Size: $([math]::Round($zipSize, 2)) KB" -ForegroundColor Gray
    Write-Host ""
    Write-Host "Next steps:" -ForegroundColor Cyan
    Write-Host "1. Deploy $zipFile to the server (Z:\browser-extension.zip)" -ForegroundColor White
    Write-Host "2. Ensure the download link in pwa.html points to /browser-extension.zip" -ForegroundColor White
} else {
    Write-Host "Error: Failed to create ZIP file!" -ForegroundColor Red
    exit 1
}


