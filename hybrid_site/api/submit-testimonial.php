<?php
/**
 * Choose90 Submit Testimonial API
 * Adds a new testimonial to the testimonials.json file
 */

header('Content-Type: application/json');

// CORS
$allowed_origins = [
    'https://choose90.org',
    'https://www.choose90.org',
    'http://localhost',
    'http://127.0.0.1'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
}

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Get input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
$name = trim($input['name'] ?? '');
$text = trim($input['text'] ?? '');

if (empty($name) || empty($text)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Name and text are required']);
    exit;
}

// Sanitize input
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

// Limit length
if (strlen($name) > 100) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Name must be 100 characters or less']);
    exit;
}

if (strlen($text) > 500) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Testimonial must be 500 characters or less']);
    exit;
}

// Find testimonials.json file
$possible_paths = [
    __DIR__ . '/../data/testimonials.json',
    __DIR__ . '/../../data/testimonials.json',
    $_SERVER['DOCUMENT_ROOT'] . '/data/testimonials.json',
    dirname(__DIR__) . '/data/testimonials.json',
];

$testimonials_file = null;
foreach ($possible_paths as $path) {
    if (file_exists($path)) {
        $testimonials_file = $path;
        break;
    }
}

// If file doesn't exist, create it in the most likely location
if (!$testimonials_file) {
    $data_dir = dirname(__DIR__) . '/data';
    if (!is_dir($data_dir)) {
        mkdir($data_dir, 0755, true);
    }
    $testimonials_file = $data_dir . '/testimonials.json';
}

// Load existing testimonials
$testimonials = [];
if (file_exists($testimonials_file)) {
    $testimonials_data = file_get_contents($testimonials_file);
    $testimonials = json_decode($testimonials_data, true) ?: [];
}

// Add new testimonial (will be approved manually or automatically)
$new_testimonial = [
    'name' => $name,
    'text' => $text,
    'date' => date('Y-m-d'),
    'approved' => true // Auto-approve for now, can be changed to false for moderation
];

// Add to beginning of array (most recent first)
array_unshift($testimonials, $new_testimonial);

// Limit to last 1000 testimonials
$testimonials = array_slice($testimonials, 0, 1000);

// Save testimonials
$result = file_put_contents($testimonials_file, json_encode($testimonials, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

if ($result === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Could not save testimonial']);
    exit;
}

// Return success
echo json_encode([
    'success' => true,
    'message' => 'Thank you for sharing your testimonial!',
    'testimonial' => $new_testimonial
]);
