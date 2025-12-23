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

    # Replace navigation header between <!-- Navigation --> and </header>
    if ($content -match "<!-- Navigation -->") {
        $content = $content -replace "(?s)<!-- Navigation -->.*?</header>", "<!-- Navigation -->`r`n$HeaderHtml"
    }

    # Replace footer between <!-- Footer --> and </footer>
    if ($content -match "<!-- Footer -->") {
        $content = $content -replace "(?s)<!-- Footer -->.*?</footer>", "<!-- Footer -->`r`n$FooterHtml"
    }

    Set-Content -Path $file.FullName -Value $content -NoNewline
    Write-Host " [OK]" -ForegroundColor Green
}

Write-Host ""
Write-Host "Done. Static HTML headers and footers updated from components." -ForegroundColor Green


