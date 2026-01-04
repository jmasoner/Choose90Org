/**
 * Choose90.org Analytics Event Tracking
 * Specific event tracking functions for key user actions
 */

(function() {
    'use strict';

    // Ensure trackEvent is available
    if (typeof window.trackEvent !== 'function') {
        console.warn('[Analytics] trackEvent not available. Make sure analytics.js is loaded first.');
        return;
    }

    /**
     * Pledge Events
     */
    window.trackPledgeSubmitted = function(pledgeType) {
        trackEvent('pledge_submitted', {
            'pledge_type': pledgeType || 'standard'
        });
    };

    window.trackPledgeWallViewed = function() {
        trackEvent('pledge_wall_viewed');
    };

    window.trackPledgeCounterViewed = function() {
        trackEvent('pledge_counter_viewed');
    };

    /**
     * Newsletter Signup Events
     */
    window.trackNewsletterSignup = function(signupLocation) {
        trackEvent('newsletter_signup', {
            'signup_location': signupLocation || 'unknown'
        });
    };

    /**
     * Tool Usage Events
     */
    window.trackContentGeneratorUsed = function(generationType) {
        trackEvent('content_generator_used', {
            'generation_type': generationType || 'unknown'
        });
    };

    window.trackBrowserExtensionDownloaded = function(platform) {
        trackEvent('browser_extension_downloaded', {
            'platform': platform || 'unknown'
        });
    };

    window.trackPWAInstalled = function() {
        trackEvent('pwa_installed');
    };

    /**
     * Social Sharing Events
     */
    window.trackSocialShare = function(platform, shareType, contentUrl) {
        trackEvent('social_share', {
            'platform': platform,
            'share_type': shareType || 'general',
            'content_url': contentUrl || window.location.href
        });
    };

    window.trackSocialHandleCaptured = function(platform) {
        trackEvent('social_handle_captured', {
            'platform': platform
        });
    };

    /**
     * Resource Engagement Events
     */
    window.trackResourceViewed = function(resourceName, resourceCategory) {
        trackEvent('resource_viewed', {
            'resource_name': resourceName,
            'resource_category': resourceCategory || 'unknown'
        });
    };

    window.trackResourceDownloaded = function(resourceName, resourceCategory) {
        trackEvent('resource_downloaded', {
            'resource_name': resourceName,
            'resource_category': resourceCategory || 'unknown'
        });
    };

    window.trackCategoryFiltered = function(category) {
        trackEvent('category_filtered', {
            'category': category || 'all'
        });
    };

    /**
     * Campaign Engagement Events
     */
    window.trackCampaignViewed = function(campaignName) {
        trackEvent('campaign_viewed', {
            'campaign_name': campaignName
        });
    };

    window.trackCampaignCTAClicked = function(campaignName, ctaText) {
        trackEvent('campaign_cta_clicked', {
            'campaign_name': campaignName,
            'cta_text': ctaText || 'unknown'
        });
    };

    /**
     * Challenge Engagement Events
     */
    window.trackChallengeStarted = function(challengeName) {
        trackEvent('challenge_started', {
            'challenge_name': challengeName
        });
    };

    window.trackChallengeDayCompleted = function(challengeName, dayNumber) {
        trackEvent('challenge_day_completed', {
            'challenge_name': challengeName,
            'day_number': dayNumber
        });
    };

    // Auto-track page views for campaign pages
    document.addEventListener('DOMContentLoaded', function() {
        const path = window.location.pathname;
        
        // Track campaign page views
        if (path.includes('kwanzaa-choose90.html') || path.includes('kwanzaa-challenge.html')) {
            trackCampaignViewed('kwanzaa');
        } else if (path.includes('new-years-resolution.html')) {
            trackCampaignViewed('new_years');
        } else if (path.includes('digital-detox-guide.html')) {
            trackCampaignViewed('digital_detox');
        }

        // Track pledge wall view
        if (path.includes('pledge-wall.html')) {
            trackPledgeWallViewed();
        }

        // Track resource hub view
        if (path.includes('resources-hub.html')) {
            trackResourceViewed('resources_hub', 'hub');
        }
    });

})();
