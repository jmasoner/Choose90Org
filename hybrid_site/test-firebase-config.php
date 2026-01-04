<?php
/**
 * Test script to diagnose Firebase config loading
 * DELETE THIS FILE AFTER USE - IT EXPOSES SENSITIVE PATH INFO
 */

// Load WordPress
$wp_load_paths = [
    __DIR__ . '/../../../../wp-load.php',
    __DIR__ . '/../../../wp-load.php',
    $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php',
];

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    $real_path = realpath($path);
    if ($real_path && file_exists($real_path)) {
        require_once($real_path);
        $wp_loaded = true;
        break;
    }
}

header('Content-Type: text/plain');

echo "=== Firebase Config Diagnostic ===\n\n";

// Test paths from components directory
$test_paths = [
    'ABSPATH (if defined)' => defined('ABSPATH') ? ABSPATH . 'secrets.json' : 'ABSPATH not defined',
    '__DIR__ from test file' => __DIR__,
    'Components dir' => dirname(__FILE__) . '/components',
    'Path 1: components/../secrets.json' => dirname(__FILE__) . '/components/../secrets.json',
    'Path 2: components/../../secrets.json' => dirname(__FILE__) . '/components/../../secrets.json',
    'Path 3: DOCUMENT_ROOT/secrets.json' => $_SERVER['DOCUMENT_ROOT'] . '/secrets.json',
    'Path 4: Two levels up' => dirname(dirname(__FILE__)) . '/secrets.json',
];

echo "Testing paths:\n";
foreach ($test_paths as $label => $path) {
    if ($path === 'ABSPATH not defined') {
        echo "$label: $path\n";
        continue;
    }
    $real = realpath($path);
    $exists = $real && file_exists($real);
    $readable = $exists && is_readable($real);
    echo "$label:\n";
    echo "  Path: $path\n";
    echo "  Real: " . ($real ?: 'NOT FOUND') . "\n";
    echo "  Exists: " . ($exists ? 'YES' : 'NO') . "\n";
    echo "  Readable: " . ($readable ? 'YES' : 'NO') . "\n";
    
    if ($readable) {
        $content = file_get_contents($real);
        $json = json_decode($content, true);
        $has_firebase = $json && isset($json['firebase']);
        echo "  Valid JSON: " . ($json !== null ? 'YES' : 'NO') . "\n";
        echo "  Has firebase key: " . ($has_firebase ? 'YES' : 'NO') . "\n";
        if ($json && isset($json['firebase'])) {
            echo "  Firebase keys: " . implode(', ', array_keys($json['firebase'])) . "\n";
        }
    }
    echo "\n";
}

// Test the actual firebase-config.php include
echo "\n=== Testing firebase-config.php include ===\n";
$config_path = dirname(__FILE__) . '/components/firebase-config.php';
if (file_exists($config_path)) {
    echo "firebase-config.php exists at: $config_path\n";
    // Don't actually include it here to avoid output
} else {
    echo "firebase-config.php NOT FOUND at: $config_path\n";
}

echo "\n=== Recommendation ===\n";
echo "secrets.json should be at the server root with a 'firebase' key containing:\n";
echo "- api_key\n";
echo "- auth_domain\n";
echo "- project_id\n";
echo "- storage_bucket\n";
echo "- messaging_sender_id\n";
echo "- app_id\n";
