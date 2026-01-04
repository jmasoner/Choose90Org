<?php
/**
 * Plugin Name: Choose90 CRM
 * Plugin URI: https://choose90.org
 * Description: Customer Relationship Management system for Choose90 chapters - manages email communications, distribution lists, and chapter leader dashboards.
 * Version: 1.0.0
 * Author: Choose90
 * Author URI: https://choose90.org
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: choose90-crm
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CHOOSE90_CRM_VERSION', '1.0.0');
define('CHOOSE90_CRM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CHOOSE90_CRM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CHOOSE90_CRM_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main plugin class
 */
class Choose90_CRM {
    
    /**
     * Single instance of the class
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init();
    }
    
    /**
     * Initialize plugin
     */
    private function init() {
        // Load plugin files
        $this->load_dependencies();
        
        // Register activation/deactivation hooks
        register_activation_hook(__FILE__, array(__CLASS__, 'activate'));
        register_deactivation_hook(__FILE__, array(__CLASS__, 'deactivate'));
        
        // Initialize components
        add_action('init', array($this, 'load_components'));
        
        // Load admin assets
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    /**
     * Load plugin dependencies
     */
    private function load_dependencies() {
        require_once CHOOSE90_CRM_PLUGIN_DIR . 'includes/class-crm-post-types.php';
        require_once CHOOSE90_CRM_PLUGIN_DIR . 'includes/class-crm-distribution-lists.php';
        require_once CHOOSE90_CRM_PLUGIN_DIR . 'includes/class-crm-email-handler.php';
        require_once CHOOSE90_CRM_PLUGIN_DIR . 'includes/class-crm-dashboard.php';
        require_once CHOOSE90_CRM_PLUGIN_DIR . 'includes/class-crm-admin.php';
        
        // Load Analytics class - ensure it's loaded before Admin class uses it
        $analytics_file = CHOOSE90_CRM_PLUGIN_DIR . 'includes/class-crm-analytics.php';
        if (file_exists($analytics_file)) {
            require_once $analytics_file;
            // Verify class was loaded
            if (!class_exists('Choose90_CRM_Analytics') && defined('WP_DEBUG') && WP_DEBUG) {
                error_log('Choose90 CRM: Analytics file loaded but class not defined. Check for PHP errors.');
            }
        } else {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('Choose90 CRM: Analytics file not found at: ' . $analytics_file);
            }
        }
    }
    
    /**
     * Load plugin components
     */
    public function load_components() {
        // Initialize post types
        Choose90_CRM_Post_Types::get_instance();
        
        // Initialize distribution lists
        Choose90_CRM_Distribution_Lists::get_instance();
        
        // Initialize email handler
        Choose90_CRM_Email_Handler::get_instance();
        
        // Initialize dashboard (only in admin)
        if (is_admin()) {
            Choose90_CRM_Dashboard::get_instance();
            Choose90_CRM_Admin::get_instance();
            Choose90_CRM_Analytics::get_instance();
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Only load on CRM pages
        $crm_pages = array(
            'toplevel_page_choose90-crm',
            'crm-email_page_choose90-crm-distribution-lists',
            'crm-email_page_choose90-crm-dashboard',
            'crm_page_choose90-crm-analytics',
            'crm_page_choose90-crm-analytics-settings',
        );
        
        if (!in_array($hook, $crm_pages) && strpos($hook, 'crm-') === false && strpos($hook, 'choose90-crm') === false) {
            return;
        }
        
        wp_enqueue_style(
            'choose90-crm-admin',
            CHOOSE90_CRM_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            CHOOSE90_CRM_VERSION
        );
        
        wp_enqueue_script(
            'choose90-crm-admin',
            CHOOSE90_CRM_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            CHOOSE90_CRM_VERSION,
            true
        );
        
        wp_localize_script('choose90-crm-admin', 'choose90Crm', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('choose90_crm_nonce'),
        ));
    }
    
    /**
     * Plugin activation (static method)
     */
    public static function activate() {
        // Load dependencies
        require_once CHOOSE90_CRM_PLUGIN_DIR . 'includes/class-crm-post-types.php';
        require_once CHOOSE90_CRM_PLUGIN_DIR . 'includes/class-crm-distribution-lists.php';
        require_once CHOOSE90_CRM_PLUGIN_DIR . 'includes/class-crm-email-handler.php';
        require_once CHOOSE90_CRM_PLUGIN_DIR . 'includes/class-crm-dashboard.php';
        require_once CHOOSE90_CRM_PLUGIN_DIR . 'includes/class-crm-admin.php';
        require_once CHOOSE90_CRM_PLUGIN_DIR . 'includes/class-crm-analytics.php';
        
        // Create custom post types
        Choose90_CRM_Post_Types::get_instance();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Create database tables if needed
        self::create_tables();
        
        // Set default options
        self::set_default_options();
    }
    
    /**
     * Plugin deactivation (static method)
     */
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Create custom database tables (static method)
     */
    private static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Distribution lists table
        $table_distribution_lists = $wpdb->prefix . 'choose90_distribution_lists';
        $sql_distribution_lists = "CREATE TABLE IF NOT EXISTS $table_distribution_lists (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email_alias varchar(255) DEFAULT NULL,
            chapter_id bigint(20) UNSIGNED DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY chapter_id (chapter_id)
        ) $charset_collate;";
        
        // Distribution list members table
        $table_list_members = $wpdb->prefix . 'choose90_distribution_list_members';
        $sql_list_members = "CREATE TABLE IF NOT EXISTS $table_list_members (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            distribution_list_id bigint(20) UNSIGNED NOT NULL,
            user_id bigint(20) UNSIGNED NOT NULL,
            role varchar(50) DEFAULT 'member',
            added_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY distribution_list_id (distribution_list_id),
            KEY user_id (user_id),
            UNIQUE KEY unique_member (distribution_list_id, user_id)
        ) $charset_collate;";
        
        // Email threads table
        $table_threads = $wpdb->prefix . 'choose90_crm_threads';
        $sql_threads = "CREATE TABLE IF NOT EXISTS $table_threads (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            subject varchar(500) DEFAULT NULL,
            chapter_id bigint(20) UNSIGNED DEFAULT NULL,
            contact_id bigint(20) UNSIGNED DEFAULT NULL,
            last_message_at datetime DEFAULT CURRENT_TIMESTAMP,
            status varchar(50) DEFAULT 'active',
            PRIMARY KEY (id),
            KEY chapter_id (chapter_id),
            KEY contact_id (contact_id)
        ) $charset_collate;";
        
        // Newsletter signups table
        $table_newsletter = $wpdb->prefix . 'choose90_newsletter_signups';
        $sql_newsletter = "CREATE TABLE IF NOT EXISTS $table_newsletter (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            email varchar(255) NOT NULL,
            name varchar(255) DEFAULT NULL,
            signup_location varchar(100) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY email (email),
            KEY signup_location (signup_location),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_distribution_lists);
        dbDelta($sql_list_members);
        dbDelta($sql_threads);
        dbDelta($sql_newsletter);
    }
    
    /**
     * Set default plugin options (static method)
     */
    private static function set_default_options() {
        $defaults = array(
            'crm_email_address' => 'chapters@choose90.org',
            'email_capture_method' => 'imap', // imap, forwarding, api
            'enable_notifications' => true,
            'notification_email' => get_option('admin_email'),
        );
        
        foreach ($defaults as $key => $value) {
            if (get_option('choose90_crm_' . $key) === false) {
                add_option('choose90_crm_' . $key, $value);
            }
        }
    }
}

/**
 * Initialize plugin
 */
function choose90_crm_init() {
    return Choose90_CRM::get_instance();
}

// Start the plugin
choose90_crm_init();

