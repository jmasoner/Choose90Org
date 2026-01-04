<?php
/**
 * Analytics Dashboard for Choose90
 * Integrates GA4, Search Console, Social Media, and WordPress data
 */

if (!defined('ABSPATH')) {
    exit;
}

class Choose90_CRM_Analytics {
    
    private static $instance = null;
    private $cache_group = 'choose90_analytics';
    private $cache_expiry = 60; // 60 seconds for real-time data
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Menu registration is now handled in class-crm-admin.php to avoid duplicates
        // Only register settings and AJAX handlers here
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_choose90_analytics_refresh', array($this, 'ajax_refresh_data'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    /**
     * Register settings for API keys
     */
    public function register_settings() {
        register_setting('choose90_analytics_settings', 'choose90_ga4_measurement_id');
        register_setting('choose90_analytics_settings', 'choose90_ga4_api_key');
        register_setting('choose90_analytics_settings', 'choose90_ga4_property_id');
        register_setting('choose90_analytics_settings', 'choose90_search_console_site');
        register_setting('choose90_analytics_settings', 'choose90_search_console_key');
        register_setting('choose90_analytics_settings', 'choose90_twitter_api_key');
        register_setting('choose90_analytics_settings', 'choose90_facebook_app_id');
        register_setting('choose90_analytics_settings', 'choose90_instagram_access_token');
        register_setting('choose90_analytics_settings', 'choose90_linkedin_access_token');
    }
    
    /**
     * Enqueue dashboard scripts and styles
     */
    public function enqueue_scripts($hook) {
        // Check if we're on the analytics dashboard or settings page
        if ($hook !== 'crm_page_choose90-crm-analytics' && $hook !== 'crm_page_choose90-crm-analytics-settings') {
            return;
        }
        
        // Chart.js for visualizations
        wp_enqueue_script(
            'chart-js',
            'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js',
            array(),
            '4.4.0',
            true
        );
        
        // Dashboard JavaScript
        wp_enqueue_script(
            'choose90-analytics-dashboard',
            plugin_dir_url(dirname(__FILE__)) . 'assets/js/analytics-dashboard.js',
            array('jquery', 'chart-js'),
            '1.0.0',
            true
        );
        
        // Dashboard CSS
        wp_enqueue_style(
            'choose90-analytics-dashboard',
            plugin_dir_url(dirname(__FILE__)) . 'assets/css/analytics-dashboard.css',
            array(),
            '1.0.0'
        );
        
        // Localize script with data
        wp_localize_script('choose90-analytics-dashboard', 'choose90Analytics', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('choose90_analytics_nonce'),
            'refresh_interval' => 60000, // 60 seconds
        ));
    }
    
    /**
     * Render analytics dashboard
     */
    public function render_dashboard() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        // Get cached data or fetch fresh
        $overview_data = $this->get_overview_metrics();
        $pledge_data = $this->get_pledge_analytics();
        $social_data = $this->get_social_media_data();
        $resource_data = $this->get_resource_engagement();
        $tool_data = $this->get_tool_usage();
        $newsletter_data = $this->get_newsletter_metrics();
        $seo_data = $this->get_seo_performance();
        $campaign_data = $this->get_campaign_performance();
        $traffic_data = $this->get_traffic_sources();
        
        $template_path = CHOOSE90_CRM_PLUGIN_DIR . 'templates/analytics-dashboard.php';
        if (file_exists($template_path)) {
            include $template_path;
        } else {
            echo '<div class="notice notice-error"><p>Analytics dashboard template not found at: ' . esc_html($template_path) . '</p></div>';
        }
    }
    
    /**
     * Render analytics settings page
     */
    public function render_settings() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        // Handle form submission
        if (isset($_POST['submit']) && check_admin_referer('choose90_analytics_settings')) {
            update_option('choose90_ga4_measurement_id', sanitize_text_field($_POST['ga4_measurement_id']));
            update_option('choose90_ga4_api_key', sanitize_text_field($_POST['ga4_api_key']));
            update_option('choose90_ga4_property_id', sanitize_text_field($_POST['ga4_property_id']));
            update_option('choose90_search_console_site', sanitize_text_field($_POST['search_console_site']));
            update_option('choose90_search_console_key', sanitize_text_field($_POST['search_console_key']));
            update_option('choose90_twitter_api_key', sanitize_text_field($_POST['twitter_api_key']));
            update_option('choose90_facebook_app_id', sanitize_text_field($_POST['facebook_app_id']));
            update_option('choose90_instagram_access_token', sanitize_text_field($_POST['instagram_access_token']));
            update_option('choose90_linkedin_access_token', sanitize_text_field($_POST['linkedin_access_token']));
            
            echo '<div class="notice notice-success"><p>' . __('Settings saved successfully!', 'choose90-crm') . '</p></div>';
        }
        
        $template_path = CHOOSE90_CRM_PLUGIN_DIR . 'templates/analytics-settings.php';
        if (file_exists($template_path)) {
            include $template_path;
        } else {
            echo '<div class="notice notice-error"><p>Analytics settings template not found at: ' . esc_html($template_path) . '</p></div>';
        }
    }
    
    /**
     * Get overview metrics (key stats at a glance)
     */
    public function get_overview_metrics() {
        $cache_key = 'overview_metrics';
        $cached = wp_cache_get($cache_key, $this->cache_group);
        
        if ($cached !== false) {
            return $cached;
        }
        
        $metrics = array(
            'total_visitors_today' => $this->get_ga4_metric('activeUsers', 'today'),
            'total_visitors_week' => $this->get_ga4_metric('activeUsers', '7daysAgo'),
            'pledge_conversions_today' => $this->get_pledge_count('today'),
            'pledge_conversions_week' => $this->get_pledge_count('week'),
            'newsletter_signups_today' => $this->get_newsletter_count('today'),
            'newsletter_signups_week' => $this->get_newsletter_count('week'),
            'social_shares_today' => $this->get_social_share_count('today'),
            'bounce_rate' => $this->get_ga4_metric('bounceRate', '7daysAgo'),
            'avg_session_duration' => $this->get_ga4_metric('averageSessionDuration', '7daysAgo'),
        );
        
        wp_cache_set($cache_key, $metrics, $this->cache_group, $this->cache_expiry);
        return $metrics;
    }
    
    /**
     * Get pledge analytics
     */
    public function get_pledge_analytics() {
        global $wpdb;
        
        $cache_key = 'pledge_analytics';
        $cached = wp_cache_get($cache_key, $this->cache_group);
        
        if ($cached !== false) {
            return $cached;
        }
        
        // Get pledge submissions from WordPress
        $pledge_count = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->posts} 
            WHERE post_type = 'page' 
            AND post_name = 'pledge' 
            AND post_status = 'publish'"
        );
        
        // Get pledge events from GA4 (if available)
        $pledge_events = $this->get_ga4_events('pledge_submitted', '7daysAgo');
        
        // Get conversion sources
        $conversion_sources = $this->get_ga4_conversion_sources('pledge_submitted', '30daysAgo');
        
        $data = array(
            'total_pledges' => $pledge_count,
            'pledges_last_7_days' => count($pledge_events),
            'pledges_last_30_days' => $this->get_pledge_count('month'),
            'conversion_rate' => $this->calculate_conversion_rate('pledge_submitted'),
            'sources' => $conversion_sources,
            'trend_data' => $this->get_pledge_trend_data('30daysAgo'),
        );
        
        wp_cache_set($cache_key, $data, $this->cache_group, $this->cache_expiry);
        return $data;
    }
    
    /**
     * Get social media performance
     */
    public function get_social_media_data() {
        $cache_key = 'social_media_data';
        $cached = wp_cache_get($cache_key, $this->cache_group);
        
        if ($cached !== false) {
            return $cached;
        }
        
        // Get hashtag performance from GA4
        $hashtag_data = $this->get_ga4_hashtag_performance('7daysAgo');
        
        // Get social share events
        $share_events = $this->get_ga4_events('social_share', '7daysAgo');
        
        // Get platform breakdown
        $platform_data = $this->get_social_platform_breakdown($share_events);
        
        $data = array(
            'hashtag_performance' => $hashtag_data,
            'total_shares' => count($share_events),
            'platform_breakdown' => $platform_data,
            'engagement_trend' => $this->get_social_engagement_trend('30daysAgo'),
        );
        
        wp_cache_set($cache_key, $data, $this->cache_group, $this->cache_expiry);
        return $data;
    }
    
    /**
     * Get resource engagement metrics
     */
    public function get_resource_engagement() {
        $cache_key = 'resource_engagement';
        $cached = wp_cache_get($cache_key, $this->cache_group);
        
        if ($cached !== false) {
            return $cached;
        }
        
        // Get resource view events
        $resource_views = $this->get_ga4_events('resource_viewed', '30daysAgo');
        
        // Get category filter events
        $category_filters = $this->get_ga4_events('category_filtered', '30daysAgo');
        
        // Process to get most popular resources
        $popular_resources = $this->process_resource_views($resource_views);
        $category_performance = $this->process_category_filters($category_filters);
        
        $data = array(
            'total_views' => count($resource_views),
            'popular_resources' => $popular_resources,
            'category_performance' => $category_performance,
            'trend_data' => $this->get_resource_trend_data('30daysAgo'),
        );
        
        wp_cache_set($cache_key, $data, $this->cache_group, $this->cache_expiry);
        return $data;
    }
    
    /**
     * Get tool usage metrics
     */
    public function get_tool_usage() {
        $cache_key = 'tool_usage';
        $cached = wp_cache_get($cache_key, $this->cache_group);
        
        if ($cached !== false) {
            return $cached;
        }
        
        // Get content generator usage
        $content_gen_events = $this->get_ga4_events('content_generator_used', '30daysAgo');
        
        // Get extension downloads
        $extension_events = $this->get_ga4_events('browser_extension_downloaded', '30daysAgo');
        
        // Get PWA installs
        $pwa_events = $this->get_ga4_events('pwa_installed', '30daysAgo');
        
        $data = array(
            'content_generator_uses' => count($content_gen_events),
            'extension_downloads' => count($extension_events),
            'pwa_installs' => count($pwa_events),
            'generation_types' => $this->process_generation_types($content_gen_events),
            'trend_data' => $this->get_tool_trend_data('30daysAgo'),
        );
        
        wp_cache_set($cache_key, $data, $this->cache_group, $this->cache_expiry);
        return $data;
    }
    
    /**
     * Get newsletter metrics
     */
    public function get_newsletter_metrics() {
        global $wpdb;
        
        $cache_key = 'newsletter_metrics';
        $cached = wp_cache_get($cache_key, $this->cache_group);
        
        if ($cached !== false) {
            return $cached;
        }
        
        // Get signup events from GA4
        $signup_events = $this->get_ga4_events('newsletter_signup', '30daysAgo');
        
        // Process signup locations
        $signup_locations = $this->process_signup_locations($signup_events);
        
        // Get from WordPress database if stored locally
        $local_signups = $this->get_local_newsletter_signups('30daysAgo');
        
        $data = array(
            'total_signups' => count($signup_events) + count($local_signups),
            'signups_last_7_days' => $this->get_newsletter_count('week'),
            'signups_last_30_days' => count($signup_events) + count($local_signups),
            'signup_locations' => $signup_locations,
            'trend_data' => $this->get_newsletter_trend_data('30daysAgo'),
        );
        
        wp_cache_set($cache_key, $data, $this->cache_group, $this->cache_expiry);
        return $data;
    }
    
    /**
     * Get SEO performance from Search Console
     */
    public function get_seo_performance() {
        $cache_key = 'seo_performance';
        $cached = wp_cache_get($cache_key, $this->cache_group);
        
        if ($cached !== false) {
            return $cached;
        }
        
        $search_console = $this->get_search_console_client();
        
        if (!$search_console) {
            return array(
                'enabled' => false,
                'message' => 'Search Console not configured'
            );
        }
        
        // Get search queries
        $queries = $this->get_search_console_queries('30daysAgo');
        
        // Get top pages
        $top_pages = $this->get_search_console_pages('30daysAgo');
        
        // Get click-through rate
        $ctr = $this->get_search_console_ctr('30daysAgo');
        
        $data = array(
            'enabled' => true,
            'total_clicks' => array_sum(array_column($queries, 'clicks')),
            'total_impressions' => array_sum(array_column($queries, 'impressions')),
            'average_ctr' => $ctr,
            'average_position' => $this->get_search_console_avg_position('30daysAgo'),
            'top_queries' => array_slice($queries, 0, 10),
            'top_pages' => array_slice($top_pages, 0, 10),
            'trend_data' => $this->get_search_console_trend('30daysAgo'),
        );
        
        wp_cache_set($cache_key, $data, $this->cache_group, $this->cache_expiry);
        return $data;
    }
    
    /**
     * Get campaign performance
     */
    public function get_campaign_performance() {
        $cache_key = 'campaign_performance';
        $cached = wp_cache_get($cache_key, $this->cache_group);
        
        if ($cached !== false) {
            return $cached;
        }
        
        $campaigns = array('kwanzaa', 'new_years', 'digital_detox');
        $campaign_data = array();
        
        foreach ($campaigns as $campaign) {
            $views = $this->get_ga4_events('campaign_viewed', '30daysAgo', array('campaign_name' => $campaign));
            $cta_clicks = $this->get_ga4_events('campaign_cta_clicked', '30daysAgo', array('campaign_name' => $campaign));
            
            $campaign_data[$campaign] = array(
                'views' => count($views),
                'cta_clicks' => count($cta_clicks),
                'conversion_rate' => count($views) > 0 ? (count($cta_clicks) / count($views)) * 100 : 0,
                'trend_data' => $this->get_campaign_trend($campaign, '30daysAgo'),
            );
        }
        
        wp_cache_set($cache_key, $campaign_data, $this->cache_group, $this->cache_expiry);
        return $campaign_data;
    }
    
    /**
     * Get traffic sources
     */
    public function get_traffic_sources() {
        $cache_key = 'traffic_sources';
        $cached = wp_cache_get($cache_key, $this->cache_group);
        
        if ($cached !== false) {
            return $cached;
        }
        
        $sources = $this->get_ga4_traffic_sources('30daysAgo');
        
        $data = array(
            'organic' => $sources['organic'] ?? 0,
            'direct' => $sources['direct'] ?? 0,
            'social' => $sources['social'] ?? 0,
            'referral' => $sources['referral'] ?? 0,
            'email' => $sources['email'] ?? 0,
            'paid' => $sources['paid'] ?? 0,
            'breakdown' => $sources,
        );
        
        wp_cache_set($cache_key, $data, $this->cache_group, $this->cache_expiry);
        return $data;
    }
    
    /**
     * AJAX handler for refreshing data
     */
    public function ajax_refresh_data() {
        check_ajax_referer('choose90_analytics_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Insufficient permissions'));
        }
        
        // Clear cache
        wp_cache_flush_group($this->cache_group);
        
        // Get fresh data
        $data = array(
            'overview' => $this->get_overview_metrics(),
            'pledge' => $this->get_pledge_analytics(),
            'social' => $this->get_social_media_data(),
            'resources' => $this->get_resource_engagement(),
            'tools' => $this->get_tool_usage(),
            'newsletter' => $this->get_newsletter_metrics(),
            'seo' => $this->get_seo_performance(),
            'campaigns' => $this->get_campaign_performance(),
            'traffic' => $this->get_traffic_sources(),
        );
        
        wp_send_json_success($data);
    }
    
    // ============================================
    // GA4 Integration Methods
    // ============================================
    
    /**
     * Get GA4 metric
     */
    private function get_ga4_metric($metric, $date_range) {
        // Placeholder - implement GA4 API integration
        // This would use Google Analytics Data API
        return 0;
    }
    
    /**
     * Get GA4 events
     */
    private function get_ga4_events($event_name, $date_range, $filters = array()) {
        // Placeholder - implement GA4 API integration
        return array();
    }
    
    /**
     * Get GA4 conversion sources
     */
    private function get_ga4_conversion_sources($event_name, $date_range) {
        // Placeholder
        return array();
    }
    
    /**
     * Get GA4 hashtag performance
     */
    private function get_ga4_hashtag_performance($date_range) {
        // Placeholder
        return array();
    }
    
    /**
     * Get GA4 traffic sources
     */
    private function get_ga4_traffic_sources($date_range) {
        // Placeholder
        return array();
    }
    
    // ============================================
    // Search Console Integration Methods
    // ============================================
    
    /**
     * Get Search Console client
     */
    private function get_search_console_client() {
        $api_key = get_option('choose90_search_console_key');
        $site = get_option('choose90_search_console_site');
        
        if (empty($api_key) || empty($site)) {
            return false;
        }
        
        // Placeholder - implement Search Console API
        return null;
    }
    
    /**
     * Get Search Console queries
     */
    private function get_search_console_queries($date_range) {
        // Placeholder
        return array();
    }
    
    /**
     * Get Search Console pages
     */
    private function get_search_console_pages($date_range) {
        // Placeholder
        return array();
    }
    
    /**
     * Get Search Console CTR
     */
    private function get_search_console_ctr($date_range) {
        // Placeholder
        return 0;
    }
    
    /**
     * Get Search Console average position
     */
    private function get_search_console_avg_position($date_range) {
        // Placeholder
        return 0;
    }
    
    /**
     * Get Search Console trend
     */
    private function get_search_console_trend($date_range) {
        // Placeholder
        return array();
    }
    
    // ============================================
    // WordPress Database Methods
    // ============================================
    
    /**
     * Get pledge count
     */
    private function get_pledge_count($period) {
        global $wpdb;
        
        $date_query = $this->get_date_query($period);
        
        // This would query your pledge storage
        // Adjust based on how pledges are stored
        return 0;
    }
    
    /**
     * Get newsletter count
     */
    private function get_newsletter_count($period) {
        global $wpdb;
        
        $date_query = $this->get_date_query($period);
        
        // Query newsletter signups from database
        $table = $wpdb->prefix . 'choose90_newsletter_signups';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            return 0;
        }
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE created_at >= %s",
            $date_query['start']
        ));
    }
    
    /**
     * Get social share count
     */
    private function get_social_share_count($period) {
        // Would query from GA4 events
        return 0;
    }
    
    /**
     * Get local newsletter signups
     */
    private function get_local_newsletter_signups($period) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'choose90_newsletter_signups';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            return array();
        }
        
        $date_query = $this->get_date_query($period);
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE created_at >= %s ORDER BY created_at DESC",
            $date_query['start']
        ));
    }
    
    // ============================================
    // Helper Methods
    // ============================================
    
    /**
     * Calculate conversion rate
     */
    private function calculate_conversion_rate($event_name) {
        // Placeholder
        return 0;
    }
    
    /**
     * Get date query for period
     */
    private function get_date_query($period) {
        $now = current_time('mysql');
        
        switch ($period) {
            case 'today':
                return array(
                    'start' => date('Y-m-d 00:00:00', strtotime($now)),
                    'end' => $now
                );
            case 'week':
                return array(
                    'start' => date('Y-m-d 00:00:00', strtotime('-7 days', strtotime($now))),
                    'end' => $now
                );
            case 'month':
                return array(
                    'start' => date('Y-m-d 00:00:00', strtotime('-30 days', strtotime($now))),
                    'end' => $now
                );
            case '30daysAgo':
                return array(
                    'start' => date('Y-m-d 00:00:00', strtotime('-30 days', strtotime($now))),
                    'end' => $now
                );
            case '7daysAgo':
                return array(
                    'start' => date('Y-m-d 00:00:00', strtotime('-7 days', strtotime($now))),
                    'end' => $now
                );
            default:
                return array(
                    'start' => date('Y-m-d 00:00:00', strtotime('-30 days', strtotime($now))),
                    'end' => $now
                );
        }
    }
    
    /**
     * Process resource views
     */
    private function process_resource_views($events) {
        $resources = array();
        
        foreach ($events as $event) {
            $resource_name = $event['resource_name'] ?? 'unknown';
            if (!isset($resources[$resource_name])) {
                $resources[$resource_name] = 0;
            }
            $resources[$resource_name]++;
        }
        
        arsort($resources);
        return $resources;
    }
    
    /**
     * Process category filters
     */
    private function process_category_filters($events) {
        $categories = array();
        
        foreach ($events as $event) {
            $category = $event['category'] ?? 'all';
            if (!isset($categories[$category])) {
                $categories[$category] = 0;
            }
            $categories[$category]++;
        }
        
        return $categories;
    }
    
    /**
     * Process generation types
     */
    private function process_generation_types($events) {
        $types = array();
        
        foreach ($events as $event) {
            $type = $event['generation_type'] ?? 'unknown';
            if (!isset($types[$type])) {
                $types[$type] = 0;
            }
            $types[$type]++;
        }
        
        return $types;
    }
    
    /**
     * Process signup locations
     */
    private function process_signup_locations($events) {
        $locations = array();
        
        foreach ($events as $event) {
            $location = $event['signup_location'] ?? 'unknown';
            if (!isset($locations[$location])) {
                $locations[$location] = 0;
            }
            $locations[$location]++;
        }
        
        return $locations;
    }
    
    /**
     * Get social platform breakdown
     */
    private function get_social_platform_breakdown($events) {
        $platforms = array();
        
        foreach ($events as $event) {
            $platform = $event['platform'] ?? 'unknown';
            if (!isset($platforms[$platform])) {
                $platforms[$platform] = 0;
            }
            $platforms[$platform]++;
        }
        
        return $platforms;
    }
    
    /**
     * Get pledge trend data
     */
    private function get_pledge_trend_data($date_range) {
        // Placeholder - would return daily pledge counts
        return array();
    }
    
    /**
     * Get social engagement trend
     */
    private function get_social_engagement_trend($date_range) {
        // Placeholder
        return array();
    }
    
    /**
     * Get resource trend data
     */
    private function get_resource_trend_data($date_range) {
        // Placeholder
        return array();
    }
    
    /**
     * Get tool trend data
     */
    private function get_tool_trend_data($date_range) {
        // Placeholder
        return array();
    }
    
    /**
     * Get newsletter trend data
     */
    private function get_newsletter_trend_data($date_range) {
        // Placeholder
        return array();
    }
    
    /**
     * Get campaign trend
     */
    private function get_campaign_trend($campaign_name, $date_range) {
        // Placeholder
        return array();
    }
}

