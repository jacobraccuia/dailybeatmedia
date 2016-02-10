<?php

require_once('now-feed/now-feed.php');
require_once('songkick/songkick.php');

// add all appropriate styles and scripts
add_action('wp_enqueue_scripts', 'db_enqueue_scripts');
function db_enqueue_scripts() {
	wp_enqueue_script('db_network_js', plugins_url('/js/scripts.js', __FILE__), array('jquery'));
	wp_enqueue_style('db_network_css', plugins_url('/css/style.css' , __FILE__));
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');

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


// saving any post
add_action('pre_post_update', 'db_save_all_posts', 1);
function db_save_all_posts($post_id) {
	$blogID = get_current_blog_id();
	update_post_meta($post_id, 'db_blog_id', trim($blogID));
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

function db_channel_guide() {
	
	$blog_id = get_current_blog_id();


	$logos = array(
		'5' => array(beatmersive_logo(), 'Beatmersive', 'http://beatmersive.com'),
		'1' => array(dailybeat_logo(), 'Daily Beat', 'http://daily-beat.com'),
		'3' => array(dailybeatmedia_logo(), 'Daily Beat Media', 'http://dailybeatmedia.com'),
	//	'0' => array('/images/db_first.png', 'DB First'),
		'4' => array('/images/lightedmusicgroup.png', 'Lighted Music Group', 'http://lightedmusicgroup.com'),
		'6' => array(raverrafting_logo(), 'Raver Rafting', 'http://raverrafting.daily-beat.com'),
		'2' => array(trc_logo(), 'Toronto Rave Community', 'http://trc.daily-beat.com'),
		);
	
	unset($logos[$blog_id]);


	?>	
	<div class="guide-wrapper">
		<?php /*	<a class="navbar-brand" href="<?php echo home_url(); ?>"><img src="<?php echo $current_logo; ?>" alt="logo" /></a> */ ?>
		<!-- <div id="toggle-channels" class="open-list" title="View DB Channels"><i class="fa fa-caret-down fa-fw"></i><i class="fa fa-caret-up fa-fw" style="display:none;"></i></div> -->
		<ul id="channel-list" class="channels">
			<?php foreach($logos as $key => $logo) {
				//$url = get_home_url($key);
				echo '<li><a href="' . $logo[2] .'">';
				if(substr($logo[0], 0, 1) === '/') echo '<img src="' . plugins_url($logo[0] , __FILE__) . '" title="' . $logo[1] . '" />';
				else echo $logo[0];
				echo '</a></li>';
			}
			?>
		</ul>

	</div>
	
	<?php	
}

function db_search() { ?>

<div id="search-dropdown" class="dropdown-content">
	<?php $ph = "type to search"; ?>
	<form action="<?php echo home_url(); ?>" class="search-wrapper" method="get">
		<label class="sr-only" for="s">Search</label>
		<span class="search-icon"><i class="fa fa-search"></i></span>
		<input type="text" class="search-input" id="s" name="s" autocomplete="off" 
		value="<?php echo $ph; ?>"
		onfocus="if(this.value=='<?php echo $ph; ?>')this.value='';"
		onblur="if(this.value=='')this.value='<?php echo $ph; ?>'"
		placeholder="<?php echo $ph; ?>"
		/>
	</form>		
</div>


<?php
}

function dailybeat_logo() {
	return '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	viewBox="0 0 181.6 24.9" enable-background="new 0 0 181.6 24.9" xml:space="preserve">
	<g>
		<path fill="#00E2CF" d="M0,24.8V0.9h8.8c0,0,0.2,0,0.5,0c0.3,0,0.8,0.1,1.3,0.2s1.1,0.2,1.8,0.4s1.3,0.5,2,0.9
		c1.5,0.8,2.8,2,3.8,3.6c0.5,0.9,0.9,2,1.2,3.1c0.3,1.2,0.5,2.4,0.5,3.7c0,2.7-0.6,5-1.8,6.8v0c-0.5,0.8-1.1,1.5-1.7,2.1
		c-0.6,0.6-1.4,1.2-2.2,1.6c-0.7,0.4-1.4,0.6-2,0.8c-0.6,0.2-1.2,0.3-1.8,0.4c-0.5,0.1-0.9,0.2-1.3,0.2c-0.3,0-0.5,0-0.5,0H0z
		M2.8,22h5.8c0,0,0.2,0,0.4,0c0.3,0,0.6-0.1,1-0.1c0.4-0.1,0.9-0.2,1.4-0.3c0.5-0.2,1-0.4,1.6-0.7c2.8-1.5,4.2-4.2,4.2-8
		c0-3.8-1.4-6.4-4.1-8c-0.4-0.3-0.9-0.5-1.3-0.6c-0.4-0.1-0.8-0.3-1.2-0.3c-0.4-0.1-0.7-0.1-1-0.2c-0.3,0-0.5,0-0.6,0H2.8V22z"/>
		<path fill="#00E2CF" d="M37.8,24.8L30.2,7.1l-7.6,17.8h-3L30.2,0l10.6,24.8H37.8z"/>
		<path fill="#00E2CF" d="M46.9,24.8h-2.8v-24h2.8V24.8z"/>
		<path fill="#00E2CF" d="M66.9,24.8H52.5V0.9h2.8V22h11.5V24.8z"/>
		<path fill="#00E2CF" d="M75.3,24.9h-2.8V15c-1.4-2.1-2.9-4.2-4.4-6.4c-1.5-2.2-3.8-5.7-5.2-7.8h3.3l7.7,11.3l7.7-11.3H85l-9.7,14.2
		V24.9z"/>
		<path fill="#00E2CF" d="M106.4,24.8V11.4h6.2l-6.7-10.5H117c0.3,0,0.7,0.1,0.9,0.1c0.3,0,0.6,0.1,0.9,0.2c0.3,0.1,0.7,0.3,1,0.5
		l0,0c0.3,0.2,0.7,0.4,1,0.7s0.7,0.7,0.9,1.1c0.3,0.4,0.5,1,0.7,1.6c0.2,0.6,0.3,1.3,0.3,2.1c0,1.3-0.2,2.4-0.7,3.2
		c-0.5,0.8-1,1.5-1.7,2c0.3,0.2,0.7,0.5,1,0.8c0.3,0.3,0.7,0.7,0.9,1.2c0.3,0.5,0.5,1,0.7,1.6c0.2,0.6,0.2,1.3,0.2,2.1
		c0,0.9-0.1,1.6-0.3,2.3c-0.2,0.6-0.5,1.2-0.8,1.7c-0.3,0.5-0.7,0.8-1,1.2c-0.4,0.3-0.8,0.6-1.1,0.8c-0.4,0.2-0.8,0.4-1.1,0.5
		c-0.4,0.1-0.7,0.2-1,0.3c-0.3,0.1-0.5,0.1-0.7,0.1c-0.2,0-0.3,0-0.3,0H106.4z M109.2,22h7.6c0,0,0.1,0,0.1,0c0,0,0.1,0,0.1,0
		c0.2,0,0.4-0.1,0.8-0.1s0.7-0.2,1-0.4c1.2-0.6,1.7-1.7,1.7-3.4c0-1.7-0.6-2.8-1.7-3.4c-0.2-0.1-0.5-0.2-0.7-0.3
		c-0.2-0.1-0.4-0.2-0.6-0.2c-0.2,0-0.3-0.1-0.5-0.1h-7.8V22z M115.8,11.3c0.2,0,0.5-0.1,0.9-0.1c0.4-0.1,0.8-0.2,1.2-0.4
		c1.4-0.7,2.1-1.8,2.1-3.5c0-1.5-0.5-2.6-1.5-3.1v0C118.4,4,118.2,4,118,3.9c-0.2-0.1-0.3-0.1-0.5-0.1l-0.2,0c-0.1,0-0.2,0-0.2,0
		s-0.1,0-0.1,0h-6L115.8,11.3z"/>
		<path fill="#00E2CF" d="M141.9,14.6h-12.1V22h12.9v2.8h-15.7V0.9h15.7v2.8h-12.9v8.1h12.1V14.6z"/>
		<path fill="#00E2CF" d="M163.6,24.8l-7.6-17.8l-7.6,17.8h-3L156.1,0l10.6,24.8H163.6z"/>
		<path fill="#00E2CF" d="M170.6,24.8V3.7h-8.1V0.9h19.1v2.8h-8.3v21.1H170.6z"/>
	</g>
</svg>';
}

function dailybeatmedia_logo() {
	return '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	viewBox="0 0 1140.2 204.1" enable-background="new 0 0 1140.2 204.1" xml:space="preserve">
	<g id="Layer_0_xA0_Image_1_">
	</g>
	<g id="MEDIA">
		<path fill="#FFFFFF" d="M928.7,203.7c0.1-0.1,0.2-0.1,0.3-0.1C928.9,203.6,928.8,203.6,928.7,203.7z"/>
		<path fill="#00D4C2" d="M326.1,141.2c-0.7-7.5-2.2-14.7-5.4-21.6c-3.6-7.8-9.1-13.9-16.1-18.8c-2.9-2-2.7-2-0.2-4.3
		c5.8-5,10-11.3,13.2-18.2c2.7-5.8,3.5-12,4.1-18.3c0.2-3.5,0.2-7.1,0-10.6c-0.5-3.2-0.7-6.4-1.4-9.6c-2.6-11.5-8-21.4-17.8-28.4
		c-7.6-5.4-16-8.8-25.2-10c-0.8-0.3-1.6,0.1-2.4-0.3c-32.1,0-64.2,0-96.6,0c18.9,29.7,37.6,59,56.5,88.7c-1.1,0-1.9,0-2.7,0
		c-15.5,0-31,0-46.6-0.1c-2.1,0-2.7,0.4-2.7,2.6c0.1,36.2,0.1,72.4,0,108.7c0,2.2,0.5,2.8,2.8,2.8c29.8-0.1,59.6-0.1,89.4-0.1
		c0.1-0.1,0.2-0.1,0.3-0.1c0.6-0.2,1.3-0.2,2-0.2c8.2-1.4,16.1-3.7,23.3-8c9-5.5,16-12.8,20.5-22.4c2.3-5,3.7-10.3,4.7-15.7
		c0.4-0.7-0.1-1.6,0.3-2.3c0-0.9,0.1-1.7,0.1-2.6C326.4,148.5,326.4,144.9,326.1,141.2z M222.6,25.5c-0.1-0.2-0.1-0.4-0.3-0.9
		c17.3,0,34.4-0.8,51.4,0.2c13.2,0.8,21.6,7.6,23.9,22.1c1.2,7.1,1,14.3-1.6,21.2c-1.9,5.1-5,9.3-9.4,12.6c-6.8,5-14.4,7.6-22.8,8.1
		c-0.9,0.1-1.5-0.1-2-0.9C248.7,67.1,235.6,46.3,222.6,25.5z M301.1,159.1c-3.3,11.3-11.4,17.2-22.4,19.7c-4.5,1.1-9.2,1.4-13.8,1.4
		c-18.6-0.1-37.3-0.1-55.9,0c-1.8,0-2.3-0.4-2.3-2.3c0.1-20.8,0.1-41.5,0-62.3c0-2.1,0.6-2.4,2.5-2.4c10.6,0.1,21.3,0,31.9,0
		c10.1,0,20.3-0.6,30.4,0.1c19.6,1.4,29.9,11.3,31,29.6C302.9,148.4,302.7,153.8,301.1,159.1z"/>
		<path fill="#69E5DB" d="M2.8,202.9c-1.8,0-2.4-0.4-2.4-2.3c0-66,0-131.9,0-197.9c0-0.5,0-1,0.1-1.5C0.3,1.1,0.2,1,0,1
		c0,67.4,0,134.8,0,202.3c25.5,0,51.1,0,76.6,0c0.8,0,1.6,0.1,2.4-0.4c-2,0-4,0-6,0C49.6,202.8,26.2,202.8,2.8,202.9z"/>
		<path fill="#00D4C2" d="M163.9,68.9c-4.7-16.4-12.2-31.2-25.1-42.8c-18.5-16.8-40.8-24-65.2-25C61.1,0.6,48.4,1,35.8,1
		C24,1,12.3,1.1,0.5,1.1c0.9,1.2,0.7,2.5,0.7,3.8c0,16,0,31.9,0,47.9c0,48.3,0,96.7,0,145c0,4.2,0,4.2,4.2,4.2c23.2,0,46.4,0,69.6,0
		c1.4,0,2.8-0.4,4,0.8c7.3-0.5,14.5-2.1,21.5-4.1c30.7-8.8,52.1-28,62.4-58.3C170.9,116.9,170.7,92.9,163.9,68.9z M139.5,135.5
		c-7.7,20.9-23,33.8-44.1,40.3c-9.1,2.8-18.4,4-28,3.9c-13.7-0.2-27.5-0.1-41.2,0c-2,0-2.5-0.5-2.5-2.5c0.1-25.1,0-50.1,0-75.2
		c0-25,0-50,0-75c0-2.1,0.6-2.6,2.6-2.5c16.2,0.1,32.4-0.6,48.6,0.2c33.9,1.6,61.2,21.1,68,56.2C146.4,99.3,146,117.7,139.5,135.5z"
		/>
		<path fill="#00D4C2" d="M75,202.1c-23.2,0-46.4,0-69.6,0c-4.2,0-4.2,0-4.2-4.2c0-48.3,0-96.7,0-145c0-16,0-31.9,0-47.9
		c0-1.3,0.3-2.7-0.7-3.8c0,0.5-0.1,1-0.1,1.5c0,66,0,131.9,0,197.9c0,1.9,0.6,2.3,2.4,2.3c23.4-0.1,46.8,0,70.3,0c2,0,4,0,6,0
		C77.8,201.7,76.4,202.1,75,202.1z"/>
		<path fill="#FFFFFF" d="M485.3,135.1L538,0h45.7v204.1h-28.5V34.6h-1.5c-11.6,28.2-44.3,113.3-55.9,141.5h-28.2L414,34.6h-1.7
		v169.5h-27.7V0h46.6l52.7,135.1H485.3z"/>
		<path fill="#FFFFFF" d="M610.1,204.1V0h111.8v25.3h-82.7v62.6h72.2v25.3h-72.2v65.5h86.5v25.3H610.1z"/>
		<path fill="#FFFFFF" d="M746,0h56.2c62.9,0,91.4,31.7,91.4,99.3c0,62.3-25.3,104.8-91.4,104.8H746V0z M775.2,178.8h25.9
		c44,0,62.3-23.6,62.3-76.3c0-57.7-23.3-77.2-63.5-77.2h-24.8V178.8z"/>
		<path fill="#FFFFFF" d="M916.7,0h29.4v204.1h-29.4V0z"/>
		<path fill="#FFFFFF" d="M1091.9,148.2h-77.8l-17.5,55.9h-30L1031.3,0h43.7l65.2,204.1h-31.2L1091.9,148.2z M1084,122.9l-17.5-56.8
		c-3.5-12.5-11.4-37.9-13.1-45.7h-1.2c-1.5,7.6-9.3,33.5-12.5,44.8l-17.8,57.7H1084z"/>
	</g>
</svg>
';
}

function trc_logo() {
	return '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
	<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	viewBox="0 0 822 153" enable-background="new 0 0 822 153" xml:space="preserve">
	<polygon id="XMLID_187_" fill="#FFFFFF" points="255,0 146.5,0 108.5,0 0,0 0,41 108.5,41 108.5,153 146.5,153 146.5,41 255,41 "/>
	<path id="XMLID_184_" fill="#FFFFFF" d="M497,0H321h-38v41v20v41v51h38v-51h127l41,51h41l-41-51h8c22.6,0,41-18.4,41-41l0,0V41
	C538,18.4,519.6,0,497,0z M321,61V41h174v20H321z"/>
	<path id="XMLID_183_" fill="#FFFFFF" d="M635.5,41H821V0H607c-22.6,0-41,18.4-41,41v71l0,0c0,22.6,18.4,41,41,41h214v-41H635.5
	c-19.6,0-28.5-15.9-28.5-35.5C607,56.9,615.9,41,635.5,41z"/>
</svg>';
}

function raverrafting_logo() {
	return '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	viewBox="0 0 1228.9 219.5" enable-background="new 0 0 1228.9 219.5" xml:space="preserve">
	<g>
		<g>
			<path fill="#FFFFFF" d="M324,58.8c-11,0-21.2-0.1-31.5,0.1c-0.9,0-2.2,1.4-2.7,2.4c-8.3,16.8-16.5,33.7-24.8,50.6
			c-2.8,5.8-5.7,11.7-8.6,17.5c-0.4-0.1-0.4-0.1-0.9-0.1c-5.4-23.5-10.9-47-16.2-70.3H204c1.6,5.9,3,11.4,4.6,16.9
			c7.2,26.4,14.5,52.8,21.7,79.2c2.5,9.3,2.4,9.3,12.1,9.3H262c3.3,0,6.3-1.8,7.8-4.7C278.9,143.1,309.7,85.5,324,58.8z"/>
			<path fill="#FFFFFF" d="M353.9,122.1c1.2,8.7,4.5,15.5,12.5,18.4c13.8,5,24.6-0.8,34-11.1c6.8,5.6,13.6,11.1,20.6,16.9
			c-6.7,7.4-14.5,12.6-23.5,16.2c-14.9,5.9-29.9,6.6-45.1,1.3c-21.6-7.4-33.1-27.3-29.9-50.9c4-28.9,23.7-49.3,52-55.1
			c11.5-2.3,22.9-2.5,33.9,2.3c12.9,5.6,19.5,16,22.1,29.3c2,10.1,0.6,20.2-0.8,30.2c-0.4,2.7-2.1,2.6-4.1,2.6
			c-22.5,0-44.9,0-67.4,0C357.1,122.1,355.7,122.1,353.9,122.1z M399.4,99.4c1.1-7-1.2-13-6.2-16.4c-3.8-2.6-8-3.2-12.5-3
			c-11.1,0.5-22.1,9.6-23.2,19.4C371.5,99.4,385.3,99.4,399.4,99.4z"/>
			<path fill="#FFFFFF" d="M152.2,164.6c0.6-4.4,1-8.1,1.6-12.7c-1.2,1.1-1.9,1.6-2.4,2.2c-10.9,12.6-34.4,16.6-50.1,10
			c-13-5.5-18.7-17.4-16-31.6c3-15.8,13.5-24.3,27.5-29.4c9.2-3.4,18.8-4.9,28.7-5.1c6.5-0.1,13-0.6,19.5-0.9
			c1.8-5.6-0.9-12-6.7-15.4c-7.6-4.5-15.6-3.7-23.2-0.5c-5.7,2.4-10.7,6.2-16.5,9.6c-4.4-5.3-9.3-11.2-14.3-17.2
			c7.6-6.3,15.8-10.6,24.9-13.4c12.9-4,26.1-4.8,39.4-2.3c16.8,3.2,26.2,14.1,26.4,31.5c0.1,9.5-1.1,19-2.2,28.4
			c-1.7,14.3-4,28.5-5.8,42.8c-0.4,3.3-1.6,4.4-4.9,4.3C169.5,164.4,161,164.6,152.2,164.6z M157.8,117.9
			c-7.3,0.5-14.8,0.3-21.9,1.6c-5.2,1-10.4,3.4-15,6.2c-3.8,2.2-4.9,6.7-4.1,11c0.8,4.2,4,6.9,8.2,7.3c4.7,0.4,9.5,0.5,14-0.4
			C151.8,140.9,158.9,131.2,157.8,117.9z"/>
			<path fill="#FFFFFF" d="M0,164.4C5,129,9.8,94,14.8,59c11.1,0,21.6,0,32.7,0c-0.7,5.4-1.4,10.7-2.2,17.1
			C56,59.4,70.4,53.4,89.3,57.5c-1.4,9.9-2.7,19.7-4,29.3c-4.9-0.6-9.5-1.6-14.2-1.6c-17.5-0.2-27.6,8.2-30.5,25.7
			c-2.5,15.4-4.6,30.9-6.8,46.4c-1,7.3-0.9,7.3-8.4,7.3c-7,0-14.1,0-21.1,0C2.9,164.6,1.6,164.4,0,164.4z"/>
			<path fill="#FFFFFF" d="M455.6,59c11.1,0,21.6,0,32.6,0c-0.7,5.2-1.3,10.1-1.9,15c0.2,0.3,0.4,0.6,0.5,0.9
			c10.5-15.9,24.9-21.4,43.3-17.4c-1.4,10.1-2.8,19.8-4,28.7c-7-0.2-13.8-1-20.4-0.4c-15.9,1.3-21.8,12.1-23.8,23.1
			c-2.7,14.7-4.6,29.6-6.9,44.5c-0.2,1.2-0.4,2.5-0.6,3.7c-1.1,7.5-1.1,7.5-8.9,7.5c-8,0-16,0-24.8,0
			C445.8,129.1,450.7,94.2,455.6,59z"/>
		</g>
		<g>
			<path fill="#FFFFFF" d="M892.6,106.7c0.8-6.2,1.8-12.3,2.7-18.7c9.6,0,18.9,0,28.5,0c1.3-8.8,2.5-17.3,3.7-26.2
			c-9.7,0-19,0-28.5,0c1.4-10.7,2.8-20.9,4.2-31.1c-11.2,0-21.9,0-32.7,0c-1.5,10.5-2.9,20.7-4.4,31c-15,0-29.6,0-44.5,0
			c1-6.9,1.7-13.6,3-20.2c1.8-9.1,6.6-13,16-13.2c3.8,0,7.6,0.9,11.6,1.4c1.7-8.7,3.5-17.6,5.4-27.1c-13.1-2.9-26.5-4.1-39.5,0.6
			c-6.9,2.5-14.3,8.3-16.8,11.7c-10.5,14.1-9.6,31-12.6,47.1c-7.2,0-14.3,0-21.3,0c-1.2,8.9-2.4,17.2-3.6,26.2c7.6,0,14.4,0,21.4,0
			c-3.7,26.6-7.3,52.7-11,79.1c11,0,21.8,0,32.7,0c0.8-5.6,1.7-11,2.5-16.4c2.8-19.8,5.6-39.7,8.2-59.5c0.4-3,1.6-3.3,4.1-3.3
			c12.4,0.1,24.9,0,37.3,0.1c1.1,0,2.2,0.2,3.6,0.3c-0.8,5.7-1.5,11-2.2,16.3c-1.7,12.5-3.7,25-5,37.6c-1.3,12.3,5.5,21.9,17.2,25.4
			c12.7,3.8,25.5,3.1,38.2-0.2c1.1-0.3,2.3-2,2.6-3.3c0.7-2.9,1-5.9,1.4-8.9c0.6-5,1.2-10,1.8-14.3c-5.3,0.5-10.2,1.6-15.1,1.4
			c-8.6-0.3-11.9-4.1-11.3-12.8C890.4,121.9,891.6,114.3,892.6,106.7z"/>
			<path fill="#FFFFFF" d="M1196.8,74.6c0.6-4.2,1.3-8.3,1.9-12.5c10.2,0,20,0,30.2,0c-2.7,18.8-5.4,37.4-8.1,56
			c-2.1,14.9-3.8,29.8-6.3,44.6c-3.9,22.8-13.9,41.6-36.4,51.1c-13.5,5.7-27.7,6.4-42.1,5.3c-10.6-0.8-21.2-2.4-30.7-7.7
			c-3.6-2-6.9-4.5-10.7-7c7.5-9.3,14.6-18.1,22-27.3c1.6,1.3,3.1,2.5,4.6,3.7c11.8,9.4,25.1,12.1,39.6,8.4
			c11.7-3,17.9-11.3,20.5-22.7c0.8-3.4,1.1-6.9,1.7-11.2c-1.6,1.4-2.7,2.3-3.7,3.2c-20.2,17.7-55.1,12.1-67.6-10.8
			c-5.7-10.5-6.8-21.9-4.8-33.6c3.5-20.7,12.9-37.7,31.5-48.3c16.3-9.3,33-9.3,49.2,0.5C1190.9,68.2,1193.4,71.5,1196.8,74.6z
			M1161.8,139.7c12.2,0,24.7-9.1,28.5-20.9c5.6-17.3-6.5-32.3-24.6-30.5c-11.7,1.2-22.9,11.5-25.7,23.7
			C1136.5,126.8,1146.7,139.7,1161.8,139.7z"/>
			<path fill="#FFFFFF" d="M1082.6,167.2c-10.8,0-21.5,0-32.6,0c1-7.2,1.9-14.1,2.8-21c1.8-13.6,3.6-27.3,5.3-40.9
			c0.3-2.4,0-5-0.4-7.5c-1-5.6-4.9-9-10.6-9.6c-12.2-1.3-21.3,4.6-23.9,16.7c-2.3,10.9-3.7,21.9-5.3,32.9
			c-1.4,9.6-2.7,19.3-4.1,29.3c-10.8,0-21.7,0-32.7,0c1.3-9.4,2.5-18.7,3.8-27.9c2.5-17.6,4.9-35.2,7.4-52.8c1-7.3,2-14.6,3.1-21.9
			c0.2-1,1.5-2.5,2.3-2.5c9.6-0.2,19.3-0.1,29.4-0.1c-0.5,4.6-1,8.8-1.6,13.8c5.8-7.2,12.3-12,20.4-14.6c10.2-3.2,20.3-2.9,30.2,0.9
			c10.2,4,15.1,12.5,15.5,22.9c0.3,10.1-0.6,20.2-1.7,30.3c-1.8,15.9-4.2,31.7-6.4,47.6C1083.2,164.3,1082.9,165.7,1082.6,167.2z"/>
			<path fill="#FFFFFF" d="M716.1,155.8c-9.4,9.8-21,13.6-33.8,14.1c-14.4,0.6-28.8-4.6-33.7-18.5c-4.2-11.8,0.7-28.4,10.6-36.5
			c11.1-9.1,24.3-12.6,38.3-13.7c8.7-0.7,17.5-0.9,26.2-1.3c2.2-9.2-5.2-17.7-15.5-18.3c-10.5-0.6-19.3,3.3-27.2,9.8
			c-1,0.8-1.9,1.6-3.2,2.7c-5-6-9.8-11.8-14.8-17.9c7.6-6.4,16-10.8,25.2-13.5c13.8-4.1,27.8-5,41.9-1.5c16,4.1,23.2,15.2,23.5,30.4
			c0.2,10-1.1,20-2.2,29.9c-1.7,14.2-3.9,28.3-5.8,42.4c-0.3,2.7-1.1,3.6-3.9,3.6c-8.8-0.2-17.5-0.1-26.7-0.1
			C715.2,163.3,715.7,159.6,716.1,155.8z M720.5,121.6c-6.3,0-12.5-0.3-18.5,0.1c-6.6,0.5-13.1,2.1-18.5,6.5
			c-3.6,2.9-5.1,6.9-4.2,11.4c0.9,4.3,4.3,6.8,8.5,7.4c3.6,0.5,7.5,0.6,11.1,0C710.7,144.9,721.2,137.9,720.5,121.6z"/>
			<path fill="#FFFFFF" d="M651.9,60.3c-1.4,9.9-2.7,19.6-4.1,29.6c-2.2-0.4-4.2-1.1-6.2-1.1c-6.5,0-13.1-0.6-19.4,0.8
			c-9.8,2.1-15.5,9.5-17.7,19c-2.3,10.1-3.7,20.4-5.3,30.6c-1.4,9.2-2.6,18.5-3.9,28c-10.9,0-21.7,0-32.7,0
			c4.9-35.1,9.8-70,14.7-105.1c10.9,0,21.6,0,32.7,0c-0.7,5.3-1.3,10.3-2.1,16.7C618.8,62.2,633.1,56.2,651.9,60.3z"/>
			<path fill="#FFFFFF" d="M960.1,167.3c-11,0-21.5,0-32.8,0c4.9-35.1,9.8-70,14.7-105.2c11,0,21.8,0,32.8,0
			C969.9,97.2,965,132.1,960.1,167.3z"/>
			<path fill="#FFFFFF" d="M960.6,47.7c-11,0.1-18.6-9.4-16.3-20.3c2-9.8,11.6-17.7,21.6-17.8c12.2-0.1,19.7,11.9,15,24.2
			C977.7,41.6,969,47.6,960.6,47.7z"/>
		</g>
	</g>
</svg>
';

}

function beatmersive_logo() {
	return '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	viewBox="-623 731.9 252.9 39.1" enable-background="new -623 731.9 252.9 39.1" xml:space="preserve">
	<g id="XMLID_1_">
		<polygon id="XMLID_15_" fill="#00E2CF" points="-532.7,770.3 -545.6,738.8 -545.6,738.8 -548.2,732.7 -563.3,770.3 -558.3,770.3 
		-548.1,745 -537.7,770.3 	"/>
		<polygon id="XMLID_14_" fill="#00E2CF" points="-591.7,732.7 -568.1,732.6 -568.1,737.3 -587.1,737.3 -587.1,749.2 -575.2,749.2 
		-575.2,753.8 -587.1,753.8 -587.1,765.6 -568.1,765.6 -568.1,770.3 -591.7,770.3 	"/>
		<path fill="#00E2CF" d="M-598.7,759.7c0,5.8-4.7,10.5-10.6,10.5h-13.1v-21h6.2l-6.8-16.5h11.9c5.9,0,10.6,4.7,10.6,10.6
		c0,3.3-1.8,6.3-4.7,7.2C-601.3,751.5-598.7,755.7-598.7,759.7 M-605.2,743.1c0-3.3-2.6-5.9-5.9-5.9h-5l4.9,12
		C-607.9,749.2-605.2,746.4-605.2,743.1 M-603.5,759.8c0-3.3-2.6-6-5.9-6h-8.1v12h8.2C-606.1,765.7-603.5,763-603.5,759.8"/>
		<polygon id="XMLID_10_" fill="#00E2CF" points="-523,764.8 -518.3,755.7 -518.3,737.4 -509,737.4 -506.6,732.7 -535.7,732.7 
		-535.7,737.4 -523,737.4 	"/>
		<path id="XMLID_9_" fill="#FFFFFF" d="M-454.6,737.4"/>
		<rect id="XMLID_8_" x="-414.7" y="732.7" fill="#FFFFFF" width="4.7" height="37.6"/>
		<polygon id="XMLID_7_" fill="#FFFFFF" points="-391.8,732.7 -397.1,752.2 -402.4,732.7 -407.3,732.7 -399.6,761.3 -399.6,761.3 
		-397.1,770.3 -397.1,770.3 -397.1,770.3 -394.7,761.3 -394.7,761.3 -386.9,732.7 	"/>
		<path id="XMLID_6_" fill="#FFFFFF" d="M-426.5,748.1c-2.4-0.6-4.3-2.8-4.3-5.3c0-3,2.5-5.5,5.5-5.5c1.9,0,3.6,1,4.6,2.6v-6.2
		c-1.5-0.7-3-1.1-4.6-1.1c-5.6,0-10.2,4.6-10.2,10.2c0,4.9,3.4,8.9,8,10c3,0.7,5.3,3.4,5.3,6.7c0,3.8-3.1,6.9-6.9,6.9
		c-2.6,0-4.9-1.4-6.1-3.7v6.7c1.8,1.1,3.9,1.7,6.1,1.7c6.4,0,11.5-5.2,11.5-11.6c0-3.1-1.2-6-3.4-8.2
		C-422.5,749.6-424.4,748.6-426.5,748.1"/>
		<polygon id="XMLID_5_" fill="#FFFFFF" points="-474,759.9 -488.2,731.9 -495.8,746.9 -503.4,731.9 -523,770.3 -517.7,770.3 
		-503.4,742.3 -498.5,752.1 -507.7,770.3 -502.4,770.3 -495.8,757.4 -489.3,770.3 -484,770.3 -493.2,752.1 -488.2,742.3 -474,770.3 
		-468.8,770.3 -459.4,770.3 -459.4,765.6 -469.4,765.6 -469.4,753.9 -462.8,753.9 -462.8,749.1 -469.4,749.1 -469.4,737.3 
		-459.4,737.3 -459.4,732.7 -474,732.7 	"/>
		<polygon id="XMLID_4_" fill="#FFFFFF" points="-370.1,737.3 -370.1,732.7 -384.8,732.7 -384.8,770.3 -370.1,770.3 -370.1,765.6 
		-380.1,765.6 -380.1,753.9 -373.6,753.9 -373.6,749.1 -380.1,749.1 -380.1,737.3 	"/>
		<path fill="#FFFFFF" d="M-437.4,747c0-3.8-1.5-7.5-4.2-10.1c-2.7-2.7-6.3-4.2-10.2-4.2h-4.5v37.7h4.7v-8.9h0.8l7,8.9h6l-8.1-10.2
		c1.6-0.7,3-1.7,4.2-2.9C-438.9,754.5-437.4,750.9-437.4,747 M-451.6,756.7v-19.3c5.3,0.1,9.4,4.4,9.4,9.7S-446.3,756.6-451.6,756.7
		"/>
	</g>
</svg>';

}

?>