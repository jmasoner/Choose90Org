# Resources Deployment Guide

## Overview

This guide explains how to add and deploy resources (PDFs, guides, etc.) to the Choose90.org website.

## Files Created

1. **page-resources.php** - WordPress template for the Resources page
2. **deploy_resources.ps1** - Deployment script for resources
3. **Choose90-Digital-Detox-Guide.pdf** - The Digital Detox Guide (already exists)

## Quick Start

### Step 1: Deploy Resources to Server

```powershell
.\deploy_resources.ps1
```

This script will:

- Create a `/resources/` directory on the server
- Copy the Digital Detox Guide PDF to the server
- Deploy the Resources page template to the WordPress theme

### Step 2: Create the Resources Page in WordPress

1. Log in to WordPress Admin at <https://choose90.org/wp-admin>
2. Go to **Pages > Add New**
3. Title: "Resources"
4. In the right sidebar under **Page Attributes**:
   - Template: Select "Resources Page"
5. Set permalink to `/resources/`
6. Click **Publish**

### Step 3: Verify

Visit <https://choose90.org/resources/> to see your new Resources page with the Digital Detox Guide available for download.

## Adding More Resources

### To add a new PDF or guide

1. **Place the file** in the project root directory
2. **Update deploy_resources.ps1**:

   ```powershell
   # Add after the Digital Detox Guide section
   $SourceNewGuide = Join-Path $PSScriptRoot "Your-New-Guide.pdf"
   if (Test-Path $SourceNewGuide) {
       Write-Host "Deploying New Guide..." -NoNewline
       Copy-Item -Path $SourceNewGuide -Destination $DestResourcesDir -Force
       Write-Host " [OK]" -ForegroundColor Green
   }
   ```

3. **Update page-resources.php**:
   - Add a new resource card in the resources grid
   - Copy the existing Digital Detox Guide card structure
   - Update the title, description, and download link

4. **Run the deployment**:

   ```powershell
   .\deploy_resources.ps1
   .\setup_child_theme.ps1  # To update the template
   ```

## File Locations

### Local (Development)

- Templates: `c:\Users\john\OneDrive\MyProjects\Choose90Org\page-resources.php`
- PDFs: `c:\Users\john\OneDrive\MyProjects\Choose90Org\*.pdf`

### Server (Production)

- Resources: `Z:\resources\` (<https://choose90.org/resources/>)
- Template: `Z:\wp-content\themes\Divi-choose90\page-resources.php`

## Troubleshooting

### "Drive Z: is not connected"

Run the Web Disk connection script:

```powershell
.\map_drive_interactive.ps1
```

### Resources page not showing

1. Make sure you selected "Resources Page" as the template in WordPress
2. Clear WordPress cache (if using a caching plugin)
3. Check that the permalink is set to `/resources/`

### PDF not downloading

1. Verify the file was copied to `Z:\resources\`
2. Check file permissions on the server
3. Try accessing the PDF directly: <https://choose90.org/resources/Choose90-Digital-Detox-Guide.pdf>

## Maintenance

### Updating the Resources Page Design

1. Edit `page-resources.php` locally
2. Run `.\setup_child_theme.ps1` to deploy changes
3. Refresh the page in your browser (Ctrl+F5 to clear cache)

### Replacing a PDF

1. Update the PDF file in the project root
2. Run `.\deploy_resources.ps1`
3. The new version will overwrite the old one
