<?php
/**
 * Choose90 Content Generator API
 * 
 * Generates positive posts and quotes using AI
 */

header('Content-Type: application/json');

// CORS
$allowed_origins = [
    'https://choose90.org',
    'https://www.choose90.org'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
}

header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Rate limiting
session_start();
$rate_limit_key = 'content_gen_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$rate_limit_file = sys_get_temp_dir() . '/choose90_content_gen_' . md5($rate_limit_key) . '.txt';

$current_time = time();
$rate_limit_window = 3600;
$max_requests = 30;

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
$type = $input['type'] ?? ''; // 'post' or 'quote'
$topic = trim($input['topic'] ?? '');
$tone = trim($input['tone'] ?? 'uplifting');
$platform = trim($input['platform'] ?? 'general');

// Validate
if (!in_array($type, ['post', 'quote'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid type. Must be "post" or "quote"']);
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

// Check if DeepSeek is enabled
if (!isset($secrets['api_keys']['deepseek']['enabled']) || !$secrets['api_keys']['deepseek']['enabled']) {
    http_response_code(503);
    echo json_encode(['error' => 'AI service not available']);
    exit;
}

$api_key = $secrets['api_keys']['deepseek']['api_key'];
$base_url = $secrets['api_keys']['deepseek']['base_url'] ?? 'https://api.deepseek.com/v1';
$model = $secrets['api_keys']['deepseek']['model'] ?? 'deepseek-chat';

// Build prompt based on type
if ($type === 'post') {
    $prompt = "You are a helpful assistant for Choose90.org, a movement encouraging people to make 90% of their online influence positive, uplifting, and constructive.

Create a positive, uplifting social media post about: {$topic}

Requirements:
- Tone: {$tone}
- Platform: {$platform}
- Length: Appropriate for the platform (Twitter/X: ~280 chars, Facebook/LinkedIn: 2-3 sentences, Instagram: 1-2 sentences)
- Include #Choose90 hashtag
- Be authentic and genuine, not fake or overly cheerful
- Focus on positivity, encouragement, or constructive sharing
- Make it feel natural and conversational

Return ONLY the post text. No explanations, no markdown, just the post.";
} else {
    // Quote generation
    $topic_text = $topic ? "about {$topic}" : "general positive and uplifting";
    $prompt = "You are a helpful assistant for Choose90.org, a movement encouraging people to make 90% of their online influence positive, uplifting, and constructive.

Generate a short, inspiring quote {$topic_text}. The quote should be:
- Positive and uplifting
- 1-2 sentences maximum
- Authentic and meaningful
- Suitable for sharing on social media

Return the quote in this format:
QUOTE: [the quote text]
AUTHOR: [author name, or 'Choose90' if no specific author]

Example:
QUOTE: Be the reason someone feels seen, heard, and valued today.
AUTHOR: Choose90";
}

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
                'content' => 'You are a helpful assistant for Choose90.org. You help people create positive, uplifting content for social media.'
            ),
            array(
                'role' => 'user',
                'content' => $prompt
            )
        ),
        'max_tokens' => 300,
        'temperature' => 0.8
    )),
    CURLOPT_TIMEOUT => 15,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2
));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($curl_error || $http_code !== 200) {
    http_response_code(500);
    echo json_encode(['error' => 'AI service unavailable']);
    exit;
}

$response_data = json_decode($response, true);
if (!isset($response_data['choices'][0]['message']['content'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Invalid API response']);
    exit;
}

$content = trim($response_data['choices'][0]['message']['content']);

// Parse response
if ($type === 'post') {
    // Clean up post
    $content = preg_replace('/^```[\w]*\n?/m', '', $content);
    $content = preg_replace('/\n?```$/m', '', $content);
    $content = trim($content);
    
    echo json_encode([
        'success' => true,
        'content' => $content,
        'type' => 'post'
    ]);
} else {
    // Parse quote
    $quote = '';
    $author = 'Choose90';
    
    if (preg_match('/QUOTE:\s*(.+?)(?:\n|AUTHOR:|$)/is', $content, $matches)) {
        $quote = trim($matches[1]);
    } else if (preg_match('/AUTHOR:\s*(.+?)$/is', $content, $matches)) {
        $author = trim($matches[1]);
    } else {
        // Fallback: use entire content as quote
        $quote = $content;
    }
    
    if (preg_match('/AUTHOR:\s*(.+?)$/is', $content, $matches)) {
        $author = trim($matches[1]);
    }
    
    // Clean up
    $quote = preg_replace('/^["\']|["\']$/', '', $quote);
    $quote = trim($quote);
    
    echo json_encode([
        'success' => true,
        'quote' => $quote,
        'author' => $author,
        'type' => 'quote'
    ]);
}


