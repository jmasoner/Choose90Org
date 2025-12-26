<?php
/**
 * Test script to debug Resources redirect
 * Visit: https://choose90.org/test-resources-redirect.php
 */

require_once('wp-load.php');

echo "<h1>Resources Redirect Debug</h1>";
echo "<pre>";

// Check if Resources page exists
$resources_page = get_page_by_path('resources');
if ($resources_page) {
    echo "✓ Resources page found (ID: {$resources_page->ID})\n";
    echo "  Title: {$resources_page->post_title}\n";
    echo "  Status: {$resources_page->post_status}\n";
    echo "  Template: " . get_post_meta($resources_page->ID, '_wp_page_template', true) . "\n";
} else {
    echo "✗ Resources page NOT found\n";
}

// Check if is_page works
echo "\nTesting is_page('resources'): ";
var_dump(is_page('resources'));

echo "\nTesting is_page({$resources_page->ID}): ";
var_dump(is_page($resources_page->ID));

// Test redirect URL
$redirect_url = home_url('/resources-hub.html');
echo "\nRedirect URL: $redirect_url\n";

// Check if resources-hub.html exists
$file_path = ABSPATH . 'resources-hub.html';
echo "File path: $file_path\n";
echo "File exists: " . (file_exists($file_path) ? 'YES' : 'NO') . "\n";

// Test actual redirect
echo "\n=== Testing Redirect ===\n";
if (is_page('resources')) {
    echo "Would redirect to: $redirect_url\n";
    echo "\nClick here to test: <a href='$redirect_url'>$redirect_url</a>\n";
} else {
    echo "Not on Resources page - cannot test redirect\n";
}

echo "</pre>";






