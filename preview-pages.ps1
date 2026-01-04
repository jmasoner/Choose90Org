# Choose90 HTML Pages Preview Script
# Opens all HTML pages in your default browser

Write-Host "Opening Choose90 pages in your browser..." -ForegroundColor Cyan
Write-Host ""

$pages = @(
    @{ Name = "New Year's Resolution"; Path = "hybrid_site\new-years-resolution.html" },
    @{ Name = "Pledge Wall"; Path = "hybrid_site\pledge-wall.html" },
    @{ Name = "Content Generator"; Path = "hybrid_site\tools\content-generator.html" },
    @{ Name = "30-Day Challenge"; Path = "hybrid_site\resources\30-day-choose90-challenge.html" },
    @{ Name = "Installation Guide"; Path = "hybrid_site\pwa.html" },
    @{ Name = "Kwanzaa Choose90"; Path = "hybrid_site\kwanzaa-choose90.html" },
    @{ Name = "Kwanzaa Challenge"; Path = "hybrid_site\kwanzaa-challenge.html" }
)

foreach ($page in $pages) {
    $filePath = Join-Path $PSScriptRoot $page.Path
    if (Test-Path $filePath) {
        $fullPath = (Resolve-Path $filePath).Path
        Write-Host "✓ Opening: $($page.Name)" -ForegroundColor Green
        Start-Process $fullPath
        Start-Sleep -Milliseconds 300
    } else {
        Write-Host "✗ Not found: $($page.Name)" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "All available pages have been opened in your browser!" -ForegroundColor Cyan
Write-Host ""
Write-Host "Note: Some features (like API calls) may not work locally." -ForegroundColor Yellow
Write-Host "For full functionality, deploy to the server first." -ForegroundColor Yellow


