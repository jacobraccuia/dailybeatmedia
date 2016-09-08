<?php

require_once('now-feed/now-feed.php');
require_once('songkick/songkick.php');
require_once('header/header.php');

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

}

// this function grabs the top 10 most popular posts from current blog and adds to a custom database
// cached into the database every 10 minutes
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

			$top_posts = stats_get_csv('postviews', 'period=month&days=60&limit=100');
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

					$old_views = get_post_meta($postID, 'db_views', true);
					update_post_meta($postID, 'db_views', $old_views++);
					
				}


			}
		}
	}
	die;
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




// saving any post
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

function social_media($brand, $url) {
	switch($brand) {
		case 'twitter';
		return '<a href="http://twitter.com/' . $url . '" target="_blank"><i class="fa fa-fw fa-twitter"></i></a>';
		case 'instagram';
		return '<a href="http://instagram.com/' . $url . '" target="_blank"><i class="fa fa-fw fa-instagram"></i></a>';
		case 'facebook';
		return '<a href="http://facebook.com/' . $url . '" target="_blank"><i class="fa fa-fw fa-facebook"></i></a>';
		case 'soundcloud';
		return '<a href="http://soundcloud.com/' . $url . '" target="_blank"><i class="fa fa-fw fa-soundcloud"></i></a>';
		case 'linkedin';
		return '<a href="http://www.linkedin.com/in/' . $url . '" target="_blank"><i class="fa fa-fw fa-linkedin"></i></a>';
		break;
		default:
		return;
	}
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
?>