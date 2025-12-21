<?php
/**
 * Choose90 API Keys Test Script - Production Version
 * 
 * This version uses proper SSL certificate verification and enhanced security.
 * 
 * SECURITY NOTES:
 * - This script should be password-protected or IP-restricted in production
 * - Consider adding rate limiting
 * - Log all access attempts
 * - Delete after testing if possible
 * 
 * Usage: 
 * - Browser: https://choose90.org/test-api-keys-production.php?password=YOUR_SECURE_PASSWORD
 * - Command line: php test-api-keys-production.php
 */

// Enhanced security check
$allowed = false;
$test_password = getenv('API_TEST_PASSWORD') ?: 'choose90_secure_test_2025'; // Use environment variable or default

// Allow from localhost
if (isset($_SERVER['REMOTE_ADDR'])) {
    $remote_ip = $_SERVER['REMOTE_ADDR'];
    if (in_array($remote_ip, ['127.0.0.1', '::1']) || $remote_ip === 'localhost') {
        $allowed = true;
    }
}

// Allow with password
if (isset($_GET['password']) && hash_equals($test_password, $_GET['password'])) {
    $allowed = true;
}

// Allow from command line
if (php_sapi_name() === 'cli') {
    $allowed = true;
}

if (!$allowed) {
    http_response_code(403);
    die('Access denied. This script requires authentication.');
}

// Set headers for browser viewing
if (php_sapi_name() !== 'cli') {
    header('Content-Type: text/html; charset=utf-8');
    // Security headers
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
}

// Function to get CA certificate bundle path
function getCACertPath() {
    // Common locations for CA certificate bundles
    $caPaths = [
        '/etc/ssl/certs/ca-certificates.crt',           // Debian/Ubuntu
        '/etc/pki/tls/certs/ca-bundle.crt',             // CentOS/RHEL
        '/usr/local/etc/openssl/cert.pem',              // macOS (Homebrew)
        '/opt/homebrew/etc/openssl/cert.pem',           // macOS (Apple Silicon)
        '/etc/ssl/cert.pem',                            // Alpine Linux
        __DIR__ . '/../cacert.pem',                     // Project-specific
        ini_get('curl.cainfo'),                         // PHP ini setting
        ini_get('openssl.cafile'),                      // PHP ini setting
    ];
    
    foreach ($caPaths as $path) {
        if (file_exists($path) && is_readable($path)) {
            return $path;
        }
    }
    
    return null;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose90 API Keys Test - Production</title>
    <style>
        body {
            font-family: 'Inter', 'Arial', sans-serif;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #f9fafb;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 {
            color: #0066CC;
            margin-bottom: 30px;
        }
        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #ddd;
        }
        .test-section.success {
            background: #e8f5e9;
            border-left-color: #4caf50;
        }
        .test-section.error {
            background: #ffebee;
            border-left-color: #d32f2f;
        }
        .test-section.warning {
            background: #fff9e6;
            border-left-color: #E8B93E;
        }
        .test-section.info {
            background: #e3f2fd;
            border-left-color: #2196F3;
        }
        .test-title {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .test-result {
            margin-top: 10px;
            padding: 10px;
            background: rgba(0,0,0,0.05);
            border-radius: 4px;
            font-family: monospace;
            font-size: 13px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .key-preview {
            font-family: monospace;
            background: #f5f5f5;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
        .status-success {
            background: #4caf50;
            color: white;
        }
        .status-error {
            background: #d32f2f;
            color: white;
        }
        .status-warning {
            background: #E8B93E;
            color: #333;
        }
        .security-note {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔑 Choose90 API Keys Test (Production)</h1>
        <p style="color: #666; margin-bottom: 30px;">
            This script tests your DeepSeek and Grok API keys with proper SSL verification.
        </p>

        <div class="security-note">
            <strong>⚠️ Security Notice:</strong> This is a production test script. Delete it after verifying your API keys work, or restrict access via .htaccess/IP whitelist.
        </div>

        <?php
        // Check SSL certificate availability
        $caCertPath = getCACertPath();
        $sslAvailable = $caCertPath !== null;
        
        echo '<div class="test-section ' . ($sslAvailable ? 'success' : 'warning') . '">';
        echo '<div class="test-title">🔒 SSL Certificate Verification';
        echo '<span class="status-badge ' . ($sslAvailable ? 'status-success' : 'status-warning') . '">' . ($sslAvailable ? 'Available' : 'Not Found') . '</span>';
        echo '</div>';
        if ($sslAvailable) {
            echo '<p>✅ CA Certificate Bundle: <code>' . htmlspecialchars($caCertPath) . '</code></p>';
            echo '<p>SSL verification will be enabled for all API requests.</p>';
        } else {
            echo '<p>⚠️ CA Certificate Bundle not found. SSL verification may fail.</p>';
            echo '<p>Consider downloading <a href="https://curl.se/ca/cacert.pem" target="_blank">cacert.pem</a> and placing it in your project root.</p>';
        }
        echo '</div>';

        // Load secrets.json
        $secrets_path = __DIR__ . '/../secrets.json';
        
        if (!file_exists($secrets_path)) {
            echo '<div class="test-section error">';
            echo '<div class="test-title">❌ Configuration File Not Found</div>';
            echo '<p>Could not find <code>secrets.json</code> at: ' . htmlspecialchars($secrets_path) . '</p>';
            echo '<p>Please ensure the file exists and contains your API keys.</p>';
            echo '</div>';
            exit;
        }

        $secrets_content = file_get_contents($secrets_path);
        $secrets = json_decode($secrets_content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo '<div class="test-section error">';
            echo '<div class="test-title">❌ Invalid JSON</div>';
            echo '<p>Error parsing secrets.json: ' . json_last_error_msg() . '</p>';
            echo '</div>';
            exit;
        }

        // Function to test API with proper SSL
        function testAPI($name, $config, $caCertPath) {
            echo '<div class="test-section">';
            echo '<div class="test-title">🤖 Testing ' . htmlspecialchars($name) . ' API';
            
            if (!isset($config['api_key']) || empty($config['api_key'])) {
                echo '<span class="status-badge status-error">No Key</span>';
                echo '</div>';
                echo '<p>' . htmlspecialchars($name) . ' API key is not set in secrets.json</p>';
                echo '</div>';
                return;
            }

            $api_key = $config['api_key'];
            $base_url = $config['base_url'] ?? '';
            $model = $config['model'] ?? '';
            $enabled = $config['enabled'] ?? false;
            
            // Show key preview
            $key_preview = substr($api_key, 0, 10) . '...' . substr($api_key, -4);
            echo '<span class="status-badge status-warning">Checking</span>';
            echo '</div>';
            echo '<p>Key: <span class="key-preview">' . htmlspecialchars($key_preview) . '</span></p>';
            echo '<p>Base URL: <code>' . htmlspecialchars($base_url) . '</code></p>';
            echo '<p>Model: <code>' . htmlspecialchars($model) . '</code></p>';
            echo '<p>Enabled: ' . ($enabled ? '✅ Yes' : '❌ No') . '</p>';
            
            if (!$enabled) {
                echo '</div>';
                return;
            }
            
            // Test API call
            echo '<div class="test-result">';
            echo "Testing API connection with SSL verification...\n\n";
            
            $test_prompt = "Say 'Choose90 API test successful' if you can read this.";
            
            $ch = curl_init($base_url . '/chat/completions');
            $curl_options = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $api_key
                ),
                CURLOPT_POSTFIELDS => json_encode(array(
                    'model' => $model,
                    'messages' => array(
                        array('role' => 'user', 'content' => $test_prompt)
                    ),
                    'max_tokens' => 50
                )),
                CURLOPT_TIMEOUT => 15,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2
            );
            
            // Add CA certificate if available
            if ($caCertPath) {
                $curl_options[CURLOPT_CAINFO] = $caCertPath;
            }
            
            curl_setopt_array($ch, $curl_options);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            $ssl_verify_result = curl_getinfo($ch, CURLINFO_SSL_VERIFYRESULT);
            curl_close($ch);
            
            if ($curl_error) {
                echo '<div class="test-section error">';
                echo '<div class="test-title">❌ Connection Error</div>';
                echo '<p>CURL Error: ' . htmlspecialchars($curl_error) . '</p>';
                if (strpos($curl_error, 'SSL') !== false) {
                    echo '<p><strong>SSL Issue:</strong> This may be due to missing CA certificates. ';
                    echo 'Download <a href="https://curl.se/ca/cacert.pem" target="_blank">cacert.pem</a> and place it in your project root.</p>';
                }
                echo '</div>';
            } elseif ($http_code === 200) {
                $response_data = json_decode($response, true);
                if (isset($response_data['choices'][0]['message']['content'])) {
                    echo '<div class="test-section success">';
                    echo '<div class="test-title">✅ ' . htmlspecialchars($name) . ' API: Working!</div>';
                    echo '<p>Response: ' . htmlspecialchars($response_data['choices'][0]['message']['content']) . '</p>';
                    if ($ssl_verify_result === 0) {
                        echo '<p style="color: #4caf50; margin-top: 10px;">✅ SSL Certificate verified successfully</p>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="test-section warning">';
                    echo '<div class="test-title">⚠️ Unexpected Response</div>';
                    echo '<p>HTTP Code: ' . $http_code . '</p>';
                    echo '<p>Response: ' . htmlspecialchars(substr($response, 0, 200)) . '</p>';
                    echo '</div>';
                }
            } elseif ($http_code === 401) {
                echo '<div class="test-section error">';
                echo '<div class="test-title">❌ Authentication Failed</div>';
                echo '<p>HTTP Code: 401 (Unauthorized)</p>';
                echo '<p>Your API key may be invalid or expired. Please check your ' . htmlspecialchars($name) . ' API key.</p>';
                echo '</div>';
            } else {
                echo '<div class="test-section error">';
                echo '<div class="test-title">❌ API Error</div>';
                echo '<p>HTTP Code: ' . $http_code . '</p>';
                echo '<p>Response: ' . htmlspecialchars(substr($response, 0, 200)) . '</p>';
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
        }

        // Test DeepSeek
        if (isset($secrets['api_keys']['deepseek'])) {
            testAPI('DeepSeek', $secrets['api_keys']['deepseek'], $caCertPath);
        }

        // Test Grok
        if (isset($secrets['api_keys']['grok'])) {
            testAPI('Grok', $secrets['api_keys']['grok'], $caCertPath);
        }

        // Summary
        echo '<div class="test-section info" style="margin-top: 40px;">';
        echo '<div class="test-title">📋 Test Summary</div>';
        echo '<p><strong>Configuration File:</strong> ' . htmlspecialchars($secrets_path) . '</p>';
        echo '<p><strong>File Exists:</strong> ' . (file_exists($secrets_path) ? '✅ Yes' : '❌ No') . '</p>';
        echo '<p><strong>Valid JSON:</strong> ' . (json_last_error() === JSON_ERROR_NONE ? '✅ Yes' : '❌ No') . '</p>';
        echo '<p><strong>SSL Verification:</strong> ' . ($sslAvailable ? '✅ Enabled' : '⚠️ Not Available') . '</p>';
        echo '<p style="margin-top: 20px; color: #666; font-size: 14px;">';
        echo '<strong>⚠️ Security Note:</strong> Delete this test file after verifying your API keys work, or restrict access via .htaccess/IP whitelist.';
        echo '</p>';
        echo '</div>';
        ?>

    </div>
</body>
</html>

