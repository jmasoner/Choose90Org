<?php
get_header();
?>

<div id="main-content">
    <?php while (have_posts()):
        the_post(); ?>

        <!-- Hero / Header -->
        <div class="chapter-header"
            style="background: linear-gradient(135deg, #0066CC 0%, #0052A3 100%); padding: 60px 0; color: white; text-align: center;">
            <div class="container">
                <h1 class="entry-title" style="color: white; font-size: 3rem;"><?php the_title(); ?></h1>
                <p class="chapter-location" style="font-size: 1.2rem; opacity: 0.9;">
                    <?php echo get_the_term_list(get_the_ID(), 'chapter_region', '', ', '); ?>
                </p>
            </div>
        </div>

        <div class="container" style="padding: 50px 0;">
            <div class="chapter-content-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 50px;">

                <!-- Main Content -->
                <div class="chapter-main">
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>

                    <div class="community-agreement"
                        style="margin-top: 50px; padding: 30px; background: #f0f4ff; border-radius: 10px;">
                        <h3><span style="color: #0066CC;">🤝</span> Community Agreement</h3>
                        <p>By attending this chapter, members agree to:</p>
                        <ul>
                            <li>Focus on the 90% good in humanity.</li>
                            <li>Listen to understand, not to argue.</li>
                            <li>Leave politics and divisiveness at the door.</li>
                        </ul>
                    </div>
                </div>

                <!-- Sidebar / Details -->
                <div class="chapter-sidebar">
                    <div class="sidebar-card"
                        style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-top: 4px solid #E8B93E;">
                        <h3 style="margin-top: 0; color: #0066CC;">Chapter Details</h3>

                        <?php
                        // Get meta fields
                        $city = get_post_meta(get_the_ID(), '_chapter_city', true);
                        $state = get_post_meta(get_the_ID(), '_chapter_state', true);
                        $meeting_pattern = get_post_meta(get_the_ID(), '_chapter_meeting_pattern', true);
                        $location_name = get_post_meta(get_the_ID(), '_chapter_location_name', true);
                        $location_address = get_post_meta(get_the_ID(), '_chapter_location_address', true);
                        $member_count = get_post_meta(get_the_ID(), '_chapter_member_count', true);
                        $status = get_post_meta(get_the_ID(), '_chapter_status', true) ?: 'active';
                        
                        // Display location
                        if ($city || $state) {
                            echo '<p style="margin-bottom: 15px;"><strong style="color: #004A99;">📍 Location:</strong><br>';
                            if ($city && $state) {
                                echo esc_html($city) . ', ' . esc_html($state);
                            } elseif ($city) {
                                echo esc_html($city);
                            } elseif ($state) {
                                echo esc_html($state);
                            }
                            echo '</p>';
                        }
                        
                        // Display meeting info
                        if ($meeting_pattern || $location_name) {
                            echo '<p style="margin-bottom: 15px;"><strong style="color: #004A99;">📅 Meeting Details:</strong><br>';
                            if ($meeting_pattern) {
                                echo '<span style="color: #0066CC; font-weight: 600;">' . esc_html($meeting_pattern) . '</span><br>';
                            }
                            if ($location_name) {
                                echo esc_html($location_name);
                                if ($location_address) {
                                    echo '<br><small style="color: #666;">' . esc_html($location_address) . '</small>';
                                }
                            } elseif (!$meeting_pattern) {
                                echo 'Check description for meeting details.';
                            }
                            echo '</p>';
                        }
                        
                        // Display member count if available
                        if ($member_count && $member_count > 0) {
                            echo '<p style="margin-bottom: 15px;"><strong style="color: #004A99;">👥 Members:</strong><br>';
                            echo esc_html($member_count) . ' active members';
                            echo '</p>';
                        }
                        
                        // If no meta fields set, show default message
                        if (!$city && !$state && !$meeting_pattern && !$location_name) {
                            echo '<p style="color: #666; font-style: italic;">Chapter details coming soon. Check the description below.</p>';
                        }
                        ?>

                        <?php
                        // Include contact form component
                        if (file_exists(get_stylesheet_directory() . '/components/chapter-contact-form.php')) {
                            include(get_stylesheet_directory() . '/components/chapter-contact-form.php');
                        } elseif (file_exists(get_stylesheet_directory() . '/../hybrid_site/components/chapter-contact-form.php')) {
                            include(get_stylesheet_directory() . '/../hybrid_site/components/chapter-contact-form.php');
                        } else {
                            // Fallback to mailto links if form component not found
                            ?>
                            <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee;">
                                <a href="mailto:chapters@choose90.org?subject=Contact Host: <?php the_title_attribute(); ?>"
                                    class="btn btn-primary" style="width: 100%; text-align: center; display: block; padding: 12px; margin-bottom: 10px; text-decoration: none; border-radius: 6px; background: #0066CC; color: white; font-weight: 600;">Contact Host</a>
                                <a href="mailto:chapters@choose90.org?subject=Join Chapter: <?php the_title_attribute(); ?>"
                                    class="btn btn-outline" style="width: 100%; text-align: center; display: block; padding: 12px; text-decoration: none; border-radius: 6px; border: 2px solid #0066CC; color: #0066CC; font-weight: 600;">Join This Chapter</a>
                            </div>
                            <?php
                        }
                        ?>
                        
                        <?php if ($location_address): ?>
                            <div style="margin-top: 15px;">
                                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($location_address); ?>" 
                                   target="_blank" 
                                   style="color: #0066CC; text-decoration: none; font-size: 14px;">
                                    🗺️ View on Map
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>