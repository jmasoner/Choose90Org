<?php
/**
 * Template Name: Custom Login Page
 * Description: Beautiful animated login page with star effects
 */

// If user is already logged in, redirect to homepage
if (is_user_logged_in()) {
    // Check if this is an OAuth callback (don't redirect those)
    $is_oauth_callback = isset($_GET['social_security']) || isset($_GET['lz_social_provider']) || isset($_GET['lz_api']);
    
    if (!$is_oauth_callback) {
        wp_redirect(home_url('/'));
        exit;
    }
}

get_header();
?>

<link rel="stylesheet" href="<?php echo esc_url(home_url('/css/star-field.css')); ?>">

<style>

/* Login Form Container - Ensure visibility */
.login-container {
    position: relative;
    z-index: 10;
    min-height: 100vh;
    display: flex !important;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    visibility: visible !important;
    opacity: 1 !important;
}

.login-box {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 50px 40px;
    max-width: 450px;
    width: 100%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 40px rgba(100, 150, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    animation: slideUp 0.6s ease-out;
    visibility: visible !important;
    display: block !important;
    opacity: 1 !important;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-header {
    text-align: center;
    margin-bottom: 40px;
}

.login-header .logo {
    font-family: 'Outfit', sans-serif;
    font-size: 48px;
    font-weight: 800;
    margin-bottom: 15px;
    margin-top: 0;
    padding: 0;
    line-height: 1.2;
    display: block;
    overflow: visible;
    background: linear-gradient(135deg, #0066CC 0%, #E8B93E 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.login-header h1 {
    font-family: 'Outfit', sans-serif;
    font-size: 28px;
    color: #333;
    margin: 0;
    font-weight: 600;
}

.login-header p {
    color: #666;
    margin-top: 10px;
    font-size: 14px;
}

.login-form {
    visibility: visible !important;
    display: block !important;
}

.login-form .form-group {
    margin-bottom: 25px;
    visibility: visible !important;
    display: block !important;
}

/* BPS CAPTCHA styling */
.bps-captcha-container {
    margin: 20px 0;
}

.bps-captcha-container label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 600;
    font-size: 14px;
}

.bps-captcha-container input[type="text"] {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: white;
    box-sizing: border-box;
}

.bps-captcha-container input[type="text"]:focus {
    outline: none;
    border-color: #0066CC;
    box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

.login-form label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 600;
    font-size: 14px;
}

.login-form input[type="text"],
.login-form input[type="email"],
.login-form input[type="password"] {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: white;
    box-sizing: border-box;
}

.login-form input:focus {
    outline: none;
    border-color: #0066CC;
    box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

.remember-me {
    display: flex;
    align-items: center;
    margin-bottom: 25px;
}

.remember-me input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin-right: 10px;
    cursor: pointer;
}

.remember-me label {
    margin: 0;
    font-weight: normal;
    cursor: pointer;
    color: #555;
}

.login-button {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #0066CC 0%, #004A99 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 102, 204, 0.3);
    margin-bottom: 20px;
}

.login-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 102, 204, 0.4);
    background: linear-gradient(135deg, #004A99 0%, #003366 100%);
}

.login-button:active {
    transform: translateY(0);
}

.login-links {
    text-align: center;
    margin-top: 25px;
    padding-top: 25px;
    border-top: 1px solid #e0e0e0;
}

.login-links a {
    color: #0066CC;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s ease;
}

.login-links a:hover {
    color: #004A99;
    text-decoration: underline;
}

.login-error {
    background: #ffebee;
    border: 2px solid #f44336;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 25px;
    color: #c62828;
    font-size: 14px;
    animation: shake 0.5s;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
}

.login-success {
    background: #e8f5e9;
    border: 2px solid #4caf50;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 25px;
    color: #2e7d32;
    font-size: 14px;
}

/* Responsive */
@media (max-width: 600px) {
    .login-box {
        padding: 40px 30px;
    }
    
    .login-header .logo {
        font-size: 40px;
    }
}
</style>

<!-- Login Template Loaded Successfully -->
<div class="star-field" id="starField"></div>

<div class="login-container">
    <div class="login-box">
        <div class="login-header">
            <div class="logo" style="line-height: 1.2; padding: 0; margin-bottom: 15px;">Choose<span style="color: #E8B93E;">90</span>.</div>
            <h1>Welcome Back</h1>
            <p>Sign in to continue your journey</p>
        </div>

        <?php
        // Show error messages from WordPress
        if (isset($_GET['login']) && $_GET['login'] == 'failed') {
            echo '<div class="login-error">Invalid username or password. Please try again.</div>';
        }
        if (isset($_GET['loggedout']) && $_GET['loggedout'] == 'true') {
            echo '<div class="login-success">You have been logged out successfully.</div>';
        }
        if (isset($_GET['registered']) && $_GET['registered'] == 'true') {
            echo '<div class="login-success">Registration successful! Please log in.</div>';
        }
        // Check for error parameter
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            if ($error == 'incorrect_password' || $error == 'empty_password') {
                echo '<div class="login-error">Invalid username or password. Please try again.</div>';
            } elseif ($error == 'empty_username') {
                echo '<div class="login-error">Please enter your username or email.</div>';
            }
        }
        ?>

        <!-- Firebase Auth - Social Login Options -->
        <?php
        // Include Firebase config if available
        $firebase_config_path = get_template_directory() . '/../hybrid_site/components/firebase-config.php';
        if (file_exists($firebase_config_path)) {
            include $firebase_config_path;
        }
        ?>
        
        <div class="firebase-auth-section" id="firebase-auth-section" style="margin-bottom: 25px; padding: 20px; background: rgba(255, 255, 255, 0.5); border-radius: 12px; border: 1px solid rgba(0, 102, 204, 0.2);">
            <p style="text-align: center; color: #666; margin-bottom: 15px; font-size: 14px;">Quick sign in with</p>
            
            <div class="firebase-auth-buttons" style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <button type="button" id="firebase-google-login-btn" class="firebase-auth-btn" style="padding: 12px 24px; background: white; border: 2px solid #4285f4; border-radius: 8px; color: #4285f4; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 8px; font-size: 14px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" style="fill: #4285f4;">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Google
                </button>
                
                <button type="button" id="firebase-facebook-login-btn" class="firebase-auth-btn" style="padding: 12px 24px; background: #1877f2; border: 2px solid #1877f2; border-radius: 8px; color: white; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 8px; font-size: 14px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="white">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Facebook
                </button>
            </div>
            
            <div style="text-align: center; margin: 15px 0; position: relative;">
                <span style="background: rgba(255, 255, 255, 0.5); padding: 0 12px; color: #666; font-size: 12px; position: relative; z-index: 1;">or</span>
                <hr style="position: absolute; top: 50%; left: 0; right: 0; margin: 0; border: none; border-top: 1px solid rgba(0, 0, 0, 0.1); z-index: 0;">
            </div>
        </div>

        <form class="login-form" name="loginform" id="loginform" action="<?php echo esc_url(site_url('wp-login.php', 'login_post')); ?>" method="post">
            <?php
            // Check if BPS is active - only show CAPTCHA if it is
            $bps_active = false;
            if (function_exists('is_plugin_active')) {
                $bps_active = is_plugin_active('bulletproof-security/bulletproof-security.php');
            } else {
                // Fallback: check if BPS functions exist
                $bps_active = function_exists('bps_captcha_login_form_field');
            }
            
            // Only capture CAPTCHA if BPS is active
            $login_form_hooks = '';
            if ($bps_active) {
                ob_start();
                do_action('login_form');
                $login_form_hooks = ob_get_clean();
            }
            ?>
            
            <div class="form-group">
                <label for="user_login">Username or Email</label>
                <input type="text" name="log" id="user_login" class="input" value="<?php echo isset($_GET['log']) ? esc_attr($_GET['log']) : ''; ?>" size="20" required autofocus autocomplete="username">
            </div>

            <div class="form-group">
                <label for="user_pass">Password</label>
                <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" required autocomplete="current-password">
            </div>

            <?php
            // Output CAPTCHA only if BPS is active and CAPTCHA was generated
            if ($bps_active && !empty($login_form_hooks)) {
                echo '<div class="form-group bps-captcha-container">';
                echo $login_form_hooks;
                echo '</div>';
            }
            ?>

            <div class="remember-me">
                <input name="rememberme" type="checkbox" id="rememberme" value="forever">
                <label for="rememberme">Remember me</label>
            </div>

            <?php
            // Get redirect URL
            $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : home_url('/');
            ?>
            <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">
            
            <button type="submit" name="wp-submit" id="wp-submit" class="login-button">
                Sign In
            </button>
        </form>

        <div class="login-links">
            <a href="<?php echo wp_lostpassword_url(); ?>">Forgot your password?</a>
            <?php if (get_option('users_can_register')): ?>
                <span style="color: #999;"> | </span>
                <a href="<?php echo wp_registration_url(); ?>">Create an account</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="<?php echo esc_url(home_url('/js/star-field.js')); ?>"></script>
<script src="<?php echo esc_url(home_url('/js/firebase-auth.js')); ?>"></script>
<script>
// Firebase Auth Integration for Login
let choose90AuthInstance = null;

function initFirebaseAuth() {
    if (window.choose90FirebaseConfig && window.Choose90FirebaseAuth) {
        choose90AuthInstance = new window.Choose90FirebaseAuth(window.choose90FirebaseConfig);
        choose90AuthInstance.init().then(() => {
            setupFirebaseLoginButtons();
        });
    } else {
        // Hide Firebase section if not available
        const firebaseSection = document.getElementById('firebase-auth-section');
        if (firebaseSection) {
            firebaseSection.style.display = 'none';
        }
    }
}

function setupFirebaseLoginButtons() {
    const googleBtn = document.getElementById('firebase-google-login-btn');
    const facebookBtn = document.getElementById('firebase-facebook-login-btn');
    
    if (googleBtn && choose90AuthInstance) {
        googleBtn.addEventListener('click', async function() {
            try {
                googleBtn.disabled = true;
                googleBtn.textContent = 'Redirecting...';
                
                // Store redirect URL
                const redirectTo = new URLSearchParams(window.location.search).get('redirect_to') || '/';
                sessionStorage.setItem('choose90_login_redirect', redirectTo);
                
                // This will redirect to Google, then back to our site
                // The redirect result is handled in firebase-auth.js handleAuthRedirect()
                await choose90AuthInstance.signInWithGoogle();
                
                // Note: User will be redirected, so we won't reach here
            } catch (error) {
                console.error('Google sign-in error:', error);
                alert('Sign-in failed: ' + (error.message || 'Please try again'));
                googleBtn.disabled = false;
                googleBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" style="fill: #4285f4;"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg> Google';
            }
        });
    }
    
    if (facebookBtn && choose90AuthInstance) {
        facebookBtn.addEventListener('click', async function() {
            try {
                facebookBtn.disabled = true;
                facebookBtn.textContent = 'Redirecting...';
                
                // Store redirect URL
                const redirectTo = new URLSearchParams(window.location.search).get('redirect_to') || '/';
                sessionStorage.setItem('choose90_login_redirect', redirectTo);
                
                // This will redirect to Facebook, then back to our site
                // The redirect result is handled in firebase-auth.js handleAuthRedirect()
                await choose90AuthInstance.signInWithFacebook();
                
                // Note: User will be redirected, so we won't reach here
            } catch (error) {
                console.error('Facebook sign-in error:', error);
                alert('Sign-in failed: ' + (error.message || 'Please try again'));
                facebookBtn.disabled = false;
                facebookBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg> Facebook';
            }
        });
    }
}

// Initialize star field
document.addEventListener('DOMContentLoaded', function() {
    if (typeof StarField !== 'undefined') {
        window.loginStarField = new StarField('starField', {
            starCount: 150,
            maxDistance: 300,
            baseSpeed: 0.015,
            sparkleRatio: 0.1,
            colors: ['white', '#ffffff', '#e8f4f8']
        });
    }
    
    // Initialize Firebase Auth
    initFirebaseAuth();
});

// Listen for auth success
window.addEventListener('choose90:auth:success', function(event) {
    const redirectTo = new URLSearchParams(window.location.search).get('redirect_to') || '/';
    window.location.href = redirectTo;
});
</script>

<?php get_footer(); ?>

