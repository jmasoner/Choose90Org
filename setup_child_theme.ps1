# setup_child_theme.ps1
# Creates/Updates the "Divi-choose90" Child Theme on the Web Disk (Z:)
# UPDATE: CSS CONSOLIDATED to Global style.css

$ThemePath = "Z:\wp-content\themes\Divi-choose90"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   Choose90.org Child Theme: RESCUE       " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

if (-not (Test-Path "Z:\")) {
    Write-Error "Drive Z: is not connected. Please run 'map_drive_interactive.ps1' first."
    exit 1
}

# 1. Update style.css (Minimal - Rest in Global)
Write-Host "Updating style.css (Minimal)..."
$StyleContent = @"
/*
 Theme Name:   Choose90 Hybrid
 Theme URI:    https://choose90.org
 Description:  Divi Child Theme for Hybrid Architecture.
 Author:       Choose90 Team
 Template:     Divi
 Version:      1.5.0 (Global CSS)
*/

/* --- FIX: WP Admin Bar Overlap & Colors --- */
body.admin-bar .site-header { top: 32px; }
@media screen and (max-width: 782px) { body.admin-bar .site-header { top: 46px; } }

/* Force Colors */
.btn-primary, .btn-starfield { color: #ffffff !important; }
"@
$StyleContent | Set-Content (Join-Path $ThemePath "style.css")

# 2. Update functions.php (Unchanged)
Write-Host "Updating functions.php..."
$FunctionsContent = @"
<?php
function choose90_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    // Enqueue Global Styles with Cache Busting
    wp_enqueue_style( 'hybrid-global-style', 'https://choose90.org/style.css', array(), time() );
    wp_enqueue_style( 'choose90-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Outfit:wght@500;700;800&display=swap', array(), null );
}
add_action( 'wp_enqueue_scripts', 'choose90_enqueue_styles' );
?>
"@
$FunctionsContent | Set-Content (Join-Path $ThemePath "functions.php")

# 3. Header/Footer/Templates (Unchanged - Keeping content)
# To save time and avoid redundancy in this artifact, I am NOT rewriting the template file blocks here 
# if they are already on the server and verified. 
# BUT, to be safe, I should overwrite them to ensure they use the correct classes.
# The previous writing of page-chapters.php etc was correct in HTML, just missing CSS.
# I will just run the header/footer updates again to be sure.

Write-Host "Updating header.php..."
$HeaderContent = @"
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title(''); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div id="page-container">
    <header class="site-header">
        <div class="container">
            <a href="/" class="logo">Choose<span>90</span>.</a>
            <div class="mobile-menu-btn">☰</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="/">Home</a></li>
                    <li><a href="/about.html">Our Story</a></li>
                    <li><a href="/chapters/">Chapters</a></li>
                    <li><a href="/resources/">Resources</a></li>
                    <li><a href="/pledge/" class="btn btn-outline" style="padding: 8px 20px; border-radius: 5px;">Pledge</a></li>
                    <li><a href="/donate/" class="btn btn-starfield" style="padding: 8px 25px; border-radius: 50px;">Donate</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div id="et-main-area">
"@
$HeaderContent | Set-Content (Join-Path $ThemePath "header.php")

Write-Host "Updating footer.php..."
$FooterContent = @"
    </div> <!-- End #et-main-area -->
    <footer>
        <div class="container">
            <div class="footer-top">
                <div class="footer-col" style="flex: 2;">
                    <a href="/" class="logo" style="color: white; margin-bottom: 1rem; display: block;">Choose<span>90</span>.</a>
                    <p style="color: #888; max-width: 300px;">A movement to reclaim our joy, health, and community by focusing on the good.</p>
                </div>
                <div class="footer-col">
                    <h4>Explore</h4>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/about.html">Our Story</a></li>
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
                <p>&copy; 2025 Choose90.org. All rights reserved. | <a href="/privacy.html">Privacy Policy</a></p>
            </div>
        </div>
    </footer>
    </div> <!-- End #page-container -->
    <?php wp_footer(); ?>
</body>
</html>
"@
$FooterContent | Set-Content (Join-Path $ThemePath "footer.php")

# 4. Deploy page-resources.php template
Write-Host "Updating page-resources.php template..."
$ResourcesTemplateSource = Join-Path $PSScriptRoot "page-resources.php"
if (Test-Path $ResourcesTemplateSource) {
    Copy-Item -Path $ResourcesTemplateSource -Destination (Join-Path $ThemePath "page-resources.php") -Force
    Write-Host "Resources template deployed." -ForegroundColor Green
}
else {
    Write-Warning "page-resources.php not found in project root. Skipping."
}

Write-Host "Success! Child Theme Styles Consolidated & Templates Updated."
