<?php
/**
 * Choose90 Enhanced Pledge Form
 * Include this in page-pledge.php
 */
?>

<!-- Choose90 Enhanced Pledge Form -->
<div class="pledge-form-container">
    <div class="pledge-intro">
        <h2 style="color: #0066CC; margin-top: 0;">I Choose 90.</h2>
        <p style="font-size: 1.2em; line-height: 1.6;">
            While I am not perfect, I Choose a more Positive Life.<br>
            Nobody is judging me, this is my choice.<br>
            <strong>I Choose Positive. I Choose 90.</strong>
        </p>
    </div>

    <form id="choose90-pledge-form" class="pledge-form" method="POST">
        <input type="hidden" name="action" value="choose90_pledge_submit">
        <?php wp_nonce_field('choose90_pledge_nonce', 'pledge_nonce'); ?>

        <!-- Required Fields -->
        <div class="form-section">
            <h3>Create Your Account</h3>
            <p class="section-description">All fields marked with * are required.</p>

            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required 
                       placeholder="your.email@example.com" 
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                <small class="field-help">This will be your username for logging in.</small>
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required 
                       minlength="8" 
                       placeholder="At least 8 characters">
                <small class="field-help">Choose a strong password to protect your account.</small>
            </div>

            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" required 
                       placeholder="John Smith">
                <small class="field-help">Your real name (we'll keep this private).</small>
            </div>

            <div class="form-group">
                <label for="screen_name">Screen Name *</label>
                <input type="text" id="screen_name" name="screen_name" required 
                       placeholder="Choose90John" 
                       maxlength="50">
                <small class="field-help">This is how you'll appear on Choose90. You can change it anytime.</small>
            </div>
        </div>

        <!-- Optional but Encouraged -->
        <div class="form-section">
            <h3>Help Us Personalize Your Experience</h3>
            <p class="section-description">These fields are optional but help us serve you better.</p>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" 
                       placeholder="(555) 123-4567" 
                       pattern="[0-9\s\-\(\)]+">
                <small class="field-help">We use this to personalize your experience and connect you with local chapters. We never share your number.</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="location_city">City</label>
                    <input type="text" id="location_city" name="location_city" 
                           placeholder="Seattle">
                </div>
                <div class="form-group">
                    <label for="location_state">State/Province</label>
                    <input type="text" id="location_state" name="location_state" 
                           placeholder="WA">
                </div>
            </div>
        </div>

        <!-- Social Media (Optional) -->
        <div class="form-section">
            <h3>Connect Your Social Media</h3>
            <p class="section-description">Share your Choose90 journey and help spread the movement. All fields are optional.</p>

            <div class="form-group">
                <label for="facebook_url">Facebook</label>
                <input type="url" id="facebook_url" name="facebook_url" 
                       placeholder="https://facebook.com/yourprofile">
            </div>

            <div class="form-group">
                <label for="instagram_handle">Instagram</label>
                <input type="text" id="instagram_handle" name="instagram_handle" 
                       placeholder="@yourhandle">
            </div>

            <div class="form-group">
                <label for="twitter_handle">X (Twitter)</label>
                <input type="text" id="twitter_handle" name="twitter_handle" 
                       placeholder="@yourhandle">
            </div>

            <div class="form-group">
                <label for="tiktok_handle">TikTok</label>
                <input type="text" id="tiktok_handle" name="tiktok_handle" 
                       placeholder="@yourhandle">
            </div>

            <div class="form-group">
                <label for="linkedin_url">LinkedIn</label>
                <input type="url" id="linkedin_url" name="linkedin_url" 
                       placeholder="https://linkedin.com/in/yourprofile">
            </div>

            <div class="form-group">
                <label for="reddit_username">Reddit</label>
                <input type="text" id="reddit_username" name="reddit_username" 
                       placeholder="u/yourusername">
            </div>
        </div>

        <!-- Pledge Commitment -->
        <div class="form-section pledge-commitment">
            <h3>Your Commitment</h3>
            <div class="pledge-checkbox">
                <label>
                    <input type="checkbox" name="pledge_commitment" required>
                    <span class="pledge-text">
                        <strong>I pledge to choose 90% positive content</strong> in what I post and consume. 
                        I understand that I'm not perfect, and that's okay. This is my choice to focus on the good, 
                        build real connections, and be part of a movement that chooses light over darkness.
                    </span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="pledge-submit-btn" id="pledge-submit-btn">
            Take the Pledge
        </button>

        <p class="form-footer">
            By taking the pledge, you're creating a free Choose90 account. 
            We'll never spam you, and you can unsubscribe anytime.
        </p>
    </form>

    <!-- Success Message (Hidden initially) -->
    <div id="pledge-success" class="pledge-success" style="display: none;">
        <div class="success-content">
            <div class="success-icon">🎯</div>
            <h2>Welcome to Choose90!</h2>
            <p>You've earned your first badge: <strong>I Choose 90</strong></p>
            <div id="pledge-share-buttons" class="pledge-share-section"></div>
            <a href="resources-hub.html" class="success-cta-btn">Explore Resources</a>
        </div>
    </div>
</div>

<?php
// Enqueue styles and scripts
wp_enqueue_style('choose90-pledge-form', get_template_directory_uri() . '/../hybrid_site/css/pledge-form.css');
wp_enqueue_script('choose90-badge-system', get_template_directory_uri() . '/../hybrid_site/js/badge-system.js', array('jquery'), '1.0', true);
wp_enqueue_script('choose90-social-sharing', get_template_directory_uri() . '/../hybrid_site/js/social-sharing.js', array('jquery'), '1.0', true);
wp_enqueue_style('choose90-badges-sharing', get_template_directory_uri() . '/../hybrid_site/css/badges-sharing.css');
?>

<script>
// Form validation and submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('choose90-pledge-form');
    const submitBtn = document.getElementById('pledge-submit-btn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';
            
            // Collect form data
            const formData = new FormData(form);
            
            // Submit via AJAX
            fetch('<?php echo admin_url('admin-post.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    form.style.display = 'none';
                    document.getElementById('pledge-success').style.display = 'block';
                    
                    // Award badge
                    if (typeof Choose90Badges !== 'undefined') {
                        Choose90Badges.awardBadge('pledge', data.data.user_id);
                    }
                    
                    // Show share buttons
                    if (typeof Choose90Share !== 'undefined') {
                        const shareButtons = Choose90Share.createShareButtons('Pledge', {});
                        document.getElementById('pledge-share-buttons').innerHTML = shareButtons;
                    }
                } else {
                    alert(data.data.message || 'There was an error. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Take the Pledge';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Take the Pledge';
            });
        });
    }
});
</script>

