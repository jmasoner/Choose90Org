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
                        <h3 style="margin-top: 0;">Chapter Details</h3>

                        <!-- We will add dynamic meta fields here later. For now, rely on Content or generic text. -->
                        <p><strong>Meeting Details:</strong><br>Check the description for upcoming dates.</p>

                        <a href="mailto:john@choose90.org?subject=Contact Host: <?php the_title_attribute(); ?>"
                            class="btn btn-primary" style="width: 100%; text-align: center; margin-top: 20px;">Contact
                            Host</a>
                        <a href="mailto:john@choose90.org?subject=Join Chapter: <?php the_title_attribute(); ?>"
                            class="btn btn-outline" style="width: 100%; text-align: center; margin-top: 10px;">Join This
                            Chapter</a>
                    </div>
                </div>

            </div>
        </div>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>