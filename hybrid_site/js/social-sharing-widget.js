/**
 * Choose90 Social Sharing Widget
 * One-click sharing buttons for Choose90 content
 */

class Choose90SocialSharing {
    constructor() {
        this.init();
    }
    
    init() {
        // Create sharing buttons for elements with data-share attribute
        document.querySelectorAll('[data-share]').forEach(element => {
            this.attachShareButton(element);
        });
        
        // Create floating share button if enabled
        if (document.querySelector('.choose90-floating-share')) {
            this.createFloatingButton();
        }
    }
    
    attachShareButton(element) {
        const platform = element.dataset.share;
        const url = element.dataset.shareUrl || window.location.href;
        const text = element.dataset.shareText || document.title;
        
        element.addEventListener('click', (e) => {
            e.preventDefault();
            this.share(platform, url, text);
        });
    }
    
    share(platform, url, text) {
        const encodedUrl = encodeURIComponent(url);
        const encodedText = encodeURIComponent(text);
        const encodedHashtag = encodeURIComponent('#Choose90');
        
        let shareUrl = '';
        
        switch(platform) {
            case 'twitter':
            case 'x':
                shareUrl = `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedText}&hashtags=Choose90`;
                break;
            case 'facebook':
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
                break;
            case 'linkedin':
                shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`;
                break;
            case 'whatsapp':
                shareUrl = `https://wa.me/?text=${encodedText}%20${encodedUrl}`;
                break;
            case 'email':
                shareUrl = `mailto:?subject=${encodedText}&body=${encodedText}%20${encodedUrl}`;
                break;
            case 'copy':
                navigator.clipboard.writeText(url).then(() => {
                    this.showCopyFeedback();
                });
                return;
            default:
                console.warn('Unknown platform:', platform);
                return;
        }
        
        if (shareUrl) {
            window.open(shareUrl, '_blank', 'width=600,height=400');
        }
    }
    
    showCopyFeedback() {
        // Create temporary feedback
        const feedback = document.createElement('div');
        feedback.textContent = '✓ Link copied!';
        feedback.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            z-index: 10000;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        `;
        document.body.appendChild(feedback);
        
        setTimeout(() => {
            feedback.style.opacity = '0';
            feedback.style.transition = 'opacity 0.3s';
            setTimeout(() => feedback.remove(), 300);
        }, 2000);
    }
    
    createFloatingButton() {
        // Floating share button implementation
        const button = document.createElement('div');
        button.className = 'choose90-share-float';
        button.innerHTML = '📤 Share';
        button.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 16px 24px;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            z-index: 9999;
            font-weight: 600;
            transition: transform 0.2s;
        `;
        
        button.addEventListener('click', () => {
            this.showShareMenu(button);
        });
        
        button.addEventListener('mouseenter', () => {
            button.style.transform = 'scale(1.05)';
        });
        
        button.addEventListener('mouseleave', () => {
            button.style.transform = 'scale(1)';
        });
        
        document.body.appendChild(button);
    }
    
    showShareMenu(button) {
        // Create share menu
        const menu = document.createElement('div');
        menu.className = 'choose90-share-menu';
        menu.style.cssText = `
            position: fixed;
            bottom: 90px;
            right: 20px;
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 10000;
            min-width: 200px;
        `;
        
        const platforms = [
            { name: 'Twitter/X', icon: '🐦', platform: 'twitter' },
            { name: 'Facebook', icon: '👥', platform: 'facebook' },
            { name: 'LinkedIn', icon: '💼', platform: 'linkedin' },
            { name: 'Copy Link', icon: '📋', platform: 'copy' }
        ];
        
        menu.innerHTML = platforms.map(p => `
            <div style="padding: 12px; cursor: pointer; border-radius: 8px; transition: background 0.2s;" 
                 onmouseover="this.style.background='#f3f4f6'" 
                 onmouseout="this.style.background='transparent'"
                 onclick="choose90Share.share('${p.platform}', '${window.location.href}', '${document.title}'); this.closest('.choose90-share-menu').remove();">
                ${p.icon} ${p.name}
            </div>
        `).join('');
        
        document.body.appendChild(menu);
        
        // Close on outside click
        setTimeout(() => {
            document.addEventListener('click', function closeMenu(e) {
                if (!menu.contains(e.target) && e.target !== button) {
                    menu.remove();
                    document.removeEventListener('click', closeMenu);
                }
            });
        }, 10);
    }
}

// Initialize
const choose90Share = new Choose90SocialSharing();
window.choose90Share = choose90Share;


