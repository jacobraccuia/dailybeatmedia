<?php 
/**
 * Template Name: Splash Pages
 */


get_header('splash');

$id = get_the_ID();
$bg_color = get_post_meta($id, 'db_background_color', 'true');
$splash_link = get_post_meta($id, 'db_splash_link', 'true'); ?>


<style>
	body { background:<?php echo $bg_color; ?> }
</style>

<div class="container">
	<div class="row">
    	<div class="col-sm-12">
        	<div class="featured-splash">
           		<a href="<?php echo $splash_link; ?>" target="_blank"><?php if(has_post_thumbnail()) { the_post_thumbnail('full'); } ?></a>
            </div>
                <a class="enter" href="http://trc.daily-beat.com/blog"></a>
        </div>
	</div>
</div>


<?php get_footer('splash'); ?>