<?php
/**
 * Reset BPS Login Lockout
 * Visit: https://choose90.org/hybrid_site/reset-bps-lockout.php
 * Delete after use
 */

// Load WordPress
$wp_load_paths = array(
    __DIR__ . '/../wp-load.php',
    $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php',
    dirname(__DIR__) . '/wp-load.php',
);

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    $real_path = realpath($path);
    if ($real_path && file_exists($real_path)) {
        require_once($real_path);
        $wp_loaded = true;
        break;
    }
}

if (!$wp_loaded) {
    die('Error: Could not find wp-load.php');
}

if (!current_user_can('manage_options')) {
    die('You must be logged in as an administrator.');
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset BPS Lockout</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .section { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        pre { background: #f0f0f0; padding: 10px; border-radius: 3px; overflow-x: auto; }
        button { padding: 10px 20px; background: #0066CC; color: white; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
    </style>
</head>
<body>
    <h1>Reset BPS Login Lockout</h1>
    
    <?php
    if (isset($_POST['reset_lockout'])) {
        echo '<div class="section">';
        echo '<h2>Reset Results</h2>';
        echo '<pre>';
        
        // Method 1: Delete BPS lockout files
        $bps_backup_dir = WP_CONTENT_DIR . '/bps-backup';
        $lockout_files = array(
            $bps_backup_dir . '/master-backups/Login-Security-Lockouts.txt',
            $bps_backup_dir . '/master-backups/Login-Security-Alert-Reset.txt',
        );
        
        $deleted = 0;
        foreach ($lockout_files as $file) {
            if (file_exists($file)) {
                if (unlink($file)) {
                    echo "✓ Deleted: $file\n";
                    $deleted++;
                } else {
                    echo "✗ Failed to delete: $file\n";
                }
            } else {
                echo "⚠ File not found: $file\n";
            }
        }
        
        // Method 2: Clear BPS options
        $bps_options = get_option('bulletproof_security_options_login_security');
        if ($bps_options) {
            // Reset lockout counters
            if (isset($bps_options['bps_login_security_lockouts'])) {
                unset($bps_options['bps_login_security_lockouts']);
                update_option('bulletproof_security_options_login_security', $bps_options);
                echo "✓ Cleared BPS lockout options\n";
            }
        }
        
        // Method 3: Clear transients
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_bps_%'");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_bps_%'");
        echo "✓ Cleared BPS transients\n";
        
        echo "\n✓ Lockout reset complete! You can now try logging in again.\n";
        echo '</pre>';
        echo '</div>';
    }
    ?>
    
    <div class="section">
        <h2>Current Status</h2>
        <pre>
<?php
$bps_backup_dir = WP_CONTENT_DIR . '/bps-backup';
$lockout_file = $bps_backup_dir . '/master-backups/Login-Security-Lockouts.txt';

if (file_exists($lockout_file)) {
    echo "⚠ Lockout file exists: $lockout_file\n";
    echo "Contents:\n";
    echo file_get_contents($lockout_file);
} else {
    echo "✓ No lockout file found\n";
}

$bps_options = get_option('bulletproof_security_options_login_security');
if ($bps_options && isset($bps_options['bps_login_security_lockouts'])) {
    echo "\n⚠ Lockout data in options:\n";
    print_r($bps_options['bps_login_security_lockouts']);
} else {
    echo "\n✓ No lockout data in options\n";
}
?>
        </pre>
    </div>
    
    <div class="section">
        <h2>Reset Lockout</h2>
        <p>Click the button below to reset the BPS login lockout:</p>
        <form method="post">
            <button type="submit" name="reset_lockout">Reset BPS Lockout</button>
        </form>
        <p><strong>Warning:</strong> This will clear all login lockout data. Use only if you're sure you want to reset the lockout.</p>
    </div>
    
    <div class="section">
        <h2>BPS Settings Check</h2>
        <p>You may also want to check BPS settings:</p>
        <ol>
            <li>Go to WordPress Admin → BulletProof Security → Login Security</li>
            <li>Check "Login Security Lockouts" settings</li>
            <li>Consider temporarily reducing lockout thresholds for testing</li>
            <li>Ensure your custom login page URL is whitelisted (if BPS has that option)</li>
        </ol>
    </div>
    
</body>
</html>

