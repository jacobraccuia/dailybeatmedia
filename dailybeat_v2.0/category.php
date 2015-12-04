<?php
/**
 * The main category file.
 *
 */

get_header();

	$category = get_queried_object();
	$parent_category = get_top_category($category->term_id);

?>

<div id="slider-wrapper">
    <div class="container">
		<ul class="rslides row-fluid" id="featured_slider">
			<li>
                <img src="<?php echo THEME_DIR; ?>/images/category/<?php echo $parent_category->slug; ?>.jpg" />
                <div class="caption_wrapper">
                    <h1 class="caption"><?php echo $category->name; ?></h1>
                </div>
			</li>
		</ul><!-- close featured slider -->
    </div><!-- close container -->
</div><!-- close slider wrapper -->       

<?php // featured_nav(); // this includes container class ?>

<div class="container main">
	<div class="row">
		<div class="col-md-8">
			<h3 class="section_title">The Latest:&nbsp;<strong><?php echo $category->name; ?></strong></h3>
			<div class="divider"></div>
                <?php global $exclude_posts; ?>
            	<?php get_category_posts($category->term_id); ?>
               	
        		<div class="load_posts category" data-category="<?php echo $category->term_id; ?>">Load More Posts</div>
        </div>
			<?php get_sidebar(); ?>
	</div>
</div> <!-- close container -->
    
    
    
    
<?php get_footer(); ?>