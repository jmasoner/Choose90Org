# Create ZIP file for browser extension - Simple version
# Creates ZIP in current directory to avoid path length issues

$extensionFolder = "browser-extension"
$zipFile = "browser-extension.zip"

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

# Create ZIP using PowerShell Compress-Archive (simplest method)
Write-Host "Compressing files..." -ForegroundColor Gray

try {
    # Get all files to include
    $files = Get-ChildItem -Path $extensionFolder -Recurse -File
    
    # Create a temp folder with shorter paths
    $tempFolder = "temp-ext-zip"
    if (Test-Path $tempFolder) {
        Remove-Item $tempFolder -Recurse -Force
    }
    New-Item -ItemType Directory -Path $tempFolder -Force | Out-Null
    
    # Copy files maintaining structure but with shorter base path
    foreach ($file in $files) {
        $relativePath = $file.FullName.Substring((Resolve-Path $extensionFolder).Path.Length + 1)
        $destPath = Join-Path $tempFolder $relativePath
        $destDir = Split-Path $destPath -Parent
        if (-not (Test-Path $destDir)) {
            New-Item -ItemType Directory -Path $destDir -Force | Out-Null
        }
        Copy-Item $file.FullName $destPath -Force
    }
    
    # Create ZIP from temp folder
    Compress-Archive -Path "$tempFolder\*" -DestinationPath $zipFile -Force
    
    # Clean up temp folder
    Remove-Item $tempFolder -Recurse -Force
    
    Write-Host "✓ ZIP file created successfully!" -ForegroundColor Green
    
    if (Test-Path $zipFile) {
        $zipSize = (Get-Item $zipFile).Length / 1KB
        Write-Host "  Location: $((Get-Item $zipFile).FullName)" -ForegroundColor Gray
        Write-Host "  Size: $([math]::Round($zipSize, 2)) KB" -ForegroundColor Gray
        Write-Host ""
        Write-Host "To extract without path length issues:" -ForegroundColor Cyan
        Write-Host "1. Copy ZIP to a short path like C:\temp\" -ForegroundColor White
        Write-Host "2. Extract there" -ForegroundColor White
        Write-Host "3. Then copy the browser-extension folder to your desired location" -ForegroundColor White
    }
} catch {
    Write-Host "Error: $_" -ForegroundColor Red
    exit 1
}


