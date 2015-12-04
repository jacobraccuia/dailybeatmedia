<?php
/**
/*
Template Name: Daily Beats
*/

get_header(); ?>


<div id="container">

<div id="content">
<?php

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // for pagination


$args = array(
'posts_per_page' => 6,
'paged' => $paged // for pagination
);
$args = array_merge( $args, $wp_query->query ); 
query_posts($args);

?>
<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>

<div class="blogcontainer">
    			<h4 style="font-weight:bold;"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
                <div class="tags"><ul class="tagformat"><li><?php the_category('</li><li>'); ?></li></ul></div>
      <div class="blogphoto">
     		<a href="<?php the_permalink() ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?></a>
      </div>

      <div class="blogexcerpt"><?php the_excerpt(); ?></div>
      
  	  <?php post_meta_bar("post"); ?>
	
    <div style="clear:both;"></div>
</div>
            
<?php endwhile; ?><?php endif; ?>
<?php pagination($additional_loop->max_num_pages); ?>


</div>

<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
