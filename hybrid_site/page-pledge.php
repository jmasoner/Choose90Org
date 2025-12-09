<?php
/*
Template Name: Pledge Page Custom
*/

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">
    <div class="container">
        <div id="content-area" class="clearfix">
            <div id="left-area">
                
                <!-- CUSTOM INJECTED CONTENT -->
                <div class="pledge-intro" style="margin-bottom: 30px; padding: 20px; background: #f9f9f9; border-left: 5px solid #0066cc; border-radius: 5px;">
                    <h2 style="color: #0066cc; margin-top: 0;">I Choose 90.</h2>
                    <p style="font-size: 1.2em; line-height: 1.6;"> While I am not perfect, I Choose a more Positive Life.<br>
                    Nobody is judging me, this is my choice.<br>
                    <strong>I Choose Positive. I Choose 90.</strong></p>
                </div>
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
