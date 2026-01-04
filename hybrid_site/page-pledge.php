<?php
/*
Template Name: Join Page Custom
*/

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">
    <div class="container">
        <div id="content-area" class="clearfix">
            <div id="left-area">
                
                <!-- CUSTOM INJECTED CONTENT -->
                <?php 
                // Check if user is already logged in
                if (is_user_logged_in()) {
                    $user = wp_get_current_user();
                    $screen_name = get_user_meta($user->ID, 'screen_name', true);
                    if (empty($screen_name)) {
                        $screen_name = $user->display_name;
                    }
                    ?>
                    <div class="pledge-already-pledged" style="margin-bottom: 30px; padding: 30px; background: #e8f5e9; border-left: 5px solid #4caf50; border-radius: 5px;">
                        <h2 style="color: #0066cc; margin-top: 0;">Welcome back, <?php echo esc_html($screen_name); ?>! 🎯</h2>
                        <p style="font-size: 1.2em; line-height: 1.6;">
                            Thank you for joining the movement, and for choosing to be 90% positive and uplifting!<br>
                            <a href="resources-hub.html" style="color: #0066CC; font-weight: 600;">Explore Resources</a> | 
                            <a href="/chapters" style="color: #0066CC; font-weight: 600;">Find a Chapter</a>
                        </p>
                    </div>
                    <?php
                } else {
                    // Include the pledge form
                    // Try multiple paths to find the form file
                    $possible_paths = [
                        ABSPATH . 'components/pledge-form.php',  // Server root/components/
                        ABSPATH . 'hybrid_site/components/pledge-form.php',  // Server root/hybrid_site/components/
                        get_stylesheet_directory() . '/../hybrid_site/components/pledge-form.php',  // Relative to theme
                        get_stylesheet_directory() . '/../components/pledge-form.php',  // Alternative relative
                        $_SERVER['DOCUMENT_ROOT'] . '/components/pledge-form.php',  // Document root/components/
                        $_SERVER['DOCUMENT_ROOT'] . '/hybrid_site/components/pledge-form.php',  // Document root/hybrid_site/components/
                    ];
                    
                    $pledge_form_path = null;
                    foreach ($possible_paths as $path) {
                        if (file_exists($path)) {
                            $pledge_form_path = $path;
                            break;
                        }
                    }
                    
                    if ($pledge_form_path) {
                        include($pledge_form_path);
                    } else {
                        // Fallback to HTML version
                        $html_paths = [
                            ABSPATH . 'components/pledge-form.html',
                            ABSPATH . 'hybrid_site/components/pledge-form.html',
                            get_stylesheet_directory() . '/../hybrid_site/components/pledge-form.html',
                            $_SERVER['DOCUMENT_ROOT'] . '/components/pledge-form.html',
                            $_SERVER['DOCUMENT_ROOT'] . '/hybrid_site/components/pledge-form.html',
                        ];
                        
                        foreach ($html_paths as $html_path) {
                            if (file_exists($html_path)) {
                                echo file_get_contents($html_path);
                                break;
                            }
                        }
                    }
                }
                ?>
                <!-- END CUSTOM CONTENT -->

                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <?php if ( ! $is_page_builder_used ) : ?>

                        <h1 class="entry-title main_title"><?php the_title(); ?></h1>
                    
                    <?php endif; ?>

                        <div class="entry-content">
                        <?php
                            the_content();
                        ?>
                        </div> 
                    </article> 
                <?php endwhile; ?>
            </div> 

            <?php get_sidebar(); ?>
        </div> 
    </div> 
</div> 

<?php get_footer(); ?>
