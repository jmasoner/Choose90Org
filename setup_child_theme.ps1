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
$FunctionsContent = @'
<?php
function choose90_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    // Enqueue Global Styles with Cache Busting
    wp_enqueue_style( 'hybrid-global-style', 'https://choose90.org/style.css', array(), time() );
    wp_enqueue_style( 'choose90-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Outfit:wght@500;700;800&display=swap', array(), null );
}
add_action( 'wp_enqueue_scripts', 'choose90_enqueue_styles' );

// --- REGISTER CHAPTERS CPT ---
function choose90_register_chapters() {
    $labels = array(
        'name'               => 'Chapters',
        'singular_name'      => 'Chapter',
        'add_new'            => 'Add New Chapter',
        'add_new_item'       => 'Add New Chapter',
        'edit_item'          => 'Edit Chapter',
        'new_item'           => 'New Chapter',
        'all_items'          => 'All Chapters',
        'view_item'          => 'View Chapter',
        'search_items'       => 'Search Chapters',
        'not_found'          => 'No chapters found',
        'not_found_in_trash' => 'No chapters found in Trash',
        'menu_name'          => 'Chapters'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'chapter' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' )
    );

    register_post_type( 'chapter', $args );
    
    // FLUSH RULES for Permalinks (Fixes 404s)
    flush_rewrite_rules();
}
add_action( 'init', 'choose90_register_chapters' );

// --- REGISTER REGION TAXONOMY ---
function choose90_register_region_taxonomy() {
    $labels = array(
        'name'              => 'Regions',
        'singular_name'     => 'Region',
        'search_items'      => 'Search Regions',
        'all_items'         => 'All Regions',
        'parent_item'       => 'Parent Region',
        'parent_item_colon' => 'Parent Region:',
        'edit_item'         => 'Edit Region',
        'update_item'       => 'Update Region',
        'add_new_item'      => 'Add New Region',
        'new_item_name'     => 'New Region Name',
        'menu_name'         => 'Region',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'region' ),
    );

    register_taxonomy( 'chapter_region', array( 'chapter' ), $args );
}
add_action( 'init', 'choose90_register_region_taxonomy' );
// --- ENSURE HOST KIT PAGE EXISTS ---
function choose90_ensure_pages_exist() {
    // Check if Host Starter Kit page exists
    if (!get_page_by_path('host-starter-kit')) {
        $page_id = wp_insert_post(array(
            'post_title'    => 'Host Starter Kit',
            'post_name'     => 'host-starter-kit',
            'post_status'   => 'publish',
            'post_type'     => 'page',
        ));
        if ($page_id && !is_wp_error($page_id)) {
            update_post_meta($page_id, '_wp_page_template', 'page-host-starter-kit.php');
        }
    }

    // Check if Resources page exists
    if (!get_page_by_path('resources')) {
        $res_page_id = wp_insert_post(array(
            'post_title'    => 'Resources',
            'post_name'     => 'resources',
            'post_status'   => 'publish',
            'post_type'     => 'page',
        ));
        if ($res_page_id && !is_wp_error($res_page_id)) {
            update_post_meta($res_page_id, '_wp_page_template', 'page-resources.php');
        }
    }

    // Ensure Pledge page exists and uses custom template
    $existing_pledge = get_page_by_path('pledge');
    if ($existing_pledge) {
        // Make sure it uses our custom template
        update_post_meta($existing_pledge->ID, '_wp_page_template', 'page-pledge.php');
    } else {
        $pledge_page_id = wp_insert_post(array(
            "post_title"  => "Pledge",
            "post_name"   => "pledge",
            "post_status" => "publish",
            "post_type"   => "page"
        ));
        if ($pledge_page_id && !is_wp_error($pledge_page_id)) {
            update_post_meta($pledge_page_id, '_wp_page_template', 'page-pledge.php');
        }
    }
}
add_action('init', 'choose90_ensure_pages_exist');

// Include Choose90 custom functions
// Note: Adjust paths based on your server structure
// Option 1: If wp-functions files are in a parallel directory
if (file_exists(get_stylesheet_directory() . '/../hybrid_site/wp-functions-pledge.php')) {
    require_once get_stylesheet_directory() . '/../hybrid_site/wp-functions-pledge.php';
}
if (file_exists(get_stylesheet_directory() . '/../hybrid_site/wp-functions-personalization.php')) {
    require_once get_stylesheet_directory() . '/../hybrid_site/wp-functions-personalization.php';
}
if (file_exists(get_stylesheet_directory() . '/../hybrid_site/wp-functions-chapters.php')) {
    require_once get_stylesheet_directory() . '/../hybrid_site/wp-functions-chapters.php';
}

// Option 2: If copied to theme directory
if (file_exists(get_stylesheet_directory() . '/wp-functions-pledge.php')) {
    require_once get_stylesheet_directory() . '/wp-functions-pledge.php';
}
if (file_exists(get_stylesheet_directory() . '/wp-functions-personalization.php')) {
    require_once get_stylesheet_directory() . '/wp-functions-personalization.php';
}
if (file_exists(get_stylesheet_directory() . '/wp-functions-chapters.php')) {
    require_once get_stylesheet_directory() . '/wp-functions-chapters.php';
}
if (file_exists(get_stylesheet_directory() . '/../hybrid_site/wp-functions-dashboard.php')) {
    require_once get_stylesheet_directory() . '/../hybrid_site/wp-functions-dashboard.php';
}
if (file_exists(get_stylesheet_directory() . '/wp-functions-dashboard.php')) {
    require_once get_stylesheet_directory() . '/wp-functions-dashboard.php';
}
?>
'@
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
                    <li><a href="/resources-hub.html">Resources</a></li>
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
                        <li><a href="/resources-hub.html">Resources</a></li>
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
$ResourcesTemplateSource = Join-Path $PSScriptRoot "hybrid_site\page-resources.php"
if (Test-Path $ResourcesTemplateSource) {
    Copy-Item -Path $ResourcesTemplateSource -Destination (Join-Path $ThemePath "page-resources.php") -Force
    Write-Host "Resources template deployed." -ForegroundColor Green
}
else {
    # Fallback to project root
    $ResourcesTemplateSourceFallback = Join-Path $PSScriptRoot "page-resources.php"
    if (Test-Path $ResourcesTemplateSourceFallback) {
        Copy-Item -Path $ResourcesTemplateSourceFallback -Destination (Join-Path $ThemePath "page-resources.php") -Force
        Write-Host "Resources template deployed (from root)." -ForegroundColor Green
    } else {
        Write-Warning "page-resources.php not found. Skipping."
    }
}

# 5. Deploy page-pledge.php template (custom pledge page)
Write-Host "Updating page-pledge.php template..."
$PledgeTemplateSource = Join-Path $PSScriptRoot "hybrid_site\page-pledge.php"
if (Test-Path $PledgeTemplateSource) {
    Copy-Item -Path $PledgeTemplateSource -Destination (Join-Path $ThemePath "page-pledge.php") -Force
    Write-Host "Pledge template deployed." -ForegroundColor Green
} else {
    Write-Warning "page-pledge.php not found. Skipping."
}

# 5. Deploy Chapters Templates
Write-Host "Updating Chapters templates..."
$ChaptersTemplateSource = Join-Path $PSScriptRoot "hybrid_site\page-chapters.php"
$SingleChapterTemplateSource = Join-Path $PSScriptRoot "hybrid_site\single-chapter.php"

if (Test-Path $ChaptersTemplateSource) {
    Copy-Item -Path $ChaptersTemplateSource -Destination (Join-Path $ThemePath "page-chapters.php") -Force
    Write-Host "page-chapters.php deployed to theme." -ForegroundColor Green
}
else {
    Write-Warning "page-chapters.php not found in hybrid_site. Skipping."
}

if (Test-Path $SingleChapterTemplateSource) {
    Copy-Item -Path $SingleChapterTemplateSource -Destination (Join-Path $ThemePath "single-chapter.php") -Force
    Write-Host "single-chapter.php deployed to theme." -ForegroundColor Green
}
else {
    Write-Warning "single-chapter.php not found in hybrid_site. Skipping."
}

$HostKitTemplateSource = Join-Path $PSScriptRoot "hybrid_site\page-host-starter-kit.php"
if (Test-Path $HostKitTemplateSource) {
    Copy-Item -Path $HostKitTemplateSource -Destination (Join-Path $ThemePath "page-host-starter-kit.php") -Force
    Write-Host "page-host-starter-kit.php deployed to theme." -ForegroundColor Green
}
else {
    Write-Warning "page-host-starter-kit.php not found in hybrid_site. Skipping."
}

# 6. Deploy WordPress Functions Files (Optional - copy to theme if needed)
Write-Host "Checking for WordPress functions files..."
$WpFunctionsFiles = @(
    "hybrid_site\wp-functions-pledge.php",
    "hybrid_site\wp-functions-personalization.php",
    "hybrid_site\wp-functions-chapters.php",
    "hybrid_site\wp-functions-dashboard.php"
)

foreach ($file in $WpFunctionsFiles) {
    $source = Join-Path $PSScriptRoot $file
    if (Test-Path $source) {
        $dest = Join-Path $ThemePath (Split-Path -Leaf $file)
        Copy-Item -Path $source -Destination $dest -Force
        Write-Host "Deployed $(Split-Path -Leaf $file) to theme." -ForegroundColor Green
    }
}

# 7. Deploy Components (contact forms, etc.)
Write-Host "Deploying components..."
$ComponentsDir = Join-Path $ThemePath "components"
if (-not (Test-Path $ComponentsDir)) {
    New-Item -ItemType Directory -Path $ComponentsDir -Force | Out-Null
}

$ComponentFiles = @(
    "hybrid_site\components\chapter-contact-form.php"
)

foreach ($file in $ComponentFiles) {
    $source = Join-Path $PSScriptRoot $file
    if (Test-Path $source) {
        $dest = Join-Path $ComponentsDir (Split-Path -Leaf $file)
        Copy-Item -Path $source -Destination $dest -Force
        Write-Host "Deployed component: $(Split-Path -Leaf $file)" -ForegroundColor Green
    }
}

Write-Host "Success! Child Theme Styles Consolidated & Templates Updated."
