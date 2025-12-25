<?php
/**
 * Choose90 Authentication Check Endpoint
 * 
 * Simple endpoint to check if user is logged into WordPress
 * Used by JavaScript on static HTML pages
 */

header('Content-Type: application/json');

// Load WordPress - try multiple paths
$wp_load_paths = array(
    __DIR__ . '/../../../../wp-load.php',  // Standard: api/../../../../wp-load.php
    __DIR__ . '/../../../wp-load.php',     // Alternative
    $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php',  // Document root
    dirname(dirname(dirname(dirname(__DIR__)))) . '/wp-load.php',  // Relative
);

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    $real_path = realpath($path);
    if ($real_path && file_exists($real_path)) {
        require_once($real_path);
        $wp_loaded = true;
        break;
    }
}

if ($wp_loaded) {
    // Start session if not already started (for cookie-based auth)
    if (!session_id()) {
        session_start();
    }
    
    $is_logged_in = function_exists('is_user_logged_in') && is_user_logged_in();
    
    if ($is_logged_in) {
        $user = wp_get_current_user();
        echo json_encode([
            'logged_in' => true,
            'isLoggedIn' => true,  // Also include camelCase for compatibility
            'user_id' => $user->ID,
            'userId' => $user->ID,  // Also include camelCase
            'display_name' => $user->display_name,
            'displayName' => $user->display_name,  // Also include camelCase
            'screen_name' => get_user_meta($user->ID, 'screen_name', true) ?: $user->display_name,
            'screenName' => get_user_meta($user->ID, 'screen_name', true) ?: $user->display_name
        ]);
    } else {
        echo json_encode([
            'logged_in' => false,
            'isLoggedIn' => false,  // Also include camelCase
            'login_url' => (function_exists('wp_login_url') ? wp_login_url() : '/login/') ?: '/login/'
        ]);
    }
} else {
    // WordPress not found - assume not logged in
    echo json_encode([
        'logged_in' => false,
        'isLoggedIn' => false,
        'error' => 'WordPress not found'
    ]);
}

