/**
 * Choose90 Badge System
 * Handles badge display, earning, and social sharing
 */

const Choose90Badges = {
    badges: {
        pledge: {
            id: 'pledge',
            name: 'I Choose 90',
            emoji: '🎯',
            description: 'Committed to choosing 90% positive',
            shareText: 'I just pledged to support the Choose90 movement! 🎯 #Choose90'
        },
        resource_bronze: {
            id: 'resource_bronze',
            name: 'Resource Explorer',
            emoji: '📚',
            description: 'Completed 1-3 resources',
            shareText: 'I\'ve completed Choose90 resources! 📚 #Choose90'
        },
        resource_silver: {
            id: 'resource_silver',
            name: 'Resource Champion',
            emoji: '📚',
            description: 'Completed 4-7 resources',
            shareText: 'I\'ve completed multiple Choose90 resources! 📚 #Choose90'
        },
        resource_gold: {
            id: 'resource_gold',
            name: 'Resource Master',
            emoji: '📚',
            description: 'Completed 8-12 resources',
            shareText: 'I\'ve completed many Choose90 resources! 📚 #Choose90'
        },
        resource_platinum: {
            id: 'resource_platinum',
            name: 'Resource Legend',
            emoji: '📚',
            description: 'Completed 13+ resources',
            shareText: 'I\'ve completed tons of Choose90 resources! 📚 #Choose90'
        },
        streak_7: {
            id: 'streak_7',
            name: 'Week Warrior',
            emoji: '🔥',
            description: '7-day engagement streak',
            shareText: 'I\'ve been choosing 90% positive for 7 days! 🔥 #Choose90'
        },
        streak_30: {
            id: 'streak_30',
            name: 'Monthly Champion',
            emoji: '🔥',
            description: '30-day engagement streak',
            shareText: 'I\'ve been choosing 90% positive for 30 days! 🔥 #Choose90'
        },
        streak_90: {
            id: 'streak_90',
            name: 'Quarter Master',
            emoji: '🔥',
            description: '90-day engagement streak',
            shareText: 'I\'ve been choosing 90% positive for 90 days! 🔥 #Choose90'
        },
        streak_365: {
            id: 'streak_365',
            name: 'Year of Good',
            emoji: '🔥',
            description: '365-day engagement streak',
            shareText: 'I\'ve been choosing 90% positive for a full year! 🔥 #Choose90'
        },
        chapter: {
            id: 'chapter',
            name: 'Community Builder',
            emoji: '👥',
            description: 'Active in local Choose90 chapter',
            shareText: 'I joined my local Choose90 chapter! 👥 #Choose90'
        },
        donor_supporter: {
            id: 'donor_supporter',
            name: 'Supporter',
            emoji: '💙',
            description: 'Supporter of Choose90',
            shareText: 'I\'m supporting Choose90! 💙 #Choose90'
        },
        donor_sustainer: {
            id: 'donor_sustainer',
            name: 'Sustainer',
            emoji: '💙',
            description: 'Monthly supporter of Choose90',
            shareText: 'I\'m a monthly supporter of Choose90! 💙 #Choose90'
        },
        donor_champion: {
            id: 'donor_champion',
            name: 'Champion',
            emoji: '💙',
            description: 'Champion supporter of Choose90',
            shareText: 'I\'m a Champion supporter of Choose90! 💙 #Choose90'
        },
        donor_founder: {
            id: 'donor_founder',
            name: 'Founder',
            emoji: '💙',
            description: 'Founding supporter of Choose90',
            shareText: 'I\'m a Founding supporter of Choose90! 💙 #Choose90'
        },
        detox_complete: {
            id: 'detox_complete',
            name: 'Digital Detox Master',
            emoji: '✅',
            description: 'Completed 7-day digital detox',
            shareText: 'I completed the Choose90 Digital Detox Challenge! ✅ #Choose90'
        },
        family_complete: {
            id: 'family_complete',
            name: 'Family Champion',
            emoji: '✅',
            description: 'Completed family challenge',
            shareText: 'My family completed the Choose90 Family Challenge! ✅ #Choose90'
        },
        neighborhood_complete: {
            id: 'neighborhood_complete',
            name: 'Neighborhood Builder',
            emoji: '✅',
            description: 'Completed 30-day neighborhood challenge',
            shareText: 'I completed the Choose90 Neighborhood Challenge! ✅ #Choose90'
        }
    },

    /**
     * Award a badge to a user
     */
    awardBadge: function(badgeId, userId) {
        // This would call WordPress API to save badge
        const badge = this.badges[badgeId];
        if (!badge) return false;

        // Show notification
        this.showBadgeNotification(badge);
        
        // Return badge data for sharing
        return badge;
    },

    /**
     * Display badge notification
     */
    showBadgeNotification: function(badge) {
        const notification = document.createElement('div');
        notification.className = 'badge-notification';
        notification.innerHTML = `
            <div class="badge-notification-content">
                <div class="badge-icon">${badge.emoji}</div>
                <div class="badge-info">
                    <h3>Badge Earned!</h3>
                    <p><strong>${badge.name}</strong></p>
                    <p>${badge.description}</p>
                </div>
                <button class="badge-share-btn" data-badge-id="${badge.id}">Share</button>
            </div>
        `;
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    },

    /**
     * Share badge to social media
     */
    shareBadge: function(badgeId, platform) {
        const badge = this.badges[badgeId];
        if (!badge) return;

        const shareText = encodeURIComponent(badge.shareText);
        const shareUrl = encodeURIComponent('https://choose90.org');
        
        const shareUrls = {
            facebook: `https://www.facebook.com/sharer/sharer.php?u=${shareUrl}&quote=${shareText}`,
            twitter: `https://twitter.com/intent/tweet?text=${shareText}&url=${shareUrl}`,
            linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${shareUrl}`,
            copy: shareUrl
        };

        if (platform === 'copy') {
            navigator.clipboard.writeText(`${badge.shareText} ${shareUrl}`);
            alert('Link copied to clipboard!');
        } else if (shareUrls[platform]) {
            window.open(shareUrls[platform], '_blank', 'width=600,height=400');
        }
    }
};

// Initialize badge system
document.addEventListener('DOMContentLoaded', function() {
    // Handle badge share buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('badge-share-btn')) {
            const badgeId = e.target.dataset.badgeId;
            // Show share options modal
            Choose90Badges.showShareModal(badgeId);
        }
    });
});

