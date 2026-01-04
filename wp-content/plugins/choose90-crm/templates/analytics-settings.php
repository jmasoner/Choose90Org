<?php
/**
 * Analytics Settings Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap choose90-analytics-settings">
    <h1><?php _e('Analytics Settings', 'choose90-crm'); ?></h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('choose90_analytics_settings'); ?>
        
        <div class="settings-sections">
            <!-- Google Analytics 4 Settings -->
            <div class="settings-section">
                <h2><?php _e('Google Analytics 4', 'choose90-crm'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="ga4_measurement_id"><?php _e('GA4 Measurement ID', 'choose90-crm'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="ga4_measurement_id" 
                                   name="ga4_measurement_id" 
                                   value="<?php echo esc_attr(get_option('choose90_ga4_measurement_id', 'G-9M6498Y7W4')); ?>" 
                                   class="regular-text"
                                   placeholder="G-9M6498Y7W4">
                            <p class="description"><?php _e('Your GA4 Measurement ID (currently configured: G-9M6498Y7W4)', 'choose90-crm'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="ga4_property_id"><?php _e('GA4 Property ID', 'choose90-crm'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="ga4_property_id" 
                                   name="ga4_property_id" 
                                   value="<?php echo esc_attr(get_option('choose90_ga4_property_id', '')); ?>" 
                                   class="regular-text"
                                   placeholder="123456789">
                            <p class="description">
                                <?php _e('Your GA4 Property ID for API access (numeric, e.g., 123456789).', 'choose90-crm'); ?><br>
                                <strong><?php _e('Note:', 'choose90-crm'); ?></strong> <?php _e('This is different from Stream ID. Find it in GA4 Admin → Property Settings.', 'choose90-crm'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="ga4_api_key"><?php _e('GA4 API Key', 'choose90-crm'); ?></label>
                        </th>
                        <td>
                            <input type="password" 
                                   id="ga4_api_key" 
                                   name="ga4_api_key" 
                                   value="<?php echo esc_attr(get_option('choose90_ga4_api_key', '')); ?>" 
                                   class="regular-text">
                            <p class="description">
                                <?php _e('Service account JSON key for GA4 Data API access. Paste the ENTIRE contents of the JSON file (from { to }).', 'choose90-crm'); ?><br>
                                <strong><?php _e('Important:', 'choose90-crm'); ?></strong> <?php _e('After pasting the JSON, make sure to add the service account email to GA4 Property access management with "Viewer" role.', 'choose90-crm'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Google Search Console Settings -->
            <div class="settings-section">
                <h2><?php _e('Google Search Console', 'choose90-crm'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="search_console_site"><?php _e('Search Console Site', 'choose90-crm'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="search_console_site" 
                                   name="search_console_site" 
                                   value="<?php echo esc_attr(get_option('choose90_search_console_site', '')); ?>" 
                                   class="regular-text"
                                   placeholder="https://choose90.org">
                            <p class="description">
                                <?php _e('Your verified Search Console property URL (e.g., https://choose90.org or sc-domain:choose90.org). Use the exact format shown in Search Console.', 'choose90-crm'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="search_console_key"><?php _e('Search Console API Key', 'choose90-crm'); ?></label>
                        </th>
                        <td>
                            <input type="password" 
                                   id="search_console_key" 
                                   name="search_console_key" 
                                   value="<?php echo esc_attr(get_option('choose90_search_console_key', '')); ?>" 
                                   class="regular-text">
                            <p class="description">
                                <?php _e('Service account JSON key for Search Console API access. You can use the same JSON key as GA4 (same service account).', 'choose90-crm'); ?><br>
                                <strong><?php _e('Note:', 'choose90-crm'); ?></strong> <?php _e('Service account must have "Owner" or "Full" role in Search Console (not just Viewer).', 'choose90-crm'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Social Media API Settings -->
            <div class="settings-section">
                <h2><?php _e('Social Media APIs', 'choose90-crm'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="twitter_api_key"><?php _e('Twitter/X API Key', 'choose90-crm'); ?></label>
                        </th>
                        <td>
                            <input type="password" 
                                   id="twitter_api_key" 
                                   name="twitter_api_key" 
                                   value="<?php echo esc_attr(get_option('choose90_twitter_api_key', '')); ?>" 
                                   class="regular-text">
                            <p class="description"><?php _e('Twitter/X API Bearer Token for hashtag tracking', 'choose90-crm'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="facebook_app_id"><?php _e('Facebook App ID', 'choose90-crm'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="facebook_app_id" 
                                   name="facebook_app_id" 
                                   value="<?php echo esc_attr(get_option('choose90_facebook_app_id', '')); ?>" 
                                   class="regular-text">
                            <p class="description"><?php _e('Facebook App ID for page insights', 'choose90-crm'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="instagram_access_token"><?php _e('Instagram Access Token', 'choose90-crm'); ?></label>
                        </th>
                        <td>
                            <input type="password" 
                                   id="instagram_access_token" 
                                   name="instagram_access_token" 
                                   value="<?php echo esc_attr(get_option('choose90_instagram_access_token', '')); ?>" 
                                   class="regular-text">
                            <p class="description"><?php _e('Instagram Graph API access token', 'choose90-crm'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="linkedin_access_token"><?php _e('LinkedIn Access Token', 'choose90-crm'); ?></label>
                        </th>
                        <td>
                            <input type="password" 
                                   id="linkedin_access_token" 
                                   name="linkedin_access_token" 
                                   value="<?php echo esc_attr(get_option('choose90_linkedin_access_token', '')); ?>" 
                                   class="regular-text">
                            <p class="description"><?php _e('LinkedIn API access token', 'choose90-crm'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Settings', 'choose90-crm'); ?>">
        </p>
    </form>
    
    <div class="settings-info">
        <h3><?php _e('API Setup Instructions', 'choose90-crm'); ?></h3>
        <div class="info-box">
            <h4>Google Analytics 4</h4>
            <ol>
                <li>Go to <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a></li>
                <li>Enable the <strong>Google Analytics Data API</strong> (APIs & Services → Library)</li>
                <li>Create a <strong>Service Account</strong> (APIs & Services → Credentials → Create Credentials → Service Account)</li>
                <li>Download the <strong>JSON key file</strong> (click on service account → Keys tab → Add Key → JSON)</li>
                <li>In Google Analytics, go to <strong>Admin → Property access management</strong></li>
                <li>Add the service account email (from the JSON file) with <strong>"Viewer"</strong> role</li>
                <li>Open the JSON file and <strong>copy the entire contents</strong></li>
                <li>Paste the JSON content in the "GA4 API Key" field above</li>
            </ol>
            <p><strong>Your Property ID:</strong> <code>490452975</code></p>
        </div>
        
        <div class="info-box">
            <h4>Google Search Console</h4>
            <ol>
                <li>Go to <a href="https://search.google.com/search-console" target="_blank">Google Search Console</a></li>
                <li>Find your verified property URL (e.g., <code>https://choose90.org</code> or <code>sc-domain:choose90.org</code>)</li>
                <li>In Search Console, go to <strong>Settings → Users and permissions</strong></li>
                <li>Add the service account email: <code>choose90-analytics@choose90-dashboard.iam.gserviceaccount.com</code></li>
                <li>Assign role: <strong>"Owner" or "Full"</strong> (required for API access)</li>
                <li>Enable <strong>Google Search Console API</strong> in Cloud Console (APIs & Services → Library)</li>
                <li>Paste the <strong>same JSON key</strong> in the Search Console API Key field (same as GA4)</li>
            </ol>
            <p><strong>Tip:</strong> You can use the same service account JSON key for both GA4 and Search Console!</p>
        </div>
        
        <div class="info-box">
            <h4>Social Media APIs</h4>
            <p><?php _e('Social media API keys are optional. The dashboard will work with GA4 data alone, but adding social APIs provides more detailed hashtag and engagement tracking.', 'choose90-crm'); ?></p>
        </div>
    </div>
</div>

<style>
.choose90-analytics-settings .settings-sections {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    padding: 20px;
    margin-bottom: 20px;
}

.choose90-analytics-settings .settings-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #f0f0f1;
}

.choose90-analytics-settings .settings-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.choose90-analytics-settings .settings-section h2 {
    font-size: 20px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #2271b1;
}

.settings-info {
    background: #f6f7f7;
    border: 1px solid #ccd0d4;
    padding: 20px;
    margin-top: 20px;
}

.settings-info h3 {
    margin-top: 0;
}

.info-box {
    background: #fff;
    border: 1px solid #ccd0d4;
    padding: 20px;
    margin: 15px 0;
    border-radius: 4px;
}

.info-box h4 {
    margin-top: 0;
    color: #2271b1;
}

.info-box ol {
    margin: 10px 0;
    padding-left: 25px;
}

.info-box li {
    margin: 8px 0;
}
</style>

