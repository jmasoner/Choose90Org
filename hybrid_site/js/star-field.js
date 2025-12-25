/**
 * Choose90 Star Field Animation
 * Reusable animated star field with mouse clustering effect
 * 
 * Usage:
 *   <div id="starField" class="star-field"></div>
 *   <script src="/js/star-field.js"></script>
 *   <script>new StarField('starField');</script>
 */

class StarField {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            console.error('StarField: Container not found:', containerId);
            return;
        }
        
        this.options = {
            starCount: options.starCount || 150,
            maxDistance: options.maxDistance || 300,
            baseSpeed: options.baseSpeed || 0.015,
            sparkleRatio: options.sparkleRatio || 0.1,
            colors: options.colors || ['white'],
            ...options
        };
        
        this.stars = [];
        this.mousePos = {
            x: window.innerWidth / 2,
            y: window.innerHeight / 2
        };
        this.animationFrame = null;
        
        this.init();
    }
    
    init() {
        this.createStars();
        this.setupEventListeners();
        this.animate();
    }
    
    createStars() {
        // Clear existing stars
        this.container.innerHTML = '';
        this.stars = [];
        
        for (let i = 0; i < this.options.starCount; i++) {
            const star = document.createElement('div');
            star.className = 'star-field-star';
            
            // Random position
            const x = Math.random() * 100;
            const y = Math.random() * 100;
            star.style.left = x + '%';
            star.style.top = y + '%';
            
            // Random size
            const size = Math.random() * 2 + 2;
            star.style.width = size + 'px';
            star.style.height = size + 'px';
            
            // Random color
            const color = this.options.colors[Math.floor(Math.random() * this.options.colors.length)];
            star.style.background = color;
            star.style.boxShadow = `0 0 ${size * 2}px ${color}`;
            
            // Random opacity
            const opacity = Math.random() * 0.5 + 0.3;
            star.style.opacity = opacity;
            
            this.container.appendChild(star);
            
            // Store star data
            const isSparkle = Math.random() < this.options.sparkleRatio;
            if (isSparkle) {
                star.classList.add('sparkle');
            }
            
            this.stars.push({
                element: star,
                baseX: x,
                baseY: y,
                speed: Math.random() * this.options.baseSpeed + this.options.baseSpeed,
                sparkle: isSparkle,
                size: size
            });
        }
    }
    
    setupEventListeners() {
        // Track mouse movement
        document.addEventListener('mousemove', (e) => {
            this.mousePos.x = e.clientX;
            this.mousePos.y = e.clientY;
        });
        
        // Handle window resize
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                // Recalculate positions if needed
                this.updateStarPositions();
            }, 250);
        });
    }
    
    updateStarPositions() {
        // Recalculate base positions based on new window size
        this.stars.forEach(star => {
            const starX = (star.baseX / 100) * window.innerWidth;
            const starY = (star.baseY / 100) * window.innerHeight;
            // Update stored positions if needed
        });
    }
    
    animate() {
        const centerX = window.innerWidth / 2;
        const centerY = window.innerHeight / 2;
        
        this.stars.forEach(star => {
            const starX = (star.baseX / 100) * window.innerWidth;
            const starY = (star.baseY / 100) * window.innerHeight;
            
            // Calculate distance from mouse
            const dx = this.mousePos.x - starX;
            const dy = this.mousePos.y - starY;
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            // Attraction effect - stars cluster around mouse
            if (distance < this.options.maxDistance) {
                const attraction = (1 - distance / this.options.maxDistance) * 50;
                const angle = Math.atan2(dy, dx);
                
                const newX = starX + Math.cos(angle) * attraction * star.speed;
                const newY = starY + Math.sin(angle) * attraction * star.speed;
                
                star.element.style.transform = `translate(${newX - starX}px, ${newY - starY}px)`;
                
                // Increase opacity and size when close to mouse
                const proximity = 1 - distance / this.options.maxDistance;
                const opacity = Math.min(1, 0.5 + proximity * 0.5);
                star.element.style.opacity = opacity;
                
                // Slight size increase when close
                const scale = 1 + proximity * 0.3;
                star.element.style.transform += ` scale(${scale})`;
            } else {
                // Return to base position
                star.element.style.transform = 'translate(0, 0) scale(1)';
                star.element.style.opacity = star.sparkle ? 0.8 : 0.5;
            }
        });
        
        this.animationFrame = requestAnimationFrame(() => this.animate());
    }
    
    destroy() {
        if (this.animationFrame) {
            cancelAnimationFrame(this.animationFrame);
        }
        this.container.innerHTML = '';
        this.stars = [];
    }
}

// Auto-initialize if container exists and no manual init
document.addEventListener('DOMContentLoaded', () => {
    const autoContainer = document.getElementById('starField');
    if (autoContainer && !window.starFieldInstance) {
        window.starFieldInstance = new StarField('starField');
    }
});

