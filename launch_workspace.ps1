# launch_workspace.ps1
# Automates your "Choose90" workspace startup

Write-Host "Starting Choose90 Workspace..." -ForegroundColor Cyan

# 1. Connect Web Disk
Write-Host "1. Connecting to Web Disk..." -ForegroundColor Yellow

$SecretsFile = Join-Path $PSScriptRoot "secrets.json"
$Target = "\\choose90.org@SSL@2078\DavWWWRoot"

# Disconnect existing Z: to be clean
net use Z: /delete /y 2>$null | Out-Null

if (Test-Path $SecretsFile) {
    try {
        $Secrets = Get-Content $SecretsFile | ConvertFrom-Json
        $User = $Secrets.webdisk_username
        $Pass = $Secrets.webdisk_password

        # Auto-connect using secrets
        Write-Host "   Using credentials from secrets.json..."
        net use Z: "$Target" /user:"$User" "$Pass" /persistent:yes
        
        if (Test-Path "Z:\") {
            Write-Host "   [SUCCESS] Connected to Z:!" -ForegroundColor Green
        }
        else {
            Write-Warning "   [Failed] Could not map drive even with secrets."
        }
    }
    catch {
        Write-Warning "   Error reading secrets.json. Falling back to interactive."
        .\map_drive_interactive.ps1
    }
}
else {
    Write-Warning "   'secrets.json' not found. Launching interactive login..."
    Write-Host "   (Create secrets.json for auto-login)" -ForegroundColor DarkGray
    .\map_drive_interactive.ps1
}

# 2. Launch Editor (Antigravity / VS Code)
Write-Host "2. Launching VS Code (Antigravity)..." -ForegroundColor Yellow
if (Get-Command "code" -ErrorAction SilentlyContinue) {
    code .
}
else {
    Write-Warning "VS Code command 'code' not found manually."
}

# 3. Launch Other AI Tools (Uncomment/Edit paths below)
# Start-Process "C:\Path\To\GeminiDesktop.exe"
# Start-Process "C:\Users\john\AppData\Local\AnthropicClaude\Claude.exe"

Write-Host "---------------------------------------------------"
Write-Host "Workspace Setup Initialized!" -ForegroundColor Green
Start-Sleep -Seconds 5
