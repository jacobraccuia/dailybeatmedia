<?php
/**
 * The main category file.
 *
 */

get_header();

?>
<div class="container main">
	<div class="row">
		<div class="col-md-8">
			<h3 class="section_title search sticky_search_waypoint">You Searched:&nbsp;<strong><?php echo get_search_query(); ?></strong></h3>
					<div class="sticky_search sticky_title_bar">
                    	<div class="container">
		                   	<div class="left"><h1 class="title">You Searched:&nbsp;<strong><?php echo get_search_query(); ?></strong></h1></div>
                        </div>
                    </div>
			<div class="divider"></div>
                <?php global $exclude_posts; ?>
            	<?php get_search_posts(get_search_query()); ?>
               	
        		<div class="load_posts search" data-search="<?php echo get_search_query(); ?>">Load More Posts</div>
        </div>
			<?php get_sidebar(); ?>
	</div>
</div> <!-- close container -->
    
    
    
    
<?php get_footer(); ?>