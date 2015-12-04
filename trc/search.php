<?php
/**
 * search!.
 *
 */

get_header();

	$search_str = get_search_query();
	//$parent_category = get_top_category($category->term_id);

?>

<div class="container main authors">
	<div class="row">
		<div class="col-xs-12">
			
			
				<h3 class="section-title">You Searched: <span><?php echo $search_str; ?></span></h3>
			
				<div id="posts-wrapper" style="margin-top:10px;">
					<?php global $exclude_posts; ?>
	            	<?php get_standard_posts(array('s' => $search_str)); ?>
    
				</div>           	

			<div id="load-more" class="load-posts" data-target="#posts-wrapper" data-page="home" data-exclude="<?php echo json_encode($exclude_posts); ?>" data-category="0" data-search_str="<?php echo $search_str; ?>">
				Load More Posts
				<i class="fa fa-spinner fa-spin"></i>
			</div>


        </div>
	</div>
</div> <!-- close container -->
    
    
    
    
    
<?php get_footer(); ?>