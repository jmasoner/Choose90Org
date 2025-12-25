/**
 * Choose90 Signup Popup System
 * Shows popup when users click on AI/interactive features
 */

(function() {
    'use strict';

    // Check if user is logged in (WordPress)
    // Use cached result to avoid repeated API calls
    let cachedLoginStatus = null;
    let loginCheckPromise = null;
    
    async function isUserLoggedIn() {
        // Return cached result if available (for 5 minutes)
        if (cachedLoginStatus !== null && cachedLoginStatus.expires > Date.now()) {
            return cachedLoginStatus.loggedIn;
        }
        
        // If a check is already in progress, wait for it
        if (loginCheckPromise) {
            const result = await loginCheckPromise;
            return result;
        }
        
        // Start new login check
        loginCheckPromise = (async () => {
            try {
                // First, do quick client-side checks
                const cookies = document.cookie.split(';');
                let foundCookie = false;
                for (let i = 0; i < cookies.length; i++) {
                    const cookie = cookies[i].trim();
                    if (cookie.indexOf('wordpress_logged_in') !== -1) {
                        const cookieValue = cookie.split('=')[1];
                        if (cookieValue && cookieValue.length > 10) {
                            foundCookie = true;
                            break;
                        }
                    }
                }
                
                // Check for WordPress admin bar
                if (document.body.classList.contains('admin-bar')) {
                    cachedLoginStatus = { loggedIn: true, expires: Date.now() + 300000 };
                    return true;
                }
                
                // Check for WordPress data object
                if (typeof wp !== 'undefined' && wp.data && wp.data.select('core').getCurrentUser) {
                    const user = wp.data.select('core').getCurrentUser();
                    if (user !== null) {
                        cachedLoginStatus = { loggedIn: true, expires: Date.now() + 300000 };
                        return true;
                    }
                }
                
                // Check for choose90User object
                if (typeof choose90User !== 'undefined' && choose90User.isLoggedIn) {
                    cachedLoginStatus = { loggedIn: true, expires: Date.now() + 300000 };
                    return true;
                }
                
                // If cookie found, verify with server
                if (foundCookie) {
                    try {
                        const response = await fetch('/api/check-auth.php', {
                            method: 'GET',
                            credentials: 'include'
                        });
                        if (response.ok) {
                            const data = await response.json();
                            cachedLoginStatus = { 
                                loggedIn: data.logged_in, 
                                expires: Date.now() + 300000 // 5 minutes
                            };
                            return data.logged_in;
                        }
                    } catch (e) {
                        // If API fails but cookie exists, assume logged in
                        cachedLoginStatus = { loggedIn: true, expires: Date.now() + 60000 };
                        return true;
                    }
                }
                
                // No indication of login
                cachedLoginStatus = { loggedIn: false, expires: Date.now() + 300000 };
                return false;
            } catch (error) {
                console.error('Login check error:', error);
                // On error, default to not logged in
                cachedLoginStatus = { loggedIn: false, expires: Date.now() + 60000 };
                return false;
            } finally {
                loginCheckPromise = null;
            }
        })();
        
        return await loginCheckPromise;
    }

    // Show signup popup
    async function showSignupPopup(featureName) {
        // Don't show if already logged in
        const loggedIn = await isUserLoggedIn();
        if (loggedIn) {
            return;
        }

        // Create popup overlay
        const overlay = document.createElement('div');
        overlay.id = 'choose90-signup-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeIn 0.3s ease;
        `;

        // Create popup content
        const popup = document.createElement('div');
        popup.style.cssText = `
            background: white;
            border-radius: 15px;
            max-width: 500px;
            width: 100%;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            position: relative;
            animation: slideUp 0.3s ease;
            text-align: center;
        `;

        popup.innerHTML = `
            <button id="choose90-popup-close" style="
                position: absolute;
                top: 15px;
                right: 15px;
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
                color: #999;
                width: 30px;
                height: 30px;
                line-height: 30px;
                padding: 0;
            ">×</button>
            
            <div style="margin-bottom: 20px;">
                <div style="font-size: 48px; margin-bottom: 15px;">💙</div>
                <h2 style="color: #0066CC; margin: 0 0 10px 0; font-size: 28px; font-weight: 700;">
                    Unlock ${featureName}
                </h2>
                <p style="color: #666; font-size: 16px; line-height: 1.6; margin: 0;">
                    This feature is available to all Choose90 members.
                </p>
            </div>

            <div style="background: #f0f8ff; padding: 20px; border-radius: 10px; margin: 25px 0; border-left: 4px solid #0066CC;">
                <p style="color: #0066CC; font-size: 18px; font-weight: 600; margin: 0 0 10px 0;">
                    ✨ It's 100% FREE!
                </p>
                <p style="color: #555; font-size: 14px; margin: 0; line-height: 1.5;">
                    Join Choose90 to access interactive guides, AI-powered tools, and personalized resources. 
                    No credit card required. No hidden fees. Just free support for your journey.
                </p>
            </div>

            <div style="margin-top: 30px;">
                <a href="/pledge/" style="
                    display: inline-block;
                    padding: 14px 35px;
                    background: #0066CC;
                    color: white;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    font-size: 16px;
                    margin-right: 10px;
                    transition: background 0.2s;
                " onmouseover="this.style.background='#004A99'" onmouseout="this.style.background='#0066CC'">
                    Sign Up Free →
                </a>
                <button id="choose90-popup-dismiss" style="
                    display: inline-block;
                    padding: 14px 25px;
                    background: transparent;
                    color: #666;
                    border: 2px solid #ddd;
                    border-radius: 8px;
                    font-weight: 600;
                    font-size: 16px;
                    cursor: pointer;
                    transition: all 0.2s;
                " onmouseover="this.style.borderColor='#999'; this.style.color='#333'" onmouseout="this.style.borderColor='#ddd'; this.style.color='#666'">
                    Maybe Later
                </button>
            </div>

            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                Already have an account? <a href="/wp-login.php" style="color: #0066CC; text-decoration: none;">Log in</a>
            </p>
        `;

        overlay.appendChild(popup);
        document.body.appendChild(overlay);

        // Close handlers
        const closeBtn = popup.querySelector('#choose90-popup-close');
        const dismissBtn = popup.querySelector('#choose90-popup-dismiss');
        
        function closePopup() {
            overlay.style.animation = 'fadeOut 0.3s ease';
            popup.style.animation = 'slideDown 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(overlay);
            }, 300);
        }

        closeBtn.addEventListener('click', closePopup);
        dismissBtn.addEventListener('click', closePopup);
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                closePopup();
            }
        });

        // Add CSS animations
        if (!document.getElementById('choose90-popup-styles')) {
            const style = document.createElement('style');
            style.id = 'choose90-popup-styles';
            style.textContent = `
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                @keyframes fadeOut {
                    from { opacity: 1; }
                    to { opacity: 0; }
                }
                @keyframes slideUp {
                    from { transform: translateY(30px); opacity: 0; }
                    to { transform: translateY(0); opacity: 1; }
                }
                @keyframes slideDown {
                    from { transform: translateY(0); opacity: 1; }
                    to { transform: translateY(30px); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }
    }

    // Attach to AI/interactive feature links
    document.addEventListener('DOMContentLoaded', function() {
        // Find all AI/interactive feature links
        const aiFeatures = document.querySelectorAll('[data-requires-signup], .ai-feature, [href*="phone-setup-optimizer"], [href*="digital-detox-guide"]');
        
        aiFeatures.forEach(function(link) {
            link.addEventListener('click', async function(e) {
                // Check login status (async)
                const loggedIn = await isUserLoggedIn();
                if (!loggedIn) {
                    e.preventDefault();
                    const featureName = link.getAttribute('data-feature-name') || 
                                       link.textContent.trim() || 
                                       'This Feature';
                    showSignupPopup(featureName);
                }
            });
        });
    });

    // Export for manual triggering
    window.Choose90SignupPopup = {
        show: showSignupPopup,
        isLoggedIn: isUserLoggedIn
    };
})();



