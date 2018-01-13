<?php
/**
 * The template for displaying a PAX
 *
 */

get_header();

?>

<div class="wrap">
    <h1> <?php the_archive_title( '<h1 class="entry-title">', '</h1>' );?>
    </h1>
    <h1>This is the content.</h1>
    <div> <?php the_author_meta("user_login"); ?> </div>
</div>

<?php get_footer(); ?>