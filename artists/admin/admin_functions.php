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



	?>