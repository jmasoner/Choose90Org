<?php
/**
 * Choose90 Personalization Functions
 * Add to child theme's functions.php
 */

// Pass user info to JavaScript
function choose90_enqueue_user_data() {
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $screen_name = get_user_meta($user->ID, 'screen_name', true);
        if (empty($screen_name)) {
            $screen_name = $user->display_name;
        }
        
        $user_data = array(
            'isLoggedIn' => true,
            'screenName' => $screen_name,
            'fullName' => $user->first_name . ' ' . $user->last_name,
            'userId' => $user->ID,
        );
    } else {
        $user_data = array(
            'isLoggedIn' => false,
        );
    }
    
    wp_localize_script('jquery', 'choose90User', $user_data);
}
add_action('wp_enqueue_scripts', 'choose90_enqueue_user_data');

// Display personalized greeting shortcode
function choose90_greeting_shortcode($atts) {
    if (!is_user_logged_in()) {
        return '';
    }
    
    $user = wp_get_current_user();
    $screen_name = get_user_meta($user->ID, 'screen_name', true);
    if (empty($screen_name)) {
        $screen_name = $user->display_name;
    }
    
    return '<div class="choose90-greeting">Welcome back, <strong>' . esc_html($screen_name) . '</strong>! 👋</div>';
}
add_shortcode('choose90_greeting', 'choose90_greeting_shortcode');

// Display user name shortcode
function choose90_username_shortcode($atts) {
    if (!is_user_logged_in()) {
        return 'Friend';
    }
    
    $user = wp_get_current_user();
    $screen_name = get_user_meta($user->ID, 'screen_name', true);
    if (empty($screen_name)) {
        $screen_name = $user->display_name;
    }
    
    return esc_html($screen_name);
}
add_shortcode('choose90_username', 'choose90_username_shortcode');

