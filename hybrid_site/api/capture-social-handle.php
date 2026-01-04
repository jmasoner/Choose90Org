<?php
/**
 * Capture Social Media Handle for CRM Integration
 * Stores social media handles when users share their pledge
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['platform']) || !isset($input['handle'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Missing required fields'
    ]);
    exit;
}

$platform = strtolower(trim($input['platform']));
$handle = trim($input['handle']);
$page = isset($input['page']) ? $input['page'] : 'unknown';
$url = isset($input['url']) ? $input['url'] : '';

// Validate platform
$allowedPlatforms = ['twitter', 'x', 'facebook', 'linkedin', 'instagram', 'tiktok', 'youtube'];
if (!in_array($platform, $allowedPlatforms)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid platform'
    ]);
    exit;
}

// Clean handle (remove @ if present, keep it clean)
$handle = ltrim($handle, '@');
$handle = preg_replace('/[^a-zA-Z0-9_.-]/', '', $handle);

if (empty($handle)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid handle format'
    ]);
    exit;
}

// Create data directory if it doesn't exist
$dataDir = __DIR__ . '/../data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// File to store social handles (CSV format for easy import to CRM)
$socialHandlesFile = $dataDir . '/social-handles.csv';

// Check if file exists, if not create with headers
$fileExists = file_exists($socialHandlesFile);
$file = fopen($socialHandlesFile, 'a');

if (!$file) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Could not save data'
    ]);
    exit;
}

// Write header if new file
if (!$fileExists) {
    fputcsv($file, [
        'timestamp',
        'platform',
        'handle',
        'page',
        'url',
        'ip_address'
    ]);
}

// Get IP address (respecting proxies)
$ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (strpos($ipAddress, ',') !== false) {
    $ipAddress = trim(explode(',', $ipAddress)[0]);
}

// Write data
fputcsv($file, [
    date('Y-m-d H:i:s'),
    $platform,
    $handle,
    $page,
    $url,
    $ipAddress
]);

fclose($file);

// Also store in JSON format for easier programmatic access
$jsonFile = $dataDir . '/social-handles.json';
$handles = [];

if (file_exists($jsonFile)) {
    $handles = json_decode(file_get_contents($jsonFile), true) ?: [];
}

$handles[] = [
    'timestamp' => date('Y-m-d H:i:s'),
    'platform' => $platform,
    'handle' => $handle,
    'page' => $page,
    'url' => $url,
    'ip_address' => $ipAddress
];

file_put_contents($jsonFile, json_encode($handles, JSON_PRETTY_PRINT));

// Return success
echo json_encode([
    'success' => true,
    'message' => 'Social handle saved successfully',
    'data' => [
        'platform' => $platform,
        'handle' => $handle
    ]
]);


