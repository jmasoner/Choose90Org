<?php
/**
 * Admin Interface for CRM
 */

if (!defined('ABSPATH')) {
    exit;
}

class Choose90_CRM_Admin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_choose90_crm_create_list', array($this, 'ajax_create_list'));
        add_action('wp_ajax_choose90_crm_delete_list', array($this, 'ajax_delete_list'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Main menu - accessible to all users with read capability
        // Content will differ based on user capabilities
        add_menu_page(
            __('Choose90 CRM', 'choose90-crm'),
            __('CRM', 'choose90-crm'),
            'read', // Changed from 'manage_options' to 'read' so dashboard submenu can appear
            'choose90-crm',
            array($this, 'render_main_page'),
            'dashicons-email-alt',
            30
        );
        
        add_submenu_page(
            'choose90-crm',
            __('Distribution Lists', 'choose90-crm'),
            __('Distribution Lists', 'choose90-crm'),
            'manage_options',
            'choose90-crm-distribution-lists',
            array($this, 'render_distribution_lists')
        );
        
        add_submenu_page(
            'choose90-crm',
            __('Settings', 'choose90-crm'),
            __('Settings', 'choose90-crm'),
            'manage_options',
            'choose90-crm-settings',
            array($this, 'render_settings')
        );
        
        // Analytics Dashboard (registered here to ensure it appears)
        // Ensure Analytics class is loaded before checking
        $analytics_file = CHOOSE90_CRM_PLUGIN_DIR . 'includes/class-crm-analytics.php';
        if (!class_exists('Choose90_CRM_Analytics') && file_exists($analytics_file)) {
            require_once $analytics_file;
        }
        
        // Only register menu if class exists AND menu doesn't already exist (prevent duplicates)
        global $submenu;
        $menu_already_exists = false;
        if (isset($submenu['choose90-crm'])) {
            foreach ($submenu['choose90-crm'] as $item) {
                if (isset($item[2]) && ($item[2] === 'choose90-crm-analytics' || $item[2] === 'choose90-crm-analytics-settings')) {
                    $menu_already_exists = true;
                    break;
                }
            }
        }
        
        if (class_exists('Choose90_CRM_Analytics') && !$menu_already_exists) {
            add_submenu_page(
                'choose90-crm',
                __('Analytics Dashboard', 'choose90-crm'),
                __('Analytics', 'choose90-crm'),
                'manage_options',
                'choose90-crm-analytics',
                array($this, 'render_analytics_dashboard')
            );
            
            add_submenu_page(
                'choose90-crm',
                __('Analytics Settings', 'choose90-crm'),
                __('Analytics Settings', 'choose90-crm'),
                'manage_options',
                'choose90-crm-analytics-settings',
                array($this, 'render_analytics_settings')
            );
        }
    }
    
    /**
     * Render analytics dashboard (wrapper to call Analytics class)
     */
    public function render_analytics_dashboard() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        // Check if class exists
        if (!class_exists('Choose90_CRM_Analytics')) {
            echo '<div class="wrap"><h1>Analytics Dashboard</h1>';
            echo '<div class="notice notice-error"><p><strong>Error:</strong> Choose90_CRM_Analytics class not found.</p>';
            echo '<p>This means the Analytics class file is not being loaded. Check:</p>';
            echo '<ul><li>File exists: wp-content/plugins/choose90-crm/includes/class-crm-analytics.php</li>';
            echo '<li>Plugin is activated</li>';
            echo '<li>No PHP syntax errors in the Analytics class file</li></ul></div></div>';
            return;
        }
        
        // Try to get instance
        try {
            $analytics = Choose90_CRM_Analytics::get_instance();
            if (!$analytics) {
                throw new Exception('get_instance() returned null');
            }
            
            // Check if method exists
            if (!method_exists($analytics, 'render_dashboard')) {
                echo '<div class="wrap"><h1>Analytics Dashboard</h1>';
                echo '<div class="notice notice-error"><p><strong>Error:</strong> render_dashboard() method not found in Analytics class.</p></div></div>';
                return;
            }
            
            // Call the method
            $analytics->render_dashboard();
            
        } catch (Exception $e) {
            echo '<div class="wrap"><h1>Analytics Dashboard</h1>';
            echo '<div class="notice notice-error">';
            echo '<p><strong>Error loading Analytics:</strong></p>';
            echo '<p>' . esc_html($e->getMessage()) . '</p>';
            echo '<p>Stack trace:</p><pre>' . esc_html($e->getTraceAsString()) . '</pre>';
            echo '</div></div>';
        } catch (Error $e) {
            echo '<div class="wrap"><h1>Analytics Dashboard</h1>';
            echo '<div class="notice notice-error">';
            echo '<p><strong>Fatal Error:</strong></p>';
            echo '<p>' . esc_html($e->getMessage()) . '</p>';
            echo '</div></div>';
        }
    }
    
    /**
     * Render analytics settings (wrapper to call Analytics class)
     */
    public function render_analytics_settings() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        // Check if class exists
        if (!class_exists('Choose90_CRM_Analytics')) {
            echo '<div class="wrap"><h1>Analytics Settings</h1>';
            echo '<div class="notice notice-error"><p><strong>Error:</strong> Choose90_CRM_Analytics class not found.</p></div></div>';
            return;
        }
        
        // Try to get instance
        try {
            $analytics = Choose90_CRM_Analytics::get_instance();
            if (!$analytics) {
                throw new Exception('get_instance() returned null');
            }
            
            if (!method_exists($analytics, 'render_settings')) {
                echo '<div class="wrap"><h1>Analytics Settings</h1>';
                echo '<div class="notice notice-error"><p><strong>Error:</strong> render_settings() method not found.</p></div></div>';
                return;
            }
            
            $analytics->render_settings();
            
        } catch (Exception $e) {
            echo '<div class="wrap"><h1>Analytics Settings</h1>';
            echo '<div class="notice notice-error"><p><strong>Error:</strong> ' . esc_html($e->getMessage()) . '</p></div></div>';
        } catch (Error $e) {
            echo '<div class="wrap"><h1>Analytics Settings</h1>';
            echo '<div class="notice notice-error"><p><strong>Fatal Error:</strong> ' . esc_html($e->getMessage()) . '</p></div></div>';
        }
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('choose90_crm_settings', 'choose90_crm_email_address');
        register_setting('choose90_crm_settings', 'choose90_crm_email_capture_method');
        register_setting('choose90_crm_settings', 'choose90_crm_imap_host');
        register_setting('choose90_crm_settings', 'choose90_crm_imap_user');
        register_setting('choose90_crm_settings', 'choose90_crm_imap_pass');
        register_setting('choose90_crm_settings', 'choose90_crm_imap_port');
        register_setting('choose90_crm_settings', 'choose90_crm_enable_notifications');
        register_setting('choose90_crm_settings', 'choose90_crm_notification_email');
    }
    
    /**
     * Render main CRM page
     */
    public function render_main_page() {
        // If user doesn't have admin capabilities, redirect to dashboard
        if (!current_user_can('manage_options')) {
            // Redirect non-admins to their dashboard
            wp_redirect(admin_url('admin.php?page=choose90-crm-dashboard'));
            exit;
        }
        
        // Get statistics
        $stats = $this->get_overall_stats();
        
        ?>
        <div class="wrap choose90-crm-admin">
            <h1><?php _e('Choose90 CRM', 'choose90-crm'); ?></h1>
            
            <div class="crm-stats">
                <div class="stat-box">
                    <h3><?php echo number_format($stats['total_emails']); ?></h3>
                    <p><?php _e('Total Emails', 'choose90-crm'); ?></p>
                </div>
                <div class="stat-box">
                    <h3><?php echo number_format($stats['unread_emails']); ?></h3>
                    <p><?php _e('Unread Emails', 'choose90-crm'); ?></p>
                </div>
                <div class="stat-box">
                    <h3><?php echo number_format($stats['total_contacts']); ?></h3>
                    <p><?php _e('Total Contacts', 'choose90-crm'); ?></p>
                </div>
                <div class="stat-box">
                    <h3><?php echo number_format($stats['distribution_lists']); ?></h3>
                    <p><?php _e('Distribution Lists', 'choose90-crm'); ?></p>
                </div>
            </div>
            
            <div class="crm-quick-links">
                <h2><?php _e('Quick Links', 'choose90-crm'); ?></h2>
                <ul>
                    <li><a href="<?php echo admin_url('edit.php?post_type=crm_email'); ?>"><?php _e('View All Emails', 'choose90-crm'); ?></a></li>
                    <li><a href="<?php echo admin_url('edit.php?post_type=crm_contact'); ?>"><?php _e('View All Contacts', 'choose90-crm'); ?></a></li>
                    <li><a href="<?php echo admin_url('admin.php?page=choose90-crm-distribution-lists'); ?>"><?php _e('Manage Distribution Lists', 'choose90-crm'); ?></a></li>
                    <li><a href="<?php echo admin_url('admin.php?page=choose90-crm-settings'); ?>"><?php _e('CRM Settings', 'choose90-crm'); ?></a></li>
                </ul>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render distribution lists page
     */
    public function render_distribution_lists() {
        $distribution_lists = Choose90_CRM_Distribution_Lists::get_instance();
        $lists = $distribution_lists->get_all_lists();
        
        // Handle form submissions
        if (isset($_POST['create_list']) && check_admin_referer('create_distribution_list')) {
            $name = sanitize_text_field($_POST['list_name']);
            $email_alias = sanitize_email($_POST['email_alias']);
            $chapter_id = isset($_POST['chapter_id']) ? intval($_POST['chapter_id']) : null;
            
            if ($name) {
                $distribution_lists->create_list($name, $email_alias, $chapter_id);
                echo '<div class="notice notice-success"><p>' . __('Distribution list created!', 'choose90-crm') . '</p></div>';
                $lists = $distribution_lists->get_all_lists(); // Refresh
            }
        }
        
        ?>
        <div class="wrap choose90-crm-distribution-lists">
            <h1><?php _e('Distribution Lists', 'choose90-crm'); ?></h1>
            
            <div class="create-list-form">
                <h2><?php _e('Create New Distribution List', 'choose90-crm'); ?></h2>
                <form method="post">
                    <?php wp_nonce_field('create_distribution_list'); ?>
                    <table class="form-table">
                        <tr>
                            <th><label for="list_name"><?php _e('List Name', 'choose90-crm'); ?></label></th>
                            <td><input type="text" id="list_name" name="list_name" class="regular-text" required /></td>
                        </tr>
                        <tr>
                            <th><label for="email_alias"><?php _e('Email Alias', 'choose90-crm'); ?></label></th>
                            <td>
                                <input type="email" id="email_alias" name="email_alias" class="regular-text" placeholder="chapters+austin@choose90.org" />
                                <p class="description"><?php _e('Optional: Email address for routing (e.g., chapters+austin@choose90.org)', 'choose90-crm'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="chapter_id"><?php _e('Chapter', 'choose90-crm'); ?></label></th>
                            <td>
                                <?php
                                $chapters = get_posts(array(
                                    'post_type' => 'chapter',
                                    'posts_per_page' => -1,
                                    'post_status' => 'publish',
                                ));
                                ?>
                                <select id="chapter_id" name="chapter_id">
                                    <option value=""><?php _e('Select Chapter (Optional)', 'choose90-crm'); ?></option>
                                    <?php foreach ($chapters as $chapter) : ?>
                                        <option value="<?php echo $chapter->ID; ?>"><?php echo esc_html($chapter->post_title); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" name="create_list" class="button button-primary" value="<?php _e('Create List', 'choose90-crm'); ?>" />
                    </p>
                </form>
            </div>
            
            <div class="existing-lists">
                <h2><?php _e('Existing Distribution Lists', 'choose90-crm'); ?></h2>
                <?php if (empty($lists)) : ?>
                    <p><?php _e('No distribution lists found.', 'choose90-crm'); ?></p>
                <?php else : ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e('Name', 'choose90-crm'); ?></th>
                                <th><?php _e('Email Alias', 'choose90-crm'); ?></th>
                                <th><?php _e('Chapter', 'choose90-crm'); ?></th>
                                <th><?php _e('Members', 'choose90-crm'); ?></th>
                                <th><?php _e('Actions', 'choose90-crm'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lists as $list) : 
                                $members = $distribution_lists->get_list_members($list->id);
                                $chapter = $list->chapter_id ? get_post($list->chapter_id) : null;
                            ?>
                                <tr>
                                    <td><strong><?php echo esc_html($list->name); ?></strong></td>
                                    <td><?php echo esc_html($list->email_alias ? $list->email_alias : '-'); ?></td>
                                    <td><?php echo $chapter ? esc_html($chapter->post_title) : '-'; ?></td>
                                    <td><?php echo count($members); ?></td>
                                    <td>
                                        <a href="#" class="manage-members" data-list-id="<?php echo $list->id; ?>">
                                            <?php _e('Manage Members', 'choose90-crm'); ?>
                                        </a> |
                                        <a href="#" class="delete-list" data-list-id="<?php echo $list->id; ?>">
                                            <?php _e('Delete', 'choose90-crm'); ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr class="members-row" id="members-<?php echo $list->id; ?>" style="display: none;">
                                    <td colspan="5">
                                        <h4><?php _e('Members', 'choose90-crm'); ?></h4>
                                        <ul>
                                            <?php foreach ($members as $member) : ?>
                                                <li>
                                                    <?php echo esc_html($member->display_name); ?> 
                                                    (<?php echo esc_html($member->user_email); ?>) 
                                                    - <?php echo esc_html($member->role); ?>
                                                    <a href="#" class="remove-member" data-list-id="<?php echo $list->id; ?>" data-user-id="<?php echo $member->user_id; ?>">
                                                        <?php _e('Remove', 'choose90-crm'); ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div class="add-member-form">
                                            <select id="user-select-<?php echo $list->id; ?>">
                                                <option value=""><?php _e('Select User', 'choose90-crm'); ?></option>
                                                <?php
                                                $users = get_users();
                                                foreach ($users as $user) {
                                                    echo '<option value="' . $user->ID . '">' . esc_html($user->display_name) . ' (' . esc_html($user->user_email) . ')</option>';
                                                }
                                                ?>
                                            </select>
                                            <button class="button add-member" data-list-id="<?php echo $list->id; ?>">
                                                <?php _e('Add Member', 'choose90-crm'); ?>
                                            </button>
                                        </div>
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
     * Render settings page
     */
    public function render_settings() {
        if (isset($_POST['submit']) && check_admin_referer('choose90_crm_settings')) {
            // Settings are saved via register_setting
            echo '<div class="notice notice-success"><p>' . __('Settings saved!', 'choose90-crm') . '</p></div>';
        }
        
        ?>
        <div class="wrap choose90-crm-settings">
            <h1><?php _e('CRM Settings', 'choose90-crm'); ?></h1>
            
            <form method="post" action="options.php">
                <?php settings_fields('choose90_crm_settings'); ?>
                
                <table class="form-table">
                    <tr>
                        <th><label for="choose90_crm_email_address"><?php _e('CRM Email Address', 'choose90-crm'); ?></label></th>
                        <td>
                            <input type="email" id="choose90_crm_email_address" name="choose90_crm_email_address" 
                                   value="<?php echo esc_attr(get_option('choose90_crm_email_address', 'chapters@choose90.org')); ?>" 
                                   class="regular-text" />
                            <p class="description"><?php _e('Primary email address for chapter communications', 'choose90-crm'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th><label for="choose90_crm_email_capture_method"><?php _e('Email Capture Method', 'choose90-crm'); ?></label></th>
                        <td>
                            <select id="choose90_crm_email_capture_method" name="choose90_crm_email_capture_method">
                                <option value="imap" <?php selected(get_option('choose90_crm_email_capture_method'), 'imap'); ?>>
                                    <?php _e('IMAP', 'choose90-crm'); ?>
                                </option>
                                <option value="forwarding" <?php selected(get_option('choose90_crm_email_capture_method'), 'forwarding'); ?>>
                                    <?php _e('Email Forwarding', 'choose90-crm'); ?>
                                </option>
                                <option value="api" <?php selected(get_option('choose90_crm_email_capture_method'), 'api'); ?>>
                                    <?php _e('API (Future)', 'choose90-crm'); ?>
                                </option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr class="imap-settings">
                        <th><label for="choose90_crm_imap_host"><?php _e('IMAP Host', 'choose90-crm'); ?></label></th>
                        <td>
                            <input type="text" id="choose90_crm_imap_host" name="choose90_crm_imap_host" 
                                   value="<?php echo esc_attr(get_option('choose90_crm_imap_host')); ?>" 
                                   class="regular-text" placeholder="imap.choose90.org" />
                        </td>
                    </tr>
                    
                    <tr class="imap-settings">
                        <th><label for="choose90_crm_imap_user"><?php _e('IMAP Username', 'choose90-crm'); ?></label></th>
                        <td>
                            <input type="text" id="choose90_crm_imap_user" name="choose90_crm_imap_user" 
                                   value="<?php echo esc_attr(get_option('choose90_crm_imap_user')); ?>" 
                                   class="regular-text" />
                        </td>
                    </tr>
                    
                    <tr class="imap-settings">
                        <th><label for="choose90_crm_imap_pass"><?php _e('IMAP Password', 'choose90-crm'); ?></label></th>
                        <td>
                            <input type="password" id="choose90_crm_imap_pass" name="choose90_crm_imap_pass" 
                                   value="<?php echo esc_attr(get_option('choose90_crm_imap_pass')); ?>" 
                                   class="regular-text" />
                        </td>
                    </tr>
                    
                    <tr class="imap-settings">
                        <th><label for="choose90_crm_imap_port"><?php _e('IMAP Port', 'choose90-crm'); ?></label></th>
                        <td>
                            <input type="number" id="choose90_crm_imap_port" name="choose90_crm_imap_port" 
                                   value="<?php echo esc_attr(get_option('choose90_crm_imap_port', 993)); ?>" 
                                   class="small-text" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th><label for="choose90_crm_enable_notifications"><?php _e('Enable Notifications', 'choose90-crm'); ?></label></th>
                        <td>
                            <input type="checkbox" id="choose90_crm_enable_notifications" name="choose90_crm_enable_notifications" 
                                   value="1" <?php checked(get_option('choose90_crm_enable_notifications', true)); ?> />
                            <label for="choose90_crm_enable_notifications"><?php _e('Send email notifications to distribution list members', 'choose90-crm'); ?></label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th><label for="choose90_crm_notification_email"><?php _e('Notification Email', 'choose90-crm'); ?></label></th>
                        <td>
                            <input type="email" id="choose90_crm_notification_email" name="choose90_crm_notification_email" 
                                   value="<?php echo esc_attr(get_option('choose90_crm_notification_email', get_option('admin_email'))); ?>" 
                                   class="regular-text" />
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Get overall statistics
     */
    private function get_overall_stats() {
        $emails = get_posts(array(
            'post_type' => 'crm_email',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ));
        
        $unread = 0;
        foreach ($emails as $email) {
            $status = get_post_meta($email->ID, '_crm_status', true);
            if ($status === 'unread') {
                $unread++;
            }
        }
        
        $contacts = get_posts(array(
            'post_type' => 'crm_contact',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ));
        
        $distribution_lists = Choose90_CRM_Distribution_Lists::get_instance();
        $lists = $distribution_lists->get_all_lists();
        
        return array(
            'total_emails' => count($emails),
            'unread_emails' => $unread,
            'total_contacts' => count($contacts),
            'distribution_lists' => count($lists),
        );
    }
    
    /**
     * AJAX: Create list
     */
    public function ajax_create_list() {
        check_ajax_referer('choose90_crm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'choose90-crm')));
        }
        
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email_alias = isset($_POST['email_alias']) ? sanitize_email($_POST['email_alias']) : null;
        $chapter_id = isset($_POST['chapter_id']) ? intval($_POST['chapter_id']) : null;
        
        if (!$name) {
            wp_send_json_error(array('message' => __('Name is required', 'choose90-crm')));
        }
        
        $distribution_lists = Choose90_CRM_Distribution_Lists::get_instance();
        $list_id = $distribution_lists->create_list($name, $email_alias, $chapter_id);
        
        if ($list_id) {
            wp_send_json_success(array('message' => __('List created successfully', 'choose90-crm'), 'list_id' => $list_id));
        } else {
            wp_send_json_error(array('message' => __('Failed to create list', 'choose90-crm')));
        }
    }
    
    /**
     * AJAX: Delete list
     */
    public function ajax_delete_list() {
        check_ajax_referer('choose90_crm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'choose90-crm')));
        }
        
        $list_id = isset($_POST['list_id']) ? intval($_POST['list_id']) : 0;
        
        if (!$list_id) {
            wp_send_json_error(array('message' => __('Invalid list ID', 'choose90-crm')));
        }
        
        $distribution_lists = Choose90_CRM_Distribution_Lists::get_instance();
        $result = $distribution_lists->delete_list($list_id);
        
        if ($result) {
            wp_send_json_success(array('message' => __('List deleted successfully', 'choose90-crm')));
        } else {
            wp_send_json_error(array('message' => __('Failed to delete list', 'choose90-crm')));
        }
    }
}

