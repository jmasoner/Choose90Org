<?php
/**
 * Choose90 Get Testimonials API
 * Returns approved testimonials for display
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

// Load testimonials
$testimonials = [];
if ($testimonials_file && file_exists($testimonials_file)) {
    $testimonials_data = file_get_contents($testimonials_file);
    $testimonials = json_decode($testimonials_data, true) ?: [];
}

// Filter to only approved testimonials
$approved_testimonials = array_filter($testimonials, function($testimonial) {
    return isset($testimonial['approved']) && $testimonial['approved'] === true;
});

// Convert back to indexed array
$approved_testimonials = array_values($approved_testimonials);

// Return testimonials
echo json_encode([
    'success' => true,
    'testimonials' => $approved_testimonials,
    'count' => count($approved_testimonials)
]);
