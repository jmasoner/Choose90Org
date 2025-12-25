<?php
/**
 * Template Name: Resources Page
 * Description: Plain hard-coded output of resources-hub.html content
 */

// Just output the static HTML file directly - no redirects, no fancy stuff
$html_file = ABSPATH . 'resources-hub.html';

if (file_exists($html_file)) {
    // Read and output the HTML file
    readfile($html_file);
    exit;
} else {
    // Fallback if file doesn't exist
    echo '<!DOCTYPE html><html><head><title>Resources - Choose90.org</title></head><body>';
    echo '<h1>Resources</h1>';
    echo '<p>The resources page is temporarily unavailable.</p>';
    echo '</body></html>';
}
