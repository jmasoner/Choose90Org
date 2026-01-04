<?php
/**
 * Choose90 Testimonials Widget
 * 
 * WordPress widget to display rotating testimonials in the sidebar
 * Replaces the Recent Comments widget with testimonials rotation
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the Testimonials Widget
 */
function choose90_register_testimonials_widget() {
    register_widget('Choose90_Testimonials_Widget');
}
add_action('widgets_init', 'choose90_register_testimonials_widget');

/**
 * Choose90 Testimonials Widget Class
 */
class Choose90_Testimonials_Widget extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'choose90_testimonials',
            __('Choose90 Testimonials', 'choose90'),
            array(
                'description' => __('Display rotating testimonials from Choose90 members', 'choose90'),
                'classname' => 'widget_choose90_testimonials'
            )
        );
    }

    /**
     * Output the widget content
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('What Others Are Saying', 'choose90');
        
        echo $args['before_widget'];
        
        if (!empty($title)) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }
        
        // Widget content
        ?>
        <div class="choose90-testimonials-widget" id="choose90-testimonials-widget-<?php echo $this->id; ?>" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 20px; border-radius: 12px; border: 2px solid #f59e0b; margin-bottom: 20px;">
            <div class="testimonials-rotator-sidebar" id="testimonials-rotator-sidebar-<?php echo $this->id; ?>" style="min-height: 150px; position: relative;">
                <div class="testimonial-item-sidebar" style="text-align: center;">
                    <p class="testimonial-text-sidebar" id="testimonial-text-sidebar-<?php echo $this->id; ?>" style="font-size: 15px; line-height: 1.6; color: #78350f; margin-bottom: 15px; font-style: italic; min-height: 80px;">
                        "Choose90 is exactly what I need in my life, as the news and posts have been so depressing."
                    </p>
                    <p class="testimonial-author-sidebar" id="testimonial-author-sidebar-<?php echo $this->id; ?>" style="font-size: 14px; font-weight: 600; color: #92400e;">
                        — Sarah J.
                    </p>
                </div>
            </div>
            <div class="testimonials-dots-sidebar" id="testimonials-dots-sidebar-<?php echo $this->id; ?>" style="display: flex; justify-content: center; gap: 6px; margin-top: 15px; flex-wrap: wrap;"></div>
        </div>
        
        <script>
        // Testimonials Rotator for Sidebar Widget
        (function() {
            const widgetId = '<?php echo $this->id; ?>';
            let testimonials = [];
            let currentIndex = 0;
            let rotationInterval = null;
            
            function loadTestimonials() {
                fetch('/api/get-testimonials.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.testimonials && data.testimonials.length > 0) {
                            testimonials = data.testimonials;
                            // Start at random testimonial
                            const randomIndex = Math.floor(Math.random() * testimonials.length);
                            displayTestimonial(randomIndex);
                            setupRotation();
                            createDots();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading testimonials:', error);
                    });
            }
            
            function displayTestimonial(index) {
                if (testimonials.length === 0) return;
                
                currentIndex = index % testimonials.length;
                const testimonial = testimonials[currentIndex];
                
                const textEl = document.getElementById('testimonial-text-sidebar-' + widgetId);
                const authorEl = document.getElementById('testimonial-author-sidebar-' + widgetId);
                
                if (textEl && authorEl) {
                    textEl.textContent = '"' + testimonial.text + '"';
                    authorEl.textContent = '— ' + testimonial.name;
                    updateDots();
                }
            }
            
            function setupRotation() {
                if (testimonials.length <= 1) return;
                
                if (rotationInterval) clearInterval(rotationInterval);
                rotationInterval = setInterval(() => {
                    currentIndex = (currentIndex + 1) % testimonials.length;
                    displayTestimonial(currentIndex);
                }, 5000);
            }
            
            function createDots() {
                if (testimonials.length <= 1) return;
                
                const dotsContainer = document.getElementById('testimonials-dots-sidebar-' + widgetId);
                if (!dotsContainer) return;
                
                dotsContainer.innerHTML = '';
                const dotCount = Math.min(testimonials.length, 6); // Limit to 6 dots for sidebar
                const startDotIndex = currentIndex % dotCount;
                for (let i = 0; i < dotCount; i++) {
                    const dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'testimonial-dot-sidebar';
                    dot.setAttribute('data-index', i);
                    dot.setAttribute('data-widget-id', widgetId);
                    dot.style.cssText = 'width: 8px; height: 8px; border-radius: 50%; border: 2px solid #92400e; background: ' + (i === startDotIndex ? '#92400e' : 'transparent') + '; cursor: pointer; transition: all 0.3s;';
                    dot.addEventListener('click', () => {
                        currentIndex = i;
                        displayTestimonial(i);
                        setupRotation();
                    });
                    dotsContainer.appendChild(dot);
                }
            }
            
            function updateDots() {
                const dots = document.querySelectorAll('.testimonial-dot-sidebar[data-widget-id="' + widgetId + '"]');
                dots.forEach((dot) => {
                    const dotIndex = parseInt(dot.getAttribute('data-index'));
                    if (dotIndex === currentIndex % dots.length) {
                        dot.style.background = '#92400e';
                    } else {
                        dot.style.background = 'transparent';
                    }
                });
            }
            
            // Load testimonials on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', loadTestimonials);
            } else {
                loadTestimonials();
            }
        })();
        </script>
        
        <?php
        echo $args['after_widget'];
    }

    /**
     * Output the widget settings form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('What Others Are Saying', 'choose90');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'choose90'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p style="color: #666; font-size: 13px;">
            <?php _e('Displays rotating testimonials from Choose90 members. Testimonials rotate every 5 seconds.', 'choose90'); ?>
        </p>
        <?php
    }

    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}
