<?php
/**
 * Choose90 Post Rewriting API Endpoint
 * 
 * Takes a social media post and rewrites it in a positive, uplifting way
 * while maintaining the original intent and message.
 * 
 * This is the core "muscle" feature that shows users the immediate impact of Choose90.
 */

header('Content-Type: application/json');

// CORS - allow requests from browser extension and PWA
$allowed_origins = [
    'https://choose90.org', 
    'https://www.choose90.org',
    'chrome-extension://',  // Chrome extensions
    'moz-extension://',    // Firefox extensions
    'extension://'         // Edge extensions
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$origin_allowed = false;

foreach ($allowed_origins as $allowed) {
    if (strpos($origin, $allowed) === 0 || $origin === $allowed) {
        header('Access-Control-Allow-Origin: ' . $origin);
        $origin_allowed = true;
        break;
    }
}

// For same-origin requests, allow
if (!$origin && !$origin_allowed) {
    // Same-origin request, allow it
    $origin_allowed = true;
}

if (!$origin_allowed && $origin) {
    http_response_code(403);
    echo json_encode(['error' => 'Origin not allowed']);
    exit;
}

header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Max-Age: 3600');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Load WordPress for potential authentication (optional - can be public)
$wp_load_paths = [
    __DIR__ . '/../../../../wp-load.php',
    __DIR__ . '/../../../wp-load.php',
    dirname(dirname(dirname(dirname(__DIR__)))) . '/wp-load.php',
    $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php'
];

$wp_loaded = false;
foreach ($wp_load_paths as $wp_path) {
    $real_path = realpath($wp_path);
    if ($real_path && file_exists($real_path)) {
        require_once($real_path);
        $wp_loaded = true;
        break;
    }
}

// Rate limiting
session_start();
$rate_limit_key = 'rewrite_requests_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$rate_limit_file = sys_get_temp_dir() . '/choose90_rewrite_limit_' . md5($rate_limit_key) . '.txt';

$current_time = time();
$rate_limit_window = 3600; // 1 hour
$max_requests = 50; // Max 50 rewrites per hour per IP

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
$original_text = trim($input['text'] ?? '');
$platform = trim($input['platform'] ?? 'general'); // twitter, facebook, linkedin, general
$tone = trim($input['tone'] ?? 'positive'); // positive, uplifting, constructive

// Validate input
if (empty($original_text)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required field: text']);
    exit;
}

// Length limits
if (strlen($original_text) > 2000) {
    http_response_code(400);
    echo json_encode(['error' => 'Text too long. Maximum 2000 characters.']);
    exit;
}

if (strlen($original_text) < 10) {
    http_response_code(400);
    echo json_encode(['error' => 'Text too short. Minimum 10 characters.']);
    exit;
}

// Sanitize input
$original_text = htmlspecialchars($original_text, ENT_QUOTES, 'UTF-8');
$platform = htmlspecialchars($platform, ENT_QUOTES, 'UTF-8');
$tone = htmlspecialchars($tone, ENT_QUOTES, 'UTF-8');

// Load secrets - try multiple possible paths
// On server: api/rewrite-post.php is at server_root/api/, so secrets.json should be at server_root/secrets.json (one level up)
// In local dev: hybrid_site/api/rewrite-post.php, so secrets.json is at project_root/secrets.json (two levels up)
$possible_paths = [
    __DIR__ . '/../secrets.json',     // From api/ to server root (server deployment)
    __DIR__ . '/../../secrets.json',  // From hybrid_site/api/ to project root (local dev)
    $_SERVER['DOCUMENT_ROOT'] . '/secrets.json',  // Document root
    dirname(__DIR__) . '/secrets.json',  // Same as first, but explicit
];

$secrets_path = null;
foreach ($possible_paths as $path) {
    $real_path = realpath($path);
    if ($real_path && file_exists($real_path)) {
        $secrets_path = $real_path;
        break;
    }
}

if (!$secrets_path) {
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
$prompt = "You are a helpful assistant for Choose90.org, a movement encouraging people to make 90% of their online influence positive, uplifting, and constructive.

The user has written a social media post and wants you to rewrite it in a more positive way while maintaining the original message and intent.

Original Post:
\"{$original_text}\"

Platform: {$platform}
Desired Tone: {$tone}

Instructions:
1. Keep the core message and facts intact - don't change what the user is trying to say
2. Remove negativity, complaints, or conflict where possible
3. Reframe in a constructive, uplifting way
4. Maintain authenticity - don't make it fake or overly cheerful
5. Keep it natural and conversational
6. If the post is already positive, just polish it slightly
7. Preserve any important details, links, or hashtags
8. Match the original length if possible (or slightly shorter)
9. Don't add emojis unless the original had them
10. Keep the same voice and style as much as possible

Return ONLY the rewritten post text. Do not include explanations, markdown, or any other formatting. Just the rewritten post.";

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
                'content' => 'You are a helpful assistant for Choose90.org. You help people rewrite their social media posts to be more positive and uplifting while maintaining authenticity and the original message.'
            ),
            array(
                'role' => 'user',
                'content' => $prompt
            )
        ),
        'max_tokens' => 500,
        'temperature' => 0.7 // Slightly creative but consistent
    )),
    CURLOPT_TIMEOUT => 15,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2
));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
$curl_errno = curl_errno($ch);
curl_close($ch);

// Handle errors
if ($curl_error) {
    http_response_code(500);
    echo json_encode(['error' => 'API connection failed: ' . $curl_error]);
    exit;
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

$rewritten_text = trim($response_data['choices'][0]['message']['content']);

// Clean up any markdown or formatting that AI might have added
$rewritten_text = preg_replace('/^```[\w]*\n?/m', '', $rewritten_text);
$rewritten_text = preg_replace('/\n?```$/m', '', $rewritten_text);
$rewritten_text = trim($rewritten_text);

// Return success
echo json_encode([
    'success' => true,
    'original' => $original_text,
    'rewritten' => $rewritten_text,
    'platform' => $platform,
    'tone' => $tone,
    'character_count' => [
        'original' => strlen($original_text),
        'rewritten' => strlen($rewritten_text)
    ]
]);


