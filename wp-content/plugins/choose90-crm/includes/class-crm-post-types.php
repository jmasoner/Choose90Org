<?php
/**
 * Custom Post Types for CRM
 */

if (!defined('ABSPATH')) {
    exit;
}

class Choose90_CRM_Post_Types {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
    }
    
    /**
     * Register custom post types
     */
    public function register_post_types() {
        // CRM Email Post Type
        register_post_type('crm_email', array(
            'labels' => array(
                'name' => __('CRM Emails', 'choose90-crm'),
                'singular_name' => __('CRM Email', 'choose90-crm'),
                'add_new' => __('Add New Email', 'choose90-crm'),
                'add_new_item' => __('Add New Email', 'choose90-crm'),
                'edit_item' => __('Edit Email', 'choose90-crm'),
                'new_item' => __('New Email', 'choose90-crm'),
                'view_item' => __('View Email', 'choose90-crm'),
                'search_items' => __('Search Emails', 'choose90-crm'),
                'not_found' => __('No emails found', 'choose90-crm'),
                'not_found_in_trash' => __('No emails found in trash', 'choose90-crm'),
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'choose90-crm',
            'menu_icon' => 'dashicons-email-alt',
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports' => array('title', 'editor', 'custom-fields'),
            'has_archive' => false,
            'rewrite' => false,
            'query_var' => false,
        ));
        
        // CRM Contact Post Type
        register_post_type('crm_contact', array(
            'labels' => array(
                'name' => __('CRM Contacts', 'choose90-crm'),
                'singular_name' => __('CRM Contact', 'choose90-crm'),
                'add_new' => __('Add New Contact', 'choose90-crm'),
                'add_new_item' => __('Add New Contact', 'choose90-crm'),
                'edit_item' => __('Edit Contact', 'choose90-crm'),
                'new_item' => __('New Contact', 'choose90-crm'),
                'view_item' => __('View Contact', 'choose90-crm'),
                'search_items' => __('Search Contacts', 'choose90-crm'),
                'not_found' => __('No contacts found', 'choose90-crm'),
                'not_found_in_trash' => __('No contacts found in trash', 'choose90-crm'),
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'choose90-crm',
            'menu_icon' => 'dashicons-groups',
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports' => array('title', 'editor', 'custom-fields'),
            'has_archive' => false,
            'rewrite' => false,
            'query_var' => false,
        ));
    }
    
    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Status taxonomy for emails
        register_taxonomy('crm_email_status', 'crm_email', array(
            'labels' => array(
                'name' => __('Email Status', 'choose90-crm'),
                'singular_name' => __('Status', 'choose90-crm'),
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'hierarchical' => true,
            'rewrite' => false,
        ));
        
        // Contact type taxonomy
        register_taxonomy('crm_contact_type', 'crm_contact', array(
            'labels' => array(
                'name' => __('Contact Type', 'choose90-crm'),
                'singular_name' => __('Type', 'choose90-crm'),
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'hierarchical' => true,
            'rewrite' => false,
        ));
        
        // Add default terms
        $this->add_default_terms();
    }
    
    /**
     * Add default taxonomy terms
     */
    private function add_default_terms() {
        // Email status terms
        $email_statuses = array('unread', 'read', 'replied', 'archived');
        foreach ($email_statuses as $status) {
            if (!term_exists($status, 'crm_email_status')) {
                wp_insert_term(ucfirst($status), 'crm_email_status', array('slug' => $status));
            }
        }
        
        // Contact type terms
        $contact_types = array('host', 'member', 'prospect', 'volunteer');
        foreach ($contact_types as $type) {
            if (!term_exists($type, 'crm_contact_type')) {
                wp_insert_term(ucfirst($type), 'crm_contact_type', array('slug' => $type));
            }
        }
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // Email meta boxes
        add_meta_box(
            'crm_email_details',
            __('Email Details', 'choose90-crm'),
            array($this, 'render_email_meta_box'),
            'crm_email',
            'normal',
            'high'
        );
        
        // Contact meta boxes
        add_meta_box(
            'crm_contact_details',
            __('Contact Details', 'choose90-crm'),
            array($this, 'render_contact_meta_box'),
            'crm_contact',
            'normal',
            'high'
        );
    }
    
    /**
     * Render email meta box
     */
    public function render_email_meta_box($post) {
        wp_nonce_field('crm_email_meta_box', 'crm_email_meta_box_nonce');
        
        $from_email = get_post_meta($post->ID, '_crm_from_email', true);
        $to_email = get_post_meta($post->ID, '_crm_to_email', true);
        $thread_id = get_post_meta($post->ID, '_crm_thread_id', true);
        $chapter_id = get_post_meta($post->ID, '_crm_chapter_id', true);
        $status = get_post_meta($post->ID, '_crm_status', true);
        $attachments = get_post_meta($post->ID, '_crm_attachments', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="crm_from_email"><?php _e('From Email', 'choose90-crm'); ?></label></th>
                <td><input type="email" id="crm_from_email" name="crm_from_email" value="<?php echo esc_attr($from_email); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="crm_to_email"><?php _e('To Email', 'choose90-crm'); ?></label></th>
                <td><input type="email" id="crm_to_email" name="crm_to_email" value="<?php echo esc_attr($to_email); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="crm_thread_id"><?php _e('Thread ID', 'choose90-crm'); ?></label></th>
                <td><input type="text" id="crm_thread_id" name="crm_thread_id" value="<?php echo esc_attr($thread_id); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="crm_chapter_id"><?php _e('Chapter', 'choose90-crm'); ?></label></th>
                <td>
                    <?php
                    $chapters = get_posts(array(
                        'post_type' => 'chapter',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                    ));
                    ?>
                    <select id="crm_chapter_id" name="crm_chapter_id">
                        <option value=""><?php _e('Select Chapter', 'choose90-crm'); ?></option>
                        <?php foreach ($chapters as $chapter) : ?>
                            <option value="<?php echo $chapter->ID; ?>" <?php selected($chapter_id, $chapter->ID); ?>>
                                <?php echo esc_html($chapter->post_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="crm_status"><?php _e('Status', 'choose90-crm'); ?></label></th>
                <td>
                    <select id="crm_status" name="crm_status">
                        <option value="unread" <?php selected($status, 'unread'); ?>><?php _e('Unread', 'choose90-crm'); ?></option>
                        <option value="read" <?php selected($status, 'read'); ?>><?php _e('Read', 'choose90-crm'); ?></option>
                        <option value="replied" <?php selected($status, 'replied'); ?>><?php _e('Replied', 'choose90-crm'); ?></option>
                        <option value="archived" <?php selected($status, 'archived'); ?>><?php _e('Archived', 'choose90-crm'); ?></option>
                    </select>
                </td>
            </tr>
            <?php if ($attachments) : ?>
            <tr>
                <th><?php _e('Attachments', 'choose90-crm'); ?></th>
                <td>
                    <?php
                    $attachments_array = maybe_unserialize($attachments);
                    if (is_array($attachments_array)) {
                        foreach ($attachments_array as $attachment) {
                            echo '<a href="' . esc_url($attachment['url']) . '" target="_blank">' . esc_html($attachment['name']) . '</a><br>';
                        }
                    }
                    ?>
                </td>
            </tr>
            <?php endif; ?>
        </table>
        <?php
    }
    
    /**
     * Render contact meta box
     */
    public function render_contact_meta_box($post) {
        wp_nonce_field('crm_contact_meta_box', 'crm_contact_meta_box_nonce');
        
        $email = get_post_meta($post->ID, '_crm_contact_email', true);
        $phone = get_post_meta($post->ID, '_crm_contact_phone', true);
        $chapters = get_post_meta($post->ID, '_crm_contact_chapters', true);
        $last_contact = get_post_meta($post->ID, '_crm_last_contact_date', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="crm_contact_email"><?php _e('Email', 'choose90-crm'); ?></label></th>
                <td><input type="email" id="crm_contact_email" name="crm_contact_email" value="<?php echo esc_attr($email); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="crm_contact_phone"><?php _e('Phone', 'choose90-crm'); ?></label></th>
                <td><input type="tel" id="crm_contact_phone" name="crm_contact_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="crm_contact_chapters"><?php _e('Associated Chapters', 'choose90-crm'); ?></label></th>
                <td>
                    <?php
                    $all_chapters = get_posts(array(
                        'post_type' => 'chapter',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                    ));
                    $selected_chapters = maybe_unserialize($chapters);
                    if (!is_array($selected_chapters)) {
                        $selected_chapters = array();
                    }
                    ?>
                    <select id="crm_contact_chapters" name="crm_contact_chapters[]" multiple class="regular-text" style="height: 150px;">
                        <?php foreach ($all_chapters as $chapter) : ?>
                            <option value="<?php echo $chapter->ID; ?>" <?php selected(in_array($chapter->ID, $selected_chapters)); ?>>
                                <?php echo esc_html($chapter->post_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="description"><?php _e('Hold Ctrl/Cmd to select multiple chapters', 'choose90-crm'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="crm_last_contact_date"><?php _e('Last Contact Date', 'choose90-crm'); ?></label></th>
                <td><input type="date" id="crm_last_contact_date" name="crm_last_contact_date" value="<?php echo esc_attr($last_contact); ?>" class="regular-text" /></td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save email meta
        if (isset($_POST['crm_email_meta_box_nonce']) && wp_verify_nonce($_POST['crm_email_meta_box_nonce'], 'crm_email_meta_box')) {
            if (isset($_POST['crm_from_email'])) {
                update_post_meta($post_id, '_crm_from_email', sanitize_email($_POST['crm_from_email']));
            }
            if (isset($_POST['crm_to_email'])) {
                update_post_meta($post_id, '_crm_to_email', sanitize_email($_POST['crm_to_email']));
            }
            if (isset($_POST['crm_thread_id'])) {
                update_post_meta($post_id, '_crm_thread_id', sanitize_text_field($_POST['crm_thread_id']));
            }
            if (isset($_POST['crm_chapter_id'])) {
                update_post_meta($post_id, '_crm_chapter_id', intval($_POST['crm_chapter_id']));
            }
            if (isset($_POST['crm_status'])) {
                update_post_meta($post_id, '_crm_status', sanitize_text_field($_POST['crm_status']));
            }
        }
        
        // Save contact meta
        if (isset($_POST['crm_contact_meta_box_nonce']) && wp_verify_nonce($_POST['crm_contact_meta_box_nonce'], 'crm_contact_meta_box')) {
            if (isset($_POST['crm_contact_email'])) {
                update_post_meta($post_id, '_crm_contact_email', sanitize_email($_POST['crm_contact_email']));
            }
            if (isset($_POST['crm_contact_phone'])) {
                update_post_meta($post_id, '_crm_contact_phone', sanitize_text_field($_POST['crm_contact_phone']));
            }
            if (isset($_POST['crm_contact_chapters'])) {
                $chapters = array_map('intval', $_POST['crm_contact_chapters']);
                update_post_meta($post_id, '_crm_contact_chapters', $chapters);
            } else {
                update_post_meta($post_id, '_crm_contact_chapters', array());
            }
            if (isset($_POST['crm_last_contact_date'])) {
                update_post_meta($post_id, '_crm_last_contact_date', sanitize_text_field($_POST['crm_last_contact_date']));
            }
        }
    }
}

