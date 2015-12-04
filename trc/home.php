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

<div class="spotlight">
	
	<?php
	
		$cat_object = get_category_by_slug('spotlight-featured'); 
		$spotlight_featured = $cat_object->term_id;
		
		$cat_object = get_category_by_slug('spotlight-left'); 
		$spotlight_left = $cat_object->term_id;
		
		$cat_object = get_category_by_slug('spotlight-right'); 
		$spotlight_right = $cat_object->term_id;
	
		$featured_args = array(
				'posts_per_page' => 1,
				'cat' => $spotlight_featured,
				'post_status' => 'publish',
		);
		
			$featured_query = new WP_Query($featured_args);
			while($featured_query->have_posts()) : $featured_query->the_post();
				echo '<div class="col-md-6 spotlight-posts">';
				echo spotlight_post();
				echo '</div>';
			endwhile;
	
			
			$args = array(
				'posts_per_page' => 1,
				'cat'  => $spotlight_left, // $category . ', -' . $featured_cat, // all categories, excluding featured
				'post_status' => 'publish'
			);
		
			$query = new WP_Query($args);
			while($query->have_posts()) : $query->the_post();
				echo '<div class="col-md-3 spotlight-posts">';
				echo spotlight_post();
				echo '</div>';
			endwhile;
			
			
			$args = array(
				'posts_per_page' => 1,
				'cat'  => $spotlight_right, // $category . ', -' . $featured_cat, // all categories, excluding featured
				'post_status' => 'publish'
			);
		
			$query = new WP_Query($args);
			while($query->have_posts()) : $query->the_post();
				echo '<div class="col-md-3 spotlight-posts">';
				echo spotlight_post();
				echo '</div>';
			endwhile;
	
	?>

</div>

<div class="container main">
	<div class="row">
		<div class="col-md-12">
			<?php get_ad('banner'); ?>
			<?php global $exclude_posts; ?>
			
			<div id="posts-wrapper">
				<?php get_standard_posts(array('show_ads' => 1)); ?>    
				<?php get_standard_posts(array('show_ads' => 0)); ?>    
			</div>
			
			<div id="load-more" class="load-posts" data-target="#posts-wrapper" data-page="home" data-exclude="<?php echo json_encode($exclude_posts); ?>" data-category="0">
				Load More Posts
				<i class="fa fa-spinner fa-spin"></i>
			</div>
        </div>
			<?php  //get_sidebar(); ?>
	</div>
</div> <!-- close container -->
  
    
<?php get_footer(); ?>