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

get_header(); ?>


<div id="container">

<div id="content">
<?php

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // for pagination

$args = array(
'posts_per_page' => 6,
'paged' => $paged, // for pagination
'post_status' => array('publish')
);

query_posts($args);

?>

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
        
        


</div>

<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>