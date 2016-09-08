<?php

require_once('includes/wp_bootstrap_navwalker.php');
require_once('includes/post_queries.php');
require_once('includes/custom_post_settings.php');
//require_once('now-feed/now-feed.php');

// remove meta_head generator
remove_action('wp_head', 'wp_generator');

// add all appropriate styles and scripts
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');
function my_enqueue_scripts() {
		
	wp_enqueue_script('twitter_web_intents', '//platform.twitter.com/widgets.js');
	wp_enqueue_script('featherlist_js', '//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.js');
	wp_enqueue_script('images_loaded', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.min.js');

	wp_enqueue_script('channel_js', THEME_DIR . '/js/scripts.js', array('jquery'));


	// commented out because we uglify the scripts 
	//	wp_enqueue_script('ajax_page_load', THEME_DIR . '/js/ajax_page_load.js');
	//	wp_enqueue_script('dailybeat_js', THEME_DIR . '/js/scripts.js', array('jquery'));

	/*if(is_single()) {
		wp_enqueue_script('single_scripts', THEME_DIR . '/js/single_scripts.js', array('jquery'));
	}
	*/

	wp_localize_script('channel_js', 'DB_Ajax_Call', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('channel_js', 'DB_Ajax_Call', array('ajaxurl' => admin_url('admin-ajax.php'), 'postCommentNonce' => wp_create_nonce('myajax-post-comment-nonce'),));

	wp_enqueue_style('featherlight_css', '//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.css');
	wp_enqueue_style('channel_css', THEME_DIR . '/style.css');

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



// add roles to body class
add_filter('admin_body_class', 'wpa66834_role_admin_body_class');
function wpa66834_role_admin_body_class($classes) {
	global $current_user;
	foreach($current_user->roles as $role)
		$classes .= ' role-' . $role;
	return trim($classes);
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

add_filter('the_content', 'remove_spaces');
function remove_spaces($the_content) {
	return preg_replace('/[\p{Z}\s]{2,}/u', ' ', $the_content);
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



// define THEME_DIR
define("THEME_DIR", get_template_directory_uri());


?>