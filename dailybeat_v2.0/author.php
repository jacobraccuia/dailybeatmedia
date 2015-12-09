<?php
/**
/*
Template Name: Daily Beat
*/


get_header();

$author = get_user_by('slug', get_query_var('author_name'));
$author_id = $author->ID;

?>

<?php /*<div id="slider-wrapper">
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
*/ ?>


<div class="container main authors">
	<div class="row">
		<div class="col-md-8">
			<div class="top_bio">
				<?php author_biography($author_id); ?>
			</div>
			<h3 class="section_title author">The Latest:&nbsp;<strong><?php echo $author->name; ?></strong></h3>
			<div class="divider "></div>
                <?php global $exclude_posts; ?>
            	<?php get_author_posts($author_id, array(), 0, 'author_page'); ?>
               	
        		<div class="load_posts author" data-author="<?php echo $author_id; ?>">Load More Posts</div>
        </div>
			<?php get_sidebar(); ?>
	</div>
</div> <!-- close container -->
    
    
    
    
<?php get_footer(); ?>
