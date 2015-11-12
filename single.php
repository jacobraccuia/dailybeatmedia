<?php
/**
 * The Template for displaying all single posts.
 *
 */

get_header();

	global $exclude_posts;
		if(have_posts()): while(have_posts()): the_post();
    
		$id = get_the_ID();
		
		$category = get_primary_category($id);

        // author
        $author_id = get_the_author_meta('ID');
        $author_username = get_the_author_meta('display_name');
	
		$exclude_posts = array_merge(array($id), $exclude_posts);
	
		$permalink = get_permalink();
		
		?>	
			<?php /* if(is_single()) { ?>
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
	<div id="content">

<section class="jumbotron single-header-wrapper <?php echo $category->slug; ?>">
	<div class="featured-post-header-overlay"></div>
		<?php

			$featured_images = '';
			if(class_exists('Dynamic_Featured_Image')) {
				$featured_images = $dynamic_featured_image->get_featured_images($id);
			}
			
			if(isset($featured_images[0])) {
				echo '<img src="' . $featured_images[0]['full'] . '" />';
			} else {
				if(has_post_thumbnail()) { the_post_thumbnail('full'); }
			}
		?>
		<div class="single-meta row">
			<div class="col-md-8 col-md-offset-2">
			<div class="author meta"><?php the_author_posts_link(); ?></div>
			<div class="date meta"><?php the_timestamp(); ?> ago</div>
			<?php /* <h4>
				<div class="author-image"><?php author_image($author_id); ?></div>
				<span class="spacer push-spacer">|</span>
				<span class="date"><?php //echo //the_timestamp_short(); ?></span>
				<span class="spacer">|</span>
				<span class="category"><?php //echo '<a href="' . get_term_link($category->slug, 'category') . '">' . $category->name . '</a>'; ?></span>
			</h4> */?>
			<h1><?php the_title(); ?></h1>
			</div>
		</div>
 
</section>
	</div>
    
</div> <!-- close container -->
</div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>