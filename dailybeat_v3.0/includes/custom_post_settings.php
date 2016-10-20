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
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => true,
		'menu_position' => null,
		'supports' => array('title', 'thumbnail', 'excerpt', 'comments')
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
	add_meta_box('db_exclusive', 'Exclusive Editor', 'db_meta_exclusive', 'exclusive', 'normal', 'high');
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


function db_exclusive_columns() {
	return array(
		'db_ex_subtitle',
		'db_ex_edition',
		'db_ex_theme_color',
		);
}

function db_meta_exclusive($post) {
	$post_id = $post->ID;
	$post_meta = get_post_custom();

	// assign all columns to variable
	$db_exclusive_columns = db_exclusive_columns();

	// in case value isn't set in database, set required variables to ''
	foreach($db_exclusive_columns as $column) { ${$column} = ''; }

	// assign all fields
	foreach($post_meta as $key => $meta) {
		if(array_key_exists($key, array_flip($db_exclusive_columns))) { // if we want this key
			if(isset($key) && !empty($key)) { ${$key} = trim($meta[0]); } else ${$key} = '';
		}

		// special handling for paragraph sections
		if(substr($key, 0, 5) == 'db_ex') {
			${$key} = trim($meta[0]);
		}

	}

	$i = 1;
	// find out how many sections there are
	$sections_to_show = 1; // always show at least one
	while($i < 11) {
		if(isset($post_meta['db_ex_section' . $i]) && $post_meta['db_ex_section' . $i][0] != '') {
			$sections_to_show++;
		}

		$i++;
	}
	
	?>

	<style>
		.ex_section { width:98%; margin:15px auto; }
		h3.ex_title { height:25px; font-weight:bold; padding:5px 0px!important; font-size:16px; }
		.ex_title em { font-size:12px; color:#666; }
		div.field { margin-top:10px; overflow:hidden; }
		h2#new_section { display:inline-block; font-size:12px; padding:3px 12px; font-weight:bold; text-transform:uppercase; border:1px solid #999; border-radius:2px; background-color:#B5EAA6; margin-left:5px; color:black; cursor:pointer; }
		.ex_section hr { width:80%; margin:25px auto; }
		.ex_section label { display:block; }

	</style>

	<div class="ex_section">
		<div class="field">
			<label for="db_ex_subtitle">Subtitle</label>
			<input style="padding:5px; width:450px;" autocomplete="off" class="" name="db_ex_subtitle" type="text" value="<?php echo $db_ex_subtitle; ?>" />
		</div>
		<div class="field">
			<label for="db_ex_edition">Edition #</label>
			<input style="padding:5px; width:150px;" autocomplete="off" class="" name="db_ex_edition" type="text" value="<?php echo $db_ex_edition; ?>" />
		</div>
		<div class="field">
			<label for="db_ex_theme_color">Theme Hex Color</label>
			<input style="padding:5px; width:450px;" autocomplete="off" class="" name="db_ex_theme_color" type="text" value="<?php echo $db_ex_theme_color; ?>" />
		</div>
	</div>
	<?php
	$i = 1;
	while($i < $sections_to_show) {

		// set variables
		$db_ex_section = isset(${'db_ex_section' . $i}) ? ${'db_ex_section' . $i} : '';
		$db_ex_bg_color = isset(${'db_ex_bg_color' . $i}) ? ${'db_ex_bg_color' . $i} : '';

		create_section(0, $i, $db_ex_section, $db_ex_bg_color);
		$i++;
	}	
	?>
	<h2 id="new_section" data-section="<?php echo $i; ?>" db-post_id="<?php echo $post_id; ?>">Add New Section</h2>

	<?php
}

function create_section($post_id = 0, $counter, $db_ex_section = '', $db_ex_bg_color = '') {

	if($post_id > 0) {
		$post_meta = get_post_custom($post_id);
		$db_ex_section = isset($post_meta['db_ex_section' . $i]) ? $post_meta['db_ex_section' . $i] : '';
		$db_ex_bg_color = isset($post_meta['db_ex_bg_color' . $i]) ? $post_meta['db_ex_bg_color' . $i] : '';
	}

	?>
	<div class="ex_section ex_section<?php echo $counter; ?>">

		<h3 class="ex_title">Section <?php echo $counter; ?></h3>
		<div class="ex_container">
			<?php echo wp_editor($db_ex_section, 'db_ex_section' . $counter, array('textarea_rows' => 14)); ?>

			<div class="field">
				<label for="db_beatmersive_hyperlink">Background Color for Section <?php echo $counter; ?></label>
				<input style="padding:5px; width:100px;" autocomplete="off" name="db_ex_bg_color<?php echo $counter; ?>" type="text" value="<?php echo $db_ex_bg_color; ?>" />
			</div>
		</div>

	</div>
	<?php
}

add_action('wp_ajax_load_section', 'ajax_load_section');
add_action('wp_ajax_nopriv_load_section', 'ajax_load_section');
function ajax_load_section() {

	$nonce = isset($_POST['postCommentNonce']) ? $_POST['postCommentNonce'] : '';
	if(!wp_verify_nonce($nonce, 'myajax-post-comment-nonce')) { die('Busted!'); }

	$counter = isset($_POST['counter']) ? $_POST['counter'] : '';
	$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';
	
	ob_start();
	create_section($post_id, $counter);
	$result = ob_get_contents();
	ob_end_clean();

	echo json_encode(array('results' => $result));

	die;
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


/* end meta box functions */

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