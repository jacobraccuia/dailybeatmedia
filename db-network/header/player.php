<?php 




function dbm_player() { ?>
	<div id="player">
		<div class="loading"><i class="fa fa-spinner fa-pulse"></i></div>
		<div class="sc-player-wrapper">
			<?php get_fnt_for_player(); ?>
		</div>
		<div class="offpage-fa-fix"><i class="fa fa-pause-circle"></i></div>
	</div>

	<?php }



// creates track data that can be applied to any track
// this data is read by the soundcloud js file and populated in the player
// track artist meta is the same data as get_artist_fields
function build_track_data($track_url = '', $track = '', $artist = '', $artist_meta = array()) {

	$thumb_url = isset($artist_meta['thumb_url']) ? $artist_meta['thumb_url'] : '';
	$artist_instagram = isset($artist_meta['artist_instagram']) ? $artist_meta['artist_instagram'] : '';
	$artist_twitter = isset($artist_meta['artist_twitter']) ? $artist_meta['artist_twitter'] : '';
	$artist_soundcloud = isset($artist_meta['artist_soundcloud']) ? $artist_meta['artist_soundcloud'] : '';

	return 'data-track-url="' . $track_url 
		. '" data-track="' . $track
		. '" data-title="' . $track
		. '" data-artist="' . $artist
		. '" data-thumb-url="' . $thumb_url
		. '" data-artist-instagram="' . $artist_instagram
		. '" data-artist-twitter="' . $artist_twitter
		. '" data-artist-soundcloud="' . $artist_soundcloud
		. '"';
}
	


// maybe eventually merge with fresh new tracks content call so we don't make two calls for same data
	function get_fnt_for_player($args = array()) {
		global $exclude_posts;

		$defaults = array(
			'offset' => 0,
			'posts_per_page' => 10,
			);

	// merge arguments with defaults && set keys as variables
		$args = array_merge($defaults, $args);
		foreach($args as $key => $val) { ${$key} = $val; }

		$blogID = get_blog_by_name('freshnewtracks');
		switch_to_blog($blogID);

		$exclude = $exclude_posts[$blogID];
		$args = array(
			'posts_per_page' => $posts_per_page,
		//'offset' => $offset,
			'post_status' => 'publish',
			'post_type' => 'tracks',
			);

		$i = '';
		$query = new WP_Query($args);
		while($query->have_posts()) { $query->the_post();

			$id = get_the_ID();

			$artist = get_post_meta($id, 'track_artist_name', true);
			$track = get_post_meta($id, 'track_name', true);
			$remixer = get_post_meta($id, 'track_remixer', true);
			$track_url = get_post_meta($id, 'track_url', true);
			$track_artist_id = get_post_meta($id, 'db_featured_artist_id', true);

			$artist_meta = array();
			if(is_numeric($track_artist_id)) {
				$artist_meta = get_artist_fields($track_artist_id, $blogID);
			}

			if($remixer != '') {
				$track .= ' (' . $remixer . ' Remix)';
			}

			$class = 'queue_track';
			if($i == '') {
				$class = 'sc-player';
			}

			echo '<a href="' . $track_url . '" ' . build_track_data($track_url, $track, $artist, $artist_meta) . ' class="' . $class . '">' . $track . '</a>';

			$i++;	
		}

		reset_blog();
	}




function single_artist_widget($id) {

	$blogID = get_blog_by_name('artists');
	min_switch_to_blog($blogID);

	$artist = get_post($id);

	$thumb_url =  wp_get_attachment_image_src( get_post_thumbnail_id($id), array(300,300))[0];

	$post_meta = get_post_custom($id);

	// assign all columns to variable
	$artist_columns = artist_columns();
	
	// in case value isn't set in database, set required variables to ''
	foreach($artist_columns as $column) { ${$column} = ''; }

	// assign all fields
	foreach($post_meta as $key => $meta) {
		if(array_key_exists($key, array_flip($artist_columns))) { // if we want this key
			if(isset($key) && !empty($key)) { ${$key} = trim($meta[0]); } else ${$key} = '';
		}
	}	

	reset_blog();
	
	$tour_dates = json_decode($artist_tour_dates);

	?>
	<div class="single-artist-widget">
		<?php if($artist_sponsor != '') { ?>
		<div class="artist-sponsor">Presented by <img src="<?php echo $artist_sponsor; ?>" /></div>
			<?php } ?>
		<h2>Featured Artist <span><?php echo $artist_name; ?></span></h2>
		<div class="artist-image"><img src="<?php echo $thumb_url; ?>" /></div>
		<ul class="social">
			<?php if($artist_instagram != '') { echo '<li class="instagram">' . social_media('instagram', $artist_instagram) . '</li>'; } ?>
			<?php if($artist_twitter != '') { echo '<li class="twitter">' . social_media('twitter', $artist_twitter) . '</li>'; } ?>
			<?php if($artist_soundcloud != '') { echo '<li class="soundcloud">' . social_media('soundcloud', $artist_soundcloud) . '</li>'; } ?>
		</ul>
		<?php 
		if(
			isset($tour_dates) &&
			isset($tour_dates->resultsPage) &&
			isset($tour_dates->resultsPage->results) &&
			isset($tour_dates->resultsPage->results->event) &&
			count($tour_dates->resultsPage->results->event) > 0) 
		{ 
			?>
			<h4>Upcoming Tour Dates</h4>
			<ul class="tour-dates">
				<?php
				$i = 0;
				foreach($tour_dates->resultsPage->results->event as $date) {
					$venue = $date->venue->displayName;

						// stripping separately just in case
					$display = strstr($date->displayName, ' (', true);
						$display = strstr($display, ' at ', true);

						$permalink = $date->uri;

						if($display == '') { continue; }

						$start = DateTime::createFromFormat('Y-m-d', $date->start->date);
						$start_month = $start->format('M');
						$start_day = $start->format('d');

						echo '<li>';
						echo '<div class="date"><div class="month">' . $start_month . '</div>' . $start_day . '</div>';
						echo '<div class="details">';
						echo '<a href="' . $permalink . '">' . $display . '</a>';
						echo '<div class="location">at ' . $venue . '</div>';
						echo '</div>';
						echo '</li>';
						$i++;
						if($i > 4) { break; }
					} 
					?>
				</ul>
				<?php 
			}
			if($i > 4) {
				?>
				<a class="view-all" href="http://www.songkick.com/artists/<?php echo $artist_id; ?>" target="_blank">View All Tour Dates</a>
				<?php } ?>
			</div>

			<?php 	
		}



function get_artist_fields($id, $oldBlogID = 1) {

	$blogID = get_blog_by_name('artists');
	min_switch_to_blog($blogID);

	// create array to return
	$artist_vars = array();
	
	$artist = get_post($id);

	$artist_vars['thumb_url'] =  wp_get_attachment_image_src(get_post_thumbnail_id($id), array(300,300))[0];

	$post_meta = get_post_custom($id);

	// assign all columns to variable
	$artist_columns = artist_columns();

	// in case value isn't set in database, set required variables to ''
	foreach($artist_columns as $column) { $artist_vars[$column] = ''; }

	// assign all fields
	foreach($post_meta as $key => $meta) {
		if(array_key_exists($key, array_flip($artist_columns))) { // if we want this key
			if(isset($key) && !empty($key)) { $artist_vars[$key] = trim($meta[0]); } else $artist_vars[$key] = '';
		}
	}	

	min_switch_to_blog($oldBlogID);

	return $artist_vars;
}

function artist_columns() {
		return array(
			'artist_name',
			'artist_id',
			'artist_tour_dates',
			'artist_twitter',
			'artist_soundcloud',
			'artist_instagram',
			'artist_sponsor'
			);
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


	?>