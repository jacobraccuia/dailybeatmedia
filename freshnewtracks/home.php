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

<div id="content">

<section>
	<ul>
	<?php 


	$args = array(
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'post_type' => 'tracks',
		'orderby' => 'date',
		'order' => 'DESC',
		);


	$query = new WP_Query($args);

	$i = 1;
	while($query->have_posts()) { $query->the_post();
		$id = $post->ID;

		$track_url = get_post_meta($id, 'track_url', true);

		echo '<li>' . $track_url . '</li>';
		$i++;
	}



	?></ul>

</section>

</div>

<?php get_footer(); ?>