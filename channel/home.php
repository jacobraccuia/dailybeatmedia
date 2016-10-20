<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header();

?>

<section id="content" class="news">
	<?php get_sidebar(); ?>

	<div class="col-offset-center">
		<div class="row">
			<div class="col-xs-12">
				<div class="row post-wrapper exclusive-wrapper full-width-wrapper">
					<?php get_exclusive_post(); ?>
				</div>

				<?php category_filter_posts(); ?>
			</div>
		</div>
	</div>



</section>


<?php get_footer(); ?>