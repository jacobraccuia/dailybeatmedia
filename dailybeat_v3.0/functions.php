<?php

require_once('includes/wp_bootstrap_navwalker.php');
require_once('includes/post_queries.php');
require_once('includes/custom_post_settings.php');
//require_once('now-feed/now-feed.php');
require_once('admin/admin_functions.php');

// remove meta_head generator
remove_action('wp_head', 'wp_generator');

// add all appropriate styles and scripts
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');
function my_enqueue_scripts() {
	
	wp_enqueue_script('twitter_web_intents', '//platform.twitter.com/widgets.js');
	wp_enqueue_script('featherlist_js', '//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.js');
	wp_enqueue_script('images_loaded', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.min.js');

	wp_enqueue_script('dailybeat_js', THEME_DIR . '/js/scripts.js', array('jquery'));


	// commented out because we uglify the scripts 
	//	wp_enqueue_script('ajax_page_load', THEME_DIR . '/js/ajax_page_load.js');
	//	wp_enqueue_script('dailybeat_js', THEME_DIR . '/js/scripts.js', array('jquery'));

	/*if(is_single()) {
		wp_enqueue_script('single_scripts', THEME_DIR . '/js/single_scripts.js', array('jquery'));
	}
	*/

	wp_localize_script('dailybeat_js', 'DB_Ajax_Call', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('dailybeat_js', 'DB_Ajax_Call', array('ajaxurl' => admin_url('admin-ajax.php'), 'postCommentNonce' => wp_create_nonce('myajax-post-comment-nonce'),));

	wp_enqueue_style('featherlight_css', '//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.css');
	wp_enqueue_style('jacob_css', THEME_DIR . '/style.css');

}

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




// VERY IMPORTANT, fixes shortcode injection inside wpautop.
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
function full_timestamp() { return get_the_time('F j, Y'); }

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