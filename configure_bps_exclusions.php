<?php
/**
 * BPS Configuration Script
 * Run this once via WordPress admin or WP-CLI to configure BPS exclusions
 * 
 * This script attempts to programmatically configure BPS to exclude
 * /resources/ and /resources-backup/ from all monitoring/restore features.
 */

// Load WordPress
require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('You must be logged in as an administrator to run this script.');
}

echo "<h1>BPS Configuration Helper</h1>";
echo "<pre>";

$exclusions = array();
$warnings = array();

// Check if BPS is active
if (!class_exists('bulletproof_security')) {
    $warnings[] = "⚠ WARNING: BulletProof Security plugin not detected or not active.";
    $warnings[] = "This script is designed to configure BPS settings.";
} else {
    $exclusions[] = "✓ BulletProof Security is active.";
}

// Paths to exclude
$paths_to_exclude = array(
    '/resources/',
    '/resources-backup/',
    'resources-hub.html'
);

echo "Paths to exclude from BPS monitoring:\n";
foreach ($paths_to_exclude as $path) {
    echo "  - {$path}\n";
}
echo "\n";

// Try to update BPS options directly
$bps_options = get_option('bulletproof_security_options', array());

if (!empty($bps_options)) {
    echo "Found BPS options. Attempting to add exclusions...\n\n";
    
    // ARQ Exclusions
    if (isset($bps_options['bpsarq_exclude_folders'])) {
        $current_excludes = is_array($bps_options['bpsarq_exclude_folders']) 
            ? $bps_options['bpsarq_exclude_folders'] 
            : explode("\n", $bps_options['bpsarq_exclude_folders']);
        
        foreach ($paths_to_exclude as $path) {
            if (!in_array($path, $current_excludes)) {
                $current_excludes[] = $path;
                $exclusions[] = "✓ Added to ARQ exclusions: {$path}";
            }
        }
        
        $bps_options['bpsarq_exclude_folders'] = implode("\n", array_unique($current_excludes));
    }
    
    // Try to save (this may not work if BPS validates options)
    $result = update_option('bulletproof_security_options', $bps_options);
    if ($result) {
        $exclusions[] = "✓ BPS options updated successfully.";
    } else {
        $warnings[] = "⚠ Could not automatically update BPS options.";
        $warnings[] = "You may need to configure exclusions manually in BPS admin.";
    }
} else {
    $warnings[] = "⚠ Could not find BPS options in database.";
    $warnings[] = "BPS may use a different option key or structure.";
}

echo "\n=== MANUAL CONFIGURATION REQUIRED ===\n\n";
echo "BPS Pro settings are typically protected and must be configured via the WordPress admin.\n";
echo "Please follow these steps:\n\n";

echo "1. Go to: WordPress Admin → BulletProof Security → AutoRestore|Quarantine (ARQ)\n";
echo "2. Find: 'Exclude Options' or 'Exclude Folders/Files'\n";
echo "3. Add these paths (one per line):\n";
foreach ($paths_to_exclude as $path) {
    echo "   {$path}\n";
}
echo "\n";

echo "4. Go to: BPS Pro → Monitor → File Monitor Settings\n";
echo "5. Find: 'Monitor Exclusions' or 'Skip Monitoring'\n";
echo "6. Add the same paths again\n";
echo "\n";

echo "7. Disable these features if present:\n";
echo "   - 'Auto-Restore Deleted Files'\n";
echo "   - 'Auto-Create Missing Directories'\n";
echo "\n";

echo "8. Save all changes\n";
echo "9. Check BPS Security Log after 24 hours to verify no actions on /resources/\n";
echo "\n";

// Output results
if (!empty($exclusions)) {
    echo implode("\n", $exclusions) . "\n\n";
}

if (!empty($warnings)) {
    echo implode("\n", $warnings) . "\n\n";
}

echo "=== COMPLETE ===\n";
echo "\n<strong>DELETE THIS FILE after running it for security.</strong>";
echo "</pre>";

