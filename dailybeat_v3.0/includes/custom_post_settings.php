<?php

/* featured premiere */



// add custom post type
add_action('init', 'db_custom_post_types', 1);	
function db_custom_post_types() {

	$labels = array(
		'name' => 'Featured Premieres',
		'singular_name' => 'Featured Premieres',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Featured Premieres',
		'edit_item' => 'Edit Featured Premieres',
		'new_item' => 'New Featured Premieres',
		'all_items' => 'All Premieres',
		'view_item' => 'View Premiere',
		'search_items' => 'Search Premieres',
		'not_found' =>  'No featured premieres found',
		'not_found_in_trash' => 'No featured premieres found in Trash', 
		'parent_item_colon' => '',
		'menu_name' => 'Featured Premieres'
		);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true,
		'query_var' => true,
		'taxonomies' => array('category'),
		'menu_icon' => 'dashicons-format-audio',
		'rewrite' => array('slug' => 'dbfirst'),
		'capability_type' => 'page',
		'has_archive' => true, 
		'hierarchical' => true,
		'menu_position' => null,
		'supports' => array('title', 'thumbnail', 'editor', 'excerpt', 'page-attributes', 'comments')
		); 

	register_post_type('premiere', $args);


	$labels = array(
		'name' => 'Exclusive',
		'singular_name' => 'Exclusive',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Exclusive',
		'edit_item' => 'Edit Exclusive',
		'new_item' => 'New Exclusive',
		'all_items' => 'All Exclusives',
		'view_item' => 'View Exclusive',
		'search_items' => 'Search Exclusives',
		'not_found' =>  'No exclusives found',
		'not_found_in_trash' => 'No exclusives found in Trash', 
		'parent_item_colon' => '',
		'menu_name' => 'Exclusive'
		);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true,
		'query_var' => true,
		'taxonomies' => array('category'),
		'menu_icon' => 'dashicons-format-chat',
		'rewrite' => array('slug' => 'exclusive'),
		'capability_type' => 'page',
		'has_archive' => true, 
		'hierarchical' => true,
		'menu_position' => null,
		'supports' => array('title', 'thumbnail', 'editor', 'excerpt', 'page-attributes', 'comments')
		); 

	register_post_type('exclusive', $args);

	$labels = array(
		'name' => 'Beatmersive',
		'singular_name' => 'Beatmersive',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Video',
		'edit_item' => 'Edit Video Post',
		'new_item' => 'New Video Post',
		'all_items' => 'All Beatmersive Videos',
		'view_item' => 'View Beatmersive Post',
		'search_items' => 'Search Videos',
		'not_found' =>  'No videos found',
		'not_found_in_trash' => 'No featured videos found in Trash', 
		'parent_item_colon' => '',
		'menu_name' => 'Beatmersive'
		);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true,
		'query_var' => true,
		'taxonomies' => array('category'),
		'menu_icon' => 'dashicons-video-alt',
		'rewrite' => array('slug' => 'beatmersive'),
		'capability_type' => 'page',
		'has_archive' => true, 
		'hierarchical' => true,
		'menu_position' => null,
		'supports' => array('title', 'thumbnail', 'editor', 'excerpt', 'page-attributes', 'comments')
		); 

	register_post_type('beatmersive', $args);
}


/* meta box functions */

// add meta box
add_action('admin_init', 'db_admin_init');
function db_admin_init(){
	add_meta_box('db_meta', 'Extra Information', 'db_meta', 'post', 'normal', 'high');
	add_meta_box('db_meta', 'Extra Information', 'db_meta', 'premiere', 'normal', 'high');
	add_meta_box('db_meta', 'Extra Information', 'db_meta_beatmersive', 'beatmersive', 'normal', 'high');
	add_meta_box('db_splash_meta', 'Splash', 'db_splash_meta', 'page', 'normal', 'high');
	add_meta_box('db_featured_artist', 'Featured Artist', 'db_featured_artist', 'post', 'side', 'core');
}


function db_featured_artist($post) {
	$db_featured_artist = get_post_meta(get_the_ID(), 'db_featured_artist', true);
	$db_featured_artist_id = get_post_meta(get_the_ID(), 'db_featured_artist_id', true);
	?>
	

	<label>Featured Artist:</label>
	<input style="padding:5px; width:100%; margin:5px auto;" autocomplete="off" id="db_feat_artist" name="db_featured_artist" type="text" value="<?php echo $db_featured_artist; ?>" />
	<label>Featured ID: <em>find by searching above</em></label>
	<input style="padding:5px; width:100%; margin:5px auto;" autocomplete="off" id="db_featured_artist_id" name="db_featured_artist_id" type="text" value="<?php echo $db_featured_artist_id; ?>" />

	<?php

	$blogID = get_blog_by_name('artists');
	switch_to_blog($blogID);

	$args = array(
		'posts_per_page' => -1,
		'post_type' => 'artists',
		'post_status' => 'publish'
		);

	$artists = get_posts($args);

	echo '<ul id="artist_database" style="display:none;">';

	foreach($artists as $artist) {
		$artist_name = get_post_meta($artist->ID, 'artist_name', true);

		echo '<li data-name="' . $artist_name . '" data-id="' . $artist->ID . '"></li>';
	}

	restore_current_blog();
//	reset_blog();

	echo '</ul>';
}



function db_splash_meta($post) {
	$db_background_color = get_post_meta(get_the_ID(), 'db_background_color', true);
	$db_splash_link = get_post_meta(get_the_ID(), 'db_splash_link', true);
	?>
	<style>
	.ui-autocomplete .ui-state-focus { width:92%; }
	</style>
	<div class="field">
		<label>Background Color (include #):</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_background_color" type="text" value="<?php echo $db_background_color; ?>" />
	</div>
	<div class="field">
		<label>Splash href link:</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_splash_link" type="text" value="<?php echo $db_splash_link; ?>" />
	</div>
	<?php 
}


function db_columns() {
	return array(
		'db_soundcloud',
		'db_soundcloud_color',
		'db_featured_title',
		'db_premiere_title',
		'db_ad_size',
		'db_subtitle',
		'db_blog_id',
		'db_views',
		'db_is_video',
		'db_video_url',
		'db_beatmersive_hyperlink'
		);
}

function db_meta_beatmersive($post) {

	$post_meta = get_post_custom();

	// assign all columns to variable
	$db_columns = db_columns();

	// in case value isn't set in database, set required variables to ''
	foreach($db_columns as $column) { ${$column} = ''; }

	// assign all fields
	foreach($post_meta as $key => $meta) {
		if(array_key_exists($key, array_flip($db_columns))) { // if we want this key
			if(isset($key) && !empty($key)) { ${$key} = trim($meta[0]); } else ${$key} = '';
		}
	}
	?>


	<div class="field">
		<label for="db_beatmersive_hyperlink">Hyperlink for video.</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_beatmersive_hyperlink" type="text" value="<?php echo $db_beatmersive_hyperlink; ?>" />
	</div>

	<?php
}

// display form field
function db_meta($post) {

	$post_meta = get_post_custom();

	// assign all columns to variable
	$db_columns = db_columns();

	// in case value isn't set in database, set required variables to ''
	foreach($db_columns as $column) { ${$column} = ''; }

	// assign all fields
	foreach((array) $post_meta as $key => $meta) {
		if(array_key_exists($key, array_flip($db_columns))) { // if we want this key
			if(isset($key) && !empty($key)) { ${$key} = trim($meta[0]); } else ${$key} = '';
		}
	}


	?>
	<style>
		#category-adder h4 { display:none; }
		#postexcerpt { display:none; }
		.field { margin-bottom:5px; }
		.role-administrator #category-adder h4, .role-administrator #postexcerpt { display:block; } 

	</style>

	Page Views: <?php echo $db_views; ?>
	<div class="field">
		<label>SoundCloud Link:</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_soundcloud" type="text" value="<?php echo $db_soundcloud; ?>" />
	</div>
	<div class="field">
		<label>SoundCloud HEX Color (# is required):</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_soundcloud_color" type="text" value="<?php echo $db_soundcloud_color; ?>" />
	</div>
	<div class="field">
		<label>Featured Title (optional.  this will replace the current title for a shorter title):</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_featured_title" type="text" value="<?php echo $db_featured_title; ?>" />
	</div>
	<div class="field">
		<label>SubTitle for Featured Posts. (optional.  this is a subtitle...):</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_subtitle" type="text" value="<?php echo $db_subtitle; ?>" />
	</div>
	<div class="field">
		<label>Premiere Title (optional):</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_premiere_title" type="text" value="<?php echo $db_premiere_title; ?>" />
	</div>
	<div class="field">
		<div style="float:left; margin-top:8px;">
			<label>Video Post:</label>
			<input autocomplete="off" name="db_is_video" type="hidden" value="0">
			<input autocomplete="off" name="db_is_video" type="checkbox" value="1" <?php if($db_is_video == 1) echo 'checked="checked"'; ?>>
		</div>
		<div>
			<label>Video URL (features the video):</label>
			<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_video_url" type="text" value="<?php echo $db_video_url; ?>" />
		</div>
	</div>
	<div class="field">
		<label for="ads">Ad Size (optional):</label>
		<select name="db_ad_size" style="padding:5px; width:700px;">
			<option value="300x250" <?php if($db_ad_size == "300x250") echo "selected"; ?>>300 x 250 (square)</option>
			<option value="300x600" <?php if($db_ad_size == "300x600") echo "selected"; ?>>300 x 600 (rectangle)</option>
		</select>
	</div>
	<div> views : <?php echo $db_views; ?></div>
	<?php 
}



// save field
add_action('save_post', 'db_save_post');
function db_save_post() {
	global $post;

	if(isset($post->ID)) {
		$id = $post->ID;

		foreach($_POST as $key => $val) {
			if(substr($key, 0, 3) == 'db_' || substr($key, 0, 7) == 'artist_') {
				update_post_meta($id, $key, trim($val));
			}
		}

	}
}

/* end meta box functions */


add_action('admin_enqueue_scripts', 'db_load_scripts');
function db_load_scripts($hook) {

	if($hook == 'post.php' || $hook == 'post-new.php') {
		global $post;
		if($post->post_type == 'post') {
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script('jquery-artists', THEME_DIR . '/js/admin/jquery-posts.js', array('jquery'));
		}
	}
}

add_action('admin_print_styles', 'db_load_styles');
function db_load_styles($hook) {
	if($hook == 'post.php' || $hook == 'post-new.php') {
		global $post;
		if($post->post_type == 'post') {
			//
		}
	}
}


?>