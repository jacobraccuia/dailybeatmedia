<?php

require_once('includes/wp_bootstrap_navwalker.php');

// remove meta_head generator
remove_action('wp_head', 'wp_generator');

// add all appropriate styles and scripts
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');
function my_enqueue_scripts() {

	
	wp_enqueue_style('google-fonts', 'http://fonts.googleapis.com/css?family=Open+Sans:400,700');
	
	wp_enqueue_script('my-ajax-request', THEME_DIR . '/js/ajax.js', array('jquery'));
	wp_localize_script('my-ajax-request', 'MyAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('my-ajax-request', 'MyAjax', array('ajaxurl' => admin_url('admin-ajax.php'), 'postCommentNonce' => wp_create_nonce('myajax-post-comment-nonce'),));
				
	wp_enqueue_script('marquee_js', THEME_DIR . '/js/marquee.js', array('jquery'));
	wp_enqueue_script('spin_js', THEME_DIR . '/js/spin.js', array('jquery'));

	wp_enqueue_script('slider_js', THEME_DIR . '/js/responsive.slider.min.js', array('jquery'));
 	wp_enqueue_style('slider_css', THEME_DIR . '/css/slider.css');
 	
	wp_enqueue_script('bootstrap_js', THEME_DIR . '/bootstrap/bootstrap.min.js', array('jquery'));
	wp_enqueue_style('bootstrap_css', THEME_DIR . '/bootstrap/bootstrap.min.css');
	wp_enqueue_script('underscore'); //', THEME_DIR . '/bootstrap/bootstrap.min.js', array('jquery'));
	
	//wp_enqueue_script('autoellipsis', THEME_DIR . '/js/jquery.autoellipsis.min.js', array('jquery'));

	// soundcloud shit
	wp_enqueue_script('waveform_js', THEME_DIR . '/soundcloud/waveform.js', array('jquery'));
	wp_enqueue_script('soundcloud_api_js', THEME_DIR . '/soundcloud/soundcloud.player.api.js', array('jquery'));
	wp_enqueue_script('soundcloud_player_js', THEME_DIR . '/soundcloud/sc-player.js', array('jquery'));
	wp_enqueue_style('soundcloud_css', THEME_DIR . '/soundcloud/sc-player.css');
	
	wp_enqueue_script('waypoints', get_template_directory_uri() . '/js/waypoints.min.js');
	wp_enqueue_script('waypoints-sticky', get_template_directory_uri() . '/js/waypoints-sticky.min.js');
	
	wp_enqueue_script('jacob_js', THEME_DIR . '/js/scripts.js', array('jquery'));
	
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
	
	
	$blog_id = get_current_blog_id();
	if($blog_id == 2) {
		//wp_enqueue_script('youtube', '//youtube.com/iframe_api');
		wp_enqueue_script('fullscreenvid', THEME_DIR . '/js/jquery.tubular.1.0.js', array('jquery'));
	}
	
	
	wp_enqueue_style('jacob_css', THEME_DIR . '/style.css');
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
add_action('init', 'db_featured_premiere_cpt', 1);	
function db_featured_premiere_cpt() {
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
		'menu_icon' => null,
		'rewrite' => array('slug' => 'dbfirst'),
		'capability_type' => 'page',
		'has_archive' => true, 
		'hierarchical' => true,
		'menu_position' => null,
		'supports' => array('title', 'thumbnail', 'editor', 'excerpt', 'page-attributes', 'comments')
	); 

	register_post_type('premiere', $args);
}



function featured_nav() { ?>
	<div class="container visible-md visible-lg">
    	<div class="featured_nav_wrapper">
        <?php 
			wp_nav_menu(array(
				'theme_location'	=> 'featured_menu',
				'depth'             => 1,
			));
		?>
        </div>
    </div>
    	
<?php 
}
// add featured text prior to menu print
add_filter('wp_nav_menu_items', 'pre_featured_menu', 10, 2 );
function pre_featured_menu ($items, $args) {
    if($args->theme_location == 'featured_menu') {
        $items = '<li><strong>Weekly Features:</strong></li>' . $items;
    }
    return $items;
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
}

// display form field
function db_meta($post) {
	$db_soundcloud = get_post_meta(get_the_ID(), 'db_soundcloud', true);
	$db_soundcloud_color = get_post_meta(get_the_ID(), 'db_soundcloud_color', true);
	$db_featured_title = get_post_meta(get_the_ID(), 'db_featured_title', true);
	$db_premiere_title = get_post_meta(get_the_ID(), 'db_premiere_title', true);
	$db_ad_size = get_post_meta(get_the_ID(), 'db_ad_size', true);
?>
<style>
#category-adder h4 { display:none; }
#postexcerpt { display:none; }

.role-administrator #category-adder h4, .role-administrator #postexcerpt { display:block; } 

</style>
	<div class="field">
		<label for="soundcloud">SoundCloud Link:</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_soundcloud" type="text" value="<?php echo $db_soundcloud; ?>" />
	</div>
	<div class="field">
		<label for="soundcloud">SoundCloud HEX Color (# is required):</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_soundcloud_color" type="text" value="<?php echo $db_soundcloud_color; ?>" />
	</div>
	<div class="field">
		<label for="soundcloud">Featured Title (optional):</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_featured_title" type="text" value="<?php echo $db_featured_title; ?>" />
	</div>
	<div class="field">
		<label for="soundcloud">Premiere Title (optional):</label>
		<input style="padding:5px; width:700px;" autocomplete="off" class="" name="db_premiere_title" type="text" value="<?php echo $db_premiere_title; ?>" />
	</div>
	<div class="field">
		<label for="ads">Ad Size (optional):</label>
		<select name="db_ad_size" style="padding:5px; width:700px;">
			<option value="300x250" <?php if($db_ad_size == "300x250") echo "selected"; ?>>300 x 250 (square)</option>
			<option value="300x600" <?php if($db_ad_size == "300x600") echo "selected"; ?>>300 x 600 (rectangle)</option>
		</select>
	</div>
	
<?php }

// save field
add_action('save_post', 'db_save_post');
function db_save_post() {
	global $post;
	if(isset($_POST['db_soundcloud'])) {
		update_post_meta($post->ID, 'db_soundcloud', trim($_POST['db_soundcloud']));
	}
	if(isset($_POST['db_featured_title'])) {
		update_post_meta($post->ID, 'db_featured_title', trim($_POST['db_featured_title']));
	}
	if(isset($_POST['db_premiere_title'])) {
		update_post_meta($post->ID, 'db_premiere_title', trim($_POST['db_premiere_title']));
	}
	if(isset($_POST['db_soundcloud_color'])) {
		update_post_meta($post->ID, 'db_soundcloud_color', trim($_POST['db_soundcloud_color']));
	}
	if(isset($_POST['db_ad_size'])) {
		update_post_meta($post->ID, 'db_ad_size', trim($_POST['db_ad_size']));
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

// important, but does it belong in functions.php?
global $exclude_posts;
$exclude_posts = array();

// standard post layout
function standard_post($cat = 0) {
		global $exclude_posts;
		$id = get_the_ID();
		$exclude_posts = array_merge(array($id), $exclude_posts);

		$db_soundcloud = get_post_meta($id, 'db_soundcloud', true);
		$category = get_the_terms($id, 'category');		
		
		$song_title = get_the_title();
	//echo $cat . '<pre>'; print_r($category);	
		if(isset($cat) && $cat > 0 && isset($category[$cat])) {
			move_to_top($category, $cat);	//		$category = array('one' => $myArray['one']) + $myArray;
		}
		
		
		$image_size = array(400,400);
			
		$thumb_id = get_post_thumbnail_id();
		$thumb_url = wp_get_attachment_image_src($thumb_id, $image_size, true);
		$thumb_url = $thumb_url[0];
		
		?>
	
		<div class="post_wrapper">
			<div class="post row query standard">
				<div class="featured col-xs-4 tight tight-left">
					<a href="<?php the_permalink(); ?>"><div class="featured-image" style="background-image:url('<?php echo $thumb_url; ?>');"></div></a>
				</div>
				<div class="content col-xs-8 tight tight-right">   
					<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<ul class="meta">
                    	<li class="author">
                        	<span class="glyphicon glyphicon-user"></span>
                        	<?php the_author_posts_link(); ?>
                        </li>
                        <li class="date">
							<span class="glyphicon glyphicon-calendar"></span>
							<?php echo the_timestamp_short(); ?>
                        </li>
						<li class="category">
							<span class="glyphicon glyphicon-align-left"></span>
						<?php // display 3 'random' categories
							$i = 0;
							$c = count($category);
							foreach($category as $term) {
								echo '<a href="' . get_term_link($term->slug, 'category') . '">';
									echo $term->name;
		//							if($i < 3) { echo ","; }
								echo '</a>';
								if($i == 2 or $i == $c - 1) { break; } else { echo ",&nbsp;"; }
								$i++;
							}
						?>
						</li>
                    </ul>
					<div class="excerpt">
						<?php echo which_excerpt(get_post_time(), 180); // 180 char cutoff ?>
					</div>
                    <div class="bar row">
						<div class="read fixed super_tight">
                        	<h4 class="read_more"><a href="<?php the_permalink(); ?>">Read More</a></h4>
						</div>
						<div class="player super_tight">
                        	<?php if(isset($db_soundcloud) && !empty($db_soundcloud)) { 
                        		soundcloud_player($db_soundcloud, $song_title);
							} ?>
                    	</div>
					</div>
				</div>
			</div>
		</div>

	
<?php 
}

// full post layout
function full_post() {
	global $exclude_posts;
	$id = get_the_ID();
	$exclude_posts = array_merge(array($id), $exclude_posts);
	
	$title = get_the_title();
	$new_title = get_post_meta($id, 'db_featured_title', true);
					
	if(!empty($new_title)) {
		$title = $new_title;	
	}
	
?>

<div class="post_wrapper">
    <div class="post query full">
        <a href="<?php the_permalink() ?>">
        <div class="featured">
            <?php if(has_post_thumbnail()) { the_post_thumbnail(array(750,400)); } ?>
            <div class="caption_wrapper">
                <h1 class="caption"><?php echo $title; ?></h1>
            </div>
        </div>
        </a>
    </div>
</div>

<?php
}

// sidebar post layout
function sidebar_post($i) {
	global $exclude_posts;
	$id = get_the_ID();
	$exclude_posts = array_merge(array($id), $exclude_posts);

	$category = get_the_category($id);		
	shuffle($category);
	
	$k = "right";
	if($i % 2 != 0) { $k = "left"; }
?>

<div class="post_wrapper">

    <div class="post related query full <?php echo $k; ?>">
        <a href="<?php the_permalink() ?>">
        <div class="featured">
            <?php if(has_post_thumbnail()) { the_post_thumbnail(array(300,300)); } ?>
        </div>
        <div class="category_tag_wrapper">
        	<div class="category_tag"><?php echo $category[0]->cat_name; ?></div>
        </div>
        <h4 class="title"><?php the_title(); ?></h4>
		</a>
    </div>
</div>

<?php
}

// related post layout
function related_post($i = 1, $include_cat_hover = 1, $exclude_posts = true) {
	global $exclude_posts;
	$id = get_the_ID();
	if($exclude_posts) { $exclude_posts = array_merge(array($id), $exclude_posts); }
	
	$category = get_the_category($id);		
	shuffle($category);
	
	$k = "right";
	if($i % 2 != 0) { $k = "left"; }
?>
        <div class="col-sm-4">
            <div class="post_wrapper">
            
                <div class="post related query full <?php echo $k; ?>">
                    <a href="<?php the_permalink() ?>">
                    <div class="featured">
                        <?php if(has_post_thumbnail()) { the_post_thumbnail(array(360,360)); } ?>
                    </div>
                    <?php if($include_cat_hover == 1) { ?>
						<div class="category_tag_wrapper">
                        	<div class="category_tag"><?php echo $category[0]->cat_name; ?></div>
                    	</div>
					<?php } ?>
                    <h4 class="title"><?php the_title(); ?></h4>
                    </a>
                </div>
            </div>
		</div>
<?php
}

function get_category_posts($category = 0, $offset = 0) {
	global $exclude_posts;

	$args = array(
		'posts_per_page' => 15,
		'post_type' => array('post', 'premiere'),
		'post_status' => 'publish',
		'cat' => $category,
		'offset' => $offset
	);
	
	query_posts($args);
	
	if(have_posts()): while(have_posts()): the_post();
	
		if(has_category(2545)) {
			echo full_post();
		}
		else {
			echo standard_post($category);
		}
		
	endwhile; endif;

}

function get_search_posts($search_str = "", $offset = 0) {
	global $exclude_posts;
	global $query_string;

	$query_args = explode("&", $query_string);
	$search_query = array();
	
	if(!empty($search_str) && $search_str != "") {
		$search_query = array('s' => $search_str);
	} else {
		foreach($query_args as $key => $string) {
			$query_split = explode("=", $string);
			$search_query[$query_split[0]] = urldecode($query_split[1]);
		} // foreach
	}

	$args = array(
		'posts_per_page' => 15,
		'post_type' => array('post', 'premiere'),
		'post_status' => 'publish',
	//	'post__not_in' => $exclude_posts,
		'offset' => $offset
	);
	
	$search_query = array_merge($search_query, $args);
	$my_search = new WP_Query($search_query);
	
	if($my_search->have_posts()) : while($my_search->have_posts()): $my_search->the_post();
	
		if(has_category(2545)) {
			echo full_post();
		}
		else {
			echo standard_post();
		}
		
	endwhile; else: ?>
    	<p style="text-align:center; margin:10px auto;" class="no-results">Your search returned no results.</p>
    <?php
	endif;

}

function get_homepage_posts($offset = 0, $full_offset = 0) { 
	global $exclude_posts;
	
	$args = array(
		'posts_per_page' => 10,
		'cat' => '-3013, -3014', 
		'post_status' => 'publish',
		'post_type' => array('post', 'premiere'),
	//	'post__not_in' => $exclude_posts,
		'offset' => $offset
	);
	$my_query = new WP_Query($args);
	
	$i = 1;
	while($my_query->have_posts()) : $my_query->the_post();
		echo standard_post();
		$i++;
		
		if($i == 3 || $i == 7 || $i == 10) {
			$args = array(
				'posts_per_page' => 1,
				'cat' => '3014',
				'post_type' => array('post', 'premiere'),
				'post_status' => 'publish',
			//	'post__not_in' => $exclude_posts,
				'offset' => $full_offset
			);
			$featured_query = new WP_Query($args);
			while($featured_query->have_posts()) : $featured_query->the_post();
				echo full_post();
			endwhile;
			
			$full_offset++; // increase offset counter
		}
			
	endwhile;
}

function get_related_posts($related_categories = "", $exclude = array()) {
	global $exclude_posts;
	$exclude_posts = array_merge($exclude, $exclude_posts);
	$i = $k = $x = 0;
	while($i < count($related_categories)) {
	
		// if need be, loop through categories and drop the category with the fewest posts
		if($k > 0 && $x == 0) {
			$categories_to_drop = array();
			foreach($related_categories as $key => $cat) {
				$terms = $term = get_term_by('id', $cat, 'category');
				$categories_to_drop[$terms->count] = $cat;
			}
			$x++;
			ksort($categories_to_drop);
			array_shift($categories_to_drop);
		} else if($i > 0) {
			array_shift($categories_to_drop);	
		}
		
		if($i == 0) { // on first iteration.. otherwise, above code creates new category array
			$categories_to_drop = $related_categories;
		}
		
		$args = array(
			'posts_per_page' => 3,
			'post_type' => array('post', 'premiere'),
			//'cat' => '-3013, -3014',
			'category__and' => $categories_to_drop,
			'post_status' => 'publish',
			'post__not_in' => $exclude_posts
		);
		
		$related_query = new WP_Query($args);
		while($related_query->have_posts()) : $related_query->the_post();
			echo related_post($k);
			$k++;
			if($k == 3) { break 2; }
		endwhile;
	$i++;
	}
}

function get_author_posts($author_id = 0, $exclude = array(), $offset = 0, $source = "") {
	global $exclude_posts;
	$exclude_posts = array_merge($exclude, $exclude_posts);
	
		if($source == 'author_page') {
			$posts_per_page = 15;
			$categories_to_exclude = '';
		} else { 
			$posts_per_page = 3;
//			$categories_to_exclude = '-3013, -3014';
			$categories_to_exclude = '';
		}
	
		$args = array(
			'posts_per_page' => $posts_per_page,
			'cat' => $categories_to_exclude,
			'post_type' => array('post', 'premiere'),
			'post_status' => 'publish',
			'author' => $author_id,
			'offset' => $offset,
			'post__not_in' => $exclude_posts
		);
		$k = 0;
		$related_query = new WP_Query($args);
		
		while($related_query->have_posts()) : $related_query->the_post();
			if($source == 'author_page') {
				if(has_category(2545)) {
					echo full_post();
				} else echo standard_post();
			} else { 
				echo related_post($k);
			}
			$k++;
		endwhile;
}

add_action('wp_ajax_load_posts', 'aj_load_posts');
add_action('wp_ajax_nopriv_load_posts', 'aj_load_posts');
function aj_load_posts() {
	$nonce = $_POST['postCommentNonce'];
	if(!wp_verify_nonce($nonce, 'myajax-post-comment-nonce')) { die('Busted!'); }
	
	$clicks = $_POST['clicks']; // how many times has the button been pressed?
	$page_type = $_POST['page'];
	
	$search_str = $_POST['search_str'];
	$author_id = $_POST['author_id'];
	
	$category = $_POST['category'];
	
	$offset = $clicks * 10;
	$full_offset = $clicks * 3; 
	
	if(isset($page_type) && $page_type == "home") {
		echo get_homepage_posts($offset, $full_offset);
		exit;
	} else if(isset($page_type) && $page_type == "category") {
		$offset = $clicks * 15;
		echo get_category_posts($category, $offset);
		exit;
	} else if(isset($page_type) && $page_type == "search") {
		$offset = $clicks * 15;
		echo get_search_posts($search_str, $offset);
		exit;
	} else if(isset($page_type) && $page_type == "author") {
		$offset = $clicks * 15;
		echo get_author_posts($author_id, array(), $offset, 'author_page');
		exit;
	} else {
		echo 'invalid post type!';
		exit;	
	}

}
//function featured



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

add_action('wp_footer', 'add_zuus_to_footer');
function add_zuus_to_footer() {
	echo '<div class="zuus_overlay"><div class="zuus_content"><div id="zuus-widget"></div></div>
		<div class="close">close</div>
	</div>';	
}

add_filter('the_content', 'remove_spaces');
function remove_spaces($the_content) {
    return preg_replace('/[\p{Z}\s]{2,}/u', ' ', $the_content);
}

function get_top_category($catid) {
	while ($catid) {
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



function author_biography($author_id = 0) {

	if($author_id == 0) {
		$author_id = get_the_author_meta('ID');
	}

	$author_username = get_the_author_meta('display_name', $author_id);
			
?>
<div class="author_bio_wrapper">
	<div class="row">
		<div class="col-xs-2 tight-right">
			<div class="author_photo color_overlay"><a href="<?php echo get_author_posts_url($author_id); ?>"><?php echo get_wp_user_avatar($author_id, 'medium'); ?></a></div>
		</div>
		<div class="col-xs-10">
			<div class="author_title"><h3 class="section_title"><strong><?php echo $author_username; ?></strong></h3></div>
			<div class="author_connect">
				<ul>
				<?php if(get_the_author_meta('twitter', $author_id) != "") { ?>
					<li class="twitter_bio"><a href="http://twitter.com/intent/user?screen_name=<?php echo get_the_author_meta('twitter', $author_id); ?>"></a></li>
				<?php } if(get_the_author_meta('linkedin', $author_id) != "") { ?>
					<li class="linkedin_bio"><a href="<?php echo get_the_author_meta('linkedin', $author_id); ?>" target="_blank"></a></li>
				<?php } ?>
				</ul>
			</div>
			<?php if(get_the_author_meta('job_title', $author_id) != "") { ?>
				<div class="author_job_title"><?php echo get_the_author_meta('job_title', $author_id); ?></div>
			<?php } if(get_the_author_meta('description', $author_id) != "") { ?>
				<div class="author_biography"><?php echo get_the_author_meta('description', $author_id); ?></div>
			<?php } ?>
		</div>	
	</div>
	</div>
<?php	
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


if ( current_user_can('contributor') && !current_user_can('upload_files') )
	add_action('admin_init', 'allow_contributor_uploads');
 
function allow_contributor_uploads() {
	$contributor = get_role('contributor');
	$contributor->add_cap('upload_files');
}


add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10, 3);
function remove_thumbnail_dimensions($html, $post_id, $post_image_id) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// which excerpt function should we call?
function which_excerpt($time, $count) {
	if($time < 1388179329) { // random unix date after latest post and before launch.
		return char_based_excerpt($count);
	} else {
		return char_based_excerpt($count);
	}
}

function the_timestamp_short() { the_time('M. j'); }
function the_timestamp() { echo " on "; the_time('F j, Y'); echo " at "; the_time('g:i a'); echo " EST"; }


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