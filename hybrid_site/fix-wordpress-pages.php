<?php
/**
 * One-time script to fix WordPress pages after directory removal
 * 
 * Instructions:
 * 1. Upload this file to your WordPress root directory (same level as wp-config.php)
 * 2. Visit: https://choose90.org/fix-wordpress-pages.php
 * 3. Delete this file after running
 */

// Load WordPress
require_once('wp-load.php');

// Check if user is logged in as admin (security check)
if (!is_user_logged_in() || !current_user_can('manage_options')) {
    die('You must be logged in as an administrator to run this script.');
}

echo "<h1>Fixing Choose90 WordPress Pages</h1>";
echo "<pre>";

// Function to ensure page exists
function ensure_page($title, $slug, $template) {
    $page = get_page_by_path($slug);
    
    if (!$page) {
        echo "Creating page: $title ($slug)...\n";
        $page_id = wp_insert_post(array(
            'post_title'    => $title,
            'post_name'     => $slug,
            'post_status'   => 'publish',
            'post_type'     => 'page',
        ));
        
        if ($page_id && !is_wp_error($page_id)) {
            update_post_meta($page_id, '_wp_page_template', $template);
            echo "✓ Created page ID: $page_id\n";
            return $page_id;
        } else {
            echo "✗ Error creating page: " . (is_wp_error($page_id) ? $page_id->get_error_message() : 'Unknown error') . "\n";
            return false;
        }
    } else {
        echo "Page exists: $title (ID: {$page->ID})\n";
        // Ensure template is set correctly
        $current_template = get_post_meta($page->ID, '_wp_page_template', true);
        if ($current_template !== $template) {
            update_post_meta($page->ID, '_wp_page_template', $template);
            echo "✓ Updated template to: $template\n";
        } else {
            echo "✓ Template already correct: $template\n";
        }
        return $page->ID;
    }
}

// Create/update pages
echo "\n=== Creating/Updating Pages ===\n\n";

ensure_page('Resources', 'resources', 'page-resources.php');
ensure_page('Chapters', 'chapters', 'page-chapters.php');
ensure_page('Pledge', 'pledge', 'page-pledge.php');
ensure_page('Host Starter Kit', 'host-starter-kit', 'page-host-starter-kit.php');

// Flush rewrite rules
echo "\n=== Flushing Permalinks ===\n";
flush_rewrite_rules();
echo "✓ Permalinks flushed\n";

echo "\n=== Done! ===\n";
echo "\nNext steps:\n";
echo "1. Test: https://choose90.org/resources/\n";
echo "2. Test: https://choose90.org/chapters/\n";
echo "3. Delete this file (fix-wordpress-pages.php) for security\n";
echo "</pre>";






