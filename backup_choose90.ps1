# backup_choose90.ps1
# Comprehensive backup script for Choose90.org website
# Backs up website files from Z: drive and optionally the local repository

param(
    [switch]$IncludeLocalRepo = $false,
    [string]$BackupLocation = "backup-choose90"
)

$ErrorActionPreference = "Stop"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   Choose90.org Backup Script            " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# Create timestamp for backup directory
$Timestamp = Get-Date -Format "yyyy-MM-dd_HH-mm-ss"
$BackupDir = Join-Path $PSScriptRoot $BackupLocation
$TimestampedBackup = Join-Path $BackupDir "backup_$Timestamp"

# Ensure backup directory exists
if (-not (Test-Path $BackupDir)) {
    New-Item -ItemType Directory -Path $BackupDir -Force | Out-Null
    Write-Host "Created backup directory: $BackupDir" -ForegroundColor Green
}

# Create timestamped backup directory
New-Item -ItemType Directory -Path $TimestampedBackup -Force | Out-Null
Write-Host "Backup location: $TimestampedBackup" -ForegroundColor Cyan
Write-Host ""

# Initialize backup log
$LogFile = Join-Path $TimestampedBackup "backup_log.txt"
$LogContent = @"
Choose90.org Backup Log
=======================
Backup Date: $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")
Backup Location: $TimestampedBackup

"@
$LogContent | Out-File -FilePath $LogFile -Encoding UTF8

# Function to log messages
function Write-Log {
    param([string]$Message, [string]$Color = "White")
    $Timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $LogMessage = "[$Timestamp] $Message"
    Write-Host $LogMessage -ForegroundColor $Color
    Add-Content -Path $LogFile -Value $LogMessage
}

# 1. Backup Website Files from Z: Drive
Write-Log "=== STEP 1: Backing up website files from Z: drive ===" "Cyan"

if (Test-Path "Z:\") {
    $WebsiteBackup = Join-Path $TimestampedBackup "website"
    New-Item -ItemType Directory -Path $WebsiteBackup -Force | Out-Null
    
    Write-Log "Source: Z:\" "Gray"
    Write-Log "Destination: $WebsiteBackup" "Gray"
    Write-Log "Using robocopy for WebDAV compatibility..." "Gray"
    Write-Log ""
    
    # Use robocopy for WebDAV - it handles the null character issue better
    # Robocopy options:
    # /E = copy subdirectories including empty ones
    # /COPYALL = copy all file info
    # /R:3 = retry 3 times on failed copies
    # /W:5 = wait 5 seconds between retries
    # /NP = no progress (to avoid cluttering output)
    # /NDL = no directory list
    # /NFL = no file list
    # /NJH = no job header
    # /NJS = no job summary
    # /XD = exclude directories (uploads can be very large)
    # /XF = exclude file patterns
    
    $RobocopyLog = Join-Path $TimestampedBackup "robocopy_log.txt"
    
    try {
        # Run robocopy and capture output
        $RobocopyArgs = @(
            "Z:\",
            $WebsiteBackup,
            "/E",
            "/COPY:DAT",  # Data, Attributes, Timestamps - WebDAV doesn't support NTFS Security
            "/R:3",
            "/W:5",
            "/NP",
            "/NDL",
            "/NFL",
            "/XD", "wp-content\uploads", ".git",
            "/XF", "*.log", "*.tmp",
            "/LOG:$RobocopyLog"
        )
        
        $RobocopyProcess = Start-Process -FilePath "robocopy" -ArgumentList $RobocopyArgs -Wait -PassThru -NoNewWindow
        
        # Parse robocopy log for statistics
        if (Test-Path $RobocopyLog) {
            $LogContent = Get-Content $RobocopyLog -Raw
            if ($LogContent -match "Files\s*:\s*(\d+)\s*(\d+)\s*(\d+)") {
                $TotalFiles = $Matches[1]
                $CopiedFiles = $Matches[2]
                $FailedFiles = $Matches[3]
                Write-Log "  Total files: $TotalFiles" "Gray"
                Write-Log "  Files copied: $CopiedFiles" "Green"
                if ([int]$FailedFiles -gt 0) {
                    Write-Log "  Files failed: $FailedFiles" "Yellow"
                }
            }
            if ($LogContent -match "Dirs\s*:\s*(\d+)\s*(\d+)\s*(\d+)") {
                $TotalDirs = $Matches[1]
                $CopiedDirs = $Matches[2]
                $FailedDirs = $Matches[3]
                Write-Log "  Total directories: $TotalDirs" "Gray"
                Write-Log "  Directories copied: $CopiedDirs" "Green"
                if ([int]$FailedDirs -gt 0) {
                    Write-Log "  Directories failed: $FailedDirs" "Yellow"
                }
            }
        }
        
        # Robocopy exit codes: 0-7 are success, 8+ are errors
        if ($RobocopyProcess.ExitCode -le 7) {
            Write-Log ""
            Write-Log "Website backup complete!" "Green"
            Write-Log "  Robocopy exit code: $($RobocopyProcess.ExitCode) (0-7 = success)" "Green"
            Write-Log "  Detailed log: robocopy_log.txt" "Gray"
        } else {
            Write-Log ""
            Write-Log "Website backup completed with warnings!" "Yellow"
            Write-Log "  Robocopy exit code: $($RobocopyProcess.ExitCode)" "Yellow"
            Write-Log "  Check robocopy_log.txt for details" "Yellow"
        }
    }
    catch {
        Write-Log "FATAL ERROR during website backup: $($_.Exception.Message)" "Red"
        Write-Log "Stack trace: $($_.ScriptStackTrace)" "Red"
    }
}
else {
    Write-Log "WARNING: Z: drive is not accessible. Skipping website backup." "Yellow"
    Write-Log "  Please ensure Web Disk is mapped before running backup." "Yellow"
}

Write-Log ""

# 2. Backup Local Repository (Optional)
if ($IncludeLocalRepo) {
    Write-Log "=== STEP 2: Backing up local repository ===" "Cyan"
    
    $RepoBackup = Join-Path $TimestampedBackup "repository"
    New-Item -ItemType Directory -Path $RepoBackup -Force | Out-Null
    
    # Files and directories to exclude from repo backup
    $ExcludePatterns = @(
        "backup-choose90",
        "node_modules",
        ".git",
        ".vs",
        "*.log",
        "secrets.json",
        "setup_webdisk*.ps1"
    )
    
    $RepoFileCount = 0
    $RepoDirCount = 0
    $RepoErrorCount = 0
    
    try {
        $RepoItems = Get-ChildItem -Path $PSScriptRoot -Recurse -ErrorAction SilentlyContinue | Where-Object {
            $RelativePath = $_.FullName.Substring($PSScriptRoot.Length + 1)
            $ShouldExclude = $false
            
            foreach ($Pattern in $ExcludePatterns) {
                if ($RelativePath -like "*$Pattern*") {
                    $ShouldExclude = $true
                    break
                }
            }
            
            -not $ShouldExclude
        }
        
        foreach ($Item in $RepoItems) {
            $RelativePath = $Item.FullName.Substring($PSScriptRoot.Length + 1)
            $DestPath = Join-Path $RepoBackup $RelativePath
            
            try {
                if ($Item.PSIsContainer) {
                    if (-not (Test-Path $DestPath)) {
                        New-Item -ItemType Directory -Path $DestPath -Force | Out-Null
                        $RepoDirCount++
                    }
                } else {
                    $DestParent = Split-Path $DestPath -Parent
                    if (-not (Test-Path $DestParent)) {
                        New-Item -ItemType Directory -Path $DestParent -Force | Out-Null
                    }
                    
                    Copy-Item -Path $Item.FullName -Destination $DestPath -Force -ErrorAction Stop
                    $RepoFileCount++
                }
            }
            catch {
                $RepoErrorCount++
                Write-Log "  ERROR copying: $RelativePath - $($_.Exception.Message)" "Red"
            }
        }
        
        Write-Log ""
        Write-Log "Repository backup complete!" "Green"
        Write-Log "  Files copied: $RepoFileCount" "Green"
        Write-Log "  Directories created: $RepoDirCount" "Green"
        if ($RepoErrorCount -gt 0) {
            Write-Log "  Errors: $RepoErrorCount" "Yellow"
        }
    }
    catch {
        Write-Log "FATAL ERROR during repository backup: $($_.Exception.Message)" "Red"
    }
}
else {
    Write-Log "Skipping local repository backup (use -IncludeLocalRepo to include)" "Gray"
}

Write-Log ""

# 3. Create backup manifest
Write-Log "=== STEP 3: Creating backup manifest ===" "Cyan"

$ManifestFile = Join-Path $TimestampedBackup "backup_manifest.txt"
$Manifest = @"
Choose90.org Backup Manifest
=============================
Backup Date: $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")
Backup Location: $TimestampedBackup

Contents:
---------
"@

if (Test-Path (Join-Path $TimestampedBackup "website")) {
    $WebsiteSize = (Get-ChildItem -Path (Join-Path $TimestampedBackup "website") -Recurse -ErrorAction SilentlyContinue | 
                    Measure-Object -Property Length -Sum).Sum / 1MB
    $Manifest += "`nWebsite Backup: $(Join-Path $TimestampedBackup "website")"
    $Manifest += "`n  Size: $([math]::Round($WebsiteSize, 2)) MB"
    if (Test-Path (Join-Path $TimestampedBackup "robocopy_log.txt")) {
        $Manifest += "`n  Robocopy Log: robocopy_log.txt"
    }
}

if ($IncludeLocalRepo -and (Test-Path (Join-Path $TimestampedBackup "repository"))) {
    $RepoSize = (Get-ChildItem -Path (Join-Path $TimestampedBackup "repository") -Recurse -ErrorAction SilentlyContinue | 
                 Measure-Object -Property Length -Sum).Sum / 1MB
    $Manifest += "`nRepository Backup: $(Join-Path $TimestampedBackup "repository")"
    $Manifest += "`n  Size: $([math]::Round($RepoSize, 2)) MB"
}

$Manifest += "`n`nBackup Log: backup_log.txt"
$Manifest | Out-File -FilePath $ManifestFile -Encoding UTF8

Write-Log "Manifest created: backup_manifest.txt" "Green"

# 4. Summary
Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   BACKUP COMPLETE                        " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Backup location: $TimestampedBackup" -ForegroundColor Green
Write-Host "Log file: $LogFile" -ForegroundColor Gray
Write-Host "Manifest: $ManifestFile" -ForegroundColor Gray
Write-Host ""

# Calculate total backup size
$TotalSize = (Get-ChildItem -Path $TimestampedBackup -Recurse -ErrorAction SilentlyContinue | 
              Measure-Object -Property Length -Sum).Sum / 1MB
Write-Host "Total backup size: $([math]::Round($TotalSize, 2)) MB" -ForegroundColor Cyan
Write-Host ""

Write-Log "=== BACKUP COMPLETE ===" "Green"
Write-Log "Total backup size: $([math]::Round($TotalSize, 2)) MB" "Green"

Write-Host "Backup completed successfully!" -ForegroundColor Green

