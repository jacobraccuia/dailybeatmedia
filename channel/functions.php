<?php



// includes folder
foreach(glob(get_template_directory() . '/includes/*.php') as $filename) {
	require_once($filename);
}

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
	wp_enqueue_style('channel_css', get_stylesheet_uri());
	wp_add_inline_style('channel_css', db_theme_css());
}


function db_theme_css() {

	$theme_color_light = zuko_get_options('theme_color_light');
	$theme_color_dark = zuko_get_options('theme_color_dark');

	if($theme_color_dark == '' || $theme_color_light == '') { return; }
	
	ob_start();

	?>

	/* set gradients */
	.nav-border { background:linear-gradient(to right, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }
	#page-progress { background:linear-gradient(to right, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }
	#veggie.menu-active { background:<?php echo $theme_color_light; ?>; background-image:linear-gradient(to bottom, <?php echo $theme_color_dark; ?>, <?php echo $theme_color_light; ?>); }

	#cabinet h2 span { color:<?php echo $theme_color_light; ?>; background:-webkit-gradient(linear, 0 0, 100% 100%, from(<?php echo $theme_color_light; ?>), to(<?php echo $theme_color_dark; ?>)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }

	#player_button:hover, #player_button.open { background:$blue; background-image:linear-gradient(to bottom, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }
	.h-icon.divider { background:<?php echo $theme_color_dark; ?>; background-image:linear-gradient(to bottom, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }
	.footer-border { background-color:<?php echo $theme_color_light; ?>; background:linear-gradient(to right, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }
	footer .divider { background:linear-gradient(to right, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }

	.col-header .bar { background-color:<?php echo $theme_color_light; ?>; background:linear-gradient(to right, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }
	.col-header .bar.right { background-color:<?php echo $theme_color_light; ?>; background:linear-gradient(to left, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }
	
	<?php
	$results = ob_get_contents();
	ob_end_clean();
	return $results;
}

function category_filter_posts() {

	$terms = get_terms('category', array('parent' => 0, 'hide_empty' => 0));

	$i = 0; 
	$class = 'active';
	echo '<ul class="category-picker">';

	foreach($terms as $k => $term) {
		if($term->slug == 'uncategorized') { unset($terms[$k]); continue; }

		if($i > 0) { $class = '';}
		$i = 1; 
		echo '<li data-id="' . $term->term_id . '" class="' . $class . '">' . $term->name . '</li>';
	}

	echo '</ul>';

	echo '<div class="category-post-wrapper">';

	$i = 0;
	$style = '';
	foreach($terms as $term) {
		if($i > 0) { $style = 'style="display:none;"'; }
		$i = 1;

		echo '<div class="row post-wrapper standard-wrapper" ' . $style . ' data-section-id="' . $term->term_id .'">';
		get_standard_posts(array('category' => $term->term_id, 'blazy' => false, 'posts_per_page' => 6));
		echo '</div>';	
	}

	echo '</div>';

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