<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 */

get_header(); ?>

<div id="container">

<div id="content">

	    <?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
        <?php echo get_content(); ?>
        <?php endwhile; ?><?php endif; ?>
</div>

<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>