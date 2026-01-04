/**
 * Choose90.org Analytics Configuration
 * Google Analytics 4 (GA4) Helper Functions
 * 
 * This file provides helper functions for analytics tracking
 * and ensures graceful degradation if GA4 fails to load.
 */

(function() {
    'use strict';

    // Check if gtag is available
    function isAnalyticsAvailable() {
        return typeof window.gtag === 'function';
    }

    // Safe event tracking wrapper
    window.trackEvent = function(eventName, eventParams) {
        if (!isAnalyticsAvailable()) {
            // Graceful degradation - log to console in development
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                console.log('[Analytics] Event:', eventName, eventParams);
            }
            return;
        }

        try {
            window.gtag('event', eventName, eventParams || {});
        } catch (error) {
            console.error('[Analytics] Error tracking event:', error);
        }
    };

    // Track page views (for SPA-like navigation if needed)
    window.trackPageView = function(pagePath, pageTitle) {
        if (!isAnalyticsAvailable()) return;

        try {
            window.gtag('config', 'G-9M6498Y7W4', {
                'page_path': pagePath,
                'page_title': pageTitle
            });
        } catch (error) {
            console.error('[Analytics] Error tracking page view:', error);
        }
    };

    // Track navigation clicks
    window.trackNavigation = function(menuItem) {
        trackEvent('menu_clicked', {
            'menu_item': menuItem
        });
    };

    // Track dropdown opens
    window.trackDropdown = function(dropdownName) {
        trackEvent('dropdown_opened', {
            'dropdown_name': dropdownName
        });
    };

    // Initialize navigation tracking
    document.addEventListener('DOMContentLoaded', function() {
        // Track main navigation clicks
        const navLinks = document.querySelectorAll('.nav-links a[href^="/"]');
        navLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href && href !== '#' && !href.startsWith('#')) {
                    const menuItem = this.textContent.trim().toLowerCase().replace(/\s+/g, '_');
                    trackNavigation(menuItem);
                }
            });
        });

        // Track dropdown opens
        const dropdowns = document.querySelectorAll('.nav-dropdown > a');
        dropdowns.forEach(function(dropdown) {
            dropdown.addEventListener('click', function(e) {
                // Only track if it's actually opening a dropdown (not navigating)
                if (window.innerWidth > 768) {
                    const dropdownName = this.textContent.trim().toLowerCase().replace(/\s+/g, '_');
                    trackDropdown(dropdownName);
                }
            });
        });
    });

})();
