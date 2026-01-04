/**
 * Hide Pledge Content When Logged In
 * 
 * This script hides "Join the Movement" buttons and links,
 * and other join-related CTAs when the user is already logged in.
 */

(function() {
    'use strict';
    
    function hidePledgeContent() {
        // Check if user is logged in
        fetch('/api/check-auth.php', { credentials: 'include' })
            .then(r => r.json())
            .then(data => {
                if (data.logged_in) {
                    // Hide specific elements by ID
                    const elementsToHide = [
                        'hero-pledge-btn',
                        'hero-tag',
                        'campaigns-tag',
                        'campaign-join-btn',
                        'resolution-pledge-btn',
                        'make-pledge-btn',
                        'resources-pledge-btn'
                    ];
                    
                    elementsToHide.forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.style.display = 'none';
                    });
                    
                    // Hide all links to /pledge/ that contain pledge-related text
                    document.querySelectorAll('a[href*="/pledge/"], a[href*="/pledge"]').forEach(el => {
                        const text = el.textContent.toLowerCase();
                        if (text.includes('join the movement') || text.includes('make your pledge') || text.includes('make the pledge')) {
                            el.style.display = 'none';
                        }
                    });
                    
                    // Hide "Join the Movement" text in spans and links
                    document.querySelectorAll('span, a, .hero-tag').forEach(el => {
                        if (el.textContent.includes('Join the Movement')) {
                            el.style.display = 'none';
                        }
                    });
                    
                    // Hide pledge CTAs in common sections
                    document.querySelectorAll('.cta-section a[href*="pledge"], .cta-buttons a[href*="pledge"], .hero-btns a[href*="pledge"]').forEach(el => {
                        el.style.display = 'none';
                    });
                }
            })
            .catch(() => {
                // On error, show everything (assume not logged in)
            });
    }
    
    // Run on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', hidePledgeContent);
    } else {
        hidePledgeContent();
    }
})();
