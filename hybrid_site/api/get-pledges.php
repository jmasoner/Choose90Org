<?php
/**
 * Choose90 Get Pledges API
 * Returns public pledges for the wall
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

// Get pledges
$pledges_file = __DIR__ . '/../../pledges.json';
$count_file = __DIR__ . '/../../pledge-count.txt';

$pledges = [];
if (file_exists($pledges_file)) {
    $pledges_data = file_get_contents($pledges_file);
    $pledges = json_decode($pledges_data, true) ?: [];
}

// Get count
$count = 3363; // Base count
if (file_exists($count_file)) {
    $count_data = file_get_contents($count_file);
    $count = (int)trim($count_data);
}

// Limit to 20 most recent
$pledges = array_slice($pledges, 0, 20);

echo json_encode([
    'success' => true,
    'pledges' => $pledges,
    'total_count' => $count
]);


