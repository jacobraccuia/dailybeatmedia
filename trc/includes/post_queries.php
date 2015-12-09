<?php

// important, but where does it actually belong?
global $exclude_posts;
$exclude_posts = array();

// standard post layout
function classic_post($timestamp = 1, $title = 1, $excerpt = 1, $sc = 0) {
	global $exclude_posts;
	$id = get_the_ID();
	$exclude_posts = array_merge(array($id), $exclude_posts);

	$category = get_primary_category($id);		
	
	$db_soundcloud = get_post_meta($id, 'db_soundcloud', true);
	
	$thumb_id = get_post_thumbnail_id();
	$thumb_url = wp_get_attachment_image_src($thumb_id, array(300,300), true);
	$thumb_url = $thumb_url[0];
		
	?>
	<div class="post classic <?php echo $category->slug; ?>">

		<a class="featured-image-link" href="<?php the_permalink(); ?>">
			<div class="featured-image cat-border"><div style="background-image:url('<?php echo $thumb_url; ?>');"></div></div>
			<div class="category-tag cat-background"><?php echo $category->name; ?></div>
		</a>
		<div class="post-bottom">
			<div class="post-meta left"><?php the_author(); //the_author_posts_link(); ?></div>
			
			<?php if($timestamp == 1) { ?>
				<div class="post-meta right"><?php echo the_timestamp_short(); ?></div>
			<?php } ?>
			<?php if($title == 1) { ?>
				<a href="<?php the_permalink(); ?>">
					<h3 class="title"><?php the_title(); ?></h3>
				</a>
			<?php } ?>
			<?php if($excerpt == 1) { ?>
				<p class="excerpt"><?php echo char_based_excerpt(150); ?></p>
			<?php } ?>
		</div>
		<?php if($sc == 1) { ?>
			<div class="player super_tight">
				<?php if(isset($db_soundcloud) && !empty($db_soundcloud)) { 
					soundcloud_player($db_soundcloud, $song_title);
				} ?>
			</div>
		<?php } ?>
		</div>
<?php 
}

function standard_ad($ad) {
	
	$id = $ad->ID;
	
	$hyperlink = addhttp(get_post_meta($id, 'db_ad_hyperlink', true));

	$thumb_id = get_post_thumbnail_id($id);
	$thumb_url = wp_get_attachment_image_src($thumb_id, $image_size, true);
	$thumb_url = $thumb_url[0];
		
	?>
	<div class="post featured ad">
		<a class="featured-image-link" href="<?php echo $hyperlink; ?>" target="_blank">
			<div class="featured-image cat-border"><div style="background-image:url('<?php echo $thumb_url; ?>');"></div></div>
			<div class="category-tag cat-background">Sponsored</div>
		</a>
	</div>
	<?php
}


// related posts
function related_post() {
	global $exclude_posts;
	$id = get_the_ID();
	$exclude_posts = array_merge(array($id), $exclude_posts);

	$category = get_primary_category($id);
	
	$thumb_id = get_post_thumbnail_id();
	$thumb_url = wp_get_attachment_image_src($thumb_id, array(300,300), true);
	$thumb_url = $thumb_url[0];
	
?>
	<div class="post related <?php echo $category->slug; ?>">
		<a class="featured-image-link" href="<?php the_permalink(); ?>">
			<div class="featured-image cat-border"><div style="background-image:url('<?php echo $thumb_url; ?>');"></div></div>
			<div class="category-tag cat-background"><?php echo $category->name; ?></div>
		</a>
		<h3 class="title"><?php the_title(); ?></h3>
	</div>
	
<?php 
}

// full post layout, image spans full width with text overlay
function spotlight_post() {
	global $exclude_posts;
	$id = get_the_ID();
	$exclude_posts = array_merge(array($id), $exclude_posts);
	
	$title = get_the_title();
	$new_title = get_post_meta($id, 'db_featured_title', true);
					
	if(!empty($new_title)) { $title = $new_title; }	
	
	$category = get_primary_category($id);		
	
	$image_size = array(750,750);
//	if($size == "large") { $image_size = array(500,500); }
		
	$thumb_id = get_post_thumbnail_id();
	$thumb_url = wp_get_attachment_image_src($thumb_id, $image_size, true);
	$thumb_url = $thumb_url[0];
	
?>

    <div class="post featured <?php echo $category->slug; ?>">
        <a href="<?php the_permalink() ?>">
			<div class="featured-image"><div style="background-image:url('<?php echo $thumb_url; ?>');"></div></div>

            <?php // if(has_post_thumbnail()) { the_post_thumbnail(array(350,300)); } ?>
            <h2 class="caption"><?php echo $title; ?></h2>
			<div class="spotlight-bg cat-bg-rgba"></div>
        </a>
    </div>

<?php
}


function get_standard_posts(array $args) {
	global $exclude_posts;
	global $query_string;

	$defaults = array(
		'category' => 0, 
		'author' => 0, 
		'offset' => 0,
		's' => '',
		'full_offset' => 1, 
		'show_ads' => 0
	);
	
	// merge arguments with defaults
	$args = array_merge($defaults, $args);
	foreach($args as $key => $val) {
		${$key} = $val; // set up each key as a variable	
	}	
	
	$search_query = array();
	

	
	$cat_object = get_category_by_slug('featured'); 
  	$featured_cat = $cat_object->term_id;
	
	$ad_count = 0;
	if($show_ads == 1)  {
		$ads = get_standard_ads();
		$ad_count = count($ads);
	}
	
	if($show_ads == 1 && $ad_count > 0) {
		
		$i = 1; // first post
		echo '<div class="row">'; 	
		
			echo '<div class="col-sm-8 standard-post">';	
		
			if($show_ads == 1 && array_key_exists($i, $ads)) {
				echo standard_ad($ads[$i]);
			} else {
				
				$featured_args = array(
					'posts_per_page' => 1,
					'cat' => $featured_cat,
					'post_status' => 'publish',
					'post__not_in' => $exclude_posts,
					'post_type' => 'post',
				//	'offset' => $full_offset
				);
	
				$featured_query = new WP_Query($featured_args);
				while($featured_query->have_posts()) : $featured_query->the_post();
					echo spotlight_post();
				endwhile;
				
				$full_offset++; // increase offset counter
			}
			
			echo '</div>';
			
		$i++; // move to post #2
		
		$posts_per_page = 5;
		if($show_ads == 1) {
			$posts_per_page = posts_to_query_after_ads(array(2,3,4,5,6), $ads); // spots 2 - 6
		}
		
		$x = $i;
		while($i < 6) { // (2 - 6)
			
			if($show_ads == 1) {
				check_ads($i, $ads); // check and display ads
			}
			
			if($posts_per_page > 0) { // if there is more than just ads
				$args = array(
					'posts_per_page' => $posts_per_page,
					'cat'  => $category . ', -' . $featured_cat, // all categories, excluding featured
					'post_status' => 'publish',
					'offset' => $offset,
					'author' => $author,
					'post_type' => 'post',
					'post__not_in' => $exclude_posts,
				);
			
				$post_query = new WP_Query($args);
				while($post_query->have_posts()) : $post_query->the_post();
					echo '<div class="col-sm-4 standard-post">';	
						echo classic_post();
					echo '</div>';
					
					$i++;
					
					if($show_ads == 1) {
						check_ads($i, $ads); // check and display ads
					}
				endwhile;
			}
			
			if($x == $i) { return; }
		}
		
		// i should == 7
		echo '<div class="col-sm-8 standard-post">';
			
			if($show_ads == 1 && array_key_exists($i, $ads)) {
				echo standard_ad($ads[$i]);
			} else {
				
				$featured_args = array(
					'posts_per_page' => 1,
					'cat' => $featured_cat,
					'post_type' => 'post',
					'post_status' => 'publish',
					'post__not_in' => $exclude_posts,
				//	'offset' => $full_offset
				);
			
				$featured_query = new WP_Query($featured_args);
				while($featured_query->have_posts()) : $featured_query->the_post();
					echo spotlight_post();
				endwhile;
				
				$full_offset++; // increase offset counter
			}
			
		echo '</div>';
		
		$i++; // move to post #2
		
		$posts_per_page = 3;
		if($show_ads == 1) {
			$posts_per_page = posts_to_query_after_ads(array(8,9,10), $ads); // spots 2 - 6
		}
		$x = $i;
		while($i < 11) { // (2 - 6)
			if($show_ads == 1) {
				check_ads($i, $ads); // check and display ads
			}
			
			if($posts_per_page > 0) { // if there is more than just ads
				$args = array(
					'posts_per_page' => $posts_per_page,
					'cat'  => '-' . $featured_cat, // $category . ', -' . $featured_cat, // all categories, excluding featured
					'post_status' => 'publish',
					'offset' => $offset,
					'post_type' => 'post',
					'author' => $author,
					'post__not_in' => $exclude_posts,
				);
			
				$post_query = new WP_Query($args);
				while($post_query->have_posts()) : $post_query->the_post();
					echo '<div class="col-sm-4 standard-post">';	
						echo classic_post();
					echo '</div>';
					
					$i++;
			if($show_ads == 1) {
				check_ads($i, $ads); // check and display ads
			}
			
			endwhile;
			}
			
			if($x == $i) { return; }
		}
		
		if($i > 1) { echo '</div>'; }
		if($i == 1) { echo 'That is all!'; }	
		
		return;	
	}
	
	// IF THERE ARE NO ADS: this loop is smaller amd faster
		
		$i = $x = 1;  // x is used to determine if there are posts
		echo '<div class="row">';

		$posts_per_page = 8; // show 8 posts as default
		
		$category__and = array();
		if($category == 0) { $category__and == array($featured_cat); }
		else { $category__and == array($category, $featured_cat); }
		
		
			// i == 1;
			$featured_args = array(
				'posts_per_page' => 2,
				'category__and' => $category__and,
				's' => $s,
				'post_status' => 'publish',
				'post_type' => 'post',
				'post__not_in' => $exclude_posts,
				//'offset' => $full_offset
			);
		
			$featured_args = array_merge($search_query, $featured_args); // merge potential search
			$featured_query = new WP_Query($featured_args);

			if(!$featured_query->have_posts()) { $posts_per_page = 12; } // if there are no posts, show 12 classic
			
			while($featured_query->have_posts()) : $featured_query->the_post();
				echo '<div class="col-sm-8 standard-post">';
					echo spotlight_post();		
				echo '</div>';
				
				$x++;
				if($featured_query->current_post + 1 >= $featured_query->post_count) { $posts_per_page = 10; } // if there is not another featured post, show 10 classic
				break;
			endwhile;
			
			//$full_offset++; // increase offset counter
		
		$i++;
		
		$args = array(
			'posts_per_page' => $posts_per_page,
			'cat'  => $category . ', -' . $featured_cat, // all categories, excluding featured
			'post_status' => 'publish',
			'offset' => $offset,
			's' => $s,
			'post_type' => 'post',
			'author' => $author,
			'post__not_in' => $exclude_posts,
		);
		
		$args = array_merge($search_query, $args); // merge potential search
		$post_query = new WP_Query($args);
		while($post_query->have_posts()) : $post_query->the_post();
		
			echo '<div class="col-sm-4 standard-post">';	
				echo classic_post();
			echo '</div>';
			
			$x++;
			$i++;
			if($i == 7 && $posts_per_page == 8) {
					$featured_args = array(
						'posts_per_page' => 1,
						'category__and' => $category__and,
						'post_status' => 'publish',
						's' => $s,
						'post_type' => 'post',
						'post__not_in' => $exclude_posts,
					//	'offset' => $full_offset
					);
				
					$featured_args = array_merge($search_query, $featured_args); // merge potential search
					$featured_query = new WP_Query($featured_args);
					while($featured_query->have_posts()) : $featured_query->the_post();
						echo '<div class="col-sm-8 standard-post">';
						echo spotlight_post();
						echo '</div>';
					endwhile;
					
					$full_offset++; // increase offset counter
			}
			
		endwhile;
	
		echo '</div>';
		if($x == 1) { echo 'That is all!'; }	
		
	
	
}

function get_related_posts(array $args) {
	global $exclude_posts;
	
	$defaults = array(
		'category' => 0, 
		'author' => 0, 
		'posts_per_page' => 3,
		'offset' => 0,
		'type' => 'col'
	);

	// merge arguments with defaults
	$args = array_merge($defaults, $args);
	foreach($args as $key => $val) {
		${$key} = $val; // set up each key as a variable	
	}	

	$args = array(
		'posts_per_page' => $posts_per_page,
		'cat'  => $category,
		'post_status' => 'publish',
		'offset' => $offset,
		'author' => $author,
		'post__not_in' => $exclude_posts,
	);
		
		if($type == 'col') {
			column_posts($args);	
		}
		
		if($type == 'stack') {
			stack_posts($args);	
		}
		
}

function column_posts($args) {

	echo '<div class="row">';
	
	$related_query = new WP_Query($args);
	while($related_query->have_posts()) : $related_query->the_post();
		echo '<div class="col-sm-4 related-post column-post">';
			
			ob_start();
				echo related_post(); 
			$result = ob_get_contents();
			ob_end_clean();
		
			echo $result;
						
		echo '</div>';
	endwhile;

	echo '</div>';

}

function stack_posts($args) {

	echo '<div class="row">';
	
	$related_query = new WP_Query($args);
	while($related_query->have_posts()) : $related_query->the_post();
		echo '<div class="col-xs-12 related-post stack-post">';
					
			ob_start();
				echo related_post();
			$result = ob_get_contents();
			ob_end_clean();
		
			echo $result;
	
		echo '</div>';
	endwhile;

	echo '</div>';

}

add_action('wp_ajax_load_posts', 'aj_load_posts');
add_action('wp_ajax_nopriv_load_posts', 'aj_load_posts');
function aj_load_posts() {
	
	global $exclude_posts;
	
	$nonce = $_POST['postCommentNonce'];
	if(!wp_verify_nonce($nonce, 'myajax-post-comment-nonce')) { die('Busted!'); }
	
	$exclude_posts = isset($_POST['exclude']) ? $_POST['exclude'] : array();

	$type = isset($_POST['type']) ? $_POST['type'] : '';
	$category = isset($_POST['category']) ? $_POST['category'] : 0;
	$clicks = isset($_POST['clicks']) ? $_POST['clicks'] : '';
	$page_type = isset($_POST['page_type']) ? $_POST['page_type'] : '';
	$search_str = isset($_POST['search_str']) ? $_POST['search_str'] : '';
	$author = isset($_POST['author']) ? $_POST['author'] : 0;
	
	ob_start();
		echo get_standard_posts(array('author' => $author, 'category' => $category, 's' => $search_str));
	$result = ob_get_contents();
	ob_end_clean();

	echo json_encode(array('results' => $result, 'exclude' => $exclude_posts));
	
	exit;
}



function get_ad($ad_type = '') {
	if($ad_type == '') { return; }
	global $exclude_posts;

	$args = array(
		'posts_per_page' => 1,
		'post_type' => 'ad_display',
		'tax_query' => array(
			array(
				'taxonomy' => 'ad_type',
				'field' => 'slug',
				'terms' => $ad_type,
			),
		),
		'post_status' => 'publish',
	);
	
	$ad_query = new WP_Query($args);
		while($ad_query->have_posts()) : $ad_query->the_post();
			$id = get_the_ID();
			$ad_hyperlink = get_post_meta($id, 'db_ad_hyperlink', true);
			$ad_hyperlink = addhttp($ad_hyperlink);

			if($ad_type != 'skin') {
				
				if($ad_type == 'sidebar' || $ad_type == 'sidebar-top') { echo '<div class="widget">'; }
		
					echo '<div class="custom-ad ' . $ad_type . '">';
						echo '<a href="' . $ad_hyperlink . '" target="_blank">'; 
								if(has_post_thumbnail()) { the_post_thumbnail(); } 
								if($ad_type == 'post-bottom' || $ad_type == 'post-top' || $ad_type == 'post-infeed') { echo '<div class="category-tag cat-background">Sponsored</div>'; }
							echo '</a>';
	
					echo '</div>';
					
				if($ad_type == 'sidebar' || $ad_type == 'sidebar-top') { echo '</div>'; }

			}
			
			if($ad_type == 'skin') {
							
				$thumb_id = get_post_thumbnail_id();
				$thumb_url = wp_get_attachment_image_src($thumb_id, array(1400,1400), true);
				$thumb_url = $thumb_url[0];
	
				
				
				// responsive image
				 if(class_exists('Dynamic_Featured_Image')) {
			     	global $dynamic_featured_image;
     				$featured_images = $dynamic_featured_image->get_featured_images($id);
				 }
				
				
					if($featured_images[0]) {
						echo '<a href="' . $ad_hyperlink . '" target="_blank" class="skin-ads-link low-res" style="background-image:url(' . $featured_images[0]['full'] . ');"></a>';	
						// normal image
						echo '<a href="' . $ad_hyperlink . '" target="_blank" class="skin-ads-link high-res" style="background-image:url(' . $thumb_url . ');"></a>';
					} else {
						// if there is no econd image, remove the high res class so it stays on LR.
						echo '<a href="' . $ad_hyperlink . '" target="_blank" class="skin-ads-link" style="background-image:url(' . $thumb_url . ');"></a>';
					}
				
			}
			
		endwhile;
}

// get the ads that will be displayed in standard post layout
function get_standard_ads() {
	
	$args = array(
		'posts_per_page' => -1,
		'post_type' => 'ad_display',
		'post_status' => 'publish',
		'tax_query' => array(
			array(
				'taxonomy' => 'ad_type',
				'field' => 'slug',
				'terms' => 'post', // get all ads categorized as "post"
			),
		)
	);

	$results = get_posts($args);
	
	$ads = array();
	foreach($results as $key => $post) {
		$id = $post->ID;
		
		$location = get_post_meta($id, 'db_ad_location', true);
		
		if(!array_key_exists($location, $ads)) {
			$ads[$location] = $post;	
		}
	}
	//echo '<pre>'; print_r($ads);	 echo '</pre>';
	return $ads;
}

function check_ads(&$i, &$ads) {
	if($i != 7 && array_key_exists($i, $ads)) { // 7 is a featured post, and if coming from 6, it'll return true and show an ad when it shouldn't.
		echo '<div class="col-sm-4 standard-post">';
			standard_ad($ads[$i]);
		echo '</div>';
		$i++;
		check_ads(&$i, &$ads);
	}
}

function posts_to_query_after_ads($upcoming_posts, $ads) {
	if(count($upcoming_posts) > 0) {
		$difference = array_diff_key(array_flip($upcoming_posts), $ads);	
		return count($difference); // $posts_per_page = count($upcoming_posts) - count($difference);
	//	return $posts_per_page;
	}
	return 0;
}


add_shortcode('infeed', 'infeed_ad_shortcode');
function infeed_ad_shortcode() {
	
	ob_start();
		echo get_ad('post-infeed');
	$result = ob_get_contents();
	ob_end_clean();
    return $result;
}


?>