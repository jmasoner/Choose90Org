<?php
/* Template Name: Chapters Directory */
get_header();
?>

<div id="main-content">

    <!-- HERO & PITCH SECTION (Always Visible) -->
    <div class="chapters-pitch" style="max-width: 900px; margin: 0 auto; padding: 50px 20px;">

        <!-- Hero Section -->
        <div style="text-align: center; margin-bottom: 60px;">
            <h1 style="font-size: 3.5rem; font-weight: 800; color: #0066CC; margin-bottom: 20px; line-height: 1.1;">
                Stop Waiting for Community.<br><span style="color: #E8B93E;">Build It.</span></h1>
            <p style="font-size: 1.25rem; color: #555; max-width: 700px; margin: 0 auto 30px;">
                Be the spark that starts the movement in your neighborhood.
            </p>

            <div class="hero-actions" style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="/host-starter-kit/" class="btn btn-starfield"
                    style="padding: 15px 35px; font-size: 1.2rem; border-radius: 50px; box-shadow: 0 10px 20px rgba(0,102,204,0.2);">
                    Get the Host Starter Kit
                </a>
                <a href="#chapter-directory" class="btn btn-outline"
                    style="padding: 15px 35px; font-size: 1.2rem; border-radius: 50px; border: 2px solid #0066CC; color: #0066CC;">
                    Find a Chapter
                </a>
            </div>
        </div>

        <!-- 3 Benefits Grid -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-bottom: 60px;">
            <div
                style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid #0066CC;">
                <h3 style="color: #0066CC; margin-top: 0; font-size: 1.4rem;">1. Cure Your Own Loneliness</h3>
                <p style="color: #666; font-size: 0.95rem; line-height: 1.6;">The secret to connection isn't finding
                    it—it's creating it. By gathering neighbors for positive conversations, you'll fill your own cup
                    while serving others.</p>
            </div>
            <div
                style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid #E8B93E;">
                <h3 style="color: #E8B93E; margin-top: 0; font-size: 1.4rem;">2. Be a Beacon of Light</h3>
                <p style="color: #666; font-size: 0.95rem; line-height: 1.6;">For just one hour a month, create a space
                    where hope outweighs headlines. Your coffee shop corner becomes an oasis where people rediscover
                    shared humanity.</p>
            </div>
            <div
                style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid #0066CC;">
                <h3 style="color: #0066CC; margin-top: 0; font-size: 1.4rem;">3. Do Something Real</h3>
                <p style="color: #666; font-size: 0.95rem; line-height: 1.6;">Swap scrolling for sitting together. Trade
                    online debates for eye contact. Choose90 turns passive concern into active connection.</p>
            </div>
        </div>

        <!-- How It Works Section -->
        <div style="background: #f0f4ff; padding: 50px; border-radius: 20px; text-align: center; margin-bottom: 40px;">
            <h2 style="color: #0066CC; margin-bottom: 40px; font-size: 2.2rem;">How It Works <span
                    style="font-size: 1rem; color: #666; font-weight: normal; display: block; margin-top: 10px;">(It's
                    Simpler Than You Think)</span></h2>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; text-align: left;">
                <div style="flex: 1; min-width: 250px;">
                    <h4 style="font-size: 1.2rem; margin-bottom: 10px; color: #0066CC;">Step 1: Apply (5 mins)</h4>
                    <p style="font-size: 0.95rem;">Tell us you're interested. No resume, no experience needed. We're
                        looking for hearts, not credentials.</p>
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <h4 style="font-size: 1.2rem; margin-bottom: 10px; color: #E8B93E;">Step 2: Get Your Kit (Free)</h4>
                    <p style="font-size: 0.95rem;">We'll send you discussion guides, hosting tips, and a warm welcome
                        package. Everything is designed for regular people.</p>
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <h4 style="font-size: 1.2rem; margin-bottom: 10px; color: #0066CC;">Step 3: Host (1 hr/month)</h4>
                    <p style="font-size: 0.95rem;">Pick a coffee shop, show up, and welcome people. You're not teaching
                        or preaching—you're facilitating.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- CHAPTER DIRECTORY (Grid) -->
    <div id="chapter-directory" class="container" style="padding: 50px 0; border-top: 1px solid #eee;">

        <h2 class="section-title" style="text-align: center; margin-bottom: 40px; color: #333;">Active Community
            Chapters</h2>

        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'chapter',
            'posts_per_page' => 12,
            'paged' => $paged,
            'post_status' => 'publish'
        );
        $chapters_query = new WP_Query($args);

        if ($chapters_query->have_posts()): ?>

            <div class="chapters-grid"
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
                <?php while ($chapters_query->have_posts()):
                    $chapters_query->the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('chapter-card'); ?>
                        style="border: 1px solid #eee; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); background: white;">

                        <h2 class="entry-title" style="font-size: 1.5rem; margin-bottom: 15px;">
                            <a href="<?php the_permalink(); ?>"
                                style="text-decoration: none; color: #333;"><?php the_title(); ?></a>
                        </h2>

                        <?php if (has_post_thumbnail()): ?>
                            <div class="chapter-image" style="margin-bottom: 15px;">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium', array('style' => 'width:100%; height:auto; border-radius: 5px;')); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content" style="font-size: 0.95rem; color: #666; margin-bottom: 20px;">
                            <?php the_excerpt(); ?>
                        </div>

                        <a href="<?php the_permalink(); ?>" class="btn btn-outline"
                            style="display: inline-block; padding: 8px 20px; border: 1px solid #0066CC; color: #0066CC; border-radius: 5px; text-decoration: none;">
                            View Chapter
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="pagination" style="text-align: center; margin-top: 40px;">
                <?php echo paginate_links(array('total' => $chapters_query->max_num_pages)); ?>
            </div>

        <?php else: ?>

            <p style="text-align: center; font-size: 1.2rem; color: #666;">No active chapters yet. <a href="#top"
                    style="color: #0066CC;">Start one today!</a></p>

        <?php endif;
        wp_reset_postdata(); ?>

    </div>
</div>

<?php get_footer(); ?>