<?php

require_once('includes/wp_bootstrap_navwalker.php');
require_once('includes/post_queries.php');
//require_once('now-feed/now-feed.php');
require_once('admin/admin_functions.php');

// remove meta_head generator
remove_action('wp_head', 'wp_generator');

// add all appropriate styles and scripts
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');
function my_enqueue_scripts() {

	wp_enqueue_style('google-fonts', 'http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,900,700,600,300,400italic');
	wp_enqueue_style('google-font2', 'http://fonts.googleapis.com/css?family=Roboto:400,700');
	wp_enqueue_style('google-font3', 'http://fonts.googleapis.com/css?family=Lato:400,900,700,400');
	
	wp_enqueue_script('bootstrap_js', THEME_DIR . '/bootstrap/bootstrap.min.js', array('jquery'));
	wp_enqueue_style('bootstrap_css', THEME_DIR . '/bootstrap/bootstrap.min.css');
	wp_enqueue_style('bootstrap_css_mods', THEME_DIR . '/bootstrap/bootstrap_style_modifications.css');
	
	// soundcloud shit
/*	wp_enqueue_script('waveform_js', THEME_DIR . '/soundcloud/waveform.js', array('jquery'));
	wp_enqueue_script('soundcloud_api_js', THEME_DIR . '/soundcloud/soundcloud.player.api.js', array('jquery'));
	wp_enqueue_script('soundcloud_player_js', THEME_DIR . '/soundcloud/sc-player.js', array('jquery'));
	wp_enqueue_style('soundcloud_css', THEME_DIR . '/soundcloud/sc-player.css');
*/
	wp_enqueue_script('twitter_web_intents', '//platform.twitter.com/widgets.js');
	wp_enqueue_script('featherlist_js', '//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.js');
	wp_enqueue_script('modernizr_js', THEME_DIR . '/js/modernizr.js');
	wp_enqueue_script('history_js_', THEME_DIR . '/js/html5/jquery.history.js');
	wp_enqueue_script('jquery_stickem', THEME_DIR . '/js/jquery.stickem.js');
	wp_enqueue_script('jquery_push', THEME_DIR . '/js/jquery.pushmenu.js');

	wp_enqueue_script('ajax_page_load', THEME_DIR . '/js/ajax_page_load.js');	
	wp_enqueue_script('dailybeat_js', THEME_DIR . '/js/scripts.js', array('jquery'));
	wp_localize_script('dailybeat_js', 'DB_Ajax_Call', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('dailybeat_js', 'DB_Ajax_Call', array('ajaxurl' => admin_url('admin-ajax.php'), 'postCommentNonce' => wp_create_nonce('myajax-post-comment-nonce'),));


	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');	
	wp_enqueue_style('featherlight_css', '//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.css');
	wp_enqueue_style('jacob_css', THEME_DIR . '/style.css');

		wp_enqueue_script('prefix-free', 'https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js');


}

// register post thumbnails
add_theme_support('post-thumbnails');

// register menus
register_nav_menus(array(
	'primary_menu' => 'Primary Menu',
	'sticky_menu' => 'Sticky Menu',
	'featured_menu' => 'Featured Menu',
	'footer_menu' => 'Footer Menu',
	));

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

// add roles to body class
add_filter('admin_body_class', 'wpa66834_role_admin_body_class');
function wpa66834_role_admin_body_class($classes) {
	global $current_user;
	foreach($current_user->roles as $role)
		$classes .= ' role-' . $role;
	return trim($classes);
}

/* meta box functions */

// add meta box
add_action('admin_init', 'db_admin_init');
function db_admin_init(){
	add_meta_box('db_meta', 'Extra Information', 'db_meta', 'post', 'normal', 'high');
	add_meta_box('db_meta', 'Extra Information', 'db_meta', 'premiere', 'normal', 'high');
	add_meta_box('db_meta', 'Extra Information', 'db_meta_beatmersive', 'beatmersive', 'normal', 'high');
	add_meta_box('db_splash_meta', 'Splash', 'db_splash_meta', 'page', 'normal', 'high');
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
	<?php }


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
	<?php }

// save field
	add_action('save_post', 'db_save_post');
	function db_save_post() {
		global $post;

		if(isset($post->ID)) {
			$id = $post->ID;
			
			foreach($_POST as $key => $val) {
				if(substr($key, 0, 3) == 'db_') {
					update_post_meta($id, $key, trim($val));
				}
			}

		}
	}

	/* end meta box functions */




// SoundCloud Player
	function soundcloud_player($link, $title) {
		echo '<a href="' . $link . '" class="sc-player">' . $title . '</a>';
	}

// full width soundcloud player
	function full_soundcloud_player($link, $title, $soundcloud_color = "") {
		echo '<div class="full-width-soundcloud" data-sc-color="' . $soundcloud_color . '"><a href="' . $link . '" class="sc-player">' . $title . '</a></div>';
	}

// filter the content
//add_filter('the_content', 'filter_the_content');
	function filter_the_content($content) {

		if (strpos($content,'iframe') !== false) {
			str_replace('iframe', 'iframe class="youtube"', $content);
		}

		return $content;
	}



////////// FORCE USERS TO ENTER EXCERPT AND GOOGLE SEO \\\\\\\\\\\\\\\\\\\\\\\\



	function zuus_player() {

	// count files in folder
		$directory = get_template_directory() . '/images/zuus_player/';
		$files = glob($directory . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

		$image = '';
		if($files !== false) { 
			$file = array_rand($files);
			$image = basename($files[$file]);
		}

		if($image != '') {
			echo '<div class="zuus_player">';
			echo '<img class="img-responsive" alt="zuus_player" src="' .  THEME_DIR . '/images/zuus_player/' . $image .'" />';
			echo '</div>';
		}

	}

//add_action('wp_footer', 'add_zuus_to_footer');
	function add_zuus_to_footer() {
		echo '<div class="zuus_overlay"><div class="zuus_content"><div id="zuus-widget"></div></div>
		<div class="close">close</div>
	</div>';	
}

add_filter('the_content', 'remove_spaces');
function remove_spaces($the_content) {
	return preg_replace('/[\p{Z}\s]{2,}/u', ' ', $the_content);
}






// gets the first primary category of a post
function get_primary_category($id = 0) {
	
	$category = get_the_terms($id, 'category');		
	$parent_cat = "";
	
	foreach((array) $category as $term) {
		if($term->slug)
			if($term->slug == "featured" || $term->slug == "spotlight-featured" || $term->slug == "spotlight-left" || $term->slug == "spotlight-right") { continue; }
		
		// if the first term is parent, return it
		if($term->parent == 0) {
			$parent_cat = $term;
			break;
		} else {
			// if the category isn't the parent, find the parent
			$parent_cat = get_parent_of_category($term->term_id); 
		}
	}

	return $parent_cat;		
}

// this function gets the parent category of any given category
function get_parent_of_category($category_id) {
	while($category_id) {
		$category = get_category($category_id); // get the object for the catid
		$category_id = $category->category_parent; // assign parent ID (if exists) to $catid
		
		$category_parent = $category->cat_ID;
	}
	return get_category($category_parent);
}



function get_top_category($catid) {
	while($catid) {
		$cat = get_category($catid); // get the object for the catid
		$catid = $cat->category_parent; // assign parent ID (if exists) to $catid
		// the while loop will continue whilst there is a $catid
		// when there is no longer a parent $catid will be NULL so we can assign our $catParent
		$catParent = $cat->cat_ID;
	}
	return get_category($catParent);
}
function move_to_top(&$array, $key) {
	$temp = array($key => $array[$key]);
	unset($array[$key]);
	$array = $temp + $array;
}

function get_blog_by_name($name) {
	global $blog_list;
	if(!isset($blog_list)) {
		$blog_list = wp_get_sites();
	}

	foreach($blog_list as $key => $val) {
		if(strpos($val['domain'], $name) !== false) {
			return $val['blog_id'];
		}
	}
	return null;
}

add_filter('user_contactmethods', 'add_user_fields');
function add_user_fields($profile_fields) {

	// Add new fields
	$profile_fields['twitter'] = 'Twitter Username (name - no @ symbol)';
	$profile_fields['linkedin'] = 'LinkedIn Profile URL (http://url)';
	$profile_fields['job_title'] = 'Job Title';

	// Remove old fields
	//unset($profile_fields['aim']);

	return $profile_fields;
}

function author_image($author_id = 0) {
	echo get_wp_user_avatar($author_id, 'small');
}

function author_biography($author_id = 0) {

	if($author_id == 0) {
		$author_id = get_the_author_meta('ID');
	}

	$author_username = get_the_author_meta('display_name', $author_id);

	?>
	<div class="author-bio-wrapper">
		<div class="row">
			<div class="col-xs-2 tight-right">
				<div class="author-image"><a href="<?php echo get_author_posts_url($author_id); ?>"><?php echo get_wp_user_avatar($author_id, 'medium'); ?></a></div>
			</div>
			<div class="col-xs-10">
				<div class="author-title"><h3 class="section_title"><strong><?php echo $author_username; ?></strong></h3></div>
				<div class="author-connect">
					<ul>
						<?php if(get_the_author_meta('twitter', $author_id) != "") { ?>
						<li class="twitter-bio"><a href="http://twitter.com/intent/user?screen_name=<?php echo get_the_author_meta('twitter', $author_id); ?>"><i class="fa fa-fw fa-twitter"></i></a></li>
						<?php } if(get_the_author_meta('linkedin', $author_id) != "") { ?>
						<li class="linkedin-bio"><a href="<?php echo get_the_author_meta('linkedin', $author_id); ?>" target="_blank"><i class="fa fa-fw fa-linkedin"></i></a></li>
						<?php } ?>
					</ul>
				</div>
				<?php if(get_the_author_meta('job_title', $author_id) != "") { ?>
				<div class="author-job-title"><?php echo get_the_author_meta('job_title', $author_id); ?></div>
				<?php } if(get_the_author_meta('description', $author_id) != "") { ?>
				<div class="author-biography"><?php echo get_the_author_meta('description', $author_id); ?></div>
				<?php } ?>
			</div>	
		</div>
	</div>
	<?php	
}




add_action('init', 'db_ad_cpt');
function db_ad_cpt() {
	register_post_type('ad_display',
		array(
			'labels' => array(
				'name' => __('Advertisement'),
				'singular_name' => __( 'Advertisements' ),
				'menu_name' => __( 'Ads' ),
				'all_items' => __( 'All Ads' ),
				'add_new' => __( 'Add New Ad' ),
				'add_new_item' => __( 'Add New Advertisement' ),
				'edit_item' => __( 'Edit Ad' ),
				'new_item' => __( 'New Ad' ),
				'view_item' => __( 'View Ad' ),
				'search_items' => __( 'Search Ad' ),
				'not_found' => __( 'Ad Not Found' ),
				'not_found_in_trash' => __( 'Ad Not Found In Trash' ),
				),
			'public' => true,
			'description' => 'Shows a new ad!',
			'show_ui' => true,
			'show_in_menu' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => array('slug' => 'ads'),
			'query_var' => true,
			'supports' => array('title', 'thumbnail'),
			'menu_position' => 10,
			'has_archive' => true,
			)
		);
}

add_action('admin_menu', 'db_ads_meta');
function db_ads_meta() {
	add_meta_box('db_ads_meta', 'Ad Information', 'db_ads_meta_functions', 'ad_display', 'normal', 'high');
}

add_action('init', 'db_ads_tax', 1);
function db_ads_tax() {
	register_taxonomy('ad_type', array('ad_display'), array('hierarchical' => true, 'label' => 'Ad Type', 'public' => true, 'singular_label' => 'Ad Type'));
}

function db_ads_meta_functions() {
	global $post;
	$db_ad_permalink = get_post_meta($post->ID, 'db_ad_hyperlink', true);
	$db_ad_location = get_post_meta($post->ID, 'db_ad_location', true);

	if(!isset($db_ad_location)) { $db_ad_location = 0; }
	
	?>
	<label for="ad">Ad Hyperlink ( where do you want it to go )</label>
	<input type="text" name="db_ad_hyperlink" value="<?php if(isset($db_ad_permalink)) { echo $db_ad_permalink; } ?>" />
	<br/>
	
	
	<label for="location">Ad Location</label>
	<select name="db_ad_location">
		<option <?php if($db_ad_location == 0) { echo 'selected'; } ?>>Please select location:</option>
		<option value="1" <?php if($db_ad_location == 1) { echo 'selected'; } ?>>1</option>
		<option value="2" <?php if($db_ad_location == 2) { echo 'selected'; } ?>>2</option>
		<option value="3" <?php if($db_ad_location == 3) { echo 'selected'; } ?>>3</option>
		<option value="4" <?php if($db_ad_location == 4) { echo 'selected'; } ?>>4</option>
		<option value="5" <?php if($db_ad_location == 5) { echo 'selected'; } ?>>5</option>
		<option value="6" <?php if($db_ad_location == 6) { echo 'selected'; } ?>>6</option>
		<option value="7" <?php if($db_ad_location == 7) { echo 'selected'; } ?>>7</option>
		<option value="8" <?php if($db_ad_location == 8) { echo 'selected'; } ?>>8</option>
		<option value="9" <?php if($db_ad_location == 9) { echo 'selected'; } ?>>9</option>
		<option value="10" <?php if($db_ad_location == 10) { echo 'selected'; } ?>>10</option>
	</select>
	<?php
}

// save any editted meta
add_action('save_post_ad_display', 'db_ad_save_post');
function db_ad_save_post() {
	global $post;
	if(isset($_POST['db_ad_hyperlink'])) {
		update_post_meta($post->ID, 'db_ad_hyperlink', trim($_POST['db_ad_hyperlink']));
	}
	
	if(isset($_POST['db_ad_inline'])) {
		update_post_meta($post->ID, 'db_ad_inline', trim($_POST['db_ad_inline']));
	}
	if(isset($_POST['db_ad_location'])) {
		update_post_meta($post->ID, 'db_ad_location', trim($_POST['db_ad_location']));
	}
}



// hide permalink stuff from cpt
add_filter('get_sample_permalink_html', 'db_hide_permalinks');
function db_hide_permalinks($in) {
	global $post;
	if($post->post_type == 'ad_display') { 
		return '';
	} else {
		return $in;	
	}
}

add_action('after_setup_theme', 'default_attachment_display_settings');
function default_attachment_display_settings() {
	update_option('image_default_align', 'left' );
	update_option('image_default_link_type', 'none' );
	update_option('image_default_size', 'large' );
}

if(current_user_can('contributor') && !current_user_can('upload_files')) { add_action('admin_init', 'allow_contributor_uploads'); } 
function allow_contributor_uploads() {
	$contributor = get_role('contributor');
	$contributor->add_cap('upload_files');
}


function make_bitly_url($url, $login = 'o_6e0qt9hksv', $apikey = 'R_6558905de3a882e85191efa8344751de', $format = 'xml', $version = '2.0.1') {
	//create the URL
	$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$apikey.'&format='.$format;
	
	//get the url - could also use cURL here
	$response = file_get_contents($bitly);
	
	//parse depending on desired format
	if(strtolower($format) == 'json') {
		$json = @json_decode($response,true);
		return $json['results'][$url]['shortUrl'];
	} else { // xml
		$xml = simplexml_load_string($response);
		return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
	}
}


function char_based_excerpt($count) {
	//  $permalink = get_permalink($post->ID);
	$excerpt = get_the_content();
	$excerpt = strip_tags($excerpt);
	$excerpt = substr($excerpt, 0, $count);
	$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
	$excerpt = rtrim($excerpt,",.;:- _!$&#");
	$excerpt = preg_replace('!\s+!', ' ', $excerpt);
	$excerpt = $excerpt . '...';
	//  $excerpt = $excerpt.'<a href="'.$permalink.'" style="text-decoration: none;">&nbsp;(...)</a>';
	return $excerpt;
}


add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10, 3);
function remove_thumbnail_dimensions($html, $post_id, $post_image_id) {
	$html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
	return $html;
}

function addhttp($url) {
	if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
		$url = "http://" . $url;
	}
	return $url;
}

function the_timestamp() { echo human_time_diff(get_the_time('U'), current_time('timestamp')); }

function print_pre($a) { echo '<pre>'; print_r($a); echo '</pre>'; }


// custom admin login logo
function custom_login_logo() {
	echo '<style type="text/css">
	h1 a { background-image: url('.get_bloginfo('template_directory').'/images/custom_login_logo.png) !important; height:58px!important; width:312px!important;}
</style>';
}
add_action('login_head', 'custom_login_logo');



global $broken_switch_posts; $broken_switch_posts === false;

// check to see if any links have a mismatch permalink vs. their actual site. prints :) / :(
function filter_the_posts($p) {
	global $broken_switch_posts, $post, $reason;

	
	if($broken_switch_posts === true) { return; }
	
	$blogID = $post->BLOG_ID;
	if(!$blogID || $blogID < 1) { return; } 

	if($blogID == 1) {
		switch_to_blog(1);
		if(substr(get_permalink(), 0, 10) != 'http://dev') {
			$broken_switch_posts = true;
			$reason = 'blogID = ' . $blogID . ' postID = ' . $p->ID . ' ' . substr(get_permalink(), 0 , 10) . ' ' . get_the_title();
		}
	}
	if($blogID == 2) {
		switch_to_blog(2);
		if(substr(get_permalink(), 0, 10) != 'http://trc') {
			$broken_switch_posts = true;
			$reason = 'blogID = ' . $blogID . ' postID = ' . $p->ID . ' ' . substr(get_permalink(), 0 , 10) . ' ' . get_the_title();
		}
	}
	if($blogID == 4) {
		switch_to_blog(4);
		if(substr(get_permalink(), 0, 10) != 'http://rav') {
			$broken_switch_posts = true;
			$reason = 'blogID = ' . $blogID . ' postID = ' . $p->ID . ' ' . substr(get_permalink(), 0 , 10) . ' ' . get_the_title();
		}
	}

	restore_current_blog();
	if($broken_switch_posts === true) { return; }


}
add_filter('the_post','filter_the_posts');



// define THEME_DIR
define("THEME_DIR", get_template_directory_uri());


?>