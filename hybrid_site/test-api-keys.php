<?php
/**
 * Choose90 API Keys Test Script
 * 
 * Usage: 
 * - Browser: https://choose90.org/test-api-keys.php
 * - Command line: php test-api-keys.php
 * 
 * SECURITY: Delete this file after testing!
 */

// Security check - only allow from localhost or with password
$allowed = false;

// Allow from localhost
if (isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1', 'localhost'])) {
    $allowed = true;
}

// Allow with password (set this to something secure)
$test_password = 'choose90_test_2025'; // CHANGE THIS!
if (isset($_GET['password']) && $_GET['password'] === $test_password) {
    $allowed = true;
}

if (!$allowed && php_sapi_name() !== 'cli') {
    die('Access denied. This script can only be run from localhost or with a password parameter.');
}

// Set headers for browser viewing
if (php_sapi_name() !== 'cli') {
    header('Content-Type: text/html; charset=utf-8');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose90 API Keys Test</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>🔑 Choose90 API Keys Test</h1>
        <p style="color: #666; margin-bottom: 30px;">
            This script tests your DeepSeek and Grok API keys to ensure they're configured correctly.
        </p>

        <?php
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

        // Test DeepSeek API
        echo '<div class="test-section">';
        echo '<div class="test-title">🤖 Testing DeepSeek API';
        
        if (!isset($secrets['api_keys']['deepseek']['api_key']) || empty($secrets['api_keys']['deepseek']['api_key'])) {
            echo '<span class="status-badge status-error">No Key</span>';
            echo '</div>';
            echo '<p>DeepSeek API key is not set in secrets.json</p>';
            echo '</div>';
        } else {
            $deepseek_key = $secrets['api_keys']['deepseek']['api_key'];
            $deepseek_url = $secrets['api_keys']['deepseek']['base_url'] ?? 'https://api.deepseek.com/v1';
            $deepseek_model = $secrets['api_keys']['deepseek']['model'] ?? 'deepseek-chat';
            $deepseek_enabled = $secrets['api_keys']['deepseek']['enabled'] ?? false;
            
            // Show key preview (first 10 chars + last 4 chars)
            $key_preview = substr($deepseek_key, 0, 10) . '...' . substr($deepseek_key, -4);
            echo '<span class="status-badge status-warning">Checking</span>';
            echo '</div>';
            echo '<p>Key: <span class="key-preview">' . htmlspecialchars($key_preview) . '</span></p>';
            echo '<p>Base URL: <code>' . htmlspecialchars($deepseek_url) . '</code></p>';
            echo '<p>Model: <code>' . htmlspecialchars($deepseek_model) . '</code></p>';
            echo '<p>Enabled: ' . ($deepseek_enabled ? '✅ Yes' : '❌ No') . '</p>';
            
            // Test API call
            echo '<div class="test-result">';
            echo "Testing API connection...\n\n";
            
            $test_prompt = "Say 'Choose90 API test successful' if you can read this.";
            
            $ch = curl_init($deepseek_url . '/chat/completions');
            $curlOptions = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $deepseek_key
                ),
                CURLOPT_POSTFIELDS => json_encode(array(
                    'model' => $deepseek_model,
                    'messages' => array(
                        array('role' => 'user', 'content' => $test_prompt)
                    ),
                    'max_tokens' => 50
                )),
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2
            );

            // Allow explicitly insecure mode ONLY when query param is present (local troubleshooting)
            $insecure = isset($_GET['insecure_ssl']) && $_GET['insecure_ssl'] === '1';
            if ($insecure) {
                $curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
                $curlOptions[CURLOPT_SSL_VERIFYHOST] = false;
            }

            curl_setopt_array($ch, $curlOptions);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            curl_close($ch);
            
            if ($curl_error) {
                echo '<div class="test-section error">';
                echo '<div class="test-title">❌ Connection Error</div>';
                echo '<p>CURL Error: ' . htmlspecialchars($curl_error) . '</p>';
                echo '</div>';
            } elseif ($http_code === 200) {
                $response_data = json_decode($response, true);
                if (isset($response_data['choices'][0]['message']['content'])) {
                    echo '<div class="test-section success">';
                    echo '<div class="test-title">✅ DeepSeek API: Working!</div>';
                    echo '<p>Response: ' . htmlspecialchars($response_data['choices'][0]['message']['content']) . '</p>';
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
                echo '<p>Your API key may be invalid or expired. Please check your DeepSeek API key.</p>';
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

        // Test Grok API
        echo '<div class="test-section">';
        echo '<div class="test-title">🤖 Testing Grok API';
        
        if (!isset($secrets['api_keys']['grok']['api_key']) || empty($secrets['api_keys']['grok']['api_key'])) {
            echo '<span class="status-badge status-error">No Key</span>';
            echo '</div>';
            echo '<p>Grok API key is not set in secrets.json</p>';
            echo '</div>';
        } else {
            $grok_key = $secrets['api_keys']['grok']['api_key'];
            $grok_url = $secrets['api_keys']['grok']['base_url'] ?? 'https://api.x.ai/v1';
            $grok_model = $secrets['api_keys']['grok']['model'] ?? 'grok-beta';
            $grok_enabled = $secrets['api_keys']['grok']['enabled'] ?? false;
            
            // Show key preview
            $key_preview = substr($grok_key, 0, 10) . '...' . substr($grok_key, -4);
            echo '<span class="status-badge status-warning">Checking</span>';
            echo '</div>';
            echo '<p>Key: <span class="key-preview">' . htmlspecialchars($key_preview) . '</span></p>';
            echo '<p>Base URL: <code>' . htmlspecialchars($grok_url) . '</code></p>';
            echo '<p>Model: <code>' . htmlspecialchars($grok_model) . '</code></p>';
            echo '<p>Enabled: ' . ($grok_enabled ? '✅ Yes' : '❌ No') . '</p>';
            
            // Test API call
            echo '<div class="test-result">';
            echo "Testing API connection...\n\n";
            
            $test_prompt = "Say 'Choose90 API test successful' if you can read this.";
            
            $ch = curl_init($grok_url . '/chat/completions');
            $curlOptions = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $grok_key
                ),
                CURLOPT_POSTFIELDS => json_encode(array(
                    'model' => $grok_model,
                    'messages' => array(
                        array('role' => 'user', 'content' => $test_prompt)
                    ),
                    'max_tokens' => 50
                )),
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2
            );

            if ($insecure) {
                $curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
                $curlOptions[CURLOPT_SSL_VERIFYHOST] = false;
            }

            curl_setopt_array($ch, $curlOptions);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            curl_close($ch);
            
            if ($curl_error) {
                echo '<div class="test-section error">';
                echo '<div class="test-title">❌ Connection Error</div>';
                echo '<p>CURL Error: ' . htmlspecialchars($curl_error) . '</p>';
                echo '</div>';
            } elseif ($http_code === 200) {
                $response_data = json_decode($response, true);
                if (isset($response_data['choices'][0]['message']['content'])) {
                    echo '<div class="test-section success">';
                    echo '<div class="test-title">✅ Grok API: Working!</div>';
                    echo '<p>Response: ' . htmlspecialchars($response_data['choices'][0]['message']['content']) . '</p>';
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
                echo '<p>Your API key may be invalid or expired. Please check your Grok API key.</p>';
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

        // Summary
        echo '<div class="test-section" style="background: #f0f8ff; border-left-color: #0066CC; margin-top: 40px;">';
        echo '<div class="test-title">📋 Test Summary</div>';
        echo '<p><strong>Configuration File:</strong> ' . htmlspecialchars($secrets_path) . '</p>';
        echo '<p><strong>File Exists:</strong> ' . (file_exists($secrets_path) ? '✅ Yes' : '❌ No') . '</p>';
        echo '<p><strong>Valid JSON:</strong> ' . (json_last_error() === JSON_ERROR_NONE ? '✅ Yes' : '❌ No') . '</p>';
        echo '<p style="margin-top: 20px; color: #666; font-size: 14px;">';
        echo '<strong>⚠️ Security Note:</strong> Delete this test file after verifying your API keys work.';
        echo '</p>';
        echo '</div>';
        ?>

    </div>
</body>
</html>

