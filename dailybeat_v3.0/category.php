<?php
/**
 * The main category file.
 *
 */

get_header();

	$category = get_queried_object();
	$category_name = $category->name;
	$category_id = $category->term_id;
	//$parent_category = get_top_category($category->term_id);

?>

<div class="container main authors">
	<div class="row">
		<div class="col-xs-12">
			
			
				<h3 class="section-title">Posts in: <span><?php echo $category_name; ?></span></h3>
			
				<div class="row category-description">
					<div class="col-xs-8">
						<?php echo category_description() ?>
					</div>
				</div>
	
				<div id="posts-wrapper">
			        <?php global $exclude_posts; ?>
    	        	<?php get_standard_posts(array('category' => $category_id)); ?>
        		</div>
			    
			<div id="load-more" class="load-posts" data-target="#posts-wrapper" data-page="home" data-exclude="<?php echo json_encode($exclude_posts); ?>" data-category="<?php echo $category_id; ?>">
				Load More Posts
				<i class="fa fa-spinner fa-spin"></i>
			</div>


        </div>
	</div>
</div> <!-- close container -->
    
    
    
    
    
<?php get_footer(); ?>