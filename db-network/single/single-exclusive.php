<?php
/**
 * Single Exclusive
 *
 */

get_header();
global $exclude_posts;
if(have_posts()): while(have_posts()): the_post();

$id = get_the_ID();

//$category = get_primary_category($id);

$author_id = get_the_author_meta('ID');
$author_username = get_the_author_meta('display_name');

$artist_id = get_post_meta($id, 'db_featured_artist_id', true);

$exclude_posts = array_merge(array($id), $exclude_posts);

$permalink = get_permalink();



/* if(is_single()) { ?>
<div style="position:fixed; margin:0px auto; width:100%;">
<div class="container">
<div class="row">
<div class="col-sm-8">
<div class="single-header-title"><?php the_title(); ?></div>
</div>
</div>
</div>
</div> 
<?php } */

?>
<div id="content" class="exclusive">

	<?php 

	$post_meta = get_post_custom();
	$db_ex_subtitle = get_post_meta($id, 'db_ex_subtitle', true);
	$db_ex_edition = get_post_meta($id, 'db_ex_edition', true);
	$db_ex_theme_color = get_post_meta($id, 'db_ex_theme_color', true);

	?>
	<style>
	.blockquote .bq, .exclusive-content-1 p:first-child:first-letter { color:#<?php echo $db_ex_theme_color; ?>; }
	.ex-divider.thick { background-color:#<?php echo $db_ex_theme_color; ?>; }
	</style>

	<?php

	$i = 1;
	// find out how many sections there are
	while($i < 11) {
		if(isset($post_meta['db_ex_section' . $i]) && $post_meta['db_ex_section' . $i][0] != '') {


			// check if any extra featured images exist
			$featured_images = '';
			if(class_exists('Dynamic_Featured_Image')) {
				$featured_images = $dynamic_featured_image->get_featured_images($id);
			}

			$featured_url = '';
			// check if there is an image set for the loop # 
			if($i == 1 && has_post_thumbnail()) {
				$featured_id = get_post_thumbnail_id();
				$featured_url = wp_get_attachment_image_src($featured_id, array(1500, 900), true)[0];
			}
			if($i > 1 && isset($featured_images[$i - 2])) {
				$featured_url = $featured_images[$i - 2]['full'];
			}

			if($featured_url != '') {
				?>
				<section class="exclusive-image exclusive-image-<?php echo $i; ?>" style="background-image:url('<?php echo $featured_url; ?>');"><div class="overlay"></div><?php
					$caption = $dynamic_featured_image -> get_image_caption($featured_url);
					if($caption && $caption != '') {
						echo '<h5 class="wp-caption-text">' . $caption . '</h5>';
					} 	
					?>
				</section>
				<?php 
		
			}



				if($i == 1) {
					echo '<section class="exclusive-header">';
					echo '<h3 style="color:#' . $db_ex_theme_color .';">' .  full_timestamp() . ' - Daily Beat Presents - no. ' . $db_ex_edition . ' </h3>';
					echo '<h1>' . get_the_title() . '</h1>';
				
					if($db_ex_subtitle != '') {
						echo '<h2>' . $db_ex_subtitle . '</h2>';
					}
					echo '<div class="ex-divider thin"></div>';
					echo '<h4>Written by ' . get_the_author_meta('display_name') . '</h4>';
					echo '<div class="ex-divider thick"></div>';
					echo '</section>';
				}
			
			?>

			<section class="content exclusive-content exclusive-content-<?php echo $i; ?>">
					<?php echo apply_filters('the_content', $post_meta['db_ex_section' . $i][0]); ?>
			</section>

			<?php

		}
		$i++;
	}



	?>


</div> <!-- close content -->


<?php endwhile; endif; 


get_footer(); ?>
