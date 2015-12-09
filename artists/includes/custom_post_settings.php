<?php


// add custom post type
add_action('init', 'db_custom_post_types', 1);	
function db_custom_post_types() {


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


/* meta box functions */

// add meta box
add_action('admin_init', 'db_admin_init');
function db_admin_init(){
	add_meta_box('db_artists', 'Artist Info', 'db_artist', 'artists', 'normal', 'high');
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
		textarea { width:600px; display:block; height:100px; }
	</style>

	<div class="field">
		<label for="artist_name">Artist:</label>
		<input style="padding:5px; width:700px;" autocomplete="off" id="artist_name" name="artist_name" type="text" value="<?php echo $artist_name; ?>" />
		<br/>
		<label for="artist_id">Artist ID:<em> be careful editing me</em></label>
		<input style="padding:5px; width:100px;" autocomplete="off" id="artist_id" name="artist_id" type="text" value="<?php echo $artist_id; ?>" />
		<br/>
		<label>Artist Tour Dates:</label>
		<textarea id="artist_tour_dates" name="artist_tour_dates"><?php echo $artist_tour_dates; ?></textarea>
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
	global $post, $wpdb;

	if(isset($post->ID)) {
		$id = $post->ID;

		foreach($_POST as $key => $val) {
			if(substr($key, 0, 3) == 'db_' || substr($key, 0, 7) == 'artist_') {
				update_post_meta($id, $key, trim($val));
			}
		}

		// update post title if necessary
		if($_POST['artist_name'] != "") {	
			$title = $_POST['artist_name']; 
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
		if($post->post_type == 'artists') {
			wp_enqueue_script('jquery-artists', THEME_DIR . '/js/admin/jquery-artists.js', array('jquery'));
		}
	}
}



?>