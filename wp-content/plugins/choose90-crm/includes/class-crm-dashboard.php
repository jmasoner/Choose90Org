<?php
/**
 * Chapter Leader Dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

class Choose90_CRM_Dashboard {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Use priority 11 to ensure main menu is registered first
        add_action('admin_menu', array($this, 'add_dashboard_menu'), 11);
        add_action('wp_ajax_choose90_crm_mark_read', array($this, 'ajax_mark_read'));
        add_action('wp_ajax_choose90_crm_mark_replied', array($this, 'ajax_mark_replied'));
    }
    
    /**
     * Add dashboard menu
     */
    public function add_dashboard_menu() {
        // Check if main CRM menu exists (for admins)
        global $submenu;
        $menu_exists = isset($submenu['choose90-crm']);
        
        if ($menu_exists) {
            // Main menu exists, add as submenu
            add_submenu_page(
                'choose90-crm',
                __('My Dashboard', 'choose90-crm'),
                __('My Dashboard', 'choose90-crm'),
                'read',
                'choose90-crm-dashboard',
                array($this, 'render_dashboard')
            );
        } else {
            // Main menu doesn't exist for this user, add as top-level menu
            add_menu_page(
                __('My CRM Dashboard', 'choose90-crm'),
                __('My CRM', 'choose90-crm'),
                'read',
                'choose90-crm-dashboard',
                array($this, 'render_dashboard'),
                'dashicons-email-alt',
                30
            );
        }
    }
    
    /**
     * Render dashboard
     */
    public function render_dashboard() {
        $current_user_id = get_current_user_id();
        $distribution_lists = Choose90_CRM_Distribution_Lists::get_instance();
        $user_lists = $distribution_lists->get_user_lists($current_user_id);
        
        // Get emails for user's distribution lists
        $emails = $this->get_user_emails($current_user_id, $user_lists);
        
        // Get statistics
        $stats = $this->get_user_stats($current_user_id, $user_lists);
        
        ?>
        <div class="wrap choose90-crm-dashboard">
            <h1><?php _e('My CRM Dashboard', 'choose90-crm'); ?></h1>
            
            <div class="crm-stats">
                <div class="stat-box">
                    <h3><?php echo number_format($stats['unread']); ?></h3>
                    <p><?php _e('Unread Emails', 'choose90-crm'); ?></p>
                </div>
                <div class="stat-box">
                    <h3><?php echo number_format($stats['replied']); ?></h3>
                    <p><?php _e('Replied', 'choose90-crm'); ?></p>
                </div>
                <div class="stat-box">
                    <h3><?php echo number_format($stats['total']); ?></h3>
                    <p><?php _e('Total Emails', 'choose90-crm'); ?></p>
                </div>
                <div class="stat-box">
                    <h3><?php echo number_format(count($user_lists)); ?></h3>
                    <p><?php _e('Distribution Lists', 'choose90-crm'); ?></p>
                </div>
            </div>
            
            <div class="crm-filters">
                <select id="crm-status-filter">
                    <option value=""><?php _e('All Statuses', 'choose90-crm'); ?></option>
                    <option value="unread"><?php _e('Unread', 'choose90-crm'); ?></option>
                    <option value="read"><?php _e('Read', 'choose90-crm'); ?></option>
                    <option value="replied"><?php _e('Replied', 'choose90-crm'); ?></option>
                    <option value="archived"><?php _e('Archived', 'choose90-crm'); ?></option>
                </select>
                
                <select id="crm-chapter-filter">
                    <option value=""><?php _e('All Chapters', 'choose90-crm'); ?></option>
                    <?php
                    $chapters = $this->get_user_chapters($user_lists);
                    foreach ($chapters as $chapter) {
                        echo '<option value="' . $chapter->ID . '">' . esc_html($chapter->post_title) . '</option>';
                    }
                    ?>
                </select>
                
                <input type="text" id="crm-search" placeholder="<?php _e('Search emails...', 'choose90-crm'); ?>" />
            </div>
            
            <div class="crm-emails-list">
                <?php if (empty($emails)) : ?>
                    <p><?php _e('No emails found.', 'choose90-crm'); ?></p>
                <?php else : ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e('From', 'choose90-crm'); ?></th>
                                <th><?php _e('Subject', 'choose90-crm'); ?></th>
                                <th><?php _e('Chapter', 'choose90-crm'); ?></th>
                                <th><?php _e('Status', 'choose90-crm'); ?></th>
                                <th><?php _e('Date', 'choose90-crm'); ?></th>
                                <th><?php _e('Actions', 'choose90-crm'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($emails as $email) : 
                                $status = get_post_meta($email->ID, '_crm_status', true);
                                $from_email = get_post_meta($email->ID, '_crm_from_email', true);
                                $chapter_id = get_post_meta($email->ID, '_crm_chapter_id', true);
                                $chapter = $chapter_id ? get_post($chapter_id) : null;
                            ?>
                                <tr data-status="<?php echo esc_attr($status); ?>" data-chapter="<?php echo esc_attr($chapter_id); ?>">
                                    <td><?php echo esc_html($from_email); ?></td>
                                    <td>
                                        <a href="<?php echo admin_url('post.php?post=' . $email->ID . '&action=edit'); ?>">
                                            <?php echo esc_html($email->post_title); ?>
                                        </a>
                                    </td>
                                    <td><?php echo $chapter ? esc_html($chapter->post_title) : '-'; ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo esc_attr($status); ?>">
                                            <?php echo esc_html(ucfirst($status)); ?>
                                        </span>
                                    </td>
                                    <td><?php echo get_the_date('Y-m-d H:i', $email->ID); ?></td>
                                    <td>
                                        <button class="button mark-read" data-email-id="<?php echo $email->ID; ?>">
                                            <?php _e('Mark Read', 'choose90-crm'); ?>
                                        </button>
                                        <button class="button mark-replied" data-email-id="<?php echo $email->ID; ?>">
                                            <?php _e('Mark Replied', 'choose90-crm'); ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get emails for user
     */
    private function get_user_emails($user_id, $user_lists) {
        if (empty($user_lists)) {
            return array();
        }
        
        $list_ids = array();
        foreach ($user_lists as $list) {
            $list_ids[] = $list->id;
        }
        
        // Get chapters from lists
        $chapter_ids = array();
        foreach ($user_lists as $list) {
            if ($list->chapter_id) {
                $chapter_ids[] = $list->chapter_id;
            }
        }
        
        // Query emails
        $args = array(
            'post_type' => 'crm_email',
            'posts_per_page' => 50,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => array(
                array(
                    'key' => '_crm_chapter_id',
                    'value' => $chapter_ids,
                    'compare' => 'IN',
                ),
            ),
        );
        
        return get_posts($args);
    }
    
    /**
     * Get user statistics
     */
    private function get_user_stats($user_id, $user_lists) {
        $emails = $this->get_user_emails($user_id, $user_lists);
        
        $stats = array(
            'total' => count($emails),
            'unread' => 0,
            'read' => 0,
            'replied' => 0,
            'archived' => 0,
        );
        
        foreach ($emails as $email) {
            $status = get_post_meta($email->ID, '_crm_status', true);
            if (isset($stats[$status])) {
                $stats[$status]++;
            }
        }
        
        return $stats;
    }
    
    /**
     * Get chapters for user
     */
    private function get_user_chapters($user_lists) {
        $chapter_ids = array();
        
        foreach ($user_lists as $list) {
            if ($list->chapter_id) {
                $chapter_ids[] = $list->chapter_id;
            }
        }
        
        if (empty($chapter_ids)) {
            return array();
        }
        
        return get_posts(array(
            'post_type' => 'chapter',
            'post__in' => array_unique($chapter_ids),
            'posts_per_page' => -1,
        ));
    }
    
    /**
     * AJAX: Mark email as read
     */
    public function ajax_mark_read() {
        check_ajax_referer('choose90_crm_nonce', 'nonce');
        
        $email_id = isset($_POST['email_id']) ? intval($_POST['email_id']) : 0;
        
        if (!$email_id) {
            wp_send_json_error(array('message' => __('Invalid email ID', 'choose90-crm')));
        }
        
        // Check if user has access to this email
        if (!$this->user_has_access($email_id)) {
            wp_send_json_error(array('message' => __('Access denied', 'choose90-crm')));
        }
        
        update_post_meta($email_id, '_crm_status', 'read');
        
        wp_send_json_success(array('message' => __('Email marked as read', 'choose90-crm')));
    }
    
    /**
     * AJAX: Mark email as replied
     */
    public function ajax_mark_replied() {
        check_ajax_referer('choose90_crm_nonce', 'nonce');
        
        $email_id = isset($_POST['email_id']) ? intval($_POST['email_id']) : 0;
        
        if (!$email_id) {
            wp_send_json_error(array('message' => __('Invalid email ID', 'choose90-crm')));
        }
        
        // Check if user has access to this email
        if (!$this->user_has_access($email_id)) {
            wp_send_json_error(array('message' => __('Access denied', 'choose90-crm')));
        }
        
        update_post_meta($email_id, '_crm_status', 'replied');
        
        wp_send_json_success(array('message' => __('Email marked as replied', 'choose90-crm')));
    }
    
    /**
     * Check if user has access to email
     */
    private function user_has_access($email_id) {
        $current_user_id = get_current_user_id();
        
        // Admins have access to all
        if (current_user_can('manage_options')) {
            return true;
        }
        
        $chapter_id = get_post_meta($email_id, '_crm_chapter_id', true);
        if (!$chapter_id) {
            return false;
        }
        
        $distribution_lists = Choose90_CRM_Distribution_Lists::get_instance();
        $user_lists = $distribution_lists->get_user_lists($current_user_id);
        
        foreach ($user_lists as $list) {
            if ($list->chapter_id == $chapter_id) {
                return true;
            }
        }
        
        return false;
    }
}

