<?php
/**
 * Chapter Contact Form Component
 * Replaces mailto links with an actual contact form
 */

// Get chapter info
$chapter_id = get_the_ID();
$chapter_title = get_the_title();
$chapter_city = get_post_meta($chapter_id, '_chapter_city', true);
$chapter_state = get_post_meta($chapter_id, '_chapter_state', true);
$chapter_location = ($chapter_city && $chapter_state) ? $chapter_city . ', ' . $chapter_state : ($chapter_state ? $chapter_state : '');

// Handle form submission
$form_submitted = false;
$form_error = '';
$form_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['chapter_contact_submit'])) {
    // Verify nonce
    if (!isset($_POST['chapter_contact_nonce']) || !wp_verify_nonce($_POST['chapter_contact_nonce'], 'chapter_contact_' . $chapter_id)) {
        $form_error = 'Security check failed. Please try again.';
    } else {
        // Sanitize inputs
        $contact_name = sanitize_text_field($_POST['contact_name']);
        $contact_email = sanitize_email($_POST['contact_email']);
        $contact_phone = isset($_POST['contact_phone']) ? sanitize_text_field($_POST['contact_phone']) : '';
        $contact_message = sanitize_textarea_field($_POST['contact_message']);
        $contact_type = sanitize_text_field($_POST['contact_type']); // 'host' or 'join'
        
        // Validate
        if (empty($contact_name) || empty($contact_email) || empty($contact_message)) {
            $form_error = 'Please fill in all required fields.';
        } elseif (!is_email($contact_email)) {
            $form_error = 'Please enter a valid email address.';
        } else {
            // Send email
            $subject = $contact_type === 'host' 
                ? 'Contact Host: ' . $chapter_title 
                : 'Join Chapter: ' . $chapter_title;
            
            $email_body = "New contact form submission for chapter: $chapter_title\n\n";
            $email_body .= "Type: " . ($contact_type === 'host' ? 'Contact Host' : 'Join Chapter') . "\n";
            $email_body .= "Name: $contact_name\n";
            $email_body .= "Email: $contact_email\n";
            if ($contact_phone) {
                $email_body .= "Phone: $contact_phone\n";
            }
            $email_body .= "\nMessage:\n$contact_message\n";
            if ($chapter_location) {
                $email_body .= "\nChapter Location: $chapter_location\n";
            }
            
            $headers = array(
                'From: ' . $contact_name . ' <' . $contact_email . '>',
                'Reply-To: ' . $contact_email
            );
            
            $sent = wp_mail('chapters@choose90.org', $subject, $email_body, $headers);
            
            if ($sent) {
                $form_success = true;
                $form_submitted = true;
            } else {
                $form_error = 'There was an error sending your message. Please try again or email us directly.';
            }
        }
    }
}
?>

<div class="chapter-contact-form-container" style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee;">
    
    <?php if ($form_success): ?>
        <div class="form-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            <strong>✓ Message Sent!</strong> We'll get back to you soon.
        </div>
    <?php endif; ?>
    
    <?php if ($form_error): ?>
        <div class="form-error" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <strong>Error:</strong> <?php echo esc_html($form_error); ?>
        </div>
    <?php endif; ?>
    
    <?php if (!$form_success): ?>
        <!-- Contact Host Form -->
        <form method="POST" action="" class="chapter-contact-form" style="margin-bottom: 15px;">
            <?php wp_nonce_field('chapter_contact_' . $chapter_id, 'chapter_contact_nonce'); ?>
            <input type="hidden" name="contact_type" value="host">
            
            <h4 style="margin-bottom: 15px; color: #0066CC;">Contact Host</h4>
            
            <div style="margin-bottom: 12px;">
                <label for="contact_name_host" style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Your Name *</label>
                <input type="text" id="contact_name_host" name="contact_name" required 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"
                       value="<?php echo isset($_POST['contact_name']) ? esc_attr($_POST['contact_name']) : ''; ?>">
            </div>
            
            <div style="margin-bottom: 12px;">
                <label for="contact_email_host" style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Your Email *</label>
                <input type="email" id="contact_email_host" name="contact_email" required 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"
                       value="<?php echo isset($_POST['contact_email']) ? esc_attr($_POST['contact_email']) : ''; ?>">
            </div>
            
            <div style="margin-bottom: 12px;">
                <label for="contact_phone_host" style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Phone (Optional)</label>
                <input type="tel" id="contact_phone_host" name="contact_phone" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"
                       value="<?php echo isset($_POST['contact_phone']) ? esc_attr($_POST['contact_phone']) : ''; ?>">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="contact_message_host" style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Message *</label>
                <textarea id="contact_message_host" name="contact_message" required rows="4"
                          style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;"><?php echo isset($_POST['contact_message']) ? esc_textarea($_POST['contact_message']) : ''; ?></textarea>
            </div>
            
            <button type="submit" name="chapter_contact_submit" 
                    class="btn btn-primary" 
                    style="width: 100%; padding: 12px; border-radius: 6px; background: #0066CC; color: white; font-weight: 600; border: none; cursor: pointer;">
                Send Message
            </button>
        </form>
        
        <!-- Join Chapter Form -->
        <form method="POST" action="" class="chapter-contact-form">
            <?php wp_nonce_field('chapter_contact_' . $chapter_id, 'chapter_contact_nonce'); ?>
            <input type="hidden" name="contact_type" value="join">
            
            <h4 style="margin-bottom: 15px; color: #0066CC;">Join This Chapter</h4>
            
            <div style="margin-bottom: 12px;">
                <label for="contact_name_join" style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Your Name *</label>
                <input type="text" id="contact_name_join" name="contact_name" required 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 12px;">
                <label for="contact_email_join" style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Your Email *</label>
                <input type="email" id="contact_email_join" name="contact_email" required 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 12px;">
                <label for="contact_phone_join" style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Phone (Optional)</label>
                <input type="tel" id="contact_phone_join" name="contact_phone" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="contact_message_join" style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Tell us about yourself *</label>
                <textarea id="contact_message_join" name="contact_message" required rows="4"
                          style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;"
                          placeholder="Why are you interested in joining this chapter?"></textarea>
            </div>
            
            <button type="submit" name="chapter_contact_submit" 
                    class="btn btn-outline" 
                    style="width: 100%; padding: 12px; border-radius: 6px; border: 2px solid #0066CC; color: #0066CC; font-weight: 600; background: transparent; cursor: pointer;">
                Join Chapter
            </button>
        </form>
    <?php endif; ?>
    
    <div style="margin-top: 15px; text-align: center; font-size: 0.9rem; color: #666;">
        <p>Or email directly: <a href="mailto:chapters@choose90.org?subject=<?php echo urlencode('Chapter: ' . $chapter_title); ?>" style="color: #0066CC;">chapters@choose90.org</a></p>
    </div>
</div>

