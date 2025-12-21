<?php
/**
 * Choose90 Enhanced Pledge Form Handler
 * Add this to your child theme's functions.php or create as a plugin
 */

// Register user meta fields
function choose90_register_user_meta_fields() {
    // Screen name (display name)
    register_meta('user', 'screen_name', array(
        'type' => 'string',
        'description' => 'User display name',
        'single' => true,
        'show_in_rest' => true,
    ));
    
    // Phone
    register_meta('user', 'phone', array(
        'type' => 'string',
        'description' => 'User phone number',
        'single' => true,
        'show_in_rest' => false,
    ));
    
    // Location
    register_meta('user', 'location_city', array(
        'type' => 'string',
        'description' => 'User city',
        'single' => true,
        'show_in_rest' => false,
    ));
    
    register_meta('user', 'location_state', array(
        'type' => 'string',
        'description' => 'User state/province',
        'single' => true,
        'show_in_rest' => false,
    ));
    
    // Social Media
    register_meta('user', 'facebook_url', array(
        'type' => 'string',
        'description' => 'Facebook profile URL',
        'single' => true,
        'show_in_rest' => false,
    ));
    
    register_meta('user', 'instagram_handle', array(
        'type' => 'string',
        'description' => 'Instagram handle',
        'single' => true,
        'show_in_rest' => false,
    ));
    
    register_meta('user', 'twitter_handle', array(
        'type' => 'string',
        'description' => 'Twitter/X handle',
        'single' => true,
        'show_in_rest' => false,
    ));
    
    register_meta('user', 'tiktok_handle', array(
        'type' => 'string',
        'description' => 'TikTok handle',
        'single' => true,
        'show_in_rest' => false,
    ));
    
    register_meta('user', 'linkedin_url', array(
        'type' => 'string',
        'description' => 'LinkedIn profile URL',
        'single' => true,
        'show_in_rest' => false,
    ));
    
    register_meta('user', 'reddit_username', array(
        'type' => 'string',
        'description' => 'Reddit username',
        'single' => true,
        'show_in_rest' => false,
    ));
    
    // Engagement tracking
    register_meta('user', 'pledge_date', array(
        'type' => 'string',
        'description' => 'Date user took the pledge',
        'single' => true,
        'show_in_rest' => false,
    ));
    
    register_meta('user', 'badges_earned', array(
        'type' => 'string',
        'description' => 'JSON array of badge IDs',
        'single' => true,
        'show_in_rest' => false,
    ));
    
    register_meta('user', 'resource_count', array(
        'type' => 'integer',
        'description' => 'Number of resources completed',
        'single' => true,
        'show_in_rest' => false,
    ));
    
    register_meta('user', 'last_active', array(
        'type' => 'string',
        'description' => 'Last active date',
        'single' => true,
        'show_in_rest' => false,
    ));
}
add_action('init', 'choose90_register_user_meta_fields');

// Handle pledge form submission
function choose90_handle_pledge_submission() {
    // Verify nonce
    if (!isset($_POST['pledge_nonce']) || !wp_verify_nonce($_POST['pledge_nonce'], 'choose90_pledge_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed. Please refresh and try again.'));
        return;
    }
    
    // Sanitize and validate input
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $full_name = sanitize_text_field($_POST['full_name']);
    $screen_name = sanitize_text_field($_POST['screen_name']);
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $location_city = isset($_POST['location_city']) ? sanitize_text_field($_POST['location_city']) : '';
    $location_state = isset($_POST['location_state']) ? sanitize_text_field($_POST['location_state']) : '';
    
    // Social media
    $facebook_url = isset($_POST['facebook_url']) ? esc_url_raw($_POST['facebook_url']) : '';
    $instagram_handle = isset($_POST['instagram_handle']) ? sanitize_text_field($_POST['instagram_handle']) : '';
    $twitter_handle = isset($_POST['twitter_handle']) ? sanitize_text_field($_POST['twitter_handle']) : '';
    $tiktok_handle = isset($_POST['tiktok_handle']) ? sanitize_text_field($_POST['tiktok_handle']) : '';
    $linkedin_url = isset($_POST['linkedin_url']) ? esc_url_raw($_POST['linkedin_url']) : '';
    $reddit_username = isset($_POST['reddit_username']) ? sanitize_text_field($_POST['reddit_username']) : '';
    
    // Validate required fields
    if (empty($email) || empty($password) || empty($full_name) || empty($screen_name)) {
        wp_send_json_error(array('message' => 'Please fill in all required fields.'));
        return;
    }
    
    // Check if user already exists
    if (email_exists($email)) {
        wp_send_json_error(array('message' => 'An account with this email already exists. Please log in instead.'));
        return;
    }
    
    // Validate password strength
    if (strlen($password) < 8) {
        wp_send_json_error(array('message' => 'Password must be at least 8 characters long.'));
        return;
    }
    
    // Create WordPress user
    $user_id = wp_create_user($email, $password, $email);
    
    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => $user_id->get_error_message()));
        return;
    }
    
    // Update user display name
    wp_update_user(array(
        'ID' => $user_id,
        'display_name' => $screen_name,
        'first_name' => $full_name,
    ));
    
    // Save user meta fields
    update_user_meta($user_id, 'screen_name', $screen_name);
    update_user_meta($user_id, 'phone', $phone);
    update_user_meta($user_id, 'location_city', $location_city);
    update_user_meta($user_id, 'location_state', $location_state);
    
    // Social media
    update_user_meta($user_id, 'facebook_url', $facebook_url);
    update_user_meta($user_id, 'instagram_handle', $instagram_handle);
    update_user_meta($user_id, 'twitter_handle', $twitter_handle);
    update_user_meta($user_id, 'tiktok_handle', $tiktok_handle);
    update_user_meta($user_id, 'linkedin_url', $linkedin_url);
    update_user_meta($user_id, 'reddit_username', $reddit_username);
    
    // Engagement tracking
    update_user_meta($user_id, 'pledge_date', current_time('mysql'));
    update_user_meta($user_id, 'badges_earned', json_encode(array('pledge')));
    update_user_meta($user_id, 'resource_count', 0);
    update_user_meta($user_id, 'last_active', current_time('mysql'));
    
    // Set user role (default: subscriber)
    $user = new WP_User($user_id);
    $user->set_role('subscriber');
    
    // Log user in automatically
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    
    // Send welcome email
    choose90_send_welcome_email($user_id, $screen_name);
    
    // Return success
    wp_send_json_success(array(
        'message' => 'Pledge successful! Welcome to Choose90!',
        'user_id' => $user_id,
        'screen_name' => $screen_name,
    ));
}
add_action('admin_post_choose90_pledge_submit', 'choose90_handle_pledge_submission');
add_action('admin_post_nopriv_choose90_pledge_submit', 'choose90_handle_pledge_submission');

// Send welcome email
function choose90_send_welcome_email($user_id, $screen_name) {
    $user = get_userdata($user_id);
    $email = $user->user_email;
    
    $subject = 'Welcome to Choose90! You\'ve earned your first badge 🎯';
    
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 40px; background: #f9fafb;'>
            <div style='text-align: center; margin-bottom: 30px;'>
                <h1 style='color: #0066CC; font-size: 36px; margin: 0;'>Choose<span style='color: #E8B93E;'>90</span></h1>
            </div>
            
            <div style='background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);'>
                <h2 style='color: #0066CC; margin-top: 0;'>Welcome, {$screen_name}!</h2>
                
                <p>Thank you for taking the pledge to choose 90% positive. You've just joined a movement of people committed to focusing on the good, building real connections, and choosing light over darkness.</p>
                
                <div style='text-align: center; margin: 30px 0; padding: 20px; background: #f0f8ff; border-radius: 8px;'>
                    <div style='font-size: 48px; margin-bottom: 10px;'>🎯</div>
                    <h3 style='color: #0066CC; margin: 0;'>You've earned your first badge!</h3>
                    <p style='margin: 10px 0 0 0;'><strong>I Choose 90</strong></p>
                </div>
                
                <p>Here's what you can do next:</p>
                <ul style='line-height: 2;'>
                    <li><strong>Explore Resources:</strong> Check out our guides, toolkits, and challenges</li>
                    <li><strong>Join a Chapter:</strong> Connect with others in your area</li>
                    <li><strong>Share Your Journey:</strong> Let others know you've chosen 90% positive</li>
                </ul>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='https://choose90.org/resources-hub.html' style='display: inline-block; padding: 14px 30px; background: #0066CC; color: white; text-decoration: none; border-radius: 8px; font-weight: 600;'>Explore Resources</a>
                </div>
                
                <p style='margin-top: 30px; font-size: 14px; color: #666;'>
                    Remember: You're not perfect, and that's okay. This is about progress, not perfection. 
                    Connection, not control.
                </p>
            </div>
            
            <p style='text-align: center; margin-top: 30px; font-size: 12px; color: #999;'>
                &copy; 2025 Choose90.org | You're receiving this because you took the pledge.
            </p>
        </div>
    </body>
    </html>
    ";
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    wp_mail($email, $subject, $message, $headers);
}

// Add user meta to user profile in admin
function choose90_add_user_profile_fields($user) {
    ?>
    <h3>Choose90 Profile Information</h3>
    <table class="form-table">
        <tr>
            <th><label for="screen_name">Screen Name</label></th>
            <td>
                <input type="text" name="screen_name" id="screen_name" value="<?php echo esc_attr(get_user_meta($user->ID, 'screen_name', true)); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="phone">Phone</label></th>
            <td>
                <input type="tel" name="phone" id="phone" value="<?php echo esc_attr(get_user_meta($user->ID, 'phone', true)); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="location_city">City</label></th>
            <td>
                <input type="text" name="location_city" id="location_city" value="<?php echo esc_attr(get_user_meta($user->ID, 'location_city', true)); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="location_state">State/Province</label></th>
            <td>
                <input type="text" name="location_state" id="location_state" value="<?php echo esc_attr(get_user_meta($user->ID, 'location_state', true)); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label>Badges Earned</label></th>
            <td>
                <?php 
                $badges = json_decode(get_user_meta($user->ID, 'badges_earned', true), true);
                if ($badges && is_array($badges)) {
                    echo '<ul>';
                    foreach ($badges as $badge_id) {
                        echo '<li>' . esc_html($badge_id) . '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo 'No badges yet.';
                }
                ?>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'choose90_add_user_profile_fields');
add_action('edit_user_profile', 'choose90_add_user_profile_fields');

// Save user profile fields
function choose90_save_user_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    
    if (isset($_POST['screen_name'])) {
        update_user_meta($user_id, 'screen_name', sanitize_text_field($_POST['screen_name']));
    }
    
    if (isset($_POST['phone'])) {
        update_user_meta($user_id, 'phone', sanitize_text_field($_POST['phone']));
    }
    
    if (isset($_POST['location_city'])) {
        update_user_meta($user_id, 'location_city', sanitize_text_field($_POST['location_city']));
    }
    
    if (isset($_POST['location_state'])) {
        update_user_meta($user_id, 'location_state', sanitize_text_field($_POST['location_state']));
    }
}
add_action('personal_options_update', 'choose90_save_user_profile_fields');
add_action('edit_user_profile_update', 'choose90_save_user_profile_fields');

// Make admin-post.php return JSON for AJAX requests
function choose90_json_response() {
    if (isset($_POST['action']) && $_POST['action'] === 'choose90_pledge_submit') {
        header('Content-Type: application/json');
    }
}
add_action('admin_init', 'choose90_json_response');

