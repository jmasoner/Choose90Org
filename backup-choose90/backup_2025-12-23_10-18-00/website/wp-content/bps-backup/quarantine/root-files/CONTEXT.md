```markdown
<!-- FILENAME: CONTEXT.md -->
# Project: Choose90Org - AI Agent Long Term Memory

This document serves as the persistent memory and operational context for AI agents assisting with the Choose90Org project. It encapsulates the core details, objectives, and current state of the project to ensure consistent understanding and execution.

## Project Overview

*   **Name:** Choose90Org
*   **Type:** MODULAR Web Application
*   **Goal:** To establish a robust, scalable WordPress website for Choose90Org, supporting community engagement, content delivery, and donation/pledge functionalities. The project prioritizes security, performance, and ease of management through a well-defined plugin and theme ecosystem.

## Architecture & Structure

The project follows a standard WordPress directory structure, with specific attention to custom asset locations and premium plugin/theme integration.

```
/home/constit2/choose90.org
├── index.php
├── wp-admin/
├── wp-content/
│   ├── plugins/                 (Installed premium plugins)
│   │   ├── gravityforms/
│   │   ├── woocommerce/
│   │   └── ...
│   ├── themes/
│   │   └── Divi/                (Divi 4 Parent Theme)
│   ├── uploads/
│   │   └── choose90-assets/     (Brand assets: logos, favicons, custom graphics)
│   │       ├── logo.png
│   │       ├── favicon.png
│   │       └── ...
│   └── ...
├── wp-includes/
└── ...
```

## Technology Stack

*   **CMS:** WordPress
*   **Theme:** Divi 4
*   **Plugins:** Gravity Forms, WooCommerce, BulletProof Security Pro, SiteSEO Pro, GoSMTP Pro, KBR Popup Maker, WPdatatables, Loginizer Pro, Backuply Pro, FileOrganizer Pro
*   **Fonts:** Google Fonts (Montserrat, Open Sans)
*   **Languages/Scripts:** PHP, HTML, CSS, JavaScript
*   **Database:** MySQL
*   **Hosting:** Shared Hosting (initial deployment), Managed WordPress/VPS (future scaling)
*   **DevOps Tools:** Git, GitHub CLI (`gh`)

## Project Phases

The development is structured into distinct phases to manage complexity and provide clear milestones:

*   **PHASE 1: CONFIGURATION & SETUP**
    *   Initial WordPress installation and configuration.
    *   Divi theme installation and basic setup.
    *   Core plugin installation and initial settings (Gravity Forms, WooCommerce, Security, SEO, SMTP, Backup).
    *   Database connection and user setup.
*   **PHASE 2: PAGE CREATION**
    *   Development of key static pages: Homepage, Pledge, About Us, Chapters, Support, Resources, Donate.
    *   Implementation of content using Divi Builder.
    *   Integration of forms (Gravity Forms) where required.
*   **PHASE 3: NAVIGATION & FOOTER**
    *   Creation and configuration of the primary navigation menu.
    *   Development of the global footer content (copyright, quick links, social media).
*   **PHASE 4: STYLING & OPTIMIZATION**
    *   Application of custom CSS for branding consistency and unique design elements.
    *   Performance optimization checks (caching, image optimization, code minification).
    *   Security hardening review.
*   **PHASE 5: POST-LAUNCH ROADMAP**
    *   Strategy for hosting scaling (migration to Managed WordPress/VPS).
    *   Integration with advanced CRM systems.
    *   Planning and implementation of new features (e.g., membership portals, advanced reporting).

## User Profile & Contact Information

*   **Name:** John Masoner
*   **Email:** john@masoner.us
*   **Phone:** 360-513-4238
*   **OneDrive Root:** `C:\Users\john\OneDrive\MyProjects`
*   **GitHub Repo Root (Local):** `C:\Users\john\OneDrive\MyProjects`

## Hosting Configuration

*   **Host:** choose90.org
*   **User:** constit2
*   **SSH Key Path:** `C:\Users\john\.ssh\id_rsa`

---

## Master Prompt for AI Agents: Phase 1 Initiation

**AI Agent Role:** Senior Systems Engineer AI
**Project:** Choose90Org
**Current Phase:** PHASE 1: CONFIGURATION & SETUP

**Your Mission:** Your immediate task is to systematically initiate and complete 'PHASE 1: CONFIGURATION & SETUP' for the Choose90Org WordPress project. This involves ensuring all foundational elements – WordPress, Divi theme, and critical plugins – are correctly installed and configured according to best practices, the provided technology stack, and security/performance considerations.

**Instructions:**

1.  **Acknowledge Context:** Thoroughly review the `CONTEXT.md` document for all project details, architecture, tech stack, and user information.
2.  **Phase 1 Checklist:** Develop a detailed checklist of all necessary steps for 'PHASE 1: CONFIGURATION & SETUP'. This should cover:
    *   WordPress core installation and initial settings (site title, tagline, permalinks, time zone, user roles).
    *   Divi theme installation, activation, and initial global settings (layout, typography, colors, navigation).
    *   Installation and initial configuration of *all specified plugins* (Gravity Forms, WooCommerce, BulletProof Security Pro, SiteSEO Pro, GoSMTP Pro, KBR Popup Maker, WPdatatables, Loginizer Pro, Backuply Pro, FileOrganizer Pro). For each plugin, outline critical initial setup steps (e.g., license keys, basic security rules, default form settings, product pages for WooCommerce, SEO basics, SMTP testing).
    *   Database optimization considerations.
    *   Initial asset upload structure (`wp-content/uploads/choose90-assets/`).
3.  **Prioritization:** Emphasize security (BulletProof Security, Loginizer) and performance (initial caching considerations, image optimization strategy) during configuration.
4.  **Output Format:** Present your checklist and proposed configuration steps clearly, detailing specific settings or actions for each item. If specific values are needed (e.g., an SMTP host, a placeholder for an API key), suggest best practices or placeholder values.

**Goal for this prompt:** To prepare the site's foundation securely and efficiently, ready for content creation in Phase 2. Proceed with the detailed plan for Phase 1.
```
```powershell
# FILENAME: init_choose90org.ps1

# --- Script Configuration ---
$OneDriveRoot = "C:\Users\john\OneDrive"
$GitHubRepoRootInput = "C:\Users\john\OneDrive\MyProjects" # From user profile
$ProjectName = "Choose90Org"
$HostingHost = "choose90.org"
$HostingUser = "constit2"
$SSHKeyPath = "C:\Users\john\.ssh\id_rsa"
$UserEmail = "john@masoner.us"
$UserPhone = "360-513-4238"

# Determine the actual project root based on critical path logic
$ProjectRoot = Join-Path $GitHubRepoRootInput $ProjectName
$OneDriveProjectSyncPath = Join-Path $OneDriveRoot $ProjectName

# Check if GitHubRepoRootInput is effectively within OneDriveRoot for the project
if ($GitHubRepoRootInput -eq (Join-Path $OneDriveRoot "MyProjects")) {
    Write-Host "GitHub Repo Root is within OneDrive. Treating '$ProjectRoot' as the master Git repository and OneDrive sync path." -ForegroundColor Green
    # In this specific case, $ProjectRoot and $OneDriveProjectSyncPath will be the same
    # We will create $ProjectRoot and ensure Git is initialized there.
} else {
    Write-Host "GitHub Repo Root and OneDrive project sync path are different." -ForegroundColor Yellow
    Write-Host "Project Git repository will be at: '$ProjectRoot'" -ForegroundColor Yellow
    Write-Host "OneDrive sync backup path will be at: '$OneDriveProjectSyncPath'" -ForegroundColor Yellow
    # This scenario is not directly implied by the provided user profile but handled for robustness.
    # The current user profile has GitHubRepoRootInput *within* OneDrive, so this branch won't be hit with current data.
}

# --- Function to safely create directories ---
function New-SafeDirectory {
    param(
        [Parameter(Mandatory=$true)]
        [string]$Path
    )
    if (-not (Test-Path $Path)) {
        Write-Host "Creating directory: $Path" -ForegroundColor Cyan
        New-Item -ItemType Directory -Path $Path -Force | Out-Null
    } else {
        Write-Host "Directory already exists: $Path" -ForegroundColor DarkYellow
    }
}

# --- Create Project Directories ---
Write-Host "Step 1: Creating project directories..." -ForegroundColor Green
New-SafeDirectory $ProjectRoot
New-SafeDirectory (Join-Path $ProjectRoot "wp-content\plugins")
New-SafeDirectory (Join-Path $ProjectRoot "wp-content\themes")
New-SafeDirectory (Join-Path $ProjectRoot "wp-content\uploads\choose90-assets")

# --- Initialize Git Repository ---
Write-Host "Step 2: Initializing Git repository..." -ForegroundColor Green
Set-Location $ProjectRoot
if (-not (Test-Path (Join-Path $ProjectRoot ".git"))) {
    git init
    Write-Host "Git repository initialized at $ProjectRoot" -ForegroundColor Cyan
} else {
    Write-Host "Git repository already exists at $ProjectRoot" -ForegroundColor DarkYellow
}

# --- Create .gitignore file ---
Write-Host "Step 3: Creating .gitignore file..." -ForegroundColor Green
$gitignoreContent = @'
# WordPress specific ignores
/wp-content/uploads/
/wp-content/cache/
/wp-content/backup-*/
/wp-content/upgrade/
/wp-admin/
/wp-includes/
wp-config.php
.htaccess
sitemap.xml
sitemap.xml.gz
error_log
debug.log
*.log

# General ignores
node_modules/
.env
.DS_Store
dist/
vendor/
*.sublime-*
.vscode/
.idea/
.sass-cache/
npm-debug.log
yarn-error.log
package-lock.json
*.zip
*.rar
*.tar.gz
*.sql
'@
$gitignorePath = Join-Path $ProjectRoot ".gitignore"
if (-not (Test-Path $gitignorePath)) {
    $gitignoreContent | Set-Content -Path $gitignorePath
    Write-Host ".gitignore created." -ForegroundColor Cyan
} else {
    Write-Host ".gitignore already exists. Skipping creation." -ForegroundColor DarkYellow
}

# --- Write CONTEXT.md ---
Write-Host "Step 4: Writing CONTEXT.md to project root..." -ForegroundColor Green
$contextContent = @'
<!-- FILENAME: CONTEXT.md -->
# Project: Choose90Org - AI Agent Long Term Memory

This document serves as the persistent memory and operational context for AI agents assisting with the Choose90Org project. It encapsulates the core details, objectives, and current state of the project to ensure consistent understanding and execution.

## Project Overview

*   **Name:** Choose90Org
*   **Type:** MODULAR Web Application
*   **Goal:** To establish a robust, scalable WordPress website for Choose90Org, supporting community engagement, content delivery, and donation/pledge functionalities. The project prioritizes security, performance, and ease of management through a well-defined plugin and theme ecosystem.

## Architecture & Structure

The project follows a standard WordPress directory structure, with specific attention to custom asset locations and premium plugin/theme integration.

```
/home/constit2/choose90.org
├── index.php
├── wp-admin/
├── wp-content/
│   ├── plugins/                 (Installed premium plugins)
│   │   ├── gravityforms/
│   │   ├── woocommerce/
│   │   └── ...
│   ├── themes/
│   │   └── Divi/                (Divi 4 Parent Theme)
│   ├── uploads/
│   │   └── choose90-assets/     (Brand assets: logos, favicons, custom graphics)
│   │       ├── logo.png
│   │       ├── favicon.png
│   │       └── ...
│   └── ...
├── wp-includes/
└── ...
```

## Technology Stack

*   **CMS:** WordPress
*   **Theme:** Divi 4
*   **Plugins:** Gravity Forms, WooCommerce, BulletProof Security Pro, SiteSEO Pro, GoSMTP Pro, KBR Popup Maker, WPdatatables, Loginizer Pro, Backuply Pro, FileOrganizer Pro
*   **Fonts:** Google Fonts (Montserrat, Open Sans)
*   **Languages/Scripts:** PHP, HTML, CSS, JavaScript
*   **Database:** MySQL
*   **Hosting:** Shared Hosting (initial deployment), Managed WordPress/VPS (future scaling)
*   **DevOps Tools:** Git, GitHub CLI (`gh`)

## Project Phases

The development is structured into distinct phases to manage complexity and provide clear milestones:

*   **PHASE 1: CONFIGURATION & SETUP**
    *   Initial WordPress installation and configuration.
    *   Divi theme installation and basic setup.
    *   Core plugin installation and initial settings (Gravity Forms, WooCommerce, Security, SEO, SMTP, Backup).
    *   Database connection and user setup.
*   **PHASE 2: PAGE CREATION**
    *   Development of key static pages: Homepage, Pledge, About Us, Chapters, Support, Resources, Donate.
    *   Implementation of content using Divi Builder.
    *   Integration of forms (Gravity Forms) where required.
*   **PHASE 3: NAVIGATION & FOOTER**
    *   Creation and configuration of the primary navigation menu.
    *   Development of the global footer content (copyright, quick links, social media).
*   **PHASE 4: STYLING & OPTIMIZATION**
    *   Application of custom CSS for branding consistency and unique design elements.
    *   Performance optimization checks (caching, image optimization, code minification).
    *   Security hardening review.
*   **PHASE 5: POST-LAUNCH ROADMAP**
    *   Strategy for hosting scaling (migration to Managed WordPress/VPS).
    *   Integration with advanced CRM systems.
    *   Planning and implementation of new features (e.g., membership portals, advanced reporting).

## User Profile & Contact Information

*   **Name:** John Masoner
*   **Email:** john@masoner.us
*   **Phone:** 360-513-4238
*   **OneDrive Root:** C:\Users\john\OneDrive
*   **GitHub Repo Root (Local):** C:\Users\john\OneDrive\MyProjects

## Hosting Configuration

*   **Host:** choose90.org
*   **User:** constit2
*   **SSH Key Path:** C:\Users\john\.ssh\id_rsa

---

## Master Prompt for AI Agents: Phase 1 Initiation

**AI Agent Role:** Senior Systems Engineer AI
**Project:** Choose90Org
**Current Phase:** PHASE 1: CONFIGURATION & SETUP

**Your Mission:** Your immediate task is to systematically initiate and complete 'PHASE 1: CONFIGURATION & SETUP' for the Choose90Org WordPress project. This involves ensuring all foundational elements – WordPress, Divi theme, and critical plugins – are correctly installed and configured according to best practices, the provided technology stack, and security/performance considerations.

**Instructions:**

1.  **Acknowledge Context:** Thoroughly review the `CONTEXT.md` document for all project details, architecture, tech stack, and user information.
2.  **Phase 1 Checklist:** Develop a detailed checklist of all necessary steps for 'PHASE 1: CONFIGURATION & SETUP'. This should cover:
    *   WordPress core installation and initial settings (site title, tagline, permalinks, time zone, user roles).
    *   Divi theme installation, activation, and initial global settings (layout, typography, colors, navigation).
    *   Installation and initial configuration of *all specified plugins* (Gravity Forms, WooCommerce, BulletProof Security Pro, SiteSEO Pro, GoSMTP Pro, KBR Popup Maker, WPdatatables, Loginizer Pro, Backuply Pro, FileOrganizer Pro). For each plugin, outline critical initial setup steps (e.g., license keys, basic security rules, default form settings, product pages for WooCommerce, SEO basics, SMTP testing).
    *   Database optimization considerations.
    *   Initial asset upload structure (`wp-content/uploads/choose90-assets/`).
3.  **Prioritization:** Emphasize security (BulletProof Security, Loginizer) and performance (initial caching considerations, image optimization strategy) during configuration.
4.  **Output Format:** Present your checklist and proposed configuration steps clearly, detailing specific settings or actions for each item. If specific values are needed (e.g., an SMTP host, a placeholder for an API key), suggest best practices or placeholder values.

**Goal for this prompt:** To prepare the site's foundation securely and efficiently, ready for content creation in Phase 2. Proceed with the detailed plan for Phase 1.
'@
$contextPath = Join-Path $ProjectRoot "CONTEXT.md"
if (-not (Test-Path $contextPath)) {
    $contextContent | Set-Content -Path $contextPath
    Write-Host "CONTEXT.md created." -ForegroundColor Cyan
} else {
    Write-Host "CONTEXT.md already exists. Skipping creation." -ForegroundColor DarkYellow
}

# --- Create .env.example ---
Write-Host "Step 5: Creating .env.example file..." -ForegroundColor Green
$envExampleContent = @'
# Environment variables for Choose90Org project
# Rename this file to .env and fill in actual values for local development.

# WordPress Database
DB_NAME="choose90_db"
DB_USER="choose90_user"
DB_PASSWORD="your_strong_db_password"
DB_HOST="localhost" # or your database host

# WordPress Salts (Generate these unique values for wp-config.php)
AUTH_KEY='put your unique phrase here'
SECURE_AUTH_KEY='put your unique phrase here'
LOGGED_IN_KEY='put your unique phrase here'
NONCE_KEY='put your unique phrase here'
AUTH_SALT='put your unique phrase here'
SECURE_AUTH_SALT='put your unique phrase here'
LOGGED_IN_SALT='put your unique phrase here'
NONCE_SALT='put your unique phrase here'

# Plugin License Keys (Examples - replace with actual keys)
DIVI_API_KEY="your_divi_api_key"
GRAVITY_FORMS_LICENSE_KEY="your_gravity_forms_license_key"
WOOCOMMERCE_API_KEY="your_woocommerce_api_key" # If external API usage
BULLETPROOF_SECURITY_PRO_KEY="your_bps_pro_key"
SITESEO_PRO_LICENSE_KEY="your_siteseo_pro_key"
GOSMTP_PRO_LICENSE_KEY="your_gosmtp_pro_key"
KBR_POPUP_MAKER_LICENSE_KEY="your_kbr_popup_maker_key"
WPDATATABLES_LICENSE_KEY="your_wpdatatables_key"
LOGINIZER_PRO_LICENSE_KEY="your_loginizer_pro_key"
BACKUPLY_PRO_LICENSE_KEY="your_backuply_pro_key"
FILEORGANIZER_PRO_LICENSE_KEY="your_fileorganizer_pro_key"

# SMTP Settings (GoSMTP Pro)
SMTP_HOST="smtp.your-email-provider.com"
SMTP_PORT="587" # or 465 for SSL
SMTP_USERNAME="your_smtp_username"
SMTP_PASSWORD="your_smtp_password"
SMTP_ENCRYPTION="tls" # or ssl

# Other API Keys (Example for Google Maps API if used by a plugin)
GOOGLE_MAPS_API_KEY="your_google_maps_api_key"
'@
$envExamplePath = Join-Path $ProjectRoot ".env.example"
if (-not (Test-Path $envExamplePath)) {
    $envExampleContent | Set-Content -Path $envExamplePath
    Write-Host ".env.example created." -ForegroundColor Cyan
} else {
    Write-Host ".env.example already exists. Skipping creation." -ForegroundColor DarkYellow
}

# --- Create README.md ---
Write-Host "Step 6: Creating README.md file..." -ForegroundColor Green
$readmeContent = @"
# Choose90Org Project

This repository contains the foundational structure and configuration for the Choose90Org WordPress website.

## Project Overview
Choose90Org is a modular WordPress site utilizing Divi 4 and a suite of premium plugins to deliver a robust and scalable platform for community engagement, content delivery, and donation/pledge functionalities.

## Getting Started

1.  **Clone the repository:**
    ```bash
    git clone [your-repo-url]
    cd Choose90Org
    ```
2.  **Install WordPress:** Follow the standard WordPress installation procedure.
3.  **Configure `wp-config.php`:**
    *   Copy `.env.example` to `.env` and fill in your database credentials and API keys.
    *   Update `wp-config.php` to read environment variables (e.g., `getenv('DB_NAME')`).
    *   Generate unique WordPress salts using a tool like [WordPress Salt Generator](https://api.wordpress.org/secret-key/1.1/salt/).
4.  **Install Divi and Plugins:** Manually install the Divi theme and all premium plugins into `wp-content/themes/` and `wp-content/plugins/` respectively.
5.  **Refer to `CONTEXT.md`** for detailed project architecture, tech stack, and phase information.

## Project Structure
A high-level overview of the project directory structure. For detailed insights, see `CONTEXT.md`.

```
/Choose90Org/
├── wp-content/
│   ├── plugins/                 (Premium plugins go here)
│   ├── themes/
│   │   └── Divi/                (Divi theme goes here)
│   └── uploads/
│       └── choose90-assets/     (Brand assets: logos, favicons, custom graphics)
├── CONTEXT.md                   (AI Long Term Memory & Project Blueprint)
├── .env.example                 (Template for environment variables)
├── .gitignore                   (Files and directories to ignore in Git)
├── README.md                    (This file)
└── ... (Other WordPress core files)
```

## Contact Information

*   **Name:** John Masoner
*   **Email:** $UserEmail
*   **Phone:** $UserPhone

## License
[Specify your project's license here, e.g., MIT, GPLv3]

"@
$readmePath = Join-Path $ProjectRoot "README.md"
if (-not (Test-Path $readmePath)) {
    $readmeContent | Set-Content -Path $readmePath
    Write-Host "README.md created." -ForegroundColor Cyan
} else {
    Write-Host "README.md already exists. Skipping creation." -ForegroundColor DarkYellow
}

# --- Enterprise Git: GitHub CLI Integration ---
Write-Host "Step 7: Integrating with GitHub CLI..." -ForegroundColor Green
if (-not (Get-Command gh -ErrorAction SilentlyContinue)) {
    Write-Warning "GitHub CLI (gh) not found. Please install it from https://cli.github.com/ or ensure it's in your PATH to enable enterprise git features."
} else {
    Write-Host "GitHub CLI (gh) found." -ForegroundColor Green
    
    # Ensure credential helpers are set up
    Write-Host "Running 'gh auth setup-git' to ensure credential helpers are set..." -ForegroundColor Cyan
    gh auth setup-git
    if ($LASTEXITCODE -ne 0) {
        Write-Warning "Failed to set up Git credentials using 'gh auth setup-git'. You may need to authenticate manually."
    }

    # Create GitHub repository and push initial commit
    Write-Host "Attempting to create GitHub repository 'Choose90Org' and push initial commit..." -ForegroundColor Cyan
    Set-Location $ProjectRoot
    
    # Check if remote 'origin' already exists
    $remoteExists = (git remote -v | Select-String "origin") -ne $null
    
    if (-not $remoteExists) {
        # Initial commit before creating repo if not already done
        if (-not (git log -1 --pretty=%H -ErrorAction SilentlyContinue)) {
            git add .
            git commit -m "Initial project blueprint and setup files"
            if ($LASTEXITCODE -ne 0) {
                Write-Warning "Failed to make initial git commit. Please check your git configuration."
            }
        }
        
        gh repo create Choose90Org --public --source=. --remote=origin
        if ($LASTEXITCODE -ne 0) {
            Write-Warning "Failed to create GitHub repository. Attempting to refresh GitHub authentication and retry push."
            Write-Host "Running 'gh auth refresh'..." -ForegroundColor Yellow
            # Request broader scopes for repo creation and workflow if initial creation failed
            gh auth refresh -h github.com -s repo,workflow,write:packages,delete_repo
            if ($LASTEXITCODE -eq 0) {
                Write-Host "Authentication refreshed. Retrying 'gh repo create' and push." -ForegroundColor Green
                gh repo create Choose90Org --public --source=. --remote=origin
            } else {
                Write-Error "GitHub authentication refresh failed. Manual authentication or repository creation may be required."
            }
        }
        
        if ($LASTEXITCODE -eq 0) { # If repo creation was successful or already existed
            Write-Host "Pushing initial commit to GitHub..." -ForegroundColor Cyan
            git branch -M main # Ensure main branch
            git push -u origin main
            if ($LASTEXITCODE -ne 0) {
                Write-Error "Failed to push initial commit to GitHub. Please check your Git setup and permissions."
            } else {
                Write-Host "Initial project pushed to GitHub successfully!" -ForegroundColor Green
            }
        }
    } else {
        Write-Host "Git remote 'origin' already exists. Skipping 'gh repo create'." -ForegroundColor DarkYellow
        Write-Host "Ensuring current branch is 'main' and pushing changes..." -ForegroundColor Cyan
        git branch -M main # Ensure main branch
        git add .
        git commit -m "Update project files during initialization (if any changes)" -AllowEmpty -ErrorAction SilentlyContinue
        git push -u origin main
        if ($LASTEXITCODE -ne 0) {
            Write-Error "Failed to push to existing remote 'origin'. Please check your Git setup and permissions."
        } else {
            Write-Host "Existing project updated and pushed to GitHub successfully!" -ForegroundColor Green
        }
    }
}

# --- Completion ---
Write-Host "`nIgnition Complete: Choose90Org project structure initialized and committed to Git (if gh CLI was available)." -ForegroundColor Green
```
```powershell
# FILENAME: resume_choose90org.ps1

# --- Script Configuration ---
$ProjectName = "Choose90Org"
$GitHubRepoRootInput = "C:\Users\john\OneDrive\MyProjects" # From user profile
$HostingHost = "choose90.org"
$HostingUser = "constit2"
$SSHKeyPath = "C:\Users\john\.ssh\id_rsa"

# Determine the actual project root
$ProjectRoot = Join-Path $GitHubRepoRootInput $ProjectName

# --- Check if project paths exist ---
Write-Host "Step 1: Checking project paths..." -ForegroundColor Green
if (-not (Test-Path $ProjectRoot -PathType Container)) {
    Write-Error "Project root '$ProjectRoot' does not exist. Please run init_choose90org.ps1 first."
    exit 1
} else {
    Write-Host "Project root '$ProjectRoot' found." -ForegroundColor DarkYellow
}

# --- Navigate to project root and run git fetch ---
Write-Host "Step 2: Navigating to project root and fetching latest changes..." -ForegroundColor Green
Set-Location $ProjectRoot
if ($LASTEXITCODE -ne 0) {
    Write-Error "Failed to change directory to '$ProjectRoot'."
    exit 1
}

# Check if it's a Git repository before fetching
if (Test-Path (Join-Path $ProjectRoot ".git")) {
    git fetch --quiet --all
    if ($LASTEXITCODE -ne 0) {
        Write-Warning "Git fetch failed. You might not be connected to a remote or have authentication issues."
    } else {
        Write-Host "Git fetch completed silently." -ForegroundColor Cyan
    }
} else {
    Write-Warning "Not a Git repository. Skipping git fetch."
}


# --- Construct Windows Terminal Command ---
Write-Host "Step 3: Launching Windows Terminal with unified dashboard..." -ForegroundColor Green

$wtCommand = "wt -w 0 new-tab -p `"Windows PowerShell`" -d `"$ProjectRoot`" -t `"Mission Control`" ; `
    split-pane -p `"Windows PowerShell`" -H -d `"$ProjectRoot`" ; `
    new-tab -p `"Windows PowerShell`" -d `"$ProjectRoot`" -t `"AI Agents`" cmd /k `"echo Launching Claude... & claude`" ; `
    split-pane -V cmd /k `"echo Launching Gemini... & gemini`""

# Add SSH tab if hosting info is provided
if ($HostingHost -and $HostingUser -and $SSHKeyPath) {
    if (Test-Path $SSHKeyPath) {
        Write-Host "Adding SSH tab for $HostingUser@$HostingHost..." -ForegroundColor Cyan
        $sshCommand = "ssh -i `"$SSHKeyPath`" $($HostingUser)@$($HostingHost)"
        $wtCommand += " ; new-tab -p `"Windows PowerShell`" -t `"SSH Host: $HostingHost`" cmd /k `"$sshCommand; pause`""
    } else {
        Write-Warning "SSH key not found at '$SSHKeyPath'. Skipping SSH tab."
    }
}

# Execute the Windows Terminal command
try {
    Write-Host "Executing: $wtCommand" -ForegroundColor DarkGray
    Invoke-Expression $wtCommand
    Write-Host "Windows Terminal launched." -ForegroundColor Cyan
} catch {
    Write-Error "Failed to launch Windows Terminal. Ensure 'wt.exe' is in your PATH and Windows Terminal is installed. Error: $($_.Exception.Message)"
}

# --- Read CONTEXT.md and copy to clipboard ---
Write-Host "Step 4: Reading CONTEXT.md and copying to clipboard..." -ForegroundColor Green
$contextPath = Join-Path $ProjectRoot "CONTEXT.md"
if (Test-Path $contextPath) {
    try {
        Get-Content -Path $contextPath | Set-Clipboard
        Write-Host "CONTEXT.md content copied to clipboard." -ForegroundColor Cyan
    } catch {
        Write-Error "Failed to copy CONTEXT.md to clipboard. Error: $($_.Exception.Message)"
    }
} else {
    Write-Warning "CONTEXT.md not found at '$contextPath'. Cannot copy to clipboard."
}

# --- Print Completion Message ---
Write-Host "`nProject Resumed. CONTEXT.md copied to clipboard." -ForegroundColor Green
```