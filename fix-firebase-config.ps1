# Add Firebase config structure to secrets.json (text-based approach)
$secretsPath = "Z:\secrets.json"

if (-not (Test-Path $secretsPath)) {
    Write-Host "ERROR: secrets.json not found at $secretsPath" -ForegroundColor Red
    exit 1
}

# Backup first
$backupPath = "Z:\secrets.json.backup-$(Get-Date -Format 'yyyyMMdd-HHmmss')"
Copy-Item $secretsPath $backupPath
Write-Host "Backup created: $backupPath" -ForegroundColor Cyan

try {
    $content = Get-Content $secretsPath -Raw
    
    # Check if firebase already exists
    if ($content -match '"firebase"') {
        Write-Host "Firebase config already exists in secrets.json" -ForegroundColor Green
        exit 0
    }
    
    # Remove trailing whitespace
    $content = $content.TrimEnd()
    
    # Firebase config to add
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
    
    # Find the last closing brace and add Firebase config before it
    # Handle both cases: with or without trailing comma
    if ($content -match '(\s*)\}\s*$') {
        $indent = $Matches[1]
        
        # Check if we need a comma (if there's content before the closing brace)
        $beforeLastBrace = $content -replace '\s*\}\s*$', ''
        $needsComma = $beforeLastBrace.TrimEnd() -match '[^,\s\{]$'
        
        if ($needsComma) {
            $newContent = $content -replace '(\s*)\}\s*$', ",`n$($firebaseConfig)`n`$1}"
        } else {
            $newContent = $content -replace '(\s*)\}\s*$', "$($firebaseConfig)`n`$1}"
        }
        
        $newContent | Set-Content $secretsPath -NoNewline -Encoding UTF8
        
        Write-Host "✓ Firebase config structure added to server secrets.json" -ForegroundColor Green
        Write-Host ""
        Write-Host "⚠ IMPORTANT: Replace placeholder values with your actual Firebase credentials!" -ForegroundColor Yellow
        Write-Host "   1. Get values from Firebase Console > Project Settings > Your Apps" -ForegroundColor Cyan
        Write-Host "   2. Edit Z:\secrets.json and replace the placeholder values" -ForegroundColor Cyan
        Write-Host "   3. See FIREBASE_AUTH_SETUP_DETAILED.md for detailed instructions" -ForegroundColor Cyan
        
    } else {
        Write-Host "ERROR: Could not find closing brace in JSON file" -ForegroundColor Red
        exit 1
    }
    
} catch {
    Write-Host "ERROR: Failed to modify secrets.json: $_" -ForegroundColor Red
    Write-Host "Restoring from backup..." -ForegroundColor Yellow
    Copy-Item $backupPath $secretsPath -Force
    exit 1
}
