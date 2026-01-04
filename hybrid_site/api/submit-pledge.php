<?php
/**
 * Choose90 Pledge Submission API
 * Stores pledges and increments counter
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

// Load WordPress
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

// Get request data
$input = json_decode(file_get_contents('php://input'), true);
$name = trim($input['name'] ?? '');
$message = trim($input['message'] ?? '');
$isPublic = isset($input['public']) ? (bool)$input['public'] : true;

// Validate
if (empty($name)) {
    http_response_code(400);
    echo json_encode(['error' => 'Name is required']);
    exit;
}

if (strlen($name) > 50) {
    http_response_code(400);
    echo json_encode(['error' => 'Name too long']);
    exit;
}

if (strlen($message) > 200) {
    http_response_code(400);
    echo json_encode(['error' => 'Message too long']);
    exit;
}

// Sanitize
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

// Store pledge (using WordPress options API or file-based storage)
$pledges_file = __DIR__ . '/../../pledges.json';
$count_file = __DIR__ . '/../../pledge-count.txt';

// Read current count
$current_count = 3363; // Base count
if (file_exists($count_file)) {
    $count_data = file_get_contents($count_file);
    $current_count = (int)trim($count_data);
}

// Increment count
$new_count = $current_count + 1;
file_put_contents($count_file, $new_count);

// Store pledge if public
$pledge_data = null;
if ($isPublic) {
    $pledges = [];
    if (file_exists($pledges_file)) {
        $pledges_data = file_get_contents($pledges_file);
        $pledges = json_decode($pledges_data, true) ?: [];
    }
    
    // Add new pledge
    $pledge = [
        'id' => uniqid(),
        'name' => $name,
        'message' => $message,
        'date' => date('c'),
        'timestamp' => time()
    ];
    
    // Add to beginning (most recent first)
    array_unshift($pledges, $pledge);
    
    // Keep only last 100 pledges
    $pledges = array_slice($pledges, 0, 100);
    
    // Save
    file_put_contents($pledges_file, json_encode($pledges, JSON_PRETTY_PRINT));
    $pledge_data = $pledge;
}

// Return success
echo json_encode([
    'success' => true,
    'count' => $new_count,
    'pledge' => $pledge_data,
    'message' => 'Pledge added successfully'
]);


