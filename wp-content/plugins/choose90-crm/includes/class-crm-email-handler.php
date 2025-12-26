<?php
/**
 * Email Handler - Captures and processes emails
 */

if (!defined('ABSPATH')) {
    exit;
}

class Choose90_CRM_Email_Handler {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Add cron hook for email checking
        add_action('choose90_crm_check_emails', array($this, 'check_emails'));
        
        // Schedule cron if not already scheduled
        if (!wp_next_scheduled('choose90_crm_check_emails')) {
            wp_schedule_event(time(), 'hourly', 'choose90_crm_check_emails');
        }
        
        // Add custom cron interval
        add_filter('cron_schedules', array($this, 'add_cron_intervals'));
    }
    
    /**
     * Add custom cron intervals
     */
    public function add_cron_intervals($schedules) {
        $schedules['every_5_minutes'] = array(
            'interval' => 300,
            'display' => __('Every 5 Minutes', 'choose90-crm'),
        );
        return $schedules;
    }
    
    /**
     * Check emails (called by cron)
     */
    public function check_emails() {
        $method = get_option('choose90_crm_email_capture_method', 'imap');
        
        switch ($method) {
            case 'imap':
                $this->check_imap_emails();
                break;
            case 'api':
                $this->check_api_emails();
                break;
            default:
                // Forwarding method - emails are captured via webhook or manual import
                break;
        }
    }
    
    /**
     * Check IMAP emails
     */
    private function check_imap_emails() {
        $imap_host = get_option('choose90_crm_imap_host');
        $imap_user = get_option('choose90_crm_imap_user');
        $imap_pass = get_option('choose90_crm_imap_pass');
        $imap_port = get_option('choose90_crm_imap_port', 993);
        
        if (!$imap_host || !$imap_user || !$imap_pass) {
            return;
        }
        
        // Connect to IMAP
        $mailbox = '{' . $imap_host . ':' . $imap_port . '/imap/ssl}INBOX';
        
        if (!function_exists('imap_open')) {
            error_log('Choose90 CRM: IMAP extension not available');
            return;
        }
        
        $connection = @imap_open($mailbox, $imap_user, $imap_pass);
        
        if (!$connection) {
            error_log('Choose90 CRM: Failed to connect to IMAP - ' . imap_last_error());
            return;
        }
        
        // Get unread messages
        $emails = imap_search($connection, 'UNSEEN');
        
        if (!$emails) {
            imap_close($connection);
            return;
        }
        
        foreach ($emails as $email_number) {
            $this->process_imap_email($connection, $email_number);
        }
        
        imap_close($connection);
    }
    
    /**
     * Process a single IMAP email
     */
    private function process_imap_email($connection, $email_number) {
        $header = imap_headerinfo($connection, $email_number);
        $body = imap_body($connection, $email_number);
        $structure = imap_fetchstructure($connection, $email_number);
        
        // Extract email data
        $from_email = isset($header->from[0]->mailbox) ? 
            $header->from[0]->mailbox . '@' . $header->from[0]->host : '';
        $from_name = isset($header->from[0]->personal) ? $header->from[0]->personal : '';
        $subject = isset($header->subject) ? $header->subject : '';
        $to_email = isset($header->to[0]->mailbox) ? 
            $header->to[0]->mailbox . '@' . $header->to[0]->host : '';
        $date = isset($header->date) ? strtotime($header->date) : time();
        
        // Parse body (handle HTML/text)
        $body_text = $this->extract_text_from_body($body, $structure);
        
        // Determine chapter and distribution list from email
        $chapter_id = $this->determine_chapter_from_email($to_email, $subject);
        $thread_id = $this->get_or_create_thread($from_email, $subject, $chapter_id);
        
        // Save email
        $this->save_email(array(
            'from_email' => $from_email,
            'from_name' => $from_name,
            'to_email' => $to_email,
            'subject' => $subject,
            'body' => $body_text,
            'chapter_id' => $chapter_id,
            'thread_id' => $thread_id,
            'date' => $date,
        ));
        
        // Mark as read
        imap_setflag_full($connection, $email_number, "\\Seen");
    }
    
    /**
     * Extract text from email body
     */
    private function extract_text_from_body($body, $structure) {
        // Simple text extraction - can be enhanced for HTML emails
        if ($structure->type == 1) { // Multipart
            // Handle multipart messages
            return strip_tags($body);
        }
        
        return $body;
    }
    
    /**
     * Determine chapter from email address or subject
     */
    private function determine_chapter_from_email($to_email, $subject) {
        // Check for plus addressing: chapters+austin@choose90.org
        if (preg_match('/chapters\+([^@]+)@/', $to_email, $matches)) {
            $chapter_slug = $matches[1];
            $chapter = get_posts(array(
                'post_type' => 'chapter',
                'name' => $chapter_slug,
                'posts_per_page' => 1,
            ));
            
            if (!empty($chapter)) {
                return $chapter[0]->ID;
            }
        }
        
        // Check subject line for chapter name
        $chapters = get_posts(array(
            'post_type' => 'chapter',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ));
        
        foreach ($chapters as $chapter) {
            if (stripos($subject, $chapter->post_title) !== false) {
                return $chapter->ID;
            }
        }
        
        return null;
    }
    
    /**
     * Get or create email thread
     */
    private function get_or_create_thread($from_email, $subject, $chapter_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'choose90_crm_threads';
        
        // Try to find existing thread
        $thread = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE subject = %s AND chapter_id = %d ORDER BY id DESC LIMIT 1",
            $subject,
            $chapter_id
        ));
        
        if ($thread) {
            // Update last message time
            $wpdb->update(
                $table,
                array('last_message_at' => current_time('mysql')),
                array('id' => $thread->id),
                array('%s'),
                array('%d')
            );
            return $thread->id;
        }
        
        // Create new thread
        $wpdb->insert(
            $table,
            array(
                'subject' => $subject,
                'chapter_id' => $chapter_id,
                'last_message_at' => current_time('mysql'),
                'status' => 'active',
            ),
            array('%s', '%d', '%s', '%s')
        );
        
        return $wpdb->insert_id;
    }
    
    /**
     * Save email to database
     */
    public function save_email($email_data) {
        // Create or update contact
        $contact_id = $this->get_or_create_contact($email_data['from_email'], $email_data['from_name']);
        
        // Create email post
        $post_data = array(
            'post_type' => 'crm_email',
            'post_title' => $email_data['subject'],
            'post_content' => $email_data['body'],
            'post_status' => 'publish',
            'post_date' => date('Y-m-d H:i:s', $email_data['date']),
        );
        
        $email_id = wp_insert_post($post_data);
        
        if ($email_id) {
            // Save meta fields
            update_post_meta($email_id, '_crm_from_email', $email_data['from_email']);
            update_post_meta($email_id, '_crm_from_name', $email_data['from_name']);
            update_post_meta($email_id, '_crm_to_email', $email_data['to_email']);
            update_post_meta($email_id, '_crm_chapter_id', $email_data['chapter_id']);
            update_post_meta($email_id, '_crm_thread_id', $email_data['thread_id']);
            update_post_meta($email_id, '_crm_status', 'unread');
            update_post_meta($email_id, '_crm_contact_id', $contact_id);
            
            // Send notifications
            $this->send_notifications($email_id, $email_data);
        }
        
        return $email_id;
    }
    
    /**
     * Get or create contact
     */
    private function get_or_create_contact($email, $name) {
        // Check if contact exists
        $existing = get_posts(array(
            'post_type' => 'crm_contact',
            'meta_key' => '_crm_contact_email',
            'meta_value' => $email,
            'posts_per_page' => 1,
        ));
        
        if (!empty($existing)) {
            $contact_id = $existing[0]->ID;
            // Update last contact date
            update_post_meta($contact_id, '_crm_last_contact_date', current_time('Y-m-d'));
            return $contact_id;
        }
        
        // Create new contact
        $contact_data = array(
            'post_type' => 'crm_contact',
            'post_title' => $name ? $name : $email,
            'post_status' => 'publish',
        );
        
        $contact_id = wp_insert_post($contact_data);
        
        if ($contact_id) {
            update_post_meta($contact_id, '_crm_contact_email', $email);
            update_post_meta($contact_id, '_crm_last_contact_date', current_time('Y-m-d'));
        }
        
        return $contact_id;
    }
    
    /**
     * Send notifications to distribution list members
     */
    private function send_notifications($email_id, $email_data) {
        if (!get_option('choose90_crm_enable_notifications', true)) {
            return;
        }
        
        $chapter_id = $email_data['chapter_id'];
        if (!$chapter_id) {
            return;
        }
        
        // Get distribution lists for this chapter
        $distribution_lists = Choose90_CRM_Distribution_Lists::get_instance();
        $lists = $distribution_lists->get_all_lists($chapter_id);
        
        foreach ($lists as $list) {
            $members = $distribution_lists->get_list_members($list->id);
            
            foreach ($members as $member) {
                $user = get_userdata($member->user_id);
                if ($user) {
                    $this->send_email_notification($user->user_email, $email_id, $email_data);
                }
            }
        }
    }
    
    /**
     * Send email notification
     */
    private function send_email_notification($to_email, $email_id, $email_data) {
        $subject = sprintf(__('New email in Choose90 CRM: %s', 'choose90-crm'), $email_data['subject']);
        $message = sprintf(
            __('You have received a new email in the Choose90 CRM system.

From: %s
Subject: %s

View in dashboard: %s', 'choose90-crm'),
            $email_data['from_email'],
            $email_data['subject'],
            admin_url('admin.php?page=choose90-crm-dashboard&email_id=' . $email_id)
        );
        
        wp_mail($to_email, $subject, $message);
    }
    
    /**
     * Check API emails (for future implementation)
     */
    private function check_api_emails() {
        // Placeholder for API-based email capture (SendGrid, Mailgun, etc.)
    }
}




