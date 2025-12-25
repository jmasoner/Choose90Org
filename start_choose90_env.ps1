# start_choose90_env.ps1
#
# Convenience script to get your Choose90 dev environment ready after a reboot.
# You can copy this file to your Desktop and double–click it, or create a shortcut to it.
#
# NOTE: Some commands are placeholders and may need path adjustments on your machine.

Write-Host "=== Starting Choose90 local environment ===" -ForegroundColor Cyan

# 1) Go to project root
$projectPath = "C:\Users\john\OneDrive\MyProjects\Choose90Org"
if (-not (Test-Path $projectPath)) {
    Write-Host "Project path not found: $projectPath" -ForegroundColor Red
    exit 1
}
Set-Location $projectPath

Write-Host "Working directory: $PWD" -ForegroundColor Gray

# 2) (Optional) Activate Python virtual environment and run your main script
#    If you later add a run.py or venv, update these lines accordingly.
if (Test-Path ".\venv\Scripts\Activate.ps1") {
    Write-Host "Activating Python virtual environment..." -ForegroundColor Yellow
    . .\venv\Scripts\Activate.ps1
}

if (Test-Path ".\run.py") {
    Write-Host "Starting Python run.py (in background)..." -ForegroundColor Yellow
    Start-Process pwsh -ArgumentList "-NoLogo","-NoProfile","-Command","cd `"$projectPath`"; python .\run.py" | Out-Null
} else {
    Write-Host "No run.py found – skipping Python app startup." -ForegroundColor DarkGray
}

# 3) Start Ollama (if installed)
# This assumes 'ollama' is on your PATH. If not, adjust to your installation path.
try {
    Write-Host "Starting Ollama server (if available)..." -ForegroundColor Yellow
    Start-Process "ollama" -ArgumentList "serve" -WindowStyle Minimized | Out-Null
} catch {
    Write-Host "Ollama not found on PATH – skipping." -ForegroundColor DarkGray
}

# 4) Notes for DeepSeek / Grok
# These are accessed via API from PHP/JS – there is usually no local process to start.
Write-Host "DeepSeek / Grok: no local services required – APIs are called from the site." -ForegroundColor DarkGray

# 5) Open project folder in Explorer (optional convenience)
try {
    Write-Host "Opening project folder in Explorer..." -ForegroundColor Yellow
    Start-Process "explorer.exe" $projectPath
} catch {
    Write-Host "Could not open Explorer automatically." -ForegroundColor DarkGray
}

# 6) (Optional) Launch Cursor if you have a CLI path – placeholder example:
# Replace with the actual path if you want this to auto-launch.
# Example:
# $cursorExe = "$Env:LOCALAPPDATA\Programs\Cursor\Cursor.exe"
# if (Test-Path $cursorExe) {
#     Write-Host "Launching Cursor..." -ForegroundColor Yellow
#     Start-Process $cursorExe $projectPath
# }

Write-Host "`nEnvironment bootstrap complete. You can now open Cursor and continue work on Donations and CRM." -ForegroundColor Green



