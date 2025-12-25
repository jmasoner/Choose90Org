<?php
/**
 * Template Name: Custom Login Page
 * Description: Beautiful animated login page with star effects
 */

// If user is already logged in, redirect to homepage
if (is_user_logged_in()) {
    wp_redirect(home_url('/'));
    exit;
}

get_header();
?>

<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/../css/star-field.css">
<style>

/* Login Form Container */
.login-container {
    position: relative;
    z-index: 10;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}

.login-box {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 50px 40px;
    max-width: 450px;
    width: 100%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 40px rgba(100, 150, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    animation: slideUp 0.6s ease-out;
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
    margin-bottom: 10px;
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

.login-form .form-group {
    margin-bottom: 25px;
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

<div class="star-field" id="starField"></div>

<div class="login-container">
    <div class="login-box">
        <div class="login-header">
            <div class="logo">Choose<span style="color: #E8B93E;">90</span>.</div>
            <h1>Welcome Back</h1>
            <p>Sign in to continue your journey</p>
        </div>

        <?php
        // Show error messages
        if (isset($_GET['login']) && $_GET['login'] == 'failed') {
            echo '<div class="login-error">Invalid username or password. Please try again.</div>';
        }
        if (isset($_GET['loggedout']) && $_GET['loggedout'] == 'true') {
            echo '<div class="login-success">You have been logged out successfully.</div>';
        }
        ?>

        <form class="login-form" name="loginform" id="loginform" action="<?php echo esc_url(site_url('wp-login.php', 'login_post')); ?>" method="post">
            <div class="form-group">
                <label for="user_login">Username or Email</label>
                <input type="text" name="log" id="user_login" class="input" value="" size="20" required autofocus>
            </div>

            <div class="form-group">
                <label for="user_pass">Password</label>
                <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" required>
            </div>

            <div class="remember-me">
                <input name="rememberme" type="checkbox" id="rememberme" value="forever">
                <label for="rememberme">Remember me</label>
            </div>

            <input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url('/')); ?>">
            
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

<script src="<?php echo get_stylesheet_directory_uri(); ?>/../js/star-field.js"></script>
<script>
// Initialize star field
document.addEventListener('DOMContentLoaded', function() {
    window.loginStarField = new StarField('starField', {
        starCount: 150,
        maxDistance: 300,
        baseSpeed: 0.015,
        sparkleRatio: 0.1,
        colors: ['white', '#ffffff', '#e8f4f8']
    });
});
</script>

<?php get_footer(); ?>

