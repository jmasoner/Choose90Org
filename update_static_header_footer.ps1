# update_static_header_footer.ps1
# Injects shared static header/footer into all HTML pages in hybrid_site

$RootPath = Join-Path $PSScriptRoot "hybrid_site"
$HeaderPath = Join-Path $RootPath "components\static-header.html"
$FooterPath = Join-Path $RootPath "components\static-footer.html"

if (-not (Test-Path $RootPath)) {
    Write-Error "Could not find hybrid_site directory at $RootPath"
    exit 1
}

if (-not (Test-Path $HeaderPath) -or -not (Test-Path $FooterPath)) {
    Write-Error "Header or footer component not found. Expected:"
    Write-Error "  $HeaderPath"
    Write-Error "  $FooterPath"
    exit 1
}

$HeaderHtml = Get-Content $HeaderPath -Raw
$FooterHtml = Get-Content $FooterPath -Raw

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   Updating Static Headers / Footers      " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

$HtmlFiles = Get-ChildItem $RootPath -Recurse -Filter "*.html"

foreach ($file in $HtmlFiles) {
    $relative = $file.FullName.Substring($RootPath.Length + 1)

    # We only want to touch our site pages, skip any external or vendor HTML if added later
    if ($relative -like "components\*") {
        continue
    }

    Write-Host "Processing $relative..." -NoNewline
    $content = Get-Content $file.FullName -Raw
    $originalContent = $content

    # Method 1: Replace header with comment marker
    if ($content -match "<!-- Navigation -->") {
        $content = $content -replace "(?s)<!-- Navigation -->.*?</header>", "<!-- Navigation -->`r`n$HeaderHtml"
    }
    # Method 2: Replace entire <header> section (for pages with hardcoded headers)
    elseif ($content -match "<header[^>]*>") {
        $content = $content -replace "(?s)<header[^>]*>.*?</header>", $HeaderHtml
    }

    # Method 1: Replace footer with comment marker
    if ($content -match "<!-- Footer -->") {
        $content = $content -replace "(?s)<!-- Footer -->.*?</footer>", "<!-- Footer -->`r`n$FooterHtml"
    }
    # Method 2: Replace entire <footer> section
    elseif ($content -match "<footer[^>]*>") {
        $content = $content -replace "(?s)<footer[^>]*>.*?</footer>", $FooterHtml
    }

    if ($content -ne $originalContent) {
        Set-Content -Path $file.FullName -Value $content -NoNewline
        Write-Host " [OK]" -ForegroundColor Green
    } else {
        Write-Host " [SKIP - No header/footer found]" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "Done. Static HTML headers and footers updated from components." -ForegroundColor Green




