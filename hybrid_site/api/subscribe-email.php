<?php
/**
 * Choose90 Email Subscription API
 * Integrates with Mailchimp or ConvertKit
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

// Get request data
$input = json_decode(file_get_contents('php://input'), true);
$email = trim($input['email'] ?? '');
$name = trim($input['name'] ?? '');

// Validate
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Valid email is required']);
    exit;
}

// Load secrets
$secrets_path = __DIR__ . '/../../secrets.json';
$secrets = [];

if (file_exists($secrets_path)) {
    $secrets = json_decode(file_get_contents($secrets_path), true) ?: [];
}

// Try Mailchimp first, then ConvertKit
$subscribed = false;
$error = null;

// Mailchimp integration
if (isset($secrets['email']['mailchimp']['api_key']) && !empty($secrets['email']['mailchimp']['api_key'])) {
    $api_key = $secrets['email']['mailchimp']['api_key'];
    $list_id = $secrets['email']['mailchimp']['list_id'] ?? '';
    $server = explode('-', $api_key)[1] ?? 'us1';
    
    $mailchimp_url = "https://{$server}.api.mailchimp.com/3.0/lists/{$list_id}/members";
    
    $data = [
        'email_address' => $email,
        'status' => 'subscribed'
    ];
    
    if ($name) {
        $name_parts = explode(' ', $name, 2);
        $data['merge_fields'] = [
            'FNAME' => $name_parts[0],
            'LNAME' => $name_parts[1] ?? ''
        ];
    }
    
    $ch = curl_init($mailchimp_url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: apikey ' . $api_key,
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 10
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code === 200 || $http_code === 201) {
        $subscribed = true;
    } else {
        $error = 'Mailchimp subscription failed';
    }
}

// ConvertKit integration (fallback)
if (!$subscribed && isset($secrets['email']['convertkit']['api_key']) && !empty($secrets['email']['convertkit']['api_key'])) {
    $api_key = $secrets['email']['convertkit']['api_key'];
    $form_id = $secrets['email']['convertkit']['form_id'] ?? '';
    
    $convertkit_url = "https://api.convertkit.com/v3/forms/{$form_id}/subscribe";
    
    $data = [
        'api_key' => $api_key,
        'email' => $email
    ];
    
    if ($name) {
        $data['first_name'] = $name;
    }
    
    $ch = curl_init($convertkit_url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 10
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code === 200) {
        $subscribed = true;
    } else {
        $error = 'ConvertKit subscription failed';
    }
}

// Fallback: Store locally if no service configured
if (!$subscribed && !isset($secrets['email']['mailchimp']['api_key']) && !isset($secrets['email']['convertkit']['api_key'])) {
    $subscribers_file = __DIR__ . '/../../subscribers.json';
    $subscribers = [];
    
    if (file_exists($subscribers_file)) {
        $subscribers = json_decode(file_get_contents($subscribers_file), true) ?: [];
    }
    
    // Check if already subscribed
    $exists = false;
    foreach ($subscribers as $sub) {
        if ($sub['email'] === $email) {
            $exists = true;
            break;
        }
    }
    
    if (!$exists) {
        $subscribers[] = [
            'email' => $email,
            'name' => $name,
            'date' => date('c'),
            'timestamp' => time()
        ];
        
        file_put_contents($subscribers_file, json_encode($subscribers, JSON_PRETTY_PRINT));
        $subscribed = true;
    } else {
        $subscribed = true; // Already subscribed, treat as success
    }
}

if ($subscribed) {
    echo json_encode([
        'success' => true,
        'message' => 'Successfully subscribed!'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $error || 'Subscription failed'
    ]);
}


