<div id="siderbar">
 <?php //include (TEMPLATEPATH . '/searchform.php'); ?>


<div class="widget">
<?php get_search_form(); ?>
</div>

<div class="widget"> 
<h2>Stream</h2>
<a href="http://shuffler.fm/channels/site:5656"><img src="<?php bloginfo('template_url'); ?>/images/shuffler.png" onmouseover ="this.src='<?php bloginfo('template_url'); ?>/images/shuffler_hover.png'" onmouseout ="this.src='<?php bloginfo('template_url'); ?>/images/shuffler.png'"/></a>
</div>


<div class="widget"> 
<h2>The Collection</h2>
<div>
<?php wp_reset_query(); ?>

<?php
$todaysdate = date('m/d/Y H:i:s');


$args = array(
	'posts_per_page' => 10,
      'post_type' => 'post',
      'post_status' => 'publish',
	'category_name' => 'collection'
);

$q2 = query_posts($args); ?>

<?php foreach ($q2 as $post): ?>
<?php setup_postdata($post); ?>

<div class="collphoto">
	<a href="<?php the_permalink() ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
    <img class="hoverimage" src="<?php bloginfo('template_url'); ?>/images/transhover.png" alt="" /></a>
</div>
            
<?php endforeach; ?>
</div> <div style="clear:both;"></div>
</div> <!-- close widget -->

<div class="widget">
<h2>Events & Giveaways </h2>
<a href="https://www.facebook.com/events/448372785283093/"><img src="<?php bloginfo('template_url'); ?>/images/Zedd.jpg"/></a>
</div>

<div class="widget">
<h2>Event Coverage</h2>
<a href="https://www.facebook.com/media/set/?set=a.703975706279251.1073741903.497439576932866&type=1&l=5cd602b010"><img src="<?php bloginfo('template_url'); ?>/images/aokinyc.png"/></a>
<a href="https://www.facebook.com/media/set/?set=a.700747446602077.1073741897.497439576932866&type=3"><img src="<?php bloginfo('template_url'); ?>/images/bmf3lau.jpg"/></a>
<a href="https://www.facebook.com/media/set/?set=a.697904186886403.1073741894.497439576932866&type=3"><img src="<?php bloginfo('template_url'); ?>/images/dadaxfinity.jpg"/></a>
<a href="https://www.facebook.com/media/set/?set=a.649553388388150.1073741860.497439576932866&type=3"><img src="<?php bloginfo('template_url'); ?>/images/Veld1.jpg"/></a>
<a href="https://www.facebook.com/media/set/?set=a.650167628326726.1073741863.497439576932866&type=3"><img src="<?php bloginfo('template_url'); ?>/images/Veld2.jpg"/></a>
</div>
<div class="widget">
<h2>Discover</h2>
<a class="twitter-timeline" height="500" width="261" href="https://twitter.com/BeaconDailyBeat" data-widget-id="295666351152963585">Tweets by @BeaconDailyBeat</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

</div>

<div class="widget"> 
<h2>Style</h2>
<a href= "http://pinterest.com/dailybeat/"><img src="<?php bloginfo('template_url'); ?>/images/MorganPintrest.jpg" onmouseover = "this.src='<?php bloginfo('template_url'); ?>/images/pintresthover.png'" onmouseout ="this.src='<?php bloginfo('template_url'); ?>/images/MorganPintrest.jpg'"/></a>
<a href= "http://serendipityonthedaily.tumblr.com/"><img src="<?php bloginfo('template_url'); ?>/images/DBTumblr.png" width="261" height="332" onmouseover = "this.src='<?php bloginfo('template_url'); ?>/images/DBTumblrHover.png'" width="261" height="332" onmouseout ="this.src='<?php bloginfo('template_url'); ?>/images/DBTumblr.png'"/></a>

</div>

<div class="widget"> 
<h2>Rutherford House</h2>
<div>
<?php wp_reset_query(); ?>

<?php
$todaysdate = date('m/d/Y H:i:s');


$args = array(
	'posts_per_page' => 6,
      'post_type' => 'post',
      'post_status' => 'publish',
	'category_name' => 'rutherford house'
);

$q2 = query_posts($args); ?>

<?php foreach ($q2 as $post): ?>
<?php setup_postdata($post); ?>

<div class="collphoto">
	<a href="<?php the_permalink() ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
    <img class="hoverimage" src="<?php bloginfo('template_url'); ?>/images/transhover.png" alt="" /></a>
</div>
            
<?php endforeach; ?>
</div> <div style="clear:both;"></div>
</div> <!-- close widget -->


<div class="widget"> 
<h2>Trend Setter</h2>
<div>
<?php wp_reset_query(); ?>

<?php
$todaysdate = date('m/d/Y H:i:s');


$args = array(
	'posts_per_page' => 8,
      'post_type' => 'post',
      'post_status' => 'publish',
	'category_name' => 'trend-setter'
);

$q2 = query_posts($args); ?>

<?php foreach ($q2 as $post): ?>
<?php setup_postdata($post); ?>

<div class="collphoto">
	<a href="<?php the_permalink() ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
    <img class="hoverimage" src="<?php bloginfo('template_url'); ?>/images/transhover.png" alt="" /></a>
</div>

<?php endforeach; ?>
</div> <div style="clear:both;"></div>
</div> <!-- close widget -->




<div class="widget"> 
<h2>Contact Us</h2>
<div align="justify" style="margin-right:29px;">
<b>Submit: </b>Have any suggestions as to what we should post? Shoot us an e-mail at <a href="mailto:dailybeat@beaconrecords.com?Subject=Insert Artist Name & Song Here">DailyBeat@beaconrecords.com</a> with the Artist name and Song Title in the subject header.
</div>
&nbsp;
<div align="justify" style="margin-right:29px;">
<b>Advertise:</b> Want to advertise with us or explore potential partnerships with us? We can help you, just email us at: <a href="mailto:anuj@beaconrecords.com?Subject=Advertise with Daiyl Beat">anuj@beaconrecords.com</a> 
</div>
</div>



</div>