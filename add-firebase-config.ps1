# Add Firebase config structure to secrets.json
$secretsPath = "Z:\secrets.json"

if (-not (Test-Path $secretsPath)) {
    Write-Host "ERROR: secrets.json not found at $secretsPath" -ForegroundColor Red
    exit 1
}

try {
    $content = Get-Content $secretsPath -Raw
    $json = $content | ConvertFrom-Json
    
    if ($json.firebase) {
        Write-Host "Firebase config already exists in secrets.json" -ForegroundColor Green
        exit 0
    }
    
    # Remove trailing whitespace and find the last }
    $content = $content.TrimEnd()
    
    # Add Firebase config before the final }
    $firebaseConfig = @'
  "firebase": {
    "api_key": "YOUR_FIREBASE_API_KEY",
    "auth_domain": "YOUR_PROJECT.firebaseapp.com",
    "project_id": "YOUR_PROJECT_ID",
    "storage_bucket": "YOUR_PROJECT.appspot.com",
    "messaging_sender_id": "123456789",
    "app_id": "1:123456789:web:abcdef"
  }
'@
    
    # Add comma before firebase if there's already content before the final }
    if ($content -match '([^,\s])\s*\}\s*$') {
        $content = $content -replace '([^,\s])\s*\}\s*$', "`$1,`n$firebaseConfig`n}"
    } else {
        $content = $content -replace '\s*\}\s*$', ",`n$firebaseConfig`n}"
    }
    
    $content | Set-Content $secretsPath -NoNewline -Encoding UTF8
    
    Write-Host "✓ Firebase config structure added to server secrets.json" -ForegroundColor Green
    Write-Host ""
    Write-Host "⚠ IMPORTANT: Replace placeholder values with your actual Firebase credentials!" -ForegroundColor Yellow
    Write-Host "   See FIREBASE_AUTH_SETUP_DETAILED.md for instructions" -ForegroundColor Cyan
    Write-Host "   Or get values from Firebase Console > Project Settings > Your Apps" -ForegroundColor Cyan
    
} catch {
    Write-Host "ERROR: Failed to modify secrets.json: $_" -ForegroundColor Red
    exit 1
}
