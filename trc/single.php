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
	
<section class="jumbotron single-header-wrapper <?php echo $category->slug; ?>">
	<div class="featured-post-header-overlay"></div>
		<?php
			if(class_exists('Dynamic_Featured_Image')) {
				$featured_images = $dynamic_featured_image->get_featured_images($id);
			}
			
			if($featured_images[0]) {
				echo '<img src="' . $featured_images[0]['full'] . '" />';
			} else {
				if(has_post_thumbnail()) { the_post_thumbnail('full'); }
			}
		?>
		<div class="single-meta cat-border">
			<h1 class="title"><?php the_title(); ?></h1>
			<h4 class="info cat-links">
				<div class="author-image"><?php author_image($author_id); ?></div>
				<span class="author"><?php the_author_posts_link(); ?></span>
				<span class="spacer push-spacer">|</span>
				<span class="date"><?php echo the_timestamp_short(); ?></span>
				<span class="spacer">|</span>
				<span class="category"><?php echo '<a href="' . get_term_link($category->slug, 'category') . '">' . $category->name . '</a>'; ?></span>
			</h4>
		</div>
 
</section>
<div class="container main <?php echo $category->slug; ?>">
	<div class="row">
		<div class="col-md-8 sidebar-height">
				<?php get_ad('post-top'); ?>
                <?php wp_reset_query(); ?>
			  <ul class="share">
              	<?php 
					$bitly = make_bitly_url($permalink);
					$share_title = urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8'));;
				?>
              

			  	<li class="social facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $bitly; ?>" data-shared="<?php the_title(); ?>" data-social="Facebook" target="_blank"><i class="fa fa-facebook-square"></i>Share</a></li>
				<li class="social twitter"><a href="https://twitter.com/share?text=<?php echo $share_title; ?>&url=<?php echo $bitly; ?>&via=TorontoRC&hashtags=TRC" data-shared="<?php the_title(); ?>" data-social="Twitter" target="_blank"><i class="fa fa-twitter"></i>Tweet</a></li>
				<li class="social linkedin"><a href="https://www.linkedin.com/cws/share?url=<?php echo $bitly; ?>" data-shared="<?php the_title(); ?>" data-social="Google Plus" target="_blank"><i class="fa fa-linkedin-square"></i>Post</a></li>
				<li class="social email"><a href="mailto:?subject=<?php the_title(); ?>&body=<?php echo the_excerpt(); ?>" data-shared="<?php the_title(); ?>" data-social="Email"><i class="fa fa-envelope"></i>Email</a></li>
				<li class="social pinterest"><a href="https://www.pinterest.com/pin/create/button/?url=<?php echo $bitly; ?>" target="_blank" data-shared="<?php the_title(); ?>" data-social="Pinterest"><i class="fa fa-pinterest-square"></i>Pin</a></li>
				<li class="social comment"><a href="#comments"><i class="fa fa-comment"></i>Comment</a></li>
			</ul>
			
			<div class="post-content cat-links cat-first-letter">
				<?php echo the_content(); ?>
			</div>
			
            <div class="extras">
             	<?php get_ad('post-bottom'); ?>
			</div>
			<div class="extras">
				<h3 class="section-title">About The Author</h3>
				<div class="divider cat-background bottom_margins"></div>
				<?php author_biography($author_id); ?>
			</div>
			
			<div class="extras">
				<h3 class="section-title">Related</h3>
				<div class="divider cat-background bottom_margins"></div>
				<?php get_related_posts(array('category' => $category->term_id)); ?>
			</div>
			
			<?php /*
			<div class="extras">
				<h3 class="section-title">More By <strong><?php echo $author_username; ?></strong></h3>
				<div class="divider cat-background bottom_margins"></div>
				<?php get_related_posts(array('author_id' => $author_id)); ?>
			</div> */ ?>
			
			<div class="comments" id="comments">                        	
				<h3 class="section-title">Comments</h3>
				<div class=" divider cat-background"></div>
				<?php // this is really fucking important. 
					wp_reset_query(); ?>
				<?php echo do_shortcode('[fbcomments]'); ?>        
			</div>
        </div>
			<?php get_sidebar(); ?>
	</div>
    
</div> <!-- close container -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>