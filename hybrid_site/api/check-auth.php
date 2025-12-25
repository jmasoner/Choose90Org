<?php
/**
 * Choose90 Authentication Check Endpoint
 * 
 * Simple endpoint to check if user is logged into WordPress
 * Used by JavaScript on static HTML pages
 */

header('Content-Type: application/json');

// Load WordPress
$wp_load_path = __DIR__ . '/../../../../wp-load.php';
if (!file_exists($wp_load_path)) {
    // Try alternative path
    $wp_load_path = __DIR__ . '/../../../wp-load.php';
}

if (file_exists($wp_load_path)) {
    require_once($wp_load_path);
    
    $is_logged_in = function_exists('is_user_logged_in') && is_user_logged_in();
    
    if ($is_logged_in) {
        $user = wp_get_current_user();
        echo json_encode([
            'logged_in' => true,
            'user_id' => $user->ID,
            'display_name' => $user->display_name,
            'screen_name' => get_user_meta($user->ID, 'screen_name', true) ?: $user->display_name
        ]);
    } else {
        echo json_encode([
            'logged_in' => false,
            'login_url' => wp_login_url() ?: '/pledge/'
        ]);
    }
} else {
    // WordPress not found - assume not logged in
    echo json_encode([
        'logged_in' => false,
        'error' => 'WordPress not found'
    ]);
}

