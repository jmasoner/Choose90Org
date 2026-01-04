<?php
/**
 * Firebase Authentication Callback Endpoint
 * 
 * Handles Firebase authentication tokens and syncs with WordPress users
 * 
 * Flow:
 * 1. User authenticates with Firebase (Google, Facebook, Email, etc.)
 * 2. Frontend sends Firebase ID token to this endpoint
 * 3. We verify the token with Firebase
 * 4. Create/update WordPress user account
 * 5. Log user into WordPress
 * 6. Return success response
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Load WordPress
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

if (!$secrets || !isset($secrets['firebase'])) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Firebase configuration not found'
    ]);
    exit;
}

// Get Firebase token from request
$input = json_decode(file_get_contents('php://input'), true);
$firebase_token = $input['token'] ?? null;
$additional_data = $input['additional_data'] ?? []; // For pledge form data (screen_name, location, etc.)

if (!$firebase_token) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'No Firebase token provided'
    ]);
    exit;
}

/**
 * Verify Firebase ID token
 * Uses Firebase REST API to verify the token
 */
function verify_firebase_token($token, $api_key) {
    $url = 'https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=' . urlencode($api_key);
    
    $data = json_encode(['idToken' => $token]);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code !== 200) {
        return null;
    }
    
    $result = json_decode($response, true);
    
    if (isset($result['users']) && count($result['users']) > 0) {
        $user = $result['users'][0];
        return [
            'uid' => $user['localId'] ?? $user['uid'] ?? null,
            'email' => $user['email'] ?? null,
            'email_verified' => $user['emailVerified'] ?? false,
            'name' => $user['displayName'] ?? null,
            'photo_url' => $user['photoUrl'] ?? null,
            'provider' => isset($user['providerUserInfo']) && count($user['providerUserInfo']) > 0 
                ? $user['providerUserInfo'][0]['providerId'] ?? 'password' 
                : 'password'
        ];
    }
    
    return null;
}

// Verify Firebase token
$firebase_user = verify_firebase_token($firebase_token, $secrets['firebase']['api_key']);

if (!$firebase_user || !$firebase_user['email']) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid Firebase token'
    ]);
    exit;
}

// Check if WordPress user exists by email
$wp_user = get_user_by('email', $firebase_user['email']);

if ($wp_user) {
    // Existing user - update Firebase UID and log in
    $user_id = $wp_user->ID;
    update_user_meta($user_id, 'firebase_uid', $firebase_user['uid']);
    
    // Update display name if provided and different
    if (!empty($firebase_user['name']) && $wp_user->display_name !== $firebase_user['name']) {
        wp_update_user([
            'ID' => $user_id,
            'display_name' => $firebase_user['name']
        ]);
    }
    
    // Update photo if available
    if (!empty($firebase_user['photo_url'])) {
        update_user_meta($user_id, 'firebase_photo_url', $firebase_user['photo_url']);
    }
    
} else {
    // New user - create WordPress account
    // Use email as username, generate random password (not used since Firebase handles auth)
    $random_password = wp_generate_password(32, true, true);
    
    $user_id = wp_create_user(
        $firebase_user['email'],
        $random_password,
        $firebase_user['email']
    );
    
    if (is_wp_error($user_id)) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $user_id->get_error_message()
        ]);
        exit;
    }
    
    // Update user info
    $display_name = $firebase_user['name'] ?? $additional_data['screen_name'] ?? $firebase_user['email'];
    $first_name = $additional_data['full_name'] ?? $firebase_user['name'] ?? '';
    
    wp_update_user([
        'ID' => $user_id,
        'display_name' => $display_name,
        'first_name' => $first_name,
    ]);
    
    // Save Firebase UID
    update_user_meta($user_id, 'firebase_uid', $firebase_user['uid']);
    
    // Save additional data from pledge form if provided
    if (!empty($additional_data['screen_name'])) {
        update_user_meta($user_id, 'screen_name', $additional_data['screen_name']);
    }
    if (!empty($additional_data['phone'])) {
        update_user_meta($user_id, 'phone', $additional_data['phone']);
    }
    if (!empty($additional_data['location_city'])) {
        update_user_meta($user_id, 'location_city', $additional_data['location_city']);
    }
    if (!empty($additional_data['location_state'])) {
        update_user_meta($user_id, 'location_state', $additional_data['location_state']);
    }
    
    // Social media
    if (!empty($additional_data['facebook_url'])) {
        update_user_meta($user_id, 'facebook_url', $additional_data['facebook_url']);
    }
    if (!empty($additional_data['instagram_handle'])) {
        update_user_meta($user_id, 'instagram_handle', $additional_data['instagram_handle']);
    }
    if (!empty($additional_data['twitter_handle'])) {
        update_user_meta($user_id, 'twitter_handle', $additional_data['twitter_handle']);
    }
    if (!empty($additional_data['tiktok_handle'])) {
        update_user_meta($user_id, 'tiktok_handle', $additional_data['tiktok_handle']);
    }
    if (!empty($additional_data['linkedin_url'])) {
        update_user_meta($user_id, 'linkedin_url', $additional_data['linkedin_url']);
    }
    if (!empty($additional_data['reddit_username'])) {
        update_user_meta($user_id, 'reddit_username', $additional_data['reddit_username']);
    }
    
    // Set default role
    $user = new WP_User($user_id);
    $user->set_role('subscriber');
    
    // Engagement tracking
    update_user_meta($user_id, 'pledge_date', current_time('mysql'));
    update_user_meta($user_id, 'badges_earned', json_encode(['pledge']));
    update_user_meta($user_id, 'resource_count', 0);
    update_user_meta($user_id, 'last_active', current_time('mysql'));
    
    // Create CRM contact if plugin is active
    if (function_exists('choose90_create_crm_contact')) {
        choose90_create_crm_contact(
            $user_id,
            $first_name,
            $firebase_user['email'],
            $additional_data['phone'] ?? '',
            $additional_data['location_city'] ?? '',
            $additional_data['location_state'] ?? ''
        );
    }
    
    // Send welcome email
    if (function_exists('choose90_send_welcome_email')) {
        $screen_name = $additional_data['screen_name'] ?? $display_name;
        choose90_send_welcome_email($user_id, $screen_name);
    }
    
    // Update photo if available
    if (!empty($firebase_user['photo_url'])) {
        update_user_meta($user_id, 'firebase_photo_url', $firebase_user['photo_url']);
    }
}

// Log user into WordPress
wp_set_current_user($user_id);
wp_set_auth_cookie($user_id, true); // Remember me = true

// Get user info
$user = wp_get_current_user();
$screen_name = get_user_meta($user_id, 'screen_name', true) ?: $user->display_name;

// Return success
echo json_encode([
    'success' => true,
    'user_id' => $user_id,
    'user' => [
        'id' => $user_id,
        'email' => $user->user_email,
        'display_name' => $user->display_name,
        'screen_name' => $screen_name
    ],
    'redirect' => home_url('/'),
    'is_new_user' => !$wp_user
]);
