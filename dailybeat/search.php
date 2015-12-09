<?php get_header(); ?>
     
        <div id="container"> 
            <div id="content">



<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>

<div class="blogcontainer">
    			<h4><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
                <div class="tags"><ul class="tagformat"><li><?php the_category('</li><li>'); ?></li></ul></div>
      <div class="blogphoto">
     		<a href="<?php the_permalink() ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?></a>
      </div>

      <div class="blogexcerpt"><?php the_excerpt(); ?></div>
        	  <?php post_meta_bar("post"); ?>

    <div style="clear:both;"></div>
</div>
            
<?php endwhile; ?><?php endif; ?>

<?php  pagination($additional_loop->max_num_pages); ?>
        
        
 
            </div><!-- #content -->    
         
<?php get_sidebar(); ?>  

        </div><!-- #container -->
<?php get_footer(); ?>