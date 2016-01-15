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

get_header(); /* ?>

<div class="container main">
	<div class="row">
		<?php if(have_posts()): while(have_posts()): the_post(); ?>
        	<div class="col-md-8">
				<h3 class="section_title"><?php echo the_title(); ?></h3>
				<?php echo the_content(); ?>
			 </div>
		<?php endwhile; ?><?php endif; ?>
		<?php get_sidebar(); ?>
	</div>
</div> <!-- close container -->



<?php */ get_footer(); ?>