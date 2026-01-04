<?php
/**
 * Choose90 Enhanced Pledge Form
 * Include this in page-pledge.php
 */
?>

<!-- Choose90 Enhanced Pledge Form -->
<div class="pledge-form-container">
    <div class="pledge-intro">
        <h2 style="color: #0066CC; margin-top: 0;">Join the Choose90 Movement</h2>
        <p style="font-size: 1.2em; line-height: 1.6; margin-bottom: 16px;">
            <strong style="color: #10b981; font-size: 1.1em;">100% Free. No cost. No credit card. No spam.</strong>
        </p>
        <p style="font-size: 1.2em; line-height: 1.6;">
            While I am not perfect, I Choose a more Positive Life.<br>
            Nobody is judging me, this is my choice.<br>
            <strong>I Choose Positive. I Choose 90.</strong>
        </p>
    </div>

    <!-- Testimonials Section -->
    <div class="testimonials-section" id="testimonials-section" style="margin-bottom: 40px; padding: 30px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 16px; border: 2px solid #f59e0b;">
        <h3 style="text-align: center; color: #92400e; margin-top: 0; margin-bottom: 20px; font-size: 24px;">What Others Are Saying</h3>
        <div class="testimonials-rotator" id="testimonials-rotator" style="min-height: 100px; position: relative;">
            <div class="testimonial-item" id="testimonial-display" style="text-align: center; padding: 20px;">
                <p class="testimonial-text" id="testimonial-text" style="font-size: 18px; line-height: 1.6; color: #78350f; margin-bottom: 15px; font-style: italic;">
                    "Choose90 is exactly what I need in my life, as the news and posts have been so depressing."
                </p>
                <p class="testimonial-author" id="testimonial-author" style="font-size: 16px; font-weight: 600; color: #92400e;">
                    — Sarah J.
                </p>
            </div>
        </div>
        <div class="testimonials-dots" id="testimonials-dots" style="display: flex; justify-content: center; gap: 8px; margin-top: 20px;"></div>
    </div>

    <!-- Firebase Auth - Social Login Options -->
    <?php
    // Include Firebase config if available
    $firebase_config_path = __DIR__ . '/firebase-config.php';
    if (file_exists($firebase_config_path)) {
        include $firebase_config_path;
    }
    ?>
    
    <div class="firebase-auth-section" id="firebase-auth-section" style="margin-bottom: 30px; padding: 30px; background: #f8f9fa; border-radius: 12px; border: 2px solid #e9ecef;">
        <h3 style="text-align: center; color: #0066CC; margin-top: 0; margin-bottom: 15px;">Quick Sign Up <span style="color: #10b981; font-size: 0.9em;">(Free)</span></h3>
        <p style="text-align: center; color: #666; margin-bottom: 25px; font-size: 14px;">Sign up instantly with your social account - we'll get your name and email automatically! <strong style="color: #10b981;">100% free, no cost.</strong></p>
        
        <div class="firebase-auth-buttons" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-bottom: 20px;">
            <button type="button" id="firebase-google-btn" class="firebase-auth-btn" style="padding: 14px 28px; background: white; border: 2px solid #4285f4; border-radius: 8px; color: #4285f4; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 10px; font-size: 16px;">
                <svg width="20" height="20" viewBox="0 0 24 24" style="fill: #4285f4;">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Sign up with Google
            </button>
            
            <button type="button" id="firebase-facebook-btn" class="firebase-auth-btn" style="padding: 14px 28px; background: #1877f2; border: 2px solid #1877f2; border-radius: 8px; color: white; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 10px; font-size: 16px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                Sign up with Facebook
            </button>
        </div>
        
        <div style="text-align: center; margin: 20px 0; position: relative;">
            <span style="background: #f8f9fa; padding: 0 15px; color: #666; font-size: 14px; position: relative; z-index: 1;">or</span>
            <hr style="position: absolute; top: 50%; left: 0; right: 0; margin: 0; border: none; border-top: 1px solid #dee2e6; z-index: 0;">
        </div>
        
        <p style="text-align: center; color: #666; font-size: 13px; margin-bottom: 0;">Or fill out the form below with name, email, and optional phone</p>
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
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                       autocomplete="email">
                <small class="field-help">This will be your username for logging in.</small>
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required 
                       minlength="8" 
                       placeholder="At least 8 characters"
                       autocomplete="new-password">
                <small class="field-help">Choose a strong password to protect your account.</small>
            </div>

            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" required 
                       placeholder="John Smith"
                       autocomplete="name">
                <small class="field-help">Your real name (we'll keep this private).</small>
            </div>

            <div class="form-group">
                <label for="screen_name">Screen Name *</label>
                <input type="text" id="screen_name" name="screen_name" required 
                       placeholder="Choose90John" 
                       maxlength="50"
                       autocomplete="username">
                <small class="field-help">This is how you'll appear on Choose90. You can change it anytime.</small>
            </div>
        </div>

        <!-- Optional but Encouraged -->
        <div class="form-section">
            <h3>Help Us Personalize Your Experience</h3>
            <p class="section-description">These fields are optional but help us serve you better.</p>

            <div class="form-group">
                <label for="phone">Phone Number (Optional)</label>
                <input type="tel" id="phone" name="phone" 
                       placeholder="(555) 123-4567" 
                       pattern="[0-9\s\-\(\)]+"
                       autocomplete="tel">
                <small class="field-help">Optional - We use this to personalize your experience and connect you with local chapters. We never share your number.</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="location_city">City</label>
                    <input type="text" id="location_city" name="location_city" 
                           placeholder="Seattle"
                           autocomplete="address-level2">
                </div>
                <div class="form-group">
                    <label for="location_state">State/Province</label>
                    <input type="text" id="location_state" name="location_state" 
                           placeholder="WA"
                           autocomplete="address-level1">
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
                        <strong>I choose to make 90% of my online influence positive, uplifting, and constructive.</strong> 
                        I understand that I'm not perfect, and that's okay. This is my choice to focus on the good, 
                        build real connections, and be part of a movement that chooses light over darkness.
                    </span>
                </label>
            </div>
        </div>

        <!-- Error Banner -->
        <div id="pledge-error-banner" style="display:none; margin-bottom: 15px; padding: 12px 16px; border-radius: 8px; background: #ffebee; border: 1px solid #d32f2f; color: #b71c1c; font-size: 14px;"></div>

        <!-- Submit Button -->
        <button type="submit" class="pledge-submit-btn" id="pledge-submit-btn">
            Join the Movement (Free)
        </button>

               <p class="form-footer" style="text-align: center; color: #666; font-size: 14px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                   <strong style="color: #10b981;">100% Free. No cost. No credit card required.</strong><br>
                   By joining, you're creating a free Choose90 account.<br>
                   We'll never spam you, and you can unsubscribe anytime.
               </p>
    </form>

    <!-- Social Sharing Section (Always Visible - Outside Form Container) -->
    <div class="social-sharing-section" id="pledge-social-sharing" style="display: block !important; visibility: visible !important; margin-top: 40px; padding: 40px; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 16px; position: relative; z-index: 10;">
        <h3 style="text-align: center; color: #0066CC; font-size: 24px; margin-bottom: 12px;">Share Your Commitment</h3>
        <p style="text-align: center; color: #666; margin-bottom: 30px;">Help spread the Choose90 movement! Share that you joined on social media.</p>
        
        <div class="social-buttons-container" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <button class="social-share-btn" data-platform="twitter" onclick="shareChoose90Pledge('twitter')" style="padding: 12px 24px; background: white; border: 2px solid #1da1f2; border-radius: 8px; color: #1da1f2; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                🐦 Share on Twitter/X
            </button>
            <button class="social-share-btn" data-platform="facebook" onclick="shareChoose90Pledge('facebook')" style="padding: 12px 24px; background: white; border: 2px solid #1877f2; border-radius: 8px; color: #1877f2; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                👥 Share on Facebook
            </button>
            <button class="social-share-btn" data-platform="linkedin" onclick="shareChoose90Pledge('linkedin')" style="padding: 12px 24px; background: white; border: 2px solid #0077b5; border-radius: 8px; color: #0077b5; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                💼 Share on LinkedIn
            </button>
        </div>
    </div>

    <!-- Success Message (Hidden initially) -->
    <div id="pledge-success" class="pledge-success" style="display: none;">
        <div class="success-content">
            <div class="success-icon">🎯</div>
            <h2>Welcome to Choose90!</h2>
            <p>You've earned your first badge: <strong>I Choose 90</strong></p>
            <p style="margin: 20px 0; color: #666;">Thank you for joining our movement! Use the social sharing buttons above to let others know about Choose90.</p>
            <a href="resources-hub.html" class="success-cta-btn">Explore Resources</a>
        </div>
    </div>

    <!-- Share Your Testimonial Section -->
    <div class="testimonial-submit-section" style="margin-top: 40px; padding: 40px; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 16px; border: 2px solid #0066CC;">
        <h3 style="text-align: center; color: #0066CC; margin-top: 0; margin-bottom: 15px; font-size: 24px;">Share Your Experience</h3>
        <p style="text-align: center; color: #666; margin-bottom: 30px; font-size: 16px;">Help others by sharing how Choose90 has impacted your life!</p>
        
        <form id="testimonial-submit-form" style="max-width: 600px; margin: 0 auto;">
            <div style="margin-bottom: 20px;">
                <label for="testimonial-name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Your Name (First Name + Initial)</label>
                <input type="text" id="testimonial-name" name="name" required maxlength="100" placeholder="Sarah J." style="width: 100%; padding: 12px 16px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px; box-sizing: border-box;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="testimonial-text" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Your Testimonial (1-2 sentences)</label>
                <textarea id="testimonial-text" name="text" required maxlength="500" placeholder="Share how Choose90 has impacted your life..." style="width: 100%; padding: 12px 16px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px; min-height: 120px; resize: vertical; box-sizing: border-box; font-family: inherit;"></textarea>
                <small style="display: block; margin-top: 5px; color: #666; font-size: 14px;">Maximum 500 characters</small>
            </div>
            
            <button type="submit" id="testimonial-submit-btn" style="width: 100%; padding: 14px 28px; background: #0066CC; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                Share My Testimonial
            </button>
            
            <div id="testimonial-submit-message" style="margin-top: 15px; padding: 12px; border-radius: 8px; display: none; text-align: center; font-weight: 600;"></div>
        </form>
    </div>
</div>

<?php
// Enqueue styles and scripts (files are at server root)
wp_enqueue_style('choose90-pledge-form', home_url('/css/pledge-form.css'), array(), '1.0');
wp_enqueue_script('choose90-badge-system', home_url('/js/badge-system.js'), array('jquery'), '1.0', true);
wp_enqueue_script('choose90-social-sharing', home_url('/js/social-sharing.js'), array('jquery'), '1.0', true);
wp_enqueue_style('choose90-badges-sharing', home_url('/css/badges-sharing.css'), array(), '1.0');
?>

<script>
// Firebase Auth Integration
let choose90AuthInstance = null;

// Initialize Firebase Auth when ready
function initFirebaseAuth() {
    if (window.choose90FirebaseConfig && window.Choose90FirebaseAuth) {
        try {
            choose90AuthInstance = new window.Choose90FirebaseAuth(window.choose90FirebaseConfig);
            choose90AuthInstance.init().then(() => {
                setupFirebaseButtons();
            }).catch(error => {
                console.error('Firebase init error:', error);
                // Hide Firebase section if it fails
                const firebaseSection = document.getElementById('firebase-auth-section');
                if (firebaseSection) {
                    firebaseSection.style.display = 'none';
                }
            });
        } catch (error) {
            console.error('Firebase Auth initialization error:', error);
            // Hide Firebase section if it fails
            const firebaseSection = document.getElementById('firebase-auth-section');
            if (firebaseSection) {
                firebaseSection.style.display = 'none';
            }
        }
    } else {
        console.warn('Firebase config or auth class not available');
        // Hide Firebase section if config not available
        const firebaseSection = document.getElementById('firebase-auth-section');
        if (firebaseSection) {
            firebaseSection.style.display = 'none';
        }
    }
}

// Setup Firebase Auth buttons
function setupFirebaseButtons() {
    console.log('setupFirebaseButtons called', { choose90AuthInstance: !!choose90AuthInstance });
    const googleBtn = document.getElementById('firebase-google-btn');
    const facebookBtn = document.getElementById('firebase-facebook-btn');
    
    console.log('Buttons found:', { googleBtn: !!googleBtn, facebookBtn: !!facebookBtn });
    
    if (googleBtn && choose90AuthInstance) {
        // Remove any existing listeners by cloning the button
        const newGoogleBtn = googleBtn.cloneNode(true);
        googleBtn.parentNode.replaceChild(newGoogleBtn, googleBtn);
        const googleBtnRef = newGoogleBtn;
        
        console.log('Attaching Google button handler');
        googleBtnRef.addEventListener('click', async function(e) {
            console.log('Google button clicked!');
            e.preventDefault();
            e.stopPropagation();
            try {
                googleBtnRef.disabled = true;
                googleBtnRef.textContent = 'Redirecting...';
                
                // Collect optional form data if user filled it in
                const additionalData = {
                    phone: document.getElementById('phone')?.value || '',
                    location_city: document.getElementById('location_city')?.value || '',
                    location_state: document.getElementById('location_state')?.value || ''
                };
                
                // This will redirect to Google, then back to our site
                await choose90AuthInstance.signInWithGoogle(additionalData);
            } catch (error) {
                console.error('Google sign-in error:', error);
                alert('Sign-in failed: ' + (error.message || 'Please try again'));
                googleBtnRef.disabled = false;
                googleBtnRef.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" style="fill: #4285f4;"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg> Sign up with Google';
            }
        });
    }
    
    if (facebookBtn && choose90AuthInstance) {
        // Remove any existing listeners by cloning the button
        const newFacebookBtn = facebookBtn.cloneNode(true);
        facebookBtn.parentNode.replaceChild(newFacebookBtn, facebookBtn);
        const facebookBtnRef = newFacebookBtn;
        
        console.log('Attaching Facebook button handler');
        facebookBtnRef.addEventListener('click', async function(e) {
            console.log('Facebook button clicked!');
            e.preventDefault();
            e.stopPropagation();
            try {
                facebookBtnRef.disabled = true;
                facebookBtnRef.textContent = 'Redirecting...';
                
                // Collect optional form data
                const additionalData = {
                    phone: document.getElementById('phone')?.value || '',
                    location_city: document.getElementById('location_city')?.value || '',
                    location_state: document.getElementById('location_state')?.value || ''
                };
                
                // This will redirect to Facebook, then back to our site
                await choose90AuthInstance.signInWithFacebook(additionalData);
            } catch (error) {
                console.error('Facebook sign-in error:', error);
                alert('Sign-in failed: ' + (error.message || 'Please try again'));
                facebookBtnRef.disabled = false;
                facebookBtnRef.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg> Sign up with Facebook';
            }
        });
    }
}

// Add fallback handlers in case Firebase isn't initialized
function addFallbackButtonHandlers() {
    const googleBtn = document.getElementById('firebase-google-btn');
    const facebookBtn = document.getElementById('firebase-facebook-btn');
    
    if (googleBtn && !googleBtn.hasAttribute('data-handler-attached')) {
        googleBtn.setAttribute('data-handler-attached', 'true');
        googleBtn.addEventListener('click', function(e) {
            if (!choose90AuthInstance) {
                e.preventDefault();
                alert('Firebase authentication is not configured. Please configure Firebase in secrets.json with your Firebase project credentials.\n\nSee FIREBASE_AUTH_SETUP_DETAILED.md for instructions.');
                return false;
            }
        });
    }
    
    if (facebookBtn && !facebookBtn.hasAttribute('data-handler-attached')) {
        facebookBtn.setAttribute('data-handler-attached', 'true');
        facebookBtn.addEventListener('click', function(e) {
            if (!choose90AuthInstance) {
                e.preventDefault();
                alert('Firebase authentication is not configured. Please configure Firebase in secrets.json with your Firebase project credentials.\n\nSee FIREBASE_AUTH_SETUP_DETAILED.md for instructions.');
                return false;
            }
        });
    }
}

// Initialize Firebase Auth on page load - wait for scripts to load
(function() {
    function waitForFirebase() {
        // Check if Firebase and our auth class are available
        if (window.choose90FirebaseConfig && window.Choose90FirebaseAuth) {
            initFirebaseAuth();
        } else {
            // Wait a bit and try again (scripts might still be loading)
            setTimeout(waitForFirebase, 100);
        }
    }
    
    // Add fallback handlers immediately (they'll be overridden if Firebase loads)
    setTimeout(addFallbackButtonHandlers, 200);
    
    // Also add fallback handlers after a delay in case Firebase never loads
    setTimeout(function() {
        if (!choose90AuthInstance) {
            addFallbackButtonHandlers();
        }
    }, 6000);
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(waitForFirebase, 100);
        });
    } else {
        setTimeout(waitForFirebase, 100);
    }
})();

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
                    // Hide form fields and submit button, but keep social sharing visible
                    const formFields = form.querySelectorAll('.form-section, .pledge-submit-btn, .form-footer, .pledge-intro');
                    formFields.forEach(field => {
                        field.style.display = 'none';
                    });
                    
                    // Show success message
                    document.getElementById('pledge-success').style.display = 'block';
                    
                    // Ensure social sharing section is visible (force it) - it's outside the form
                    const socialSharing = document.getElementById('pledge-social-sharing');
                    if (socialSharing) {
                        socialSharing.style.display = 'block';
                        socialSharing.style.visibility = 'visible';
                        socialSharing.style.opacity = '1';
                        // Make sure it's not hidden by parent container
                        const container = document.getElementById('pledge-form-container');
                        if (container) {
                            container.style.overflow = 'visible';
                        }
                    }
                    
                    // Scroll to social sharing section first, then success message
                    if (socialSharing) {
                        socialSharing.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        setTimeout(() => {
                            document.getElementById('pledge-success').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }, 500);
                    } else {
                        document.getElementById('pledge-success').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                    
                    // Award badge
                    if (typeof Choose90Badges !== 'undefined') {
                        Choose90Badges.awardBadge('pledge', data.data.user_id);
                    }
                } else {
                    const errorBanner = document.getElementById('pledge-error-banner');
                    if (errorBanner) {
                        errorBanner.textContent = data.data.message || 'There was an error. Please try again.';
                        errorBanner.style.display = 'block';
                    } else {
                        alert(data.data.message || 'There was an error. Please try again.');
                    }
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Join the Movement (Free)';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorBanner = document.getElementById('pledge-error-banner');
                if (errorBanner) {
                    errorBanner.textContent = 'There was an error. Please try again.';
                    errorBanner.style.display = 'block';
                } else {
                    alert('There was an error. Please try again.');
                }
                submitBtn.disabled = false;
                submitBtn.textContent = 'Join the Movement';
            });
        });
    }
});

// Social sharing function
function shareChoose90Pledge(platform) {
    const pledgeText = "I just joined the Choose90 movement! I'm committing to make 90% of my online influence positive, uplifting, and constructive. Join me! 🌟 #Choose90";
    const url = window.location.origin;
    const encodedText = encodeURIComponent(pledgeText);
    const encodedUrl = encodeURIComponent(url);
    
    let shareUrl = '';
    
    switch(platform) {
        case 'twitter':
        case 'x':
            shareUrl = `https://twitter.com/intent/tweet?text=${encodedText}&url=${encodedUrl}&hashtags=Choose90`;
            break;
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}&quote=${encodedText}`;
            break;
        case 'linkedin':
            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`;
            break;
        default:
            return;
    }
    
    if (shareUrl) {
        window.open(shareUrl, '_blank', 'width=600,height=400');
        
        // Track social share
        if (typeof window.trackEvent === 'function') {
            window.trackEvent('social_share', {
                'platform': platform,
                'location': 'pledge_form'
            });
        }
    }
}

// Make function globally available
window.shareChoose90Pledge = shareChoose90Pledge;

// Testimonials Rotator
(function() {
    let testimonials = [];
    let currentIndex = 0;
    let rotationInterval = null;
    
    // Load testimonials
    function loadTestimonials() {
        fetch('/api/get-testimonials.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.testimonials && data.testimonials.length > 0) {
                    testimonials = data.testimonials;
                    // Start at random testimonial
                    const randomIndex = Math.floor(Math.random() * testimonials.length);
                    displayTestimonial(randomIndex);
                    setupRotation();
                    createDots();
                }
            })
            .catch(error => {
                console.error('Error loading testimonials:', error);
            });
    }
    
    // Display testimonial
    function displayTestimonial(index) {
        if (testimonials.length === 0) return;
        
        currentIndex = index % testimonials.length;
        const testimonial = testimonials[currentIndex];
        
        const textEl = document.getElementById('testimonial-text');
        const authorEl = document.getElementById('testimonial-author');
        
        if (textEl && authorEl) {
            textEl.textContent = '"' + testimonial.text + '"';
            authorEl.textContent = '— ' + testimonial.name;
            updateDots();
        }
    }
    
    // Setup rotation
    function setupRotation() {
        if (testimonials.length <= 1) return;
        
        if (rotationInterval) clearInterval(rotationInterval);
        rotationInterval = setInterval(() => {
            currentIndex = (currentIndex + 1) % testimonials.length;
            displayTestimonial(currentIndex);
        }, 5000);
    }
    
    // Create dots
    function createDots() {
        if (testimonials.length <= 1) return;
        
        const dotsContainer = document.getElementById('testimonials-dots');
        if (!dotsContainer) return;
        
        dotsContainer.innerHTML = '';
        const dotCount = Math.min(testimonials.length, 10);
        const startDotIndex = currentIndex % dotCount;
        for (let i = 0; i < dotCount; i++) {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'testimonial-dot';
            dot.setAttribute('data-index', i);
            dot.style.cssText = 'width: 10px; height: 10px; border-radius: 50%; border: 2px solid #92400e; background: ' + (i === startDotIndex ? '#92400e' : 'transparent') + '; cursor: pointer; transition: all 0.3s;';
            dot.addEventListener('click', () => {
                currentIndex = i;
                displayTestimonial(i);
                setupRotation();
            });
            dotsContainer.appendChild(dot);
        }
    }
    
    // Update dots
    function updateDots() {
        const dots = document.querySelectorAll('.testimonial-dot');
        dots.forEach((dot, index) => {
            const dotIndex = parseInt(dot.getAttribute('data-index'));
            if (dotIndex === currentIndex % dots.length) {
                dot.style.background = '#92400e';
            } else {
                dot.style.background = 'transparent';
            }
        });
    }
    
    // Testimonial submission
    const testimonialForm = document.getElementById('testimonial-submit-form');
    if (testimonialForm) {
        testimonialForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('testimonial-name').value.trim();
            const text = document.getElementById('testimonial-text').value.trim();
            const submitBtn = document.getElementById('testimonial-submit-btn');
            const messageEl = document.getElementById('testimonial-submit-message');
            
            if (!name || !text) {
                messageEl.style.display = 'block';
                messageEl.style.background = '#ffebee';
                messageEl.style.color = '#b71c1c';
                messageEl.style.border = '1px solid #d32f2f';
                messageEl.textContent = 'Please fill in both fields.';
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
            messageEl.style.display = 'none';
            
            fetch('/api/submit-testimonial.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name: name, text: text })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageEl.style.display = 'block';
                    messageEl.style.background = '#d1fae5';
                    messageEl.style.color = '#065f46';
                    messageEl.style.border = '1px solid #10b981';
                    messageEl.textContent = 'Thank you for sharing! Your testimonial has been added.';
                    
                    testimonialForm.reset();
                    
                    setTimeout(() => {
                        loadTestimonials();
                    }, 1000);
                } else {
                    messageEl.style.display = 'block';
                    messageEl.style.background = '#ffebee';
                    messageEl.style.color = '#b71c1c';
                    messageEl.style.border = '1px solid #d32f2f';
                    messageEl.textContent = data.error || 'Something went wrong. Please try again.';
                }
            })
            .catch(error => {
                console.error('Error submitting testimonial:', error);
                messageEl.style.display = 'block';
                messageEl.style.background = '#ffebee';
                messageEl.style.color = '#b71c1c';
                messageEl.style.border = '1px solid #d32f2f';
                messageEl.textContent = 'Something went wrong. Please try again.';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Share My Testimonial';
            });
        });
    }
    
    // Load testimonials on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadTestimonials);
    } else {
        loadTestimonials();
    }
})();
</script>

