<?php
/**
 * Choose90 Get Pledge Count API
 * Returns current pledge count
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

// Get count
$count_file = __DIR__ . '/../../pledge-count.txt';

$count = 3363; // Base count
if (file_exists($count_file)) {
    $count_data = file_get_contents($count_file);
    $count = (int)trim($count_data);
}

echo json_encode([
    'success' => true,
    'count' => $count
]);


