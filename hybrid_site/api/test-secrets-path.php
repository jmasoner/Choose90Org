<?php
/**
 * Test script to find where secrets.json should be located
 * DELETE THIS FILE AFTER TESTING - IT EXPOSES PATH INFORMATION
 */

header('Content-Type: application/json');

// Test multiple possible paths
$possible_paths = [
    '1. From api/ to root (../../secrets.json)' => __DIR__ . '/../../secrets.json',
    '2. From api/ to hybrid_site/ (../secrets.json)' => __DIR__ . '/../secrets.json',
    '3. Document root' => $_SERVER['DOCUMENT_ROOT'] . '/secrets.json',
    '4. Two levels up from DOCUMENT_ROOT' => dirname(dirname($_SERVER['DOCUMENT_ROOT'])) . '/secrets.json',
];

$results = [];
foreach ($possible_paths as $label => $path) {
    $exists = file_exists($path);
    $results[$label] = [
        'path' => $path,
        'exists' => $exists,
        'readable' => $exists ? is_readable($path) : false
    ];
}

$output = [
    'current_directory' => __DIR__,
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'not set',
    'file_exists_checks' => $results,
    'recommendation' => null
];

// Find which path exists
foreach ($results as $label => $info) {
    if ($info['exists'] && $info['readable']) {
        $output['recommendation'] = [
            'use_this_path' => $info['path'],
            'label' => $label
        ];
        break;
    }
}

echo json_encode($output, JSON_PRETTY_PRINT);