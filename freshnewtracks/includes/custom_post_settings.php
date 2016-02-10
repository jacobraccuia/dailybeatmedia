<?php

// add custom post type
add_action('init', 'db_custom_post_types', 1);	
function db_custom_post_types() {


	$labels = array(
		'name' => 'Tracks',
		'singular_name' => 'Tracks',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Track',
		'edit_item' => 'Edit Track',
		'new_item' => 'New Track',
		'all_items' => 'All Tracks',
		'view_item' => 'View Track',
		'search_items' => 'Search Tracks',
		'not_found' =>  'No tracks found',
		'not_found_in_trash' => 'No tracks found in Trash', 
		'parent_item_colon' => '',
		'menu_name' => 'Tracks'
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
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => true,
		'menu_position' => null,
		'supports' => array('title', 'thumbnail', 'editor', 'excerpt', 'comments')
		); 

	register_post_type('tracks', $args);
}


/* meta box functions */

// add meta box
add_action('admin_init', 'db_admin_init');
function db_admin_init(){
	add_meta_box('db_tracks', 'Track Info', 'db_tracks', 'tracks', 'normal', 'high');
	add_meta_box('db_featured_artist', 'Featured Artist', 'db_featured_artist', 'tracks', 'side', 'default');
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


function db_track_columns() {
	return array(
		'track_url',
		'track_artist_name',
		'track_name',
		'track_remixer',
		);
}

function db_tracks($post) {

	$post_meta = get_post_custom();

	// assign all columns to variable
	$db_tracks = db_track_columns();

	// in case value isn't set in database, set required variables to ''
	foreach($db_tracks as $column) { ${$column} = ''; }

	// assign all fields
	foreach($post_meta as $key => $meta) {
		if(array_key_exists($key, array_flip($db_tracks))) { // if we want this key
			if(isset($key) && !empty($key)) { ${$key} = trim($meta[0]); } else ${$key} = '';
		}
	}

	?>
	<style>
		input { display:block; }
	</style>
	<div class="field">
		<label for="track_artist_name">Track Artist:</label>
		<input autocomplete="off" name="track_artist_name" type="text" value="<?php echo $track_artist_name; ?>" />
		<label for="track_name">Track Name:</label>
		<input autocomplete="off" name="track_name" type="text" value="<?php echo $track_name; ?>" />
		<label for="track_remixer">Track Remixer:</label>
		<input autocomplete="off" name="track_remixer" type="text" value="<?php echo $track_remixer; ?>" />
		<label for="track_url">Track URL:</label>
		<input autocomplete="off" name="track_url" type="text" value="<?php echo $track_url; ?>" />
	</div>
	<?php 
}


// save field
add_action('save_post', 'db_track_save_post');
function db_track_save_post() {
	global $post, $wpdb;

	if(isset($post->ID)) {
		$id = $post->ID;

		foreach($_POST as $key => $val) {
			if(substr($key, 0, 3) == 'db_' || substr($key, 0, 6) == 'track_') {
				update_post_meta($id, $key, trim($val));
			}
		}

		// update post title if necessary
		if($_POST['track_name'] != '' && $_POST['track_artist_name'] != '') {	
			$remixer = '';
			if($_POST['track_remixer'] != '') { $remixer = ' (' . $_POST['track_remixer'] . ' Remix)'; }

			$title = $_POST['track_artist_name'] . ' - ' . $_POST['track_name'] . $remixer; 
			$where = array('ID' => $id);
			$wpdb->update($wpdb->posts, array('post_title' => trim($title)), $where);
		}

	}
}




/* end meta box functions */
add_action('admin_enqueue_scripts', 'db_load_scripts');
function db_load_scripts($hook) {

	if($hook == 'post.php' || $hook == 'post-new.php') {
		global $post;
		if($post->post_type == 'tracks') {
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