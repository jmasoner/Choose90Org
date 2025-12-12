<?php
/**
 * Template Part: Animated Header Logo
 * For WordPress pages (Chapters, Pledge, Donations, etc.)
 * 
 * Usage in header.php:
 * <?php get_template_part('template-parts/header', 'logo'); ?>
 * 
 * Requirements: 
 * - logo-animated.css enqueued in functions.php
 * - Outfit font loaded
 */
?>
<a href="<?php echo esc_url(home_url('/')); ?>" class="logo-animated" aria-label="<?php bloginfo('name'); ?> Home">
    <svg viewBox="0 0 320 100" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="logoTitle">
        <title id="logoTitle"><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?></title>
        
        <!-- Blue figure (left of 9) - slides in from LEFT -->
        <ellipse class="figure-blue" cx="30" cy="50" rx="15" ry="25" fill="#0066CC"/>
        
        <!-- Yellow figure (right of 0) - slides in from RIGHT -->
        <ellipse class="figure-yellow" cx="150" cy="50" rx="15" ry="25" fill="#E8B93E"/>
        
        <!-- Gray figure (center, connecting) - fades in with scale -->
        <ellipse class="figure-gray" cx="90" cy="50" rx="12" ry="20" fill="#9CA3AF"/>
        
        <!-- Connecting arcs forming "90" shape -->
        <path class="arc-connector" d="M 45 35 Q 70 20, 90 35" stroke="#0066CC" stroke-width="8" fill="none"/>
        <path class="arc-connector" d="M 90 65 Q 110 80, 135 65" stroke="#E8B93E" stroke-width="8" fill="none"/>
        <circle class="arc-connector" cx="142" cy="50" r="18" stroke="#E8B93E" stroke-width="6" fill="none"/>
        
        <!-- Text: "CHOOSE 90" - fades in from bottom -->
        <text class="logo-text" x="15" y="95" font-family="'Outfit', sans-serif" font-size="18" font-weight="700" fill="#1F2937" letter-spacing="2">CHOOSE</text>
        <text class="logo-text" x="235" y="95" font-family="'Outfit', sans-serif" font-size="18" font-weight="700" fill="#1F2937" letter-spacing="2">90</text>
    </svg>
</a>
