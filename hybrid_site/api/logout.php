<?php
/**
 * Choose90 Logout Endpoint
 * 
 * Handles logout for both WordPress and Firebase authentication
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

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

if (!$wp_loaded) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'WordPress not found'
    ]);
    exit;
}

// Log out from WordPress
if (function_exists('wp_logout')) {
    // Get current user ID before logout
    $user_id = get_current_user_id();
    
    // Log out
    wp_logout();
    
    // Destroy session
    if (session_id()) {
        session_destroy();
    }
    
    // Clear all cookies
    if (isset($_COOKIE)) {
        foreach ($_COOKIE as $key => $value) {
            if (strpos($key, 'wordpress_') === 0 || strpos($key, 'wp_') === 0) {
                setcookie($key, '', time() - 3600, '/');
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Successfully logged out',
        'redirect_to' => home_url('/')
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'WordPress logout function not available'
    ]);
}
