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

<div id="slider-wrapper">
    <div class="container">
		<ul class="rslides row-fluid" id="featured_slider">
			<?php
                $args = array(
                    'category'  => 3013, // featured image category
                    'showposts' => 4,
                );
    
                $posts = get_posts($args);	
    
                foreach($posts as $post) {
                    $id = $post->ID;
					$title = $post->post_title;
					$guid = $post->guid;
				    
					$featured_images = $dynamic_featured_image->get_featured_images($id);
					
					echo "<li>";
						echo '<a href="' . $guid . '">';
							if($featured_images[0]) { 
								echo '<img src="' . $featured_images[0]['full'] . '" />';
							} else {
								echo get_the_post_thumbnail($id, 'full');
							}
							echo '<div class="caption_wrapper"><h1 class="caption">' . $title .'</h1></div>';
						echo '</a>';
                	echo "</li>";
				}
                
                wp_reset_postdata();
            ?>

</ul><!-- close featured slider -->

</div><!-- close container -->
</div><!-- close slider wrapper -->   
 

<div class="container main">

	<div class="row">
		<div class="col-md-8">
			<div class="top_banner">
					
		<!-- DailyBeat_728x90 -->
		<div id='div-gpt-ad-1395271462856-3' style='width:728px; height:90px;'>
		<script type='text/javascript'>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1395271462856-3'); });
		</script>
		</div>
			
			
			<?php /*	
			<script type="text/javascript"><!--
				google_ad_client = "ca-pub-7516968278145985";
				/* Leaderboard Latest 001 
				google_ad_slot = "8945509525";
				google_ad_width = 728;
				google_ad_height = 90;
			//-->
			</script>
			<script type="text/javascript"
				src="//pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
				
		<?php /*	
				<div class="category_tag_wrapper">
					<div class="category_tag">Tickets</div>
				</div>
				<a href="http://www.wantickets.com/events/ShowEvent.aspx?eventId=147486" target="_blank"><img class="color_overlay img-responsive" src="<?php echo THEME_DIR; ?>/images/homepage_ad.jpg" alt="FEHR Play" /></a>
				
				*/ ?>
				
				
			</div>

			<h3 class="section_title">The Latest</h3>
			<div class="divider"></div>
            	<?php global $exclude_posts; ?>
            	<?php get_homepage_posts(); ?>    
        	<div class="load_posts home">Load More Posts</div>
        </div>
			<?php get_sidebar(); ?>
	</div>
</div> <!-- close container -->

<?php get_footer(); ?>
