<?php


// includes folder
foreach(glob(plugin_dir_path(__FILE__) . '/includes/*.php') as $filename) {
	require_once($filename);
}

if(is_admin()) {	
	foreach(glob(plugin_dir_path(__FILE__) . 'includes/admin/*.php') as $filename) {
		require_once($filename);
	}

	// init theme settings
	new Zuko_Theme_Settings();
}

require_once('now-feed/now-feed.php');
require_once('songkick/songkick.php');
require_once('header/header.php');
require_once('footer/footer.php');

// global scripts
// these scripts are used across EVERY site
add_action('wp_enqueue_scripts', 'dbm_enqueue_scripts');
function dbm_enqueue_scripts() {

	wp_enqueue_script('bootstrap_js', plugins_url('/bootstrap/bootstrap.min.js', __FILE__), array('jquery'));
	wp_enqueue_style('bootstrap_css', plugins_url('/bootstrap/bootstrap.min.css', __FILE__));
	wp_enqueue_style('bootstrap_css_mods', plugins_url('/bootstrap/bootstrap_style_modifications.css', __FILE__));
	
	// soundcloud shit
	// maybe this should all be concat and uglified together as well
	wp_enqueue_script('waveform_js', plugins_url('/soundcloud/waveform.js', __FILE__), array('jquery'));
	wp_enqueue_script('soundcloud_api_js', plugins_url('/soundcloud/soundcloud.player.api.js', __FILE__), array('jquery'));
	wp_enqueue_script('soundcloud_player_js', plugins_url('/soundcloud/sc-player.js', __FILE__), array('jquery'));
	wp_enqueue_style('soundcloud_css', plugins_url('/soundcloud/sc-player.css', __FILE__));

	//**
	// this file concats all custom scripts
	// located inside /src/js/*.js
	// any folders inside /src/js/ are NOT included
	//**

	wp_enqueue_script('db_network_js', plugins_url('/js/db-network.js', __FILE__), array('jquery'));

	  /*                     *\	
	 **        UGLIFY         **
	**   /src/js/uglify/*.js   **

	history_js  _ history.js - ajax across all sites
	stickem     - sticky 
	blazy lazy  - loading
	dotdotdot   - truncate text
	pushmenu    - cabinet
	waypoints   - not entirely sure what we use this for
	marquee     - for soundcloud
	modernizrr  - for browser shit

	*/

	wp_enqueue_script('db_network_uglify_js', plugins_url('/js/db-network-uglify.js', __FILE__), array('jquery'));
	
	wp_enqueue_style('db_network_css', plugins_url('/style.css' , __FILE__));
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');

	// create ajax functionality for my custom js
	wp_localize_script('db_network_js', 'DB_Ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('db_network_js', 'DB_Ajax', array('ajaxurl' => admin_url('admin-ajax.php'), 'postCommentNonce' => wp_create_nonce('myajax-post-comment-nonce'),));

	wp_localize_script('soundcloud_player_js', 'sc_params', get_theme_colors());

}

function get_theme_colors() {

	$theme_color_light = zuko_get_options('theme_color_light');
	$theme_color_dark = zuko_get_options('theme_color_dark');

	if($theme_color_light == '') { $theme_color_light = '#00E2CF'; }
	if($theme_color_dark == '') { $theme_color_dark = '#5784E0'; }

	return array(
		'color_light' => $theme_color_light,
		'color_dark' => $theme_color_dark,
		);
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

// this function grabs the top 10 most popular posts from current blog and adds to a custom database
// cached into the database every 10 minutes
// this probably needs fixing
add_action('wp_ajax_db_popular_post_aggregator', 'db_popular_post_aggregator');
add_action('wp_ajax_nopriv_db_popular_post_aggregator', 'db_popular_post_aggregator');
function db_popular_post_aggregator() {
	if(function_exists('stats_get_csv')) {
		global $wpdb;

		$table_name = $wpdb->base_prefix . 'db_stats';
		$blogID = get_current_blog_id();

    	// get any row with blog ID for date comparison
		$result = $wpdb->get_row("SELECT * FROM {$table_name} WHERE blog_id = {$blogID} ORDER BY date_added ASC LIMIT 1", ARRAY_A);

		if(!$result || strtotime($result['date_added']) + 0 < time()) {

			// supposedly grabbing post views from past 60 months...
			$top_posts = stats_get_csv('postviews', 'period=month&days=60&limit=-1');
			if($top_posts) {
				$wpdb->delete($table_name, array('blog_id' => $blogID), array('%d'));
				$accepted_post_types = array('post', 'premiere');

				foreach($top_posts as $post) {
					
					$postID = absint($post['post_id']);
					$post_views = $post['views'];

					if($postID <= 0) { continue; }
					if(!in_array(get_post_type($postID), $accepted_post_types)) { continue; }


					$arr = array(
						'blog_id' => $blogID,
						'post_id' => $postID,
						'views' => $post_views,
						'date_added' => date('Y-m-d H:i:s', time())
						);

					$wpdb->insert($table_name, $arr, array('%d', '%d', '%d', '%s'));

					update_post_meta($postID, 'db_weekly_views', $post_views);
					
				}


			}
		}
	}
	die;
}

// add job to cron
add_action('init', 'register_update_wp_view_count');
function register_update_wp_view_count() {
	if(!wp_next_scheduled('db_popular_post_aggregator')) {
		wp_schedule_event(time(), '10mins', 'db_popular_post_aggregator');
	}
}   


// use single.php located in plugin
add_filter('template_include', 'wpse72544_set_template');
function wpse72544_set_template($template){

	if(is_singular('exclusive') && 'single-exclusive.php' != $template){
		$template = dirname(__FILE__) . '/single/single-exclusive.php';
	} else if(is_singular()) {
		$template = dirname(__FILE__) . '/single/single.php';
	}

	return $template;
}



function author_image($author_id = 0) {
	echo get_wp_user_avatar($author_id, 'small');
}

function single_author_widget($author_id = 0) {

	if($author_id == 0) {
		$author_id = get_the_author_meta('ID');
	}

	$author_username = explode(' ', get_the_author_meta('display_name', $author_id));

	?>
	<div class="author-bio-wrapper">
		<div class="author-image"><a href="<?php echo get_author_posts_url($author_id); ?>"><?php echo get_wp_user_avatar($author_id, 'thumbnail'); ?></a></div>
		<div class="author-title">
			<h3><?php
				$i = 0; // put br inside authors name - Jacob<br/>Raccuia
				foreach($author_username as $name) {
					if($i > 0) { echo '<br/>'; }
					echo $name;
					$i++;
				}
				?>
			</h3>
		</div>
		<div class="author-connect">
			<ul>
				<?php if(get_the_author_meta('twitter', $author_id) != "") { ?>
				<li class="twitter-bio"><a href="http://twitter.com/intent/user?screen_name=<?php echo get_the_author_meta('twitter', $author_id); ?>"><i class="fa fa-fw fa-twitter"></i></a></li>
				<?php } if(get_the_author_meta('linkedin', $author_id) != "") { ?>
				<li class="linkedin-bio"><a href="<?php echo get_the_author_meta('linkedin', $author_id); ?>" target="_blank"><i class="fa fa-fw fa-linkedin"></i></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>	
	<?php	
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


add_action('init', 'register_db_shortcodes');
function register_db_shortcodes(){
	add_shortcode('blockquote', 'db_blockquote');
	add_shortcode('divider', 'db_divider');
}

function db_blockquote($atts, $content = null) {
	extract(shortcode_atts(array(
		'side' => 'left',
		'by' => '',
		), $atts));

	$class = 'blockquote';
	if($side == 'right')  {
		$class .= ' right';
	}

	$by_text = '';
	if($by != '') {
		$by_text = '<div class="by"> - ' . $by . '</div>';
	}

	return '<div class="' . $class . '"><div class="bq">&ldquo;</div>' . $content . '"' . $by_text . '</div>';
	
}

function db_divider($atts) {
	extract(shortcode_atts(array(
		//'side' => 'left',
		), $atts));

	return '<div class="ex-divider thick"></div>';
	
}




// display post views in meta box
add_action('post_submitbox_misc_actions', 'dbn_show_view_count');
function dbn_show_view_count($post) {
	if(is_admin()) {
		$views = get_post_meta($post->ID, 'db_weekly_views', true);
		echo '<div class="misc-pub-section misc-pub-visibility">Weekly Views: <strong>' . $views . '</strong></div>';
	}
	$views = get_post_meta($post->ID, 'db_total_views', true);
	echo '<div class="misc-pub-section misc-pub-visibility">Total Views: <strong>' . $views . '</strong></div>';	
}

// show featured artist on single.php

// add meta box
add_action('admin_init', 'dbm_admin_init');
function dbm_admin_init(){
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


// when saving custom post meta across the entire site

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



// track views per page!!!!

// to keep the count accurate, lets get rid of prefetching. ????
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

add_action('wp_head', 'dbn_track_post_views');
function dbn_track_post_views($post_id) {
	if(!is_single()) return;
	if(empty($post_id)) {
		global $post;
		$post_id = $post->ID;    
	}

	$views = get_post_meta($post_id, 'db_total_views', true);
	if($views == '') {
		delete_post_meta($post_id, 'db_total_views');
		add_post_meta($post_id, 'db_total_views', '1');
	} else {
		$views = $views + 1;
		update_post_meta($post_id, 'db_total_views', $views);
	}
}


// saving any post
// add blog id to post meta
add_action('pre_post_update', 'db_save_all_posts', 1);
function db_save_all_posts($post_id) {
	$blogID = get_current_blog_id();
	update_post_meta($post_id, 'db_blog_id', trim($blogID));
}

function get_blog_by_name($name) {
	global $blog_list;
	if(!isset($blog_list)) {
		$blog_list = get_sites();
	}
	
	foreach($blog_list as $key => $val) {
		if(strpos($val->domain, $name) !== false) {
			return $val->blog_id;
		}
	}
	return null;
}


add_action('admin_enqueue_scripts', 'db_load_scripts');
function db_load_scripts($hook) {

	if($hook == 'post.php' || $hook == 'post-new.php') {
		global $post;

		if($post->post_type == 'post' || $post->post_type == 'exclusive') {
			wp_enqueue_script('jquery-ui-autocomplete');

			wp_enqueue_script('dbn_admin_js', plugins_url('/js/admin/jquery-posts.js', __FILE__), array('jquery'));

			wp_localize_script('dbn_admin_js', 'DB_Ajax_Call', array('ajaxurl' => admin_url('admin-ajax.php')));
			wp_localize_script('dbn_admin_js', 'DB_Ajax_Call', array('ajaxurl' => admin_url('admin-ajax.php'), 'postCommentNonce' => wp_create_nonce('myajax-post-comment-nonce'),));
		}
	}
}

function social_media($brand, $url) {
	switch($brand) {
		case 'twitter';
		return '<a href="http://twitter.com/' . $url . '" target="_blank"><i class="fa fa-fw fa-twitter"></i></a>';
		case 'instagram';
		return '<a href="http://instagram.com/' . $url . '" target="_blank"><i class="fa fa-fw fa-instagram"></i></a>';
		case 'facebook';
		return '<a href="http://facebook.com/' . $url . '" target="_blank"><i class="fa fa-fw fa-facebook"></i></a>';
		case 'soundcloud';
		return '<a href="http_date()://soundcloud.com/' . $url . '" target="_blank"><i class="fa fa-fw fa-soundcloud"></i></a>';
		case 'linkedin';
		return '<a href="http://www.linkedin.com/in/' . $url . '" target="_blank"><i class="fa fa-fw fa-linkedin"></i></a>';
		break;
		default:
		return;
	}
}



// set up global exclude posts
global $exclude_posts;
$exclude_posts = array();

// for up to 20 sites :)
for($i = 1; $i <= 20; $i++) {
	$exclude_posts[$i] = array(0);
}

function exclude_this_post($post_id, $blogID = 0) {
	global $exclude_posts;

	if($post_id == 0) { return; }

	if($blogID == 0) {
		$blogID = get_current_blog_id();
	}

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
	switch_to_blog($GLOBALS['DEFAULT_BLOG_ID']);
	unset($GLOBALS['_wp_switched_stack']);
	$GLOBALS['switched'] = false; 
}


$GLOBALS['DEFAULT_BLOG_ID'] = get_current_blog_id();

define('PLUGIN_DIR', plugin_dir_url( __FILE__ ));

?>