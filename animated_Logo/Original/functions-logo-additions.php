<?php
/**
 * Add to your Divi child theme's functions.php
 * Enqueues the animated logo CSS for WordPress pages
 */

// Enqueue logo animation styles
function choose90_enqueue_logo_styles() {
    wp_enqueue_style(
        'choose90-logo-animated',
        get_stylesheet_directory_uri() . '/css/logo-animated.css',
        array(),
        '1.0.0',
        'all'
    );
}
add_action('wp_enqueue_scripts', 'choose90_enqueue_logo_styles');

// Optional: Enqueue Outfit font if not already loaded
function choose90_enqueue_fonts() {
    wp_enqueue_style(
        'choose90-outfit-font',
        'https://fonts.googleapis.com/css2?family=Outfit:wght@700&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'choose90_enqueue_fonts');
