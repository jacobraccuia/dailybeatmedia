<?php
/**
 * The Template for displaying all single posts.
 *
 */

get_header();	

	global $exclude_posts;
	if(have_posts()): while(have_posts()): the_post();
    
		$id = get_the_ID();
		$db_soundcloud = get_post_meta($id, 'db_soundcloud', true);
		$db_soundcloud_color = get_post_meta($id, 'db_soundcloud_color', true);
		$category = get_the_terms($id, 'category');		
		
		$ad_size = get_post_meta($id, 'db_ad_size', true);

		
		$song_title = get_the_title();
		
		$title = get_the_title();
		$premiere_title = get_post_meta($id, 'db_premiere_title', true);
						
		if(empty($premiere_title)) {
			$premiere_title = $song_title;	
		}
		
		$exclude_posts = array_merge(array($id), $exclude_posts);


		$thumb_id = get_post_thumbnail_id();
		$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'full', true);
		$thumb_url = $thumb_url_array[0];
	
	?>
	
		<style>
		 .post_content a { color:<?php echo $db_soundcloud_color; ?>!important; }
		</style>
	
	
		<section id="full-width-premiere" style="background-image:url(<?php echo $thumb_url; ?>);">
			<div class="content-overlay">
				<div class="category" style="background-color:<?php echo $db_soundcloud_color; ?>!important;">
					<?php // display 1 'random' categories
						foreach((array) $category as $term) {
							if($term->name == "featured") { continue; }
							echo '<a href="' . get_term_link($term->slug, 'category') . '">' . $term->name . '</a>';
							break;
						}
					?>
				</div>
				<div class="timestamp"><?php echo the_timestamp_short(); ?></div>
				<h2 class="premiere-title">
					<a href="#play" class="sc-play" style="color:<?php echo $db_soundcloud_color; ?>!important;"><span class="glyphicon glyphicon-play"></span></a>
					<a href="#pause" class="sc-pause hidden" style="color:<?php echo $db_soundcloud_color; ?>!important;"><span class="glyphicon glyphicon-pause"></a>
					<?php echo $premiere_title; ?>
				</h2>
				<?php full_soundcloud_player($db_soundcloud, $song_title, $db_soundcloud_color); ?>

			</div>
			
			<div id="large-waveform"></div>
		</section>
		
		
		<div class="container">
			<div class="row">
				<div class="col-sm-9">
					<div class="content">
						<div class="sticky_title">
							<div class="container">
								<div class="right"><span class='st_facebook_hcount share_this' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='facebook'></span>&nbsp;<span st_via='BeaconDailyBeat #DailyBeat' st_username='@BeaconDailyBeat' class='st_twitter_hcount share_this' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='twitter'></span></div>
								<div class="left"><h1 class="title"><?php the_title(); ?></h1></div>
							</div>
						</div>
						<div class="post_content">
						
							<?php echo the_content(); ?>
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<?php if($ad_size == "300x250") { ?>
						<div id="div-gpt-ad-1395271462856-1" style="width:300px; height:250px;">
							<script type="text/javascript">googletag.cmd.push(function() { googletag.display('div-gpt-ad-1395271462856-1'); });</script>
						</div>
					<?php } else if($ad_size =="300x600") { ?>
						<div id='div-gpt-ad-1395271462856-2' style='width:300px; height:600px;'>
						<script type='text/javascript'>googletag.cmd.push(function() { googletag.display('div-gpt-ad-1395271462856-2'); });</script>
						</div>
					<?php } ?>
				</div>		
			</div>
		</div>
					
	<?php endwhile; endif; ?>
		
        
	<?php get_footer(); ?>