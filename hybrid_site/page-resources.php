<?php
/**
 * Template Name: Resources Page
 * Description: Redirects to resources-hub.html
 */

// Redirect must happen before any output
if (!headers_sent()) {
    wp_redirect(home_url('/resources-hub.html'), 301);
    exit;
} else {
    // Fallback if headers already sent
    echo '<script>window.location.href="' . esc_url(home_url('/resources-hub.html')) . '";</script>';
    echo '<meta http-equiv="refresh" content="0;url=' . esc_url(home_url('/resources-hub.html')) . '">';
    echo '<a href="' . esc_url(home_url('/resources-hub.html')) . '">Click here if not redirected</a>';
}

