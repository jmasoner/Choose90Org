<?php
/**
 * Choose90 Phone Setup AI Endpoint
 * 
 * Provides AI-powered, device-specific instructions for phone setup optimization
 * Uses DeepSeek API to generate step-by-step guides based on user's device
 * 
 * Security: This endpoint should be protected and rate-limited in production
 */

header('Content-Type: application/json');
// Production CORS - restrict to choose90.org domain
$allowed_origins = ['https://choose90.org', 'https://www.choose90.org'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
} else {
    // For same-origin requests, don't set CORS header
    if ($origin) {
        http_response_code(403);
        echo json_encode(['error' => 'Origin not allowed']);
        exit;
    }
}
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Max-Age: 3600');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Check WordPress login status
// Load WordPress for authentication check
$wp_load_paths = [
    __DIR__ . '/../../../../wp-load.php',  // Standard WordPress location
    __DIR__ . '/../../../wp-load.php',     // Alternative location
    dirname(dirname(dirname(dirname(__DIR__)))) . '/wp-load.php'  // Relative to api/ folder
];

$wp_loaded = false;
foreach ($wp_load_paths as $wp_path) {
    if (file_exists($wp_path)) {
        require_once($wp_path);
        $wp_loaded = true;
        break;
    }
}

// Authentication check - be more lenient to avoid false negatives
$is_authenticated = false;

if ($wp_loaded) {
    // Check if user is logged in via WordPress
    if (function_exists('is_user_logged_in') && is_user_logged_in()) {
        $is_authenticated = true;
    }
}

// Fallback: Check for WordPress cookies (for static HTML pages)
if (!$is_authenticated) {
    foreach ($_COOKIE as $key => $value) {
        // Check for WordPress logged-in cookie
        if (strpos($key, 'wordpress_logged_in') !== false && !empty($value) && strlen($value) > 20) {
            // Cookie exists and looks valid - allow access
            $is_authenticated = true;
            break;
        }
    }
}

// Only block if we're certain they're NOT logged in
if (!$is_authenticated) {
    // Log for debugging (remove in production if needed)
    error_log('Phone Setup AI: Authentication failed. Cookies: ' . json_encode(array_keys($_COOKIE)));
    
    http_response_code(401);
    echo json_encode([
        'error' => 'Authentication required',
        'message' => 'Please sign up or log in to use this feature.',
        'login_url' => $wp_loaded && function_exists('home_url') ? home_url('/pledge/') : '/pledge/',
        'debug' => [
            'wp_loaded' => $wp_loaded,
            'cookies_found' => array_keys($_COOKIE)
        ]
    ]);
    exit;
}

// Load secrets
$secrets_path = __DIR__ . '/../../secrets.json';
if (!file_exists($secrets_path)) {
    http_response_code(500);
    echo json_encode(['error' => 'Configuration not found']);
    exit;
}

$secrets = json_decode(file_get_contents($secrets_path), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    echo json_encode(['error' => 'Invalid configuration']);
    exit;
}

// Simple rate limiting (in-memory, resets on server restart)
// For production, consider using Redis or database-based rate limiting
session_start();
$rate_limit_key = 'api_requests_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$rate_limit_file = sys_get_temp_dir() . '/choose90_rate_limit_' . md5($rate_limit_key) . '.txt';

$current_time = time();
$rate_limit_window = 3600; // 1 hour
$max_requests = 20; // Max 20 requests per hour per IP

if (file_exists($rate_limit_file)) {
    $rate_data = json_decode(file_get_contents($rate_limit_file), true);
    if ($rate_data && ($current_time - $rate_data['first_request']) < $rate_limit_window) {
        if ($rate_data['count'] >= $max_requests) {
            http_response_code(429);
            echo json_encode(['error' => 'Rate limit exceeded. Please try again later.']);
            exit;
        }
        $rate_data['count']++;
    } else {
        $rate_data = ['first_request' => $current_time, 'count' => 1];
    }
} else {
    $rate_data = ['first_request' => $current_time, 'count' => 1];
}

file_put_contents($rate_limit_file, json_encode($rate_data));

// Get request data
$input = json_decode(file_get_contents('php://input'), true);
$brand = trim($input['brand'] ?? '');
$model = trim($input['model'] ?? '');
$software_version = trim($input['software_version'] ?? '');
$step = trim($input['step'] ?? '');
$task = trim($input['task'] ?? '');

// Validate input
if (empty($brand) || empty($model) || empty($step) || empty($task)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields: brand, model, step, task']);
    exit;
}

// Sanitize input (prevent injection)
$brand = htmlspecialchars($brand, ENT_QUOTES, 'UTF-8');
$model = htmlspecialchars($model, ENT_QUOTES, 'UTF-8');
$software_version = htmlspecialchars($software_version, ENT_QUOTES, 'UTF-8');
$step = htmlspecialchars($step, ENT_QUOTES, 'UTF-8');
$task = htmlspecialchars($task, ENT_QUOTES, 'UTF-8');

// Length limits
if (strlen($brand) > 50 || strlen($model) > 100 || strlen($software_version) > 50 || strlen($task) > 500) {
    http_response_code(400);
    echo json_encode(['error' => 'Input too long']);
    exit;
}

// Check if DeepSeek is enabled
if (!isset($secrets['api_keys']['deepseek']['enabled']) || !$secrets['api_keys']['deepseek']['enabled']) {
    http_response_code(503);
    echo json_encode(['error' => 'AI service not available']);
    exit;
}

$api_key = $secrets['api_keys']['deepseek']['api_key'];
$base_url = $secrets['api_keys']['deepseek']['base_url'] ?? 'https://api.deepseek.com/v1';
$model = $secrets['api_keys']['deepseek']['model'] ?? 'deepseek-chat';

// Build the AI prompt
$prompt = "You are a helpful assistant for Choose90.org, a movement encouraging people to post more positive and uplifting content on social media.

The user needs step-by-step instructions for setting up their phone. Provide clear, accurate, device-specific instructions.

Device Information:
- Brand: {$input['brand']}
- Model: {$input['model']}
- Software Version: {$input['software_version']}

Task: {$task}

Instructions:
1. Provide exact step-by-step instructions for this specific device and software version
2. Use clear, numbered steps
3. Include the exact menu paths (e.g., 'Settings → Display → Screen Time')
4. Be specific about button names and locations
5. If the feature doesn't exist on this device/version, explain what alternative options are available
6. Keep the tone friendly and encouraging, matching Choose90's positive mission
7. Format the response in clear, easy-to-follow steps
8. If you're unsure about a specific detail, say so rather than guessing

Format your response as a clear, numbered list of steps. Do not include any markdown formatting or code blocks.";

// Make API request
$ch = curl_init($base_url . '/chat/completions');
curl_setopt_array($ch, array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ),
    CURLOPT_POSTFIELDS => json_encode(array(
        'model' => $model,
        'messages' => array(
            array(
                'role' => 'system',
                'content' => 'You are a helpful technical assistant for Choose90.org. Provide clear, accurate, step-by-step instructions for phone setup tasks. Always be specific to the device model and software version provided.'
            ),
            array(
                'role' => 'user',
                'content' => $prompt
            )
        ),
        'max_tokens' => 500,
        'temperature' => 0.3 // Lower temperature for more consistent, factual responses
    )),
    CURLOPT_TIMEOUT => 15,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2
));

// Try to use system certificates first, then fallback to no verification if needed
// Don't set CURLOPT_CAINFO unless the file exists - let PHP use system certs

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
$curl_errno = curl_errno($ch);
curl_close($ch);

// Handle errors
if ($curl_error) {
    // In production, fail hard on SSL errors to avoid insecure fallbacks
    $is_ssl_error = ($curl_errno === CURLE_SSL_PEER_CERTIFICATE ||
                     $curl_errno === CURLE_SSL_CACERT ||
                     stripos($curl_error, 'SSL') !== false ||
                     stripos($curl_error, 'certificate') !== false);

    // Optional dev-only insecure fallback, enabled only when this constant is defined and true
    if ($is_ssl_error && defined('CHOOSE90_DEV_ALLOW_INSECURE_SSL') && CHOOSE90_DEV_ALLOW_INSECURE_SSL) {
        $ch = curl_init($base_url . '/chat/completions');
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $api_key
            ),
            CURLOPT_POSTFIELDS => json_encode(array(
                'model' => $model,
                'messages' => array(
                    array(
                        'role' => 'system',
                        'content' => 'You are a helpful technical assistant for Choose90.org. Provide clear, accurate, step-by-step instructions for phone setup tasks.'
                    ),
                    array(
                        'role' => 'user',
                        'content' => $prompt
                    )
                ),
                'max_tokens' => 500,
                'temperature' => 0.3
            )),
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'API connection failed: ' . $curl_error]);
        exit;
    }
}

if ($http_code !== 200) {
    http_response_code($http_code);
    $error_data = json_decode($response, true);
    echo json_encode([
        'error' => 'API request failed',
        'details' => $error_data['error']['message'] ?? 'Unknown error',
        'code' => $http_code
    ]);
    exit;
}

// Parse response
$response_data = json_decode($response, true);
if (!isset($response_data['choices'][0]['message']['content'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Invalid API response']);
    exit;
}

$instructions = $response_data['choices'][0]['message']['content'];

// Return success
echo json_encode([
    'success' => true,
    'step' => $input['step'],
    'task' => $task,
    'instructions' => trim($instructions),
    'device' => [
        'brand' => $input['brand'],
        'model' => $input['model'],
        'software_version' => $input['software_version']
    ]
]);

