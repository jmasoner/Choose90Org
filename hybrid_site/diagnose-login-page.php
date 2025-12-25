<?php
/**
 * Diagnostic script for Login page template
 * Visit: https://choose90.org/hybrid_site/diagnose-login-page.php
 * Delete after use
 */

// Load WordPress - try multiple paths
// On server: /home/constit2/choose90.org/ is document root
// hybrid_site/ is at: /home/constit2/choose90.org/hybrid_site/
// wp-load.php is at: /home/constit2/choose90.org/wp-load.php
$wp_load_paths = array(
    __DIR__ . '/../wp-load.php',              // hybrid_site/../wp-load.php (most likely)
    $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php',  // Document root
    dirname(__DIR__) . '/wp-load.php',        // Parent of hybrid_site
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
    // Show debug info
    die('Error: Could not find wp-load.php.<br>' .
        'Current file: ' . __FILE__ . '<br>' .
        'Current dir: ' . __DIR__ . '<br>' .
        'Document root: ' . (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : 'not set') . '<br>' .
        'Tried paths: ' . implode(', ', $wp_load_paths));
}

if (!current_user_can('manage_options')) {
    die('You must be logged in as an administrator.');
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page Diagnostic</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .section { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        pre { background: #f0f0f0; padding: 10px; border-radius: 3px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Login Page Diagnostic</h1>
    
    <?php
    // 1. Check if Login page exists
    echo '<div class="section">';
    echo '<h2>1. Page Check</h2>';
    $login_page = get_page_by_path('login');
    if ($login_page) {
        echo '<p class="success">✓ Login page found (ID: ' . $login_page->ID . ')</p>';
        echo '<p>Status: ' . $login_page->post_status . '</p>';
        echo '<p>Slug: ' . $login_page->post_name . '</p>';
    } else {
        echo '<p class="error">✗ Login page NOT FOUND</p>';
        echo '<p>You need to create a page with slug "login" in WordPress admin.</p>';
    }
    echo '</div>';
    
    // 2. Check template assignment
    if ($login_page) {
        echo '<div class="section">';
        echo '<h2>2. Template Assignment</h2>';
        $template = get_page_template_slug($login_page->ID);
        if ($template) {
            echo '<p>Selected Template: <strong>' . esc_html($template) . '</strong></p>';
        } else {
            echo '<p class="warning">⚠ No template explicitly selected</p>';
        }
        
        // Check if page-login.php exists
        $template_path = get_stylesheet_directory() . '/page-login.php';
        if (file_exists($template_path)) {
            echo '<p class="success">✓ page-login.php exists in child theme</p>';
            echo '<p>Path: ' . esc_html($template_path) . '</p>';
        } else {
            echo '<p class="error">✗ page-login.php NOT FOUND in child theme</p>';
            echo '<p>Expected: ' . esc_html($template_path) . '</p>';
        }
        echo '</div>';
        
        // 3. Check what template WordPress will use
        echo '<div class="section">';
        echo '<h2>3. WordPress Template Resolution</h2>';
        $resolved_template = get_page_template();
        if ($resolved_template) {
            echo '<p class="success">✓ WordPress will use:</p>';
            echo '<pre>' . esc_html($resolved_template) . '</pre>';
        } else {
            echo '<p class="error">✗ No template resolved</p>';
        }
        echo '</div>';
        
        // 4. Check template file contents
        echo '<div class="section">';
        echo '<h2>4. Template File Check</h2>';
        if (file_exists($template_path)) {
            $content = file_get_contents($template_path);
            if (strpos($content, 'name="loginform"') !== false) {
                echo '<p class="success">✓ Template contains login form</p>';
            } else {
                echo '<p class="error">✗ Template does NOT contain login form</p>';
            }
            if (strpos($content, 'Template Name: Custom Login Page') !== false) {
                echo '<p class="success">✓ Template has correct "Template Name" header</p>';
            } else {
                echo '<p class="warning">⚠ Template Name header may be missing or incorrect</p>';
            }
        }
        echo '</div>';
        
        // 5. Fix recommendations
        echo '<div class="section">';
        echo '<h2>5. Fix Actions</h2>';
        echo '<ol>';
        if (!$template) {
            echo '<li>Go to WordPress Admin → Pages → Login → Edit</li>';
            echo '<li>In the Page Attributes box (right sidebar), select "Custom Login Page" template</li>';
            echo '<li>Update the page</li>';
        }
        if (!file_exists($template_path)) {
            echo '<li>Deploy page-login.php to: ' . esc_html($template_path) . '</li>';
        }
        if ($login_page->post_status !== 'publish') {
            echo '<li>Ensure the Login page is published (not draft/private)</li>';
        }
        echo '<li>Clear any caching (browser cache, WordPress cache, server cache)</li>';
        echo '<li>Visit: <a href="' . get_permalink($login_page->ID) . '" target="_blank">' . get_permalink($login_page->ID) . '</a></li>';
        echo '</ol>';
        echo '</div>';
    }
    ?>
    
    <div class="section">
        <h2>Quick Fix</h2>
        <p>Click the button below to automatically fix the Login page:</p>
        <form method="post">
            <button type="submit" name="fix_login" style="padding: 10px 20px; background: #0066CC; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Fix Login Page Now
            </button>
        </form>
    </div>
    
    <?php
    if (isset($_POST['fix_login'])) {
        echo '<div class="section">';
        echo '<h2>Fix Results</h2>';
        echo '<pre>';
        
        $login_page = get_page_by_path('login');
        if (!$login_page) {
            // Create the page
            $page_id = wp_insert_post(array(
                'post_title' => 'Login',
                'post_name' => 'login',
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page'
            ));
            if ($page_id && !is_wp_error($page_id)) {
                echo "✓ Created Login page (ID: $page_id)\n";
                $login_page = get_post($page_id);
            } else {
                echo "✗ Failed to create Login page\n";
            }
        }
        
        if ($login_page) {
            // Clear content
            wp_update_post(array(
                'ID' => $login_page->ID,
                'post_content' => ''
            ));
            echo "✓ Cleared page editor content\n";
            
            // Set template - use full path relative to theme
            $template_file = 'page-login.php';
            update_post_meta($login_page->ID, '_wp_page_template', $template_file);
            echo "✓ Set template to '$template_file'\n";
            
            // Force template by setting page slug match (WordPress will auto-use page-{slug}.php)
            // But we also need to ensure the template is selected
            $current_template = get_page_template_slug($login_page->ID);
            echo "  Current template meta: " . ($current_template ?: 'none') . "\n";
            
            // Ensure published
            if ($login_page->post_status !== 'publish') {
                wp_update_post(array(
                    'ID' => $login_page->ID,
                    'post_status' => 'publish'
                ));
                echo "✓ Set page status to 'publish'\n";
            }
            
            // Clear any object cache
            if (function_exists('wp_cache_flush')) {
                wp_cache_flush();
                echo "✓ Cleared WordPress cache\n";
            }
            
            echo "\n✓ Login page fixed! Visit: " . get_permalink($login_page->ID) . "\n";
            echo "\n⚠ If template still doesn't load, try:\n";
            echo "  1. Go to WordPress Admin → Pages → Login → Edit\n";
            echo "  2. In Page Attributes, manually select 'Custom Login Page' template\n";
            echo "  3. Update the page\n";
        }
        
        echo '</pre>';
        echo '</div>';
    }
    ?>
    
</body>
</html>

