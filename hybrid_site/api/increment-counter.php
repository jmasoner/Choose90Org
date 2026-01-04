<?php
/**
 * Choose90 Counter Auto-Increment
 * 
 * Increments the counter based on time of day:
 * - 13 per hour between 8am-8pm
 * - 8 per hour between 8pm-8am
 * 
 * This should be called hourly via cron job or scheduled task
 */

// Prevent direct browser access (optional - can be removed if needed for testing)
// if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['cron'])) {
//     http_response_code(403);
//     exit('Forbidden');
// }

// Get current hour (24-hour format)
$current_hour = (int)date('H');
$current_timestamp = time();

// Determine increment based on time of day
// 8am-8pm (08:00-20:00) = +13 per hour
// 8pm-8am (20:00-08:00) = +8 per hour
if ($current_hour >= 8 && $current_hour < 20) {
    $increment = 13;
} else {
    $increment = 8;
}

// Find pledge-count.txt file
$possible_paths = [
    __DIR__ . '/../../pledge-count.txt',
    __DIR__ . '/../pledge-count.txt',
    $_SERVER['DOCUMENT_ROOT'] . '/pledge-count.txt',
    dirname(__DIR__) . '/pledge-count.txt',
];

$count_file = null;
foreach ($possible_paths as $path) {
    if (file_exists($path)) {
        $count_file = $path;
        break;
    }
}

// If file doesn't exist, create it with base count
if (!$count_file) {
    $count_file = dirname(__DIR__) . '/pledge-count.txt';
    // Start with base count if file doesn't exist
    $base_count = 3363;
    file_put_contents($count_file, $base_count);
    $current_count = $base_count;
} else {
    // Read current count
    $current_count = (int)trim(file_get_contents($count_file));
    if ($current_count < 3363) {
        $current_count = 3363; // Ensure minimum base count
    }
}

// Increment count
$new_count = $current_count + $increment;

// Save updated count
file_put_contents($count_file, $new_count);

// Log the increment (optional - for tracking)
$log_file = dirname(__DIR__) . '/data/counter-increments.log';
$log_dir = dirname($log_file);
if (!is_dir($log_dir)) {
    mkdir($log_dir, 0755, true);
}
$log_entry = date('Y-m-d H:i:s') . " | Hour: $current_hour | Increment: +$increment | Count: $current_count -> $new_count\n";
file_put_contents($log_file, $log_entry, FILE_APPEND);

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'increment' => $increment,
    'previous_count' => $current_count,
    'new_count' => $new_count,
    'hour' => $current_hour,
    'timestamp' => date('Y-m-d H:i:s')
]);
