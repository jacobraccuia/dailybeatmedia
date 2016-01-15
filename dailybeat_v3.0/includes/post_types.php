<?php
// all post views

global $exclude_posts;
$exclude_posts = array();

for($i = 1; $i <= 8; $i++) {
	$exclude_posts[$i] = array(0);
}

// standard post layout
function classic_post($blogID = 1, $args = array()) {
	min_switch_to_blog($blogID);
	$id = get_the_ID();
	exclude_this_post($blogID, $id);

	$defaults = array(
		'show_via' => true,
		);

	// merge arguments with defaults && set keys as variables
	$args = array_merge($defaults, $args);
	foreach($args as $key => $val) { ${$key} = $val; }

	$external = '';
	if($blogID > 1) { $external = 'external-link'; }

	$blog_name = blog_name($blogID);
	$short_blog_name = short_blog_name($blog_name);

	?>

	<article class="classic-post">
		<a href="<?php the_permalink(); ?>">
			<div class="featured-image" style="background-image:url('<?php echo get_thumb_url(350, 350); ?>');"></div>
		</a>
		<?php if(1 == 1) { ?>
		<div class="via-bar <?php echo $short_blog_name; ?>"><?php echo $blog_name; ?></div>
		<?php } ?>
		<div class="post-info">
			<a href="<?php the_permalink(); ?>">
				<h3 class="dotdotdot <?php echo $external; ?>"><?php the_title(); ?></h3>
			</a>
			<h6 class="post-meta">
				<span class="timestamp"><?php the_timestamp(); ?></span> - <span class="author"><?php the_author_posts_link(); ?></span>
			</h6>
		</div>
	</article>

	<?php
}

// variant post layout
function variant_post($blogID = 1, $args = array()) {
	min_switch_to_blog($blogID);
	$id = get_the_ID();
	exclude_this_post($blogID, $id);

	$defaults = array(
		'show_via' => true,
		);

	// merge arguments with defaults && set keys as variables
	$args = array_merge($defaults, $args);
	foreach($args as $key => $val) { ${$key} = $val; }

	$external = '';
	if($blogID > 1) { $external = 'external-link'; }

	$blog_name = blog_name($blogID);
	$short_blog_name = short_blog_name($blog_name);

	?>

	<article class="variant-post">
		<a href="<?php the_permalink(); ?>">
			<div class="featured-image" style="background-image:url('<?php echo get_thumb_url(350, 350); ?>');"></div>
		</a>
		<?php if(1 == 1) { ?>
		<div class="via-bar <?php echo $short_blog_name; ?>"><?php echo $blog_name; ?></div>
		<?php } ?>
		<div class="post-info">
			<a href="<?php the_permalink(); ?>">
				<h3 class="dotdotdot <?php echo $external; ?>"><?php the_title(); ?></h3>
			</a>
			<h6 class="post-meta">
				<span class="timestamp"><?php the_timestamp(); ?></span> - <span class="author"><?php the_author_posts_link(); ?></span>
			</h6>
		</div>
	</article>

	<?php

}

function trending_post($blogID = 1, $trending_post_content = '') {

	min_switch_to_blog($blogID);
	$id = get_the_ID();
	exclude_this_post($blogID, $id);

	$external = '';
	if($blogID > 1) { $external = 'external-link'; }

	?>
	<article class="trending-post">
		<a href="<?php the_permalink(); ?>">
			<div class="featured-image" style="background-image:url('<?php echo get_thumb_url(700, 700); ?>');"></div>
		</a>
		<div class="post-info">
			<div class="col-md-7 left-story">
				<h6 class="post-meta">
					<span class="timestamp"><?php the_timestamp(); ?></span> - <span class="author"><?php the_author_posts_link(); ?></span>
					<?php if($blogID > 1) { ?> via <?php echo blog_svg($blogID); } ?>
				</h6>
				<a href="<?php the_permalink(); ?>">
					<h1 class="<?php echo $external; ?>"><?php the_title(); ?></h1>
				</a>

				<div class="post-excerpt"><?php the_excerpt(); ?></div>
			</div>
			<div class="col-md-5 right-story">
				<?php if($trending_post_content != '') { echo $trending_post_content; } ?>
			</div>
			<div style="clear:both;"></div>
		</div>
	</article>

	<?php
}


function vintage_post($blogID = 1) {
	min_switch_to_blog($blogID);
	$id = get_the_ID();
	exclude_this_post($blogID, $id);

	$external = '';
	if($blogID > 1) { $external = 'external-link'; }

	?>
	<article class="vintage-post">
		<div class="row">
			<div class="col-md-4">
				<a href="<?php the_permalink(); ?>">
					<div class="featured-image" style="background-image:url('<?php echo get_thumb_url(700, 700); ?>');"></div>
				</a>
			</div>
			<div class="col-md-8">
				<div class="post-info">
					<h6 class="post-meta">
						<span class="timestamp"><?php the_timestamp(); ?></span> - <span class="author"><?php the_author_posts_link(); ?></span>
					</h6>
					<a href="<?php the_permalink(); ?>">
						<h2 class="<?php echo $external; ?>"><?php the_title(); ?></h2>
					</a>
					<div class="post-excerpt"><?php the_excerpt(); ?></div>
					<div class="read-more"><a href="<?php the_permalink(); ?>">Read Full Story</a><?php if($blogID > 1) { ?> at <?php echo blog_svg($blogID); } ?></div>
				</div>
			</div>
		</div>

	</article>

	<?php
}

function video_post($blogID = 1, $args = array()) {
	min_switch_to_blog($blogID);
	$id = get_the_ID();
	exclude_this_post($blogID, $id);

	$defaults = array(
		'beatmersive' => false,
		);

	// merge arguments with defaults && set keys as variables
	$args = array_merge($defaults, $args);
	foreach($args as $key => $val) { ${$key} = $val; }

	$hyperlink = get_permalink();
	if($beatmersive) { $hyperlink = addhttp(get_post_meta($id, 'db_beatmersive_hyperlink', true)); }

	$external = '';
	if($blogID > 1 || $beatmersive) { $external = 'external-link'; }

	?>

	<article class="video-post">
		<a href="<?php echo $hyperlink; ?>">
			<div class="featured-image" style="background-image:url('<?php echo get_thumb_url(700, 700); ?>');">
				<div class="video-overlay">
					<span class="fa-stack">
						<i class="fa fa-circle fa-stack-2x"></i>
						<i class="fa fa-play-circle fa-stack-1x"></i>
					</span>
				</div>
			</div>
		</a>
		<div class="caption">
			<?php if(!$beatmersive) { ?>
			<h6 class="post-meta">
				<span class="timestamp"><?php the_timestamp(); ?></span> - <span class="author"><?php the_author_posts_link(); ?></span>
				<?php if($blogID > 1) { ?> via <?php echo blog_svg($blogID, 0); } ?>
			</h6>
			<?php } ?>
			<a href="<?php echo $hyperlink; ?>"><h2 class="<?php echo $external; ?>"><?php the_title(); ?></h2></a>
		</div>
	</article>
	<?php
}

function exclusive_post($blogID = 1) {
	min_switch_to_blog($blogID);
	$id = get_the_ID();
	exclude_this_post($blogID, $id);

	$external = '';
	if($blogID > 1) { $external = 'external-link'; }
	?>

	<article class="exclusive-post">
		<a href="<?php the_permalink(); ?>">
			<div class="featured-image" style="background-image:url('<?php echo get_thumb_url(900, 900); ?>');">
				<div class="spotlight-bg"></div>


			</div>
		</a>
		<div class="caption">
			<h1 class="<?php echo $external; ?>"><?php the_title(); ?></h1>
			<h2>by <span class="author"><?php the_author_posts_link(); if($blogID > 1) { ?> via <?php echo blog_svg($blogID); } ?></h2>
		</div>
	</article>
	<?php
}


function list_post($blogID = 1) {
	min_switch_to_blog($blogID);
	$id = get_the_ID();
	exclude_this_post($blogID, $id);

	$external = '';
	if($blogID > 1) { $external = 'external-link'; }

	?>
	<a href="<?php the_permalink(); ?>" class="dotdotdot <?php echo $external; ?>"><?php the_title(); ?></a>

	<?php
}

function fresh_new_track($blogID, $rank) {

	$id = get_the_ID();
	exclude_this_post($blogID, $id);

	$artist = get_post_meta($id, 'track_artist_name', true);
	$track = get_post_meta($id, 'track_name', true);
	$remixer = get_post_meta($id, 'track_remixer', true);
	$track_url = get_post_meta($id, 'track_url', true);

	if($remixer != '') {
		$track .= ' (' . $remixer . ' Remix)';
	}

	?>
	<div class="track track-<?php echo $rank; ?>">
		<div class="track-meta">
			<h4><span><?php echo $rank; ?></span></h4>
			<div class="album-art" data-play="true" data-track="<?php echo $track_url; ?>">
				<img src="<?php echo get_thumb_url(75, 75); ?>" title="<?php echo $artist; ?> - <?php echo $track; ?>"/>
				<div class="video-overlay">
					<span class="fa-stack">
						<i class="fa fa-circle fa-stack-2x"></i>
						<i class="fa fa-play-circle fa-stack-1x"></i>
					</span>
				</div>
			</div>
		</div>		
		<a href="<?php the_permalink(); ?>" target="_blank">
			<div class="track-info-wrapper">
				<div class="track-info">
					<div class="song"><?php echo $track; ?></div>
					<div class="artist"><?php echo $artist; ?></div>
				</div>
			</div>
		</a>
	</div>
	<?php
}

// 
// full post layout, image spans full width with text overlay

function spotlight_post($blogID = 1, $args = array()) {
	min_switch_to_blog($blogID);
	$id = get_the_ID();
	exclude_this_post($blogID, $id);

	$defaults = array(
		'center' => false,
		);

	// merge arguments with defaults && set keys as variables
	$args = array_merge($defaults, $args);
	foreach($args as $key => $val) { ${$key} = $val; }

	$new_title = get_post_meta($id, 'db_featured_title', true);
	$subtitle = get_post_meta($id, 'db_subtitle', true);

	$title = get_the_title();
	if(!empty($new_title)) { $title = $new_title; }

	$w = 450; $h = 450;
	$styles = '';

	if($center) { $styles = ' main-spotlight'; $w = 800; $h = 800; }

	$external = '';
	if($blogID > 1) { $external = 'external-link'; }

	?>

	<article class="spotlight-post<?php echo $styles; ?>">
		<a href="<?php the_permalink(); ?>">
			<div class="featured-image" style="background-image:url('<?php echo get_thumb_url($w, $h); ?>');">
				<div class="spotlight-bg"></div>
			</div>
		</a>
		<div class="caption">
			<?php if($blogID > 1) { ?>
			<h4>via <?php echo blog_svg($blogID); ?></h4>
			<?php } ?>

			<h1 class="<?php echo $external; ?>"><?php echo $title; ?></h1>

			<?php if(isset($subtitle) && $center == 1) { ?>
			<h2><?php echo $subtitle; ?></h2>
			<?php } ?>
		</div>
	</article>

	<?php

}



function get_thumb_url($width = 300, $height = 300) {
	$thumb_id = get_post_thumbnail_id();
	$thumb_url = wp_get_attachment_image_src($thumb_id, array($width, $height), true);
	return $thumb_url[0];
}

function blog_svg($blogID = 1, $link = 1) {

	$url = get_site_url($blogID);
	$svg = 'svg';

	if(strpos($url, 'trc') !== false) { $svg .= '-trc'; }
	else if(strpos($url, 'raver') !== false) { $svg .= '-rr'; }

	echo '<div class="via-wrapper">';
	if($link > 0) { echo '<a href="' . $url  . '">'; }
	echo '<span class="svg ' . $svg .'"></span>';
	if($link > 0) { echo '</a>'; }
	echo '</div>';

}

function blog_name($blogID = 1) {
	$blog_details = get_blog_details($blogID);
	return $blog_details->blogname;
}

function short_blog_name($blog_name) {
	$words = explode(' ', $blog_name);
	$acronym = '';

	foreach($words as $w) {
		$acronym .= $w[0];
	}

	return strtolower($acronym);
}



function exclude_this_post($blogID, $post_id) {
	global $exclude_posts;

	if($blogID == 0 || $post_id == 0) { return; }

	if(!isset($exclude_posts[$blogID])) {
		$exclude_posts[$blogID] = array();
	}

	$exclude_posts[$blogID] = array_merge($exclude_posts[$blogID], array($post_id));
}

function min_switch_to_blog($blogID) {
	if($blogID == $GLOBALS['blog_id']) { return true; }

	//switch_to_blog($blogID);
	//return true;

	global $wpdb;
	$wpdb->set_blog_id($blogID);
	$GLOBALS['blog_id'] = $blogID;
	wp_cache_init();

	return true;
}

function reset_blog() {
	switch_to_blog(1);
	unset($GLOBALS['_wp_switched_stack']);
	$GLOBALS['switched'] = false; 
}

?>