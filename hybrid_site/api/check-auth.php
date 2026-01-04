<?php
/**
 * Choose90 Authentication Check Endpoint
 * 
 * Checks if user is logged into WordPress (via cookie or Firebase token)
 * Used by JavaScript on static HTML pages
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

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
    echo json_encode([
        'logged_in' => false,
        'isLoggedIn' => false,
        'error' => 'WordPress not found'
    ]);
    exit;
}

// Start session if not already started (for cookie-based auth)
if (!session_id()) {
    session_start();
}

// Check if Firebase token is provided
$input = json_decode(file_get_contents('php://input'), true);
$firebase_token = $input['firebase_token'] ?? null;

// If Firebase token provided, verify it and sync with WordPress
if ($firebase_token) {
    // Load secrets.json
    $secrets_paths = [
        __DIR__ . '/../secrets.json',
        __DIR__ . '/../../secrets.json',
        $_SERVER['DOCUMENT_ROOT'] . '/secrets.json',
        dirname(__DIR__) . '/secrets.json',
    ];
    
    $secrets = null;
    foreach ($secrets_paths as $path) {
        $real_path = realpath($path);
        if ($real_path && file_exists($real_path)) {
            $secrets = json_decode(file_get_contents($real_path), true);
            break;
        }
    }
    
    if ($secrets && isset($secrets['firebase']['api_key'])) {
        // Verify Firebase token (reuse function from firebase-auth-callback.php)
        $url = 'https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=' . urlencode($secrets['firebase']['api_key']);
        $data = json_encode(['idToken' => $firebase_token]);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 200) {
            $result = json_decode($response, true);
            if (isset($result['users']) && count($result['users']) > 0) {
                $firebase_user = $result['users'][0];
                $email = $firebase_user['email'] ?? null;
                
                if ($email) {
                    // Find WordPress user by email or Firebase UID
                    $wp_user = get_user_by('email', $email);
                    
                    if (!$wp_user) {
                        // Try to find by Firebase UID
                        $users = get_users([
                            'meta_key' => 'firebase_uid',
                            'meta_value' => $firebase_user['localId'] ?? $firebase_user['uid'] ?? null
                        ]);
                        if (!empty($users)) {
                            $wp_user = $users[0];
                        }
                    }
                    
                    if ($wp_user) {
                        // Log user into WordPress
                        wp_set_current_user($wp_user->ID);
                        wp_set_auth_cookie($wp_user->ID, true);
                    }
                }
            }
        }
    }
}

// Check WordPress authentication
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

