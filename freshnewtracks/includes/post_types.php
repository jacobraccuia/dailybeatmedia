<?php
// all post views

global $exclude_posts;
$exclude_posts = array();

for($i = 1; $i <= 4; $i++) {
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

	?>

	<article class="classic-post">
		<a href="<?php the_permalink(); ?>">
			<div class="featured-image" style="background-image:url('<?php echo get_thumb_url(350, 350); ?>');"></div>
		</a>
		<div class="post-info">
			<a href="<?php the_permalink(); ?>">
				<h3 class="<?php echo $external; ?>"><?php the_title(); ?></h3>
			</a>
			<h6 class="post-meta">
				<span class="timestamp"><?php the_timestamp(); ?></span> - <span class="author"><?php the_author_posts_link(); ?></span>
				<?php if($blogID > 1 && $show_via === true) { ?> via <?php echo blog_svg($blogID); } ?>
			</h6>
		</div>
	</article>

	<?php

}

function trending_post($blogID = 1) {
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

			<h6 class="post-meta">
				<span class="timestamp"><?php the_timestamp(); ?></span> - <span class="author"><?php the_author_posts_link(); ?></span>
				<?php if($blogID > 1) { ?> via <?php echo blog_svg($blogID); } ?>
			</h6>
			<a href="<?php the_permalink(); ?>">
				<h1 class="<?php echo $external; ?>"><?php the_title(); ?></h1>
			</a>

			<div class="post-excerpt"><?php the_excerpt(); ?></div>
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
				<div class="play-overlay">
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

function full_story_post($blogID = 1) {
	min_switch_to_blog($blogID);
	$id = get_the_ID();
	exclude_this_post($blogID, $id);

	$external = '';
	if($blogID > 1) { $external = 'external-link'; }

	?>

	<article class="full-story-post">
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

	<a href="<?php the_permalink(); ?>" class="<?php echo $external; ?>"><?php the_title(); ?></a>

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