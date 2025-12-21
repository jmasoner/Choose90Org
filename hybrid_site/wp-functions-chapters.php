<?php
/**
 * Choose90 Chapters Custom Functions
 * 
 * Handles custom meta fields, meta boxes, and chapter-specific functionality
 * Add this to your child theme's functions.php or include it
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Chapter Meta Fields
 * Adds custom meta boxes to chapter post type
 */
function choose90_register_chapter_meta_boxes() {
    add_meta_box(
        'chapter_details',
        'Chapter Details',
        'choose90_chapter_details_callback',
        'chapter',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'choose90_register_chapter_meta_boxes');

/**
 * Meta Box Callback - Chapter Details Form
 */
function choose90_chapter_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('choose90_save_chapter_meta', 'choose90_chapter_meta_nonce');
    
    // Get existing values
    $city = get_post_meta($post->ID, '_chapter_city', true);
    $state = get_post_meta($post->ID, '_chapter_state', true);
    $leader_name = get_post_meta($post->ID, '_chapter_leader_name', true);
    $leader_email = get_post_meta($post->ID, '_chapter_leader_email', true);
    $meeting_pattern = get_post_meta($post->ID, '_chapter_meeting_pattern', true);
    $location_name = get_post_meta($post->ID, '_chapter_location_name', true);
    $location_address = get_post_meta($post->ID, '_chapter_location_address', true);
    $status = get_post_meta($post->ID, '_chapter_status', true) ?: 'active';
    $member_count = get_post_meta($post->ID, '_chapter_member_count', true);
    
    // US States array
    $us_states = array(
        'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
        'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
        'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
        'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
        'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
        'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
        'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
        'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
        'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
        'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
        'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
        'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
        'WI' => 'Wisconsin', 'WY' => 'Wyoming', 'DC' => 'District of Columbia'
    );
    
    ?>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 15px;">
        
        <!-- Left Column -->
        <div>
            <h3 style="margin-top: 0; color: #0066CC;">Location Information</h3>
            
            <p>
                <label for="chapter_city" style="display: block; margin-bottom: 5px; font-weight: bold;">City *</label>
                <input type="text" id="chapter_city" name="chapter_city" value="<?php echo esc_attr($city); ?>" 
                       style="width: 100%; padding: 8px;" placeholder="e.g., Austin" required>
            </p>
            
            <p>
                <label for="chapter_state" style="display: block; margin-bottom: 5px; font-weight: bold;">State/Province *</label>
                <select id="chapter_state" name="chapter_state" style="width: 100%; padding: 8px;" required>
                    <option value="">Select State...</option>
                    <?php foreach ($us_states as $abbr => $name): ?>
                        <option value="<?php echo esc_attr($abbr); ?>" <?php selected($state, $abbr); ?>>
                            <?php echo esc_html($name); ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="Other" <?php selected($state, 'Other'); ?>>Other (International)</option>
                </select>
            </p>
            
            <h3 style="margin-top: 25px; color: #0066CC;">Meeting Information</h3>
            
            <p>
                <label for="chapter_meeting_pattern" style="display: block; margin-bottom: 5px; font-weight: bold;">Meeting Pattern</label>
                <input type="text" id="chapter_meeting_pattern" name="chapter_meeting_pattern" 
                       value="<?php echo esc_attr($meeting_pattern); ?>" 
                       style="width: 100%; padding: 8px;" 
                       placeholder="e.g., 3rd Tuesday @ 7 PM">
                <small style="color: #666;">When does this chapter meet? (e.g., "3rd Tuesday @ 7 PM", "Every 2nd Saturday")</small>
            </p>
            
            <p>
                <label for="chapter_location_name" style="display: block; margin-bottom: 5px; font-weight: bold;">Meeting Location Name</label>
                <input type="text" id="chapter_location_name" name="chapter_location_name" 
                       value="<?php echo esc_attr($location_name); ?>" 
                       style="width: 100%; padding: 8px;" 
                       placeholder="e.g., Starbucks on Main St">
                <small style="color: #666;">Public name of the meeting place</small>
            </p>
            
            <p>
                <label for="chapter_location_address" style="display: block; margin-bottom: 5px; font-weight: bold;">Meeting Location Address</label>
                <textarea id="chapter_location_address" name="chapter_location_address" 
                          style="width: 100%; padding: 8px; min-height: 60px;" 
                          placeholder="123 Main St, Austin, TX 78701"><?php echo esc_textarea($location_address); ?></textarea>
                <small style="color: #666;">Optional - Only include if you want it displayed publicly</small>
            </p>
        </div>
        
        <!-- Right Column -->
        <div>
            <h3 style="margin-top: 0; color: #0066CC;">Chapter Leader</h3>
            
            <p>
                <label for="chapter_leader_name" style="display: block; margin-bottom: 5px; font-weight: bold;">Leader Name</label>
                <input type="text" id="chapter_leader_name" name="chapter_leader_name" 
                       value="<?php echo esc_attr($leader_name); ?>" 
                       style="width: 100%; padding: 8px;" 
                       placeholder="John Doe">
                <small style="color: #666;">Not displayed publicly - for internal use</small>
            </p>
            
            <p>
                <label for="chapter_leader_email" style="display: block; margin-bottom: 5px; font-weight: bold;">Leader Email</label>
                <input type="email" id="chapter_leader_email" name="chapter_leader_email" 
                       value="<?php echo esc_attr($leader_email); ?>" 
                       style="width: 100%; padding: 8px;" 
                       placeholder="leader@example.com">
                <small style="color: #666;">Hidden from public - used for distribution lists</small>
            </p>
            
            <h3 style="margin-top: 25px; color: #0066CC;">Chapter Status</h3>
            
            <p>
                <label for="chapter_status" style="display: block; margin-bottom: 5px; font-weight: bold;">Status</label>
                <select id="chapter_status" name="chapter_status" style="width: 100%; padding: 8px;">
                    <option value="active" <?php selected($status, 'active'); ?>>Active</option>
                    <option value="pending" <?php selected($status, 'pending'); ?>>Pending (Not yet live)</option>
                    <option value="paused" <?php selected($status, 'paused'); ?>>Paused</option>
                    <option value="forming" <?php selected($status, 'forming'); ?>>Forming (Getting started)</option>
                </select>
                <small style="color: #666;">Only "Active" chapters show on the public directory</small>
            </p>
            
            <p>
                <label for="chapter_member_count" style="display: block; margin-bottom: 5px; font-weight: bold;">Member Count (Optional)</label>
                <input type="number" id="chapter_member_count" name="chapter_member_count" 
                       value="<?php echo esc_attr($member_count); ?>" 
                       style="width: 100%; padding: 8px;" 
                       min="0" placeholder="0">
                <small style="color: #666;">Approximate number of active members</small>
            </p>
        </div>
        
    </div>
    
    <div style="margin-top: 20px; padding: 15px; background: #f0f8ff; border-left: 4px solid #0066CC; border-radius: 4px;">
        <p style="margin: 0; color: #555;">
            <strong>💡 Tip:</strong> The leader email will be added to the chapters@choose90.org distribution list. 
            Make sure to add this email to your email system's distribution list for this chapter.
        </p>
    </div>
    
    <?php
}

/**
 * Save Chapter Meta Fields
 */
function choose90_save_chapter_meta($post_id) {
    // Check if nonce is set
    if (!isset($_POST['choose90_chapter_meta_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['choose90_chapter_meta_nonce'], 'choose90_save_chapter_meta')) {
        return;
    }
    
    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Check if this is a chapter post type
    if (get_post_type($post_id) !== 'chapter') {
        return;
    }
    
    // Save meta fields
    $fields = array(
        'chapter_city' => '_chapter_city',
        'chapter_state' => '_chapter_state',
        'chapter_leader_name' => '_chapter_leader_name',
        'chapter_leader_email' => '_chapter_leader_email',
        'chapter_meeting_pattern' => '_chapter_meeting_pattern',
        'chapter_location_name' => '_chapter_location_name',
        'chapter_location_address' => '_chapter_location_address',
        'chapter_status' => '_chapter_status',
        'chapter_member_count' => '_chapter_member_count'
    );
    
    foreach ($fields as $input_name => $meta_key) {
        if (isset($_POST[$input_name])) {
            $value = sanitize_text_field($_POST[$input_name]);
            update_post_meta($post_id, $meta_key, $value);
        } else {
            // Delete meta if field is empty (except for status, which defaults to 'active')
            if ($meta_key !== '_chapter_status') {
                delete_post_meta($post_id, $meta_key);
            }
        }
    }
    
    // Auto-assign region taxonomy based on state
    if (!empty($_POST['chapter_state'])) {
        $state = sanitize_text_field($_POST['chapter_state']);
        $city = isset($_POST['chapter_city']) ? sanitize_text_field($_POST['chapter_city']) : '';
        
        // Create region term if it doesn't exist (format: "City, State" or just "State")
        if (!empty($city)) {
            $region_term = $city . ', ' . $state;
        } else {
            $region_term = $state;
        }
        
        // Check if term exists
        $term = term_exists($region_term, 'chapter_region');
        if (!$term) {
            $term = wp_insert_term($region_term, 'chapter_region');
        }
        
        if (!is_wp_error($term) && isset($term['term_id'])) {
            wp_set_object_terms($post_id, $term['term_id'], 'chapter_region', false);
        }
    }
}
add_action('save_post', 'choose90_save_chapter_meta');

/**
 * Helper Functions to Get Chapter Meta
 */
function get_chapter_city($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_chapter_city', true);
}

function get_chapter_state($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_chapter_state', true);
}

function get_chapter_location($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $city = get_chapter_city($post_id);
    $state = get_chapter_state($post_id);
    
    if ($city && $state) {
        return $city . ', ' . $state;
    } elseif ($state) {
        return $state;
    }
    return '';
}

function get_chapter_meeting_info($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $pattern = get_post_meta($post_id, '_chapter_meeting_pattern', true);
    $location = get_post_meta($post_id, '_chapter_location_name', true);
    
    $info = array();
    if ($pattern) {
        $info['pattern'] = $pattern;
    }
    if ($location) {
        $info['location'] = $location;
    }
    
    return $info;
}

function get_chapter_status($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $status = get_post_meta($post_id, '_chapter_status', true);
    return $status ?: 'active';
}

/**
 * Filter chapters by status in queries
 * Only show 'active' chapters on frontend by default
 */
function choose90_filter_active_chapters($query) {
    // Only apply to frontend chapter queries
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('chapter')) {
        $meta_query = array(
            array(
                'key' => '_chapter_status',
                'value' => 'active',
                'compare' => '='
            )
        );
        $query->set('meta_query', $meta_query);
    }
}
add_action('pre_get_posts', 'choose90_filter_active_chapters');

