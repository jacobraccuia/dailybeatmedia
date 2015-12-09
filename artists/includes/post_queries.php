<?php

require_once('post_types.php');


// all wp_query calls

function get_standard_loop($args = array()) {
	$defaults = array(
		'loops' => '1',
		);

	// merge arguments with defaults && set keys as variables
	$args = array_merge($defaults, $args);
	foreach($args as $key => $val) { ${$key} = $val; }

	$i = 0;
	while($i < $loops) {
		?>
		<div class="row post-wrapper full-width-wrapper video-wrapper">
			<?php get_video_post(); ?>
		</div>
		<div class="row post-wrapper vintage-wrapper">
			<?php get_vintage_posts(); ?>
		</div>
		<div class="row post-wrapper vintage-wrapper">
			<?php get_vintage_posts(); ?>
		</div>
		<div class="row post-wrapper full-width-wrapper full-story-wrapper">
			<?php get_full_story_post(); ?>
		</div>
		<div class="row post-wrapper trending-wrapper">
			<?php get_standard_post_feature(); ?>
		</div>
		<?php /* <div class="row post-wrapper standard-wrapper">
			<?php get_standard_posts(); ?>
		</div>
		<div class="row post-wrapper full-width-wrapper video-wrapper">
			<?php get_video_post(); ?>
		</div>
		<div class="row post-wrapper trending-wrapper">
			<?php get_standard_post_feature(array('swap_columns' => true)); ?>
		</div> */ ?>
		<?php
		$i++;
	}
}


function get_standard_posts($args = array()) {
	global $post, $exclude_posts;

	$defaults = array(
		'tags' => '',
		'trending_logo' => '',
		);

	// merge arguments with defaults && set keys as variables
	$args = array_merge($defaults, $args);
	foreach($args as $key => $val) { ${$key} = $val; }

	$args = array(
		'posts_per_page' => 3,
		'post_status' => 'publish',
		'post__not_in' => $exclude_posts,
		'post_type' => 'post',
		'tag' => $tags,
		'orderby' => 'date',
		'order' => 'DESC',
		);


	$query = new WP_Query();
	$network_query = $query->posts = network_query_posts($args);
	$post_count = $query->post_count = count($network_query);

	$i = 1;
	while($query->have_posts()) { $query->the_post();
		$blogID = $post->BLOG_ID;

		echo '<div class="col-md-4">';
		echo classic_post($blogID, array('show_via' => true));
		echo '</div>';

		$i++;
	}
}

function get_brand_posts($blog_name) {
	global $exclude_posts;

	$blogID = get_blog_by_name($blog_name);
	switch_to_blog($blogID);

	$exclude = $exclude_posts[$blogID];
	$args = array(
		'posts_per_page' => 3,
		'post_status' => 'publish',
		'post__not_in' => $exclude,
		'post_type' => 'post',
		);

	$featured_query = new WP_Query($args);
	while($featured_query->have_posts()) { $featured_query->the_post();
		echo '<div class="col-md-4">';
		echo classic_post($blogID, array('show_via' => false));
		echo '</div>';
	}

	reset_blog();
}


function get_vintage_posts($args = array()) {
	global $post, $exclude_posts;

	$defaults = array(
		'posts_per_page' => 1,
		'tags' => ''
		);

	// merge arguments with defaults && set keys as variables
	$args = array_merge($defaults, $args);
	foreach($args as $key => $val) { ${$key} = $val; }

	$args = array(
		'posts_per_page' => $posts_per_page,
		'post_status' => 'publish',
		'post__not_in' => $exclude_posts,
		'post_type' => 'post',
		'tag' => $tags,
		'orderby' => 'date',
		'order' => 'DESC',
		);

	$query = new WP_Query();
	$network_query = $query->posts = network_query_posts($args);
	$post_count = $query->post_count = count($network_query);

	$i = 1;
	while($query->have_posts()) { $query->the_post();
		$blogID = $post->BLOG_ID;

		echo '<div class="col-md-12">';
		echo vintage_post($blogID);
		echo '</div>';

		$i++;
	}

}

function get_video_post($args = array()) {
	global $post, $exclude_posts;

	$defaults = array(
		'posts_per_page' => 1
		);

// merge arguments with defaults && set keys as variables
	$args = array_merge($defaults, $args);
	foreach($args as $key => $val) { ${$key} = $val; }

	$args = array(
		'posts_per_page' => $posts_per_page,
		'post_status' => 'publish',
		'post__not_in' => $exclude_posts,
		'post_type' => 'post',
		'meta_value' => 1,
		'meta_key' => 'db_is_video',
		'meta_compare' => '=',
		'orderby' => 'date',
		'order' => 'DESC',
		);

	$query = new WP_Query();
	$network_query = $query->posts = network_query_posts($args);
	$post_count = $query->post_count = count($network_query);

	while($query->have_posts()) { $query->the_post();

		$blogID = $post->BLOG_ID;

		echo '<div class="col-xs-12">';
		echo video_post($blogID);
		echo '</div>';
	}

	reset_blog();
}

function get_full_story_post($args = array()) {
	global $post, $exclude_posts;

	$defaults = array(
		'posts_per_page' => 1
		);

// merge arguments with defaults && set keys as variables
	$args = array_merge($defaults, $args);
	foreach($args as $key => $val) { ${$key} = $val; }

	$args = array(
		'posts_per_page' => $posts_per_page,
		'post_status' => 'publish',
		'post__not_in' => $exclude_posts,
		'post_type' => 'post',
		'orderby' => 'date',
		'order' => 'DESC',
		);

	$query = new WP_Query();
	$network_query = $query->posts = network_query_posts($args);
	$post_count = $query->post_count = count($network_query);

	while($query->have_posts()) { $query->the_post();

		$blogID = $post->BLOG_ID;

		echo '<div class="col-xs-12">';
		echo full_story_post($blogID);
		echo '</div>';
	}

	reset_blog();

}

function get_standard_post_feature($args = array()) {
	global $post, $exclude_posts;

	$defaults = array(
		'swap_columns' => false,
		'tags' => '',
		);

	// merge arguments with defaults && set keys as variables
	$args = array_merge($defaults, $args);
	foreach($args as $key => $val) { ${$key} = $val; }
	
	$args = array(
		'posts_per_page' => 6,
		'post_status' => 'publish',
		'post__not_in' => $exclude_posts,
		'post_type' => 'post',
		'tag' => $tags,
		//'orderby' => 'meta_value_num',
		//'meta_key' => 'db_views',
		'orderby' => 'date',
		'order' => 'DESC',
		);

	$query = new WP_Query();
	$network_query = $query->posts = network_query_posts($args);
	$post_count = $query->post_count = count($network_query);

	$i = 1;
	while($query->have_posts()) { $query->the_post();

		$blogID = $post->BLOG_ID;

		if($i == 1) {

			$classes = '';
			if($swap_columns) {
				$classes = ' col-lg-push-4';
			}
			?>

			<div class="col-lg-8 col-md-12<?php echo $classes; ?>">
				<?php trending_post($blogID); ?>
			</div>

			<?php
		} 

		if($i == 2) {
			
			$classes = '';
			if($swap_columns) {
				$classes = ' col-lg-pull-8';
			}

			?>

			<div class="col-lg-4 col-md-12<?php echo $classes; ?>">
				<div class="row">

					<?php 
				} 
				if($i > 1) {

					?>
					<div class="col-lg-12 col-md-6">
						<?php classic_post($blogID); ?>
					</div>

					<?php

				} 

				if($i == 3 || $i == $post_count) {
					?>

				</div>
			</div>

			<?php
			break; // so no additional loops once we run out of posts!
		}

		$i++;
	}

	wp_reset_query();
	reset_blog();
}

function get_trending_posts($args = array()) {
	global $post, $exclude_posts;

	$defaults = array(
		'tags' => '',
		'trending_logo' => '',
		'list_post_title' => 'Top Headlines'
		);

// merge arguments with defaults && set keys as variables
	$args = array_merge($defaults, $args);
	foreach($args as $key => $val) { ${$key} = $val; }

	if($trending_logo) {

		?>

		<div class="col-xs-12">
			<div class="trending-topic"><img src="<?php echo $trending_logo; ?>"/></div>
		</div>

		<?php
	}

	$args = array(
		'posts_per_page' => 6,
		'post_status' => 'publish',
		'post__not_in' => $exclude_posts,
		'post_type' => 'post',
		'tag' => $tags,
		//'orderby' => 'meta_value_num',
		//'meta_key' => 'db_views',
		'orderby' => 'date',
		'order' => 'DESC',
		);

	$query = new WP_Query();
	$network_query = $query->posts = network_query_posts($args);
	$post_count = $query->post_count = count($network_query);

	$i = 1;
	while($query->have_posts()) { $query->the_post();

		$blogID = $post->BLOG_ID;

		if($i == 1) {
			?>

			<div class="col-lg-8 col-md-12">
				<?php trending_post($blogID); ?>
			</div>

			<?php
		} 

		if($i == 2) {
			?>

			<div class="col-lg-4 col-md-12">
				<div class="row">
					<div class="col-lg-12 col-md-6">
						<?php classic_post($blogID); ?>
					</div>

					<?php
					// just in case there are only posts
					if($i == $post_count) {
						echo '</div></div>';
					}
				}


				if($i == 3) {
					?>
					<div class="col-lg-12 col-md-6">

						<div class="headline-list">
							<h4><?php echo $list_post_title; ?></h4>
							<ul>
								<?php }
								if($i >= 3 && $i <= 6) {
									echo '<li>';
									echo list_post($blogID);
									echo '</li>';
								}

								if($i == 6 || $i == $post_count) { ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<?php
			break; // so no additional loops once we run out of posts!
		}

		$i++;
	}

	wp_reset_query();
	reset_blog();
}

function get_popular_post() {
	global $post;

	$args = array(
		'posts_per_page' => 13,
		'post_status' => 'publish',
		'orderby' => 'meta_value_num',
		'meta_key' => 'db_views',
				//'post__not_in' => $exclude,
		'post_type' => 'post',
								//	'offset' => $full_offset
		);

	$query = new WP_Query();
	$network_query = $query->posts = network_query_posts($args);
	$post_count = $query->post_count = count($network_query);
	$i = 1;
	while($query->have_posts()) { $query->the_post();

		$blogID = $post->BLOG_ID;

		//	if($i == 1) {// echo '<div class="col-md-4">';
			//	classic_post($blogID);
			//	echo '</div>';
		//	} else { 
		//		echo '<div class="col-md-8">';
		vintage_post($blogID);
		//		echo '</div>';
		break;

		$i++;
	}
	reset_blog();

}

function get_beatmersive_posts() {

	$args = array(
		'posts_per_page' => 3,
		'post_status' => 'publish',
		'post_type' => 'beatmersive',
		);

	$query = new WP_Query($args);
	while($query->have_posts()) { $query->the_post();

		echo '<div class="col-md-4">';
		video_post(1, array('beatmersive' => true));
		echo '</div>';
	}

}

function get_spotlight_posts() {
	global $exclude_posts, $wpdb, $post;

	$cat_object = get_category_by_slug('spotlight-featured'); 
	$spotlight_featured = $cat_object->term_id;

	$main_featured_args = array(
		'posts_per_page' => 1,
		'cat'  => $spotlight_featured, // $category . ', -' . $featured_cat, // all categories, excluding featured
		'post_status' => 'publish'
		);

	$main_featured_id = ''; 
	$main_featured_query = new WP_Query($main_featured_args);

	// is there a quicker way to get the ID?
	if($main_featured_query->have_posts()) { 
		$main_featured_id = $main_featured_query->posts[0]->ID;
	}

	exclude_this_post(1, $main_featured_id);


	$table_name = $wpdb->base_prefix . 'db_stats';
	$results = $wpdb->get_results("SELECT * FROM {$table_name}", ARRAY_A);

	if($results) {

		// sort by views
		usort($results, function($a, $b) { return $b['views'] - $a['views']; });

		$posts_to_query = array();
		$posts_added = 0;
		$blogs_added = array();
		$result_count = count($results);

		while(list($key, $post) = each($results)) {

			// remove day old posts
			if(strtotime($post['date_added']) < strtotime('-1 day')) { unset($results[$key]); }

			$blogID = $post['blog_id'];
			$post_id = $post['post_id'];

			if($blogID == 1 && $post_id == $main_featured_id || $post_id == 1) { continue; }

			// if only one blog exists by the 3rd post skip till new blog ( so we don't have all posts from the same blog.. )
			if(count($posts_to_query) == 1 && $posts_added == 3 && $key + 1 < $result_count) {
				if(in_array($blogID, $blogs_added)) { continue; }
			}

			$blogs_added[] = $blogID;
			$posts_to_query[$blogID][] = $post_id;
			$posts_added++;

			if($posts_added == 6) { break; }

		}
		
	}

	$args = array(
		'posts_per_page' => 10 - count($posts_to_query),
		'post_status' => 'publish',
		'post__in' => $posts_to_query,
		'post_type' => 'post',
		'orderby' => 'meta_value_num',
		'meta_key' => 'db_views',
		);

	$query = new WP_Query();
	$network_query = $query->posts = network_query_posts($args);
	$post_count = $query->post_count = count($network_query);

	$posts_looped = 0;
	while($query->have_posts()) { $query->the_post();

		$blogID = $post->BLOG_ID;

		if($posts_looped == 0 || $posts_looped == 3) {
			echo '<div class="col-md-3">';
		}

		if($posts_looped != 2) {
			echo spotlight_post($blogID);
			$posts_looped++;
		}

		if($posts_looped == 2 || $posts_looped == 5) {
			echo '</div>';
		} 

		if($posts_looped == 2) {

			while($main_featured_query->have_posts()) { $main_featured_query->the_post();
				echo '<div class="col-md-6">';
				echo spotlight_post(1, array('center' => true));
				echo '</div>';
			}

			$posts_looped++;
		}

		if($posts_looped == 5) { break; }

	}

	reset_blog();
}

add_action('wp_ajax_load_posts', 'ajax_load_posts');
add_action('wp_ajax_nopriv_load_posts', 'ajax_load_posts');
function ajax_load_posts() {
	global $exclude_posts;

	$nonce = isset($_POST['postCommentNonce']) ? $_POST['postCommentNonce'] : '';
	if(!wp_verify_nonce($nonce, 'myajax-post-comment-nonce')) { die('Busted!'); }

	$options = array();
	parse_str($_POST['options'], $options);

	$exclude = isset($_POST['exclude_posts']) ? json_decode(stripslashes($_POST['exclude_posts'])) : array();

	$defaults = array(
		'author' => 0,
		'type' => '',
		'loops' => 2,
		'category' => 0,
		'search_str' => '',
		);

	// merge options with defaults
	$options = array_merge($defaults, $options);
//	$exclude_posts = array_merge((array) $exclude_posts, (array) $exclude);

	$combined_exclude_posts = array();
	foreach($exclude as $key => $arr) {
		$combined_exclude_posts[$key] = array_unique(array_merge($arr, (array)$exclude_posts[$key]));
	}

	$exclude_posts = $combined_exclude_posts;

	ob_start();
	get_standard_loop($options);
	$result = ob_get_contents();
	ob_end_clean();

	echo json_encode(array('results' => $result, 'exclude_posts' => $exclude_posts));

	exit;
}




?>