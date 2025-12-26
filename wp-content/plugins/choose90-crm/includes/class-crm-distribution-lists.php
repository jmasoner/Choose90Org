<?php
/**
 * Distribution Lists Management
 */

if (!defined('ABSPATH')) {
    exit;
}

class Choose90_CRM_Distribution_Lists {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Add AJAX handlers
        add_action('wp_ajax_choose90_crm_add_list_member', array($this, 'ajax_add_list_member'));
        add_action('wp_ajax_choose90_crm_remove_list_member', array($this, 'ajax_remove_list_member'));
    }
    
    /**
     * Create a distribution list
     */
    public function create_list($name, $email_alias = null, $chapter_id = null) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'choose90_distribution_lists';
        
        $result = $wpdb->insert(
            $table,
            array(
                'name' => sanitize_text_field($name),
                'email_alias' => sanitize_email($email_alias),
                'chapter_id' => $chapter_id ? intval($chapter_id) : null,
                'created_at' => current_time('mysql'),
            ),
            array('%s', '%s', '%d', '%s')
        );
        
        if ($result) {
            return $wpdb->insert_id;
        }
        
        return false;
    }
    
    /**
     * Get distribution list by ID
     */
    public function get_list($list_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'choose90_distribution_lists';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE id = %d",
            $list_id
        ));
    }
    
    /**
     * Get all distribution lists
     */
    public function get_all_lists($chapter_id = null) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'choose90_distribution_lists';
        
        if ($chapter_id) {
            return $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM $table WHERE chapter_id = %d ORDER BY name ASC",
                $chapter_id
            ));
        }
        
        return $wpdb->get_results("SELECT * FROM $table ORDER BY name ASC");
    }
    
    /**
     * Update distribution list
     */
    public function update_list($list_id, $data) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'choose90_distribution_lists';
        
        $update_data = array();
        $format = array();
        
        if (isset($data['name'])) {
            $update_data['name'] = sanitize_text_field($data['name']);
            $format[] = '%s';
        }
        
        if (isset($data['email_alias'])) {
            $update_data['email_alias'] = sanitize_email($data['email_alias']);
            $format[] = '%s';
        }
        
        if (isset($data['chapter_id'])) {
            $update_data['chapter_id'] = intval($data['chapter_id']);
            $format[] = '%d';
        }
        
        if (empty($update_data)) {
            return false;
        }
        
        return $wpdb->update(
            $table,
            $update_data,
            array('id' => $list_id),
            $format,
            array('%d')
        );
    }
    
    /**
     * Delete distribution list
     */
    public function delete_list($list_id) {
        global $wpdb;
        
        $table_lists = $wpdb->prefix . 'choose90_distribution_lists';
        $table_members = $wpdb->prefix . 'choose90_distribution_list_members';
        
        // Delete members first
        $wpdb->delete($table_members, array('distribution_list_id' => $list_id), array('%d'));
        
        // Delete list
        return $wpdb->delete($table_lists, array('id' => $list_id), array('%d'));
    }
    
    /**
     * Add member to distribution list
     */
    public function add_member($list_id, $user_id, $role = 'member') {
        global $wpdb;
        
        $table = $wpdb->prefix . 'choose90_distribution_list_members';
        
        // Check if member already exists
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE distribution_list_id = %d AND user_id = %d",
            $list_id,
            $user_id
        ));
        
        if ($existing) {
            // Update role if exists
            return $wpdb->update(
                $table,
                array('role' => sanitize_text_field($role)),
                array('id' => $existing),
                array('%s'),
                array('%d')
            );
        }
        
        return $wpdb->insert(
            $table,
            array(
                'distribution_list_id' => intval($list_id),
                'user_id' => intval($user_id),
                'role' => sanitize_text_field($role),
                'added_at' => current_time('mysql'),
            ),
            array('%d', '%d', '%s', '%s')
        );
    }
    
    /**
     * Remove member from distribution list
     */
    public function remove_member($list_id, $user_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'choose90_distribution_list_members';
        
        return $wpdb->delete(
            $table,
            array(
                'distribution_list_id' => intval($list_id),
                'user_id' => intval($user_id),
            ),
            array('%d', '%d')
        );
    }
    
    /**
     * Get members of a distribution list
     */
    public function get_list_members($list_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'choose90_distribution_list_members';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT m.*, u.display_name, u.user_email 
             FROM $table m 
             INNER JOIN {$wpdb->users} u ON m.user_id = u.ID 
             WHERE m.distribution_list_id = %d 
             ORDER BY m.role DESC, u.display_name ASC",
            $list_id
        ));
    }
    
    /**
     * Get distribution lists for a user
     */
    public function get_user_lists($user_id) {
        global $wpdb;
        
        $table_members = $wpdb->prefix . 'choose90_distribution_list_members';
        $table_lists = $wpdb->prefix . 'choose90_distribution_lists';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT l.*, m.role 
             FROM $table_lists l 
             INNER JOIN $table_members m ON l.id = m.distribution_list_id 
             WHERE m.user_id = %d 
             ORDER BY l.name ASC",
            $user_id
        ));
    }
    
    /**
     * Get distribution list by email alias
     */
    public function get_list_by_alias($email_alias) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'choose90_distribution_lists';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE email_alias = %s",
            $email_alias
        ));
    }
    
    /**
     * AJAX: Add list member
     */
    public function ajax_add_list_member() {
        check_ajax_referer('choose90_crm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'choose90-crm')));
        }
        
        $list_id = isset($_POST['list_id']) ? intval($_POST['list_id']) : 0;
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $role = isset($_POST['role']) ? sanitize_text_field($_POST['role']) : 'member';
        
        if (!$list_id || !$user_id) {
            wp_send_json_error(array('message' => __('Invalid parameters', 'choose90-crm')));
        }
        
        $result = $this->add_member($list_id, $user_id, $role);
        
        if ($result) {
            wp_send_json_success(array('message' => __('Member added successfully', 'choose90-crm')));
        } else {
            wp_send_json_error(array('message' => __('Failed to add member', 'choose90-crm')));
        }
    }
    
    /**
     * AJAX: Remove list member
     */
    public function ajax_remove_list_member() {
        check_ajax_referer('choose90_crm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'choose90-crm')));
        }
        
        $list_id = isset($_POST['list_id']) ? intval($_POST['list_id']) : 0;
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        
        if (!$list_id || !$user_id) {
            wp_send_json_error(array('message' => __('Invalid parameters', 'choose90-crm')));
        }
        
        $result = $this->remove_member($list_id, $user_id);
        
        if ($result) {
            wp_send_json_success(array('message' => __('Member removed successfully', 'choose90-crm')));
        } else {
            wp_send_json_error(array('message' => __('Failed to remove member', 'choose90-crm')));
        }
    }
}






