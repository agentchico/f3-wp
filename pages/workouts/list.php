<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header(); ?>
    <div class="container">
        <div class="page_content">
            <header class="page-header">
                <h1 class="entry-title">Workouts</h1>
            </header><!-- .page-header -->

            <section class="site-main">
                <?php if (have_posts()) : ?>
                    <h3>Upcoming Workouts</h3>
                    <div class="blog-post">
                        <?php /* Start the Loop */ ?>
                        <?php while (have_posts()) : the_post(); ?>
                            <?php get_template_part('content', get_post_format()); ?>
                            <h3><?php echo the_title() ?></h3>

                        <?php endwhile; ?>
                    </div>
                    <?php
                    // Previous/next post navigation.
                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => __('Back', 'skt-strong'),
                        'next_text' => __('Next', 'skt-strong'),
                        'screen_reader_text' => __('Posts navigation', 'skt-strong')
                    ));
                    ?>
                <?php else : ?>
                    <?php get_template_part('no-results'); ?>
                <?php endif; ?>
            </section>
            <?php get_sidebar(); ?>
            <div class="clear"></div>
        </div><!-- site-aligner -->
    </div><!-- container -->

<?php get_footer(); ?>