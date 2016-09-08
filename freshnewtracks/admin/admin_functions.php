<?php


add_action('admin_menu', 'artist_api_admin_menu', 9);
function artist_api_admin_menu() {
	$page_title = 'Artist API Dashboard';
	$menu_title = 'Artist API Dashboard';
	$capability = 'manage_options';
	$menu_slug = 'artist_api_dashboard';
	$function = 'artist_api_dashboard';
	$icon_url = 'dashicons-share';
	$position = 10;

	add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
}

add_action('admin_enqueue_scripts', 'now_feed_scripts');
function now_feed_scripts($hook) {	
	if('toplevel_page_now_feed_dashboard' != $hook) { return; }
	wp_enqueue_style('bootstrap_css_admin', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css');

}	

function artist_api_dashboard() {
	isset($_GET['page']) ? $page = $_GET['page'] : $page = 'null';
	switch($page) {
		case 'artist_api_dashboard':
		include_once(get_stylesheet_directory() . '/admin/artist_api_dashboard.php');
		break;
	}
}

add_action('wp_dashboard_setup', 'fnt_dashboard_widgets');
function fnt_dashboard_widgets() {
	add_meta_box('fnt_tracks', 'Broken Tracks!', 'fnt_dashboard_widget', 'dashboard', 'side', 'high');
}

function fnt_dashboard_widget() {

	$clientid = '9f690b3117f0c43767528e2b60bc70ce'; 

	$args = array(
		'posts_per_page' => -1,
		'offset' => 0,
		'post_status' => array('publish', 'draft'),
		'post_type' => 'tracks',
		);

	$errors = 0;
	$query = new WP_Query($args);
	while($query->have_posts()) { $query->the_post();

		$id = get_the_ID();
		$track_url = get_post_meta($id, 'track_url', true);

		$errored = 0;

		if(get_post_status() == 'draft') {
			$errored = 1;
		} else {
			$soundcloud_url = 'https://api.soundcloud.com/resolve?url=' . $track_url . '&format=json&consumer_key=' . $clientid;
			$track_json = file_get_contents($soundcloud_url);
			$track = json_decode($track_json);
			if($track == null || isset($track->errors) && isset($track->errors[0]) && $track->errors[0]->error_message == '404 - Not Found' || $track->errors[0]->error_message == 'HTTP Error: 403') {
				$errored = 1;

				$update = array('ID' => $id, 'post_status' => 'draft');
				wp_update_post($update);
			}
		}

		if($errored > 0) {
			echo '<span style="color:red;">SC Track is private & won\'t play: </span>&nbsp;' . '<a href="' . get_edit_post_link() . '" target="_blank">' . get_the_title() . '</a><br/>';

			$errors++;
		}
	}

	if($errors > 0) {
		echo '<br/><em>These ' . $errors . ' posts are all drafts, but should probably be fixed or removed.</em>';
	} else {
		echo 'There are no broken tracks! That is SHOCKING :)';
	}

}


?>