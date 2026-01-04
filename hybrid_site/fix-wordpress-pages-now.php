<?php
/**
 * Fix WordPress Pages - Run once via browser or WP-CLI
 * 
 * This script ensures:
 * 1. Chapters page has empty editor content and uses correct template
 * 2. Resources page exists and uses correct template
 * 3. All pages have correct template assignments
 */

// Load WordPress
// Try multiple paths
$wp_load_paths = array(
    __DIR__ . '/../../../wp-load.php',
    dirname(__DIR__) . '/../../wp-load.php',
    dirname(dirname(dirname(__DIR__))) . '/wp-load.php'
);

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        require_once($path);
        $wp_loaded = true;
        break;
    }
}

if (!$wp_loaded) {
    // Last resort: try relative to document root
    $doc_root = $_SERVER['DOCUMENT_ROOT'] ?? dirname(dirname(dirname(__DIR__)));
    $wp_load = $doc_root . '/wp-load.php';
    if (file_exists($wp_load)) {
        require_once($wp_load);
    } else {
        die('Error: Could not find wp-load.php');
    }
}

if (!current_user_can('manage_options')) {
    die('You must be logged in as an administrator to run this script.');
}

echo "<h1>Fixing Choose90 WordPress Pages</h1>";
echo "<pre>";

$fixes = array();

// 1. Fix Chapters Page
$chapters_page = get_page_by_path('chapters');
if ($chapters_page) {
    // Clear editor content so template shows
    $updated = wp_update_post(array(
        'ID' => $chapters_page->ID,
        'post_content' => '',  // Empty content = template takes over
        'post_status' => 'publish'
    ));
    
    // Ensure template is set
    update_post_meta($chapters_page->ID, '_wp_page_template', 'page-chapters.php');
    
    if ($updated) {
        $fixes[] = "✓ Chapters page: Cleared editor content and set template to 'page-chapters.php'";
    } else {
        $fixes[] = "✗ Chapters page: Failed to update";
    }
} else {
    $fixes[] = "✗ Chapters page: Not found - creating...";
    $new_id = wp_insert_post(array(
        'post_title' => 'Chapters',
        'post_name' => 'chapters',
        'post_content' => '',
        'post_status' => 'publish',
        'post_type' => 'page'
    ));
    if ($new_id && !is_wp_error($new_id)) {
        update_post_meta($new_id, '_wp_page_template', 'page-chapters.php');
        $fixes[] = "✓ Chapters page: Created with template 'page-chapters.php'";
    }
}

// 2. Fix Resources Page (if it exists - but we use resources-hub.html directly)
// Note: All links point to /resources-hub.html (static file), but if WordPress /resources/ page exists,
// we'll set it to output the static HTML directly via page-resources.php (no redirects)
$resources_page = get_page_by_path('resources');
if ($resources_page) {
    update_post_meta($resources_page->ID, '_wp_page_template', 'page-resources.php');
    $fixes[] = "✓ Resources page: Template set to 'page-resources.php' (outputs resources-hub.html directly)";
}

// 3. Fix Pledge Page
$pledge_page = get_page_by_path('pledge');
if ($pledge_page) {
    update_post_meta($pledge_page->ID, '_wp_page_template', 'page-pledge.php');
    $fixes[] = "✓ Pledge page: Template set to 'page-pledge.php'";
}

// 4. Fix Donate Page
$donate_page = get_page_by_path('donate');
if ($donate_page) {
    update_post_meta($donate_page->ID, '_wp_page_template', 'page-donate.php');
    $fixes[] = "✓ Donate page: Template set to 'page-donate.php'";
}

// Flush rewrite rules
flush_rewrite_rules();
$fixes[] = "✓ Flushed rewrite rules";

echo implode("\n", $fixes);
echo "\n\n=== COMPLETE ===";
echo "\n\nNext steps:";
echo "\n1. Go to WordPress Admin > Settings > Permalinks and click 'Save Changes' (no changes needed, just save to refresh)";
echo "\n2. Clear any caching (if using a cache plugin)";
echo "\n3. Test /chapters/ and /resources/ in your browser";
echo "\n\n<strong>DELETE THIS FILE after running it for security.</strong>";
echo "</pre>";

