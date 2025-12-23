# Script to add headers and footers to all resource HTML files
$ResourceFiles = @(
    "conversation-starter-kit.html",
    "phone-setup-optimizer.html",
    "small-acts-toolkit.html",
    "neighborhood-challenge.html",
    "media-mindfulness-guide.html",
    "intergenerational-guide.html",
    "conflict-translator.html",
    "local-directory-template.html",
    "choose90-meeting-agenda.html"
)

$ResourcesPath = "hybrid_site\resources"

foreach ($file in $ResourceFiles) {
    $filePath = Join-Path $ResourcesPath $file
    if (Test-Path $filePath) {
        Write-Host "Processing: $file" -ForegroundColor Yellow
        
        $content = Get-Content $filePath -Raw
        
        # Check if header already exists
        if ($content -notmatch "site-header") {
            # Add header after <body>
            $header = @"
    <!-- Navigation -->
    <header class="site-header">
        <div class="container">
            <a href="../index.html" class="logo">Choose<span>90</span>.</a>
            <div class="mobile-menu-btn">☰</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="../about.html">Our Story</a></li>
                    <li><a href="/chapters/">Chapters</a></li>
                    <li><a href="/resources/">Resources</a></li>
                    <li><a href="/pledge/" class="btn btn-outline" style="padding: 8px 20px; border-radius: 5px;">Pledge</a></li>
                    <li><a href="/donate/" class="btn btn-starfield" style="padding: 8px 25px; border-radius: 50px;">Donate</a></li>
                </ul>
            </nav>
        </div>
    </header>

"@
            $content = $content -replace "(<body[^>]*>)", "`$1`n$header"
        }
        
        # Check if footer already exists
        if ($content -notmatch "footer-top") {
            $footer = @"
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-top">
                <div class="footer-col" style="flex: 2;">
                    <a href="../index.html" class="logo" style="color: white; margin-bottom: 1rem; display: block;">Choose<span>90</span>.</a>
                    <p style="color: #888; max-width: 300px;">A movement to reclaim our joy, health, and community by focusing on the good.</p>
                </div>
                <div class="footer-col">
                    <h4>Explore</h4>
                    <ul>
                        <li><a href="../index.html">Home</a></li>
                        <li><a href="../about.html">Our Story</a></li>
                        <li><a href="/chapters/">Chapters</a></li>
                        <li><a href="/resources/">Resources</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Action</h4>
                    <ul>
                        <li><a href="/pledge/">Take the Pledge</a></li>
                        <li><a href="/donate/">Donate</a></li>
                        <li><a href="/support.html">Contact Support</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Choose90.org. All rights reserved. | <a href="../privacy.html">Privacy Policy</a></p>
            </div>
        </div>
    </footer>

    <script src="../script.js"></script>
"@
            $content = $content -replace "(</body>)", "$footer`n`$1"
        }
        
        # Update font links and add style.css
        $content = $content -replace 'href="https://fonts\.googleapis\.com/css2\?family=Outfit[^"]*"', 'rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="../style.css"'
        
        # Update body padding
        $content = $content -replace 'padding:\s*20px;', 'padding: 0; padding-top: 80px;'
        
        Set-Content -Path $filePath -Value $content -NoNewline
        Write-Host "  ✓ Updated $file" -ForegroundColor Green
    } else {
        Write-Host "  ✗ Not found: $file" -ForegroundColor Red
    }
}

Write-Host "`nDone! All resource files updated." -ForegroundColor Green

