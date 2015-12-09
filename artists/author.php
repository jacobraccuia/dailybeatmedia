<?php
/**
/*

*/


get_header();

$author = get_user_by('slug', get_query_var('author_name'));
$author_id = $author->ID;

?>

<div class="container main authors">
	<div class="row">
		<div class="col-xs-12">
			<div class="top_bio">
				<div class="row">
					<div class="col-xs-8">
						<?php author_biography($author_id); ?>
					</div>
					<div class="col-xs-4">
					</div>
				</div>
			</div>
		
			<div id="posts-wrapper">
		
		        <?php global $exclude_posts; ?>
            	<?php get_standard_posts(array('author_id' => $author_id)); ?>
               	
			</div>

			<div id="load-more" class="load-posts" data-target="#posts-wrapper" data-page="home" data-exclude="<?php echo json_encode($exclude_posts); ?>" data-author="<?php echo $author_id; ?>">
				Load More Posts
				<i class="fa fa-spinner fa-spin"></i>
			</div>


        </div>
	</div>
</div> <!-- close container -->
    
    
    
    
<?php get_footer(); ?>
