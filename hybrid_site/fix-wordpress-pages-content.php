<?php
/**
 * Script to fix WordPress pages content issues
 * - Clears page editor content so templates work
 * - Ensures correct templates are assigned
 * 
 * Instructions:
 * 1. Upload this file to your WordPress root directory
 * 2. Visit: https://choose90.org/fix-wordpress-pages-content.php
 * 3. Delete this file after running
 */

// Load WordPress
require_once('wp-load.php');

// Check if user is logged in as admin (security check)
if (!is_user_logged_in() || !current_user_can('manage_options')) {
    die('You must be logged in as an administrator to run this script.');
}

echo "<h1>Fixing Choose90 WordPress Pages Content</h1>";
echo "<pre>";

// Function to fix page content and template
function fix_page($slug, $template, $description) {
    $page = get_page_by_path($slug);
    
    if (!$page) {
        echo "✗ Page not found: $slug\n";
        return false;
    }
    
    echo "Fixing page: $description ($slug)...\n";
    echo "  Current ID: {$page->ID}\n";
    
    // Clear page content (so template provides all content)
    $updated = wp_update_post(array(
        'ID' => $page->ID,
        'post_content' => '', // Clear editor content
    ));
    
    if (is_wp_error($updated)) {
        echo "  ✗ Error clearing content: " . $updated->get_error_message() . "\n";
        return false;
    }
    
    echo "  ✓ Cleared page editor content\n";
    
    // Ensure template is set correctly
    $current_template = get_post_meta($page->ID, '_wp_page_template', true);
    if ($current_template !== $template) {
        update_post_meta($page->ID, '_wp_page_template', $template);
        echo "  ✓ Updated template to: $template\n";
    } else {
        echo "  ✓ Template already correct: $template\n";
    }
    
    // Ensure page is published
    if ($page->post_status !== 'publish') {
        wp_update_post(array(
            'ID' => $page->ID,
            'post_status' => 'publish',
        ));
        echo "  ✓ Set status to Published\n";
    }
    
    return true;
}

// Fix pages
echo "\n=== Fixing Pages ===\n\n";

fix_page('chapters', 'page-chapters.php', 'Chapters');
fix_page('resources', 'page-resources.php', 'Resources');
fix_page('pledge', 'page-pledge.php', 'Pledge');
fix_page('host-starter-kit', 'page-host-starter-kit.php', 'Host Starter Kit');

// Flush rewrite rules
echo "\n=== Flushing Permalinks ===\n";
flush_rewrite_rules();
echo "✓ Permalinks flushed\n";

echo "\n=== Done! ===\n";
echo "\nNext steps:\n";
echo "1. Test: https://choose90.org/resources/ (should redirect to resources-hub.html)\n";
echo "2. Test: https://choose90.org/chapters/ (should show chapter directory)\n";
echo "3. Delete this file (fix-wordpress-pages-content.php) for security\n";
echo "</pre>";






