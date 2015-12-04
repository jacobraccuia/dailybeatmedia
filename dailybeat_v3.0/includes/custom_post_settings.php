<?php

/* featured premiere */



// add custom post type
add_action('init', 'db_custom_post_types', 1);	
function db_custom_post_types() {


	// display on artists site only
	if(1 == get_current_blog_id()) {

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

	// display on artists site only
	if(get_blog_by_name('artists') == get_current_blog_id()) {
		$labels = array(
			'name' => 'Artists',
			'singular_name' => 'Artists',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Artist',
			'edit_item' => 'Edit Artist',
			'new_item' => 'New Artist',
			'all_items' => 'All Artists',
			'view_item' => 'View Artist',
			'search_items' => 'Search Artists',
			'not_found' =>  'No artists found',
			'not_found_in_trash' => 'No artists found in Trash', 
			'parent_item_colon' => '',
			'menu_name' => 'Artists'
			);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true,
			'query_var' => true,
			'menu_icon' => 'dashicons-format-audio',
			'rewrite' => false,
			'capability_type' => 'page',
			'has_archive' => true, 
			'hierarchical' => true,
			'menu_position' => null,
			'supports' => array('title', 'thumbnail', 'excerpt', 'page-attributes', 'comments')
			); 

		register_post_type('artists', $args);
	}
}


/* meta box functions */

// add meta box
add_action('admin_init', 'db_admin_init');
function db_admin_init(){
	add_meta_box('db_meta', 'Extra Information', 'db_meta', 'post', 'normal', 'high');
	add_meta_box('db_meta', 'Extra Information', 'db_meta', 'premiere', 'normal', 'high');
	add_meta_box('db_meta', 'Extra Information', 'db_meta_beatmersive', 'beatmersive', 'normal', 'high');
	add_meta_box('db_splash_meta', 'Splash', 'db_splash_meta', 'page', 'normal', 'high');
	add_meta_box('db_artists', 'Artist Info', 'db_artist', 'artists', 'normal', 'high');
}


function db_splash_meta($post) {
	$db_background_color = get_post_meta(get_the_ID(), 'db_background_color', true);
	$db_splash_link = get_post_meta(get_the_ID(), 'db_splash_link', true);
	?>
	<div class="field">
		<label for="soundcloud">Background Color (include #):</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_background_color" type="text" value="<?php echo $db_background_color; ?>" />
	</div>
	<div class="field">
		<label for="soundcloud">Splash href link:</label>
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
	foreach($post_meta as $key => $meta) {
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
		<label for="soundcloud">SoundCloud Link:</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_soundcloud" type="text" value="<?php echo $db_soundcloud; ?>" />
	</div>
	<div class="field">
		<label for="soundcloud">SoundCloud HEX Color (# is required):</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_soundcloud_color" type="text" value="<?php echo $db_soundcloud_color; ?>" />
	</div>
	<div class="field">
		<label for="soundcloud">Featured Title (optional.  this will replace the current title for a shorter title):</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_featured_title" type="text" value="<?php echo $db_featured_title; ?>" />
	</div>
	<div class="field">
		<label for="soundcloud">SubTitle for Featured Posts. (optional.  this is a subtitle...):</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_subtitle" type="text" value="<?php echo $db_subtitle; ?>" />
	</div>
	<div class="field">
		<label for="soundcloud">Premiere Title (optional):</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_premiere_title" type="text" value="<?php echo $db_premiere_title; ?>" />
	</div>
	<div class="field">
		<div style="float:left; margin-top:8px;">
			<label for="soundcloud">Video Post:</label>
			<input autocomplete="off" name="db_is_video" type="hidden" value="0">
			<input autocomplete="off" name="db_is_video" type="checkbox" value="1" <?php if($db_is_video == 1) echo 'checked="checked"'; ?>>
		</div>
		<div>
			<label for="soundcloud">Video URL (features the video):</label>
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


function db_artist_columns() {
	return array(
		'artist_name',
		'artist_id',
		'artist_tour_dates'
		);
}

function db_artist($post) {

	$post_meta = get_post_custom();

	// assign all columns to variable
	$db_artists = db_artist_columns();

	// in case value isn't set in database, set required variables to ''
	foreach($db_artists as $column) { ${$column} = ''; }

	// assign all fields
	foreach($post_meta as $key => $meta) {
		if(array_key_exists($key, array_flip($db_artists))) { // if we want this key
			if(isset($key) && !empty($key)) { ${$key} = trim($meta[0]); } else ${$key} = '';
		}
	}

	?>

	<style>
		pre { background:#EEE; padding:5px 15px; margin:0px; max-height:1000px; overflow-y:scroll; }
		.string { color:green; }
		.number { color:dark orange; }
		.boolean { color:red; }
		.null { color:blue; }
		.key { color:purple; font-weight:bold; }
		.songkick-button.update { background:#CCFFC4; }
		.songkick-button { width:150px; height:27px; border:1px solid #999; border-radius:2px; line-height:27px; padding:2px 10px; text-align:center; display:inline; font-size:13px; background:#D1FFFC; margin-right:5px; cursor:pointer; font-weight:bold; }
		.songkick-button:hover { background:#C6C6FF; }
		#songkick_results { margin:15px 0px; width:90%; }
		#songkick_results div { font-weight:bold; color:green; margin-bottom:10px; }
	</style>

	<div class="field">
		<label for="artist_name">Artist:</label>
		<input style="padding:5px; width:700px;" autocomplete="off" id="artist_name" name="artist_name" type="text" value="<?php echo $artist_name; ?>" />
	</div>
	<div class="field">
		<label for="artist_id">Artist ID: <em>be careful editing me</em></label>
		<input style="padding:5px; width:100px;" autocomplete="off" id="artist_id" name="artist_id" type="text" value="<?php echo $artist_id; ?>" />
	</div>
	<div class="field">
		<input type="hidden" id="artist_tour_dates" name="artist_tour_dates" value="<?php echo $artist_tour_dates; ?>" />
	</div>
	<br/>
	<div class="songkick-button update" data-call='update'>Update Tour Dates</div><em>artist id required</em><br/>
	<div class="songkick-button" data-call='search'>Search for Artist ID</div><em>artist name required</em>
	<div id="songkick_results"></div>

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
		if($post->post_type == 'artists') {
			wp_enqueue_script('jquery-artists', THEME_DIR . '/js/admin/jquery-artists.js', array('jquery'));
		}
	}
}


/*

require_once('songkick/SongkickAPIExchange.php'); 

$settings = array('api_key' => '4sT0rY7JO9H5KnnE');
$url = 'http://api.songkick.com/api/3.0/artists/' . $artist_id . '/calendar.json';

$songkick = new SongkickAPIExchange($settings);
$results = $songkick->buildURL($url)->performRequest();
*/




?>