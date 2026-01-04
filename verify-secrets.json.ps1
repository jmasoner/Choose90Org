# Verify secrets.json structure
# This script checks if secrets.json has the correct structure for Firebase

Write-Host "Verifying secrets.json..." -ForegroundColor Cyan

if (-not (Test-Path "secrets.json")) {
    Write-Host "ERROR: secrets.json file not found!" -ForegroundColor Red
    exit 1
}

Write-Host "✓ File exists" -ForegroundColor Green

# Try to parse JSON
try {
    $jsonContent = Get-Content "secrets.json" -Raw
    $json = $jsonContent | ConvertFrom-Json
    Write-Host "✓ Valid JSON format" -ForegroundColor Green
} catch {
    Write-Host "ERROR: Invalid JSON format!" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    exit 1
}

# Check for required sections
$requiredSections = @("firebase")
$missingSections = @()

foreach ($section in $requiredSections) {
    if (-not ($json.PSObject.Properties.Name -contains $section)) {
        $missingSections += $section
    }
}

if ($missingSections.Count -gt 0) {
    Write-Host "ERROR: Missing required sections:" -ForegroundColor Red
    foreach ($section in $missingSections) {
        Write-Host "  - $section" -ForegroundColor Red
    }
    exit 1
}

Write-Host "✓ Required sections present" -ForegroundColor Green

# Check Firebase configuration
if ($json.firebase) {
    Write-Host "`nFirebase Configuration:" -ForegroundColor Cyan
    
    $firebaseFields = @(
        "api_key",
        "auth_domain",
        "project_id",
        "storage_bucket",
        "messaging_sender_id",
        "app_id"
    )
    
    $missingFields = @()
    $placeholderFields = @()
    
    foreach ($field in $firebaseFields) {
        if (-not ($json.firebase.PSObject.Properties.Name -contains $field)) {
            $missingFields += $field
        } else {
            $value = $json.firebase.$field
            $isPlaceholder = $false
            if ($value -match "^your-") { $isPlaceholder = $true }
            if ($value -match "^your_") { $isPlaceholder = $true }
            if ($value -eq "123456789") { $isPlaceholder = $true }
            if ($value -match "^abcdef") { $isPlaceholder = $true }
            if ($value -match "^AIzaSyAbCdEf") { $isPlaceholder = $true }
            
            if ($isPlaceholder) {
                $placeholderFields += $field
            } else {
                $displayValue = $value
                if ($displayValue.Length -gt 30) {
                    $displayValue = $displayValue.Substring(0, 30) + "..."
                }
                Write-Host "  ✓ $field : $displayValue" -ForegroundColor Green
            }
        }
    }
    
    if ($missingFields.Count -gt 0) {
        Write-Host "`nERROR: Missing Firebase fields:" -ForegroundColor Red
        foreach ($field in $missingFields) {
            Write-Host "  - $field" -ForegroundColor Red
        }
    }
    
    if ($placeholderFields.Count -gt 0) {
        Write-Host "`nWARNING: Fields still contain placeholder values:" -ForegroundColor Yellow
        foreach ($field in $placeholderFields) {
            Write-Host "  - $field" -ForegroundColor Yellow
        }
    }
    
    if ($missingFields.Count -eq 0 -and $placeholderFields.Count -eq 0) {
        Write-Host "`n✓ All Firebase fields are configured!" -ForegroundColor Green
    }
}

# Check for other sections (optional)
Write-Host "`nOther sections found:" -ForegroundColor Cyan
foreach ($prop in $json.PSObject.Properties.Name) {
    if ($prop -ne "firebase") {
        Write-Host "  ✓ $prop" -ForegroundColor Gray
    }
}

Write-Host "`nVerification complete!" -ForegroundColor Cyan
