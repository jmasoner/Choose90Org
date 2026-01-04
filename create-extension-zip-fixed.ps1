# Create ZIP file for browser extension download
# This script creates a ZIP file with proper handling for Windows path length limits

$extensionFolder = "browser-extension"
$zipFile = "browser-extension.zip"  # Create in root to avoid long paths

Write-Host "Creating browser extension ZIP file..." -ForegroundColor Cyan
Write-Host "Using short path to avoid Windows 260 character limit..." -ForegroundColor Yellow

if (-not (Test-Path $extensionFolder)) {
    Write-Host "Error: $extensionFolder folder not found!" -ForegroundColor Red
    exit 1
}

# Remove existing ZIP if it exists
if (Test-Path $zipFile) {
    Remove-Item $zipFile -Force
    Write-Host "Removed existing ZIP file" -ForegroundColor Yellow
}

# Use 7-Zip if available (handles long paths better), otherwise use PowerShell
$use7zip = $false
if (Get-Command "7z.exe" -ErrorAction SilentlyContinue) {
    $use7zip = $true
    Write-Host "Using 7-Zip (better long path support)..." -ForegroundColor Green
}

if ($use7zip) {
    # Use 7-Zip which handles long paths better
    $fullZipPath = (Resolve-Path $zipFile).Path
    $fullFolderPath = (Resolve-Path $extensionFolder).Path
    
    & 7z.exe a -tzip "$fullZipPath" "$fullFolderPath\*" | Out-Null
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✓ ZIP file created successfully with 7-Zip" -ForegroundColor Green
    } else {
        Write-Host "7-Zip failed, trying PowerShell method..." -ForegroundColor Yellow
        $use7zip = $false
    }
}

if (-not $use7zip) {
    # PowerShell method - create ZIP in current directory to minimize path length
    try {
        # Change to extension folder to minimize path lengths
        Push-Location $extensionFolder
        
        # Get all files with relative paths
        $files = Get-ChildItem -Recurse -File
        
        # Create ZIP using .NET
        Add-Type -AssemblyName System.IO.Compression.FileSystem
        $zip = [System.IO.Compression.ZipFile]::Open((Join-Path (Get-Location).Parent $zipFile), [System.IO.Compression.ZipArchiveMode]::Create)
        
        foreach ($file in $files) {
            $relativePath = $file.FullName.Replace((Get-Location).Path + "\", "").Replace("\", "/")
            if ($relativePath.Length -lt 200) {  # Only add files with reasonable path lengths
                [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $file.FullName, $relativePath) | Out-Null
            } else {
                Write-Host "Skipping file with long path: $relativePath" -ForegroundColor Yellow
            }
        }
        
        $zip.Dispose()
        Pop-Location
        
        Write-Host "✓ ZIP file created successfully: $zipFile" -ForegroundColor Green
    } catch {
        Pop-Location
        Write-Host "Error creating ZIP: $_" -ForegroundColor Red
        exit 1
    }
}

if (Test-Path $zipFile) {
    $zipSize = (Get-Item $zipFile).Length / 1KB
    $zipPath = (Resolve-Path $zipFile).Path
    Write-Host ""
    Write-Host "✓ ZIP file created successfully!" -ForegroundColor Green
    Write-Host "  Location: $zipPath" -ForegroundColor Gray
    Write-Host "  Size: $([math]::Round($zipSize, 2)) KB" -ForegroundColor Gray
    Write-Host ""
    Write-Host "Next steps:" -ForegroundColor Cyan
    Write-Host "1. Copy this ZIP to a short path location (e.g., C:\temp\)" -ForegroundColor White
    Write-Host "2. Extract it there to avoid path length issues" -ForegroundColor White
    Write-Host "3. Deploy to server: Copy-Item '$zipFile' 'Z:\browser-extension.zip'" -ForegroundColor White
} else {
    Write-Host "Error: Failed to create ZIP file!" -ForegroundColor Red
    exit 1
}


