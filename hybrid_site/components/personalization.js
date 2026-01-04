/**
 * Choose90 Personalization System
 * Displays user name and personalizes content
 */

const Choose90Personalize = {
    /**
     * Get current user info
     */
    getUserInfo: function() {
        // This would fetch from WordPress REST API
        // For now, check if user is logged in via PHP
        return {
            isLoggedIn: typeof choose90User !== 'undefined' ? choose90User.isLoggedIn : false,
            screenName: typeof choose90User !== 'undefined' ? choose90User.screenName : '',
            fullName: typeof choose90User !== 'undefined' ? choose90User.fullName : '',
        };
    },

    /**
     * Display personalized greeting
     */
    showGreeting: function(containerId) {
        const userInfo = this.getUserInfo();
        if (!userInfo.isLoggedIn) return;

        const container = document.getElementById(containerId);
        if (!container) return;

        const greeting = document.createElement('div');
        greeting.className = 'choose90-greeting';
        greeting.innerHTML = `
            <p>Welcome back, <strong>${userInfo.screenName}</strong>! 👋</p>
        `;
        container.insertBefore(greeting, container.firstChild);
    },

    /**
     * Personalize page content
     */
    personalizeContent: function() {
        const userInfo = this.getUserInfo();
        if (!userInfo.isLoggedIn) return;

        // Replace generic text with personalized versions
        const placeholders = document.querySelectorAll('[data-personalize]');
        placeholders.forEach(el => {
            const type = el.dataset.personalize;
            switch(type) {
                case 'name':
                    el.textContent = userInfo.screenName;
                    break;
                case 'greeting':
                    el.textContent = `Hi ${userInfo.screenName}!`;
                    break;
            }
        });
    },

    /**
     * Show login prompt for protected content
     */
    showLoginPrompt: function(message) {
        const prompt = document.createElement('div');
        prompt.className = 'choose90-login-prompt';
        prompt.innerHTML = `
            <div class="login-prompt-content">
                <h3>Login Required</h3>
                <p>${message || 'Please log in to access this feature.'}</p>
                <a href="/wp-login.php" class="login-prompt-btn">Log In</a>
                <a href="/pledge" class="login-prompt-btn secondary">Join the Movement</a>
            </div>
        `;
        return prompt;
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    Choose90Personalize.personalizeContent();
});

