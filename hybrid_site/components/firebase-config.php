<?php
/**
 * Firebase Configuration Loader
 * 
 * Loads Firebase config from secrets.json and outputs it as JavaScript
 * Include this in pages that need Firebase Auth
 */

// Load secrets.json - try multiple possible paths
$secrets_paths = [];

// Add ABSPATH if defined (WordPress root)
if (defined('ABSPATH')) {
    $secrets_paths[] = ABSPATH . 'secrets.json';
}

// Add paths relative to this file's location
$secrets_paths[] = __DIR__ . '/../../secrets.json';  // From components/ to server root (most likely)
$secrets_paths[] = dirname(dirname(__DIR__)) . '/secrets.json';  // Same as above, explicit
$secrets_paths[] = $_SERVER['DOCUMENT_ROOT'] . '/secrets.json';  // Document root
$secrets_paths[] = dirname(dirname(dirname(__DIR__))) . '/secrets.json';  // Three levels up (if components is nested)

// Also try one level up (if secrets.json is in hybrid_site/)
$secrets_paths[] = __DIR__ . '/../secrets.json';
$secrets_paths[] = dirname(__DIR__) . '/secrets.json';

// Remove duplicates and invalid paths
$secrets_paths = array_unique(array_filter($secrets_paths));

$secrets = null;
foreach ($secrets_paths as $path) {
    if (empty($path)) continue;
    $real_path = realpath($path);
    if ($real_path && file_exists($real_path) && is_readable($real_path)) {
        $file_contents = file_get_contents($real_path);
        $secrets = json_decode($file_contents, true);
        if ($secrets !== null && json_last_error() === JSON_ERROR_NONE) {
            break;
        }
    }
}

if ($secrets && isset($secrets['firebase'])) {
    $firebase_config = $secrets['firebase'];
    ?>
    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-auth-compat.js"></script>
    
    <!-- Firebase Configuration -->
    <script>
        window.choose90FirebaseConfig = {
            apiKey: <?php echo json_encode($firebase_config['api_key'] ?? ''); ?>,
            authDomain: <?php echo json_encode($firebase_config['auth_domain'] ?? ''); ?>,
            projectId: <?php echo json_encode($firebase_config['project_id'] ?? ''); ?>,
            storageBucket: <?php echo json_encode($firebase_config['storage_bucket'] ?? ''); ?>,
            messagingSenderId: <?php echo json_encode($firebase_config['messaging_sender_id'] ?? ''); ?>,
            appId: <?php echo json_encode($firebase_config['app_id'] ?? '') ?>
        };
    </script>
    
    <!-- Firebase Auth Helper -->
    <script src="<?php echo esc_url(home_url('/js/firebase-auth.js')); ?>"></script>
    <?php
} else {
    // Fallback: show error in console
    ?>
    <script>
        console.error('Firebase configuration not found. Please add Firebase config to secrets.json');
    </script>
    <?php
}
?>
