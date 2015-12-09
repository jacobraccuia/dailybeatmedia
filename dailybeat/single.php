<?php
/**
 * The Template for displaying all single posts.
 *
 */

get_header(); ?>	



	<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
		

        <?php endwhile; ?><?php endif; ?>
		
		
<div id="container">

<div id="content">
	<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
          
		  
        <div class="sharethisbar">
				<div>
            <span class='st_facebook_vcount' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='facebook'></span><span st_via='BeaconDailyBeat #DailyBeat' st_username='@BeaconDailyBeat' class='st_twitter_vcount' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='twitter'></span><span class='st_pinterest_vcount' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='pinterest'></span><div style="margin:5px 0px 0px 4px;">
			<script type="text/javascript">
			//reddit_url='[URL]';
			reddit_newwindow='1';</script>
			<script type="text/javascript" src="http://www.reddit.com/static/button/button2.js"></script>
           </div>
          
			</div>
            </div>
		  
		  
<div class="blogcontainer singlepost">
    			<h4><?php the_title(); ?></h4>
                <div class="tags"><ul class="tagformat"><li><?php the_category('</li><li>'); ?></li></ul></div>
      <div class="blogphoto">
     		<?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
      </div>
      	<div class="blogcontent"><?php the_content(); ?></div>

            <br/><br/>
            
      
            
			
			<div class="ratingbar">
			<?php if(function_exists("kk_star_ratings")) : echo kk_star_ratings($post->ID); endif; ?>
			</div>
			
			<?php post_meta_bar("single"); ?>

	            </div>
                
                
<?php echo do_shortcode('[fbcomments]');
	 ?>        
        <?php endwhile; ?><?php endif; ?>

   
</div><!-- #content -->


<?php get_sidebar(); ?>
            </div>
<?php get_footer(); ?>