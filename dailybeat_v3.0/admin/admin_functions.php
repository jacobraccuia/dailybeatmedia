<?php





	add_action('admin_menu', 'trending_post_admin_menu', 9);
	function trending_post_admin_menu() {
		$page_title = "Trending Post Settings";
		$menu_title = "Trending Post Settings";
		$capability = "manage_options";
		$menu_slug = "trending_post_settings";
		$function = "trending_post_settings";
		$icon_url = 'dashicons-chart-area';
		$position = 4;

		add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
	}

	add_action('admin_enqueue_scripts', 'trending_scripts');
	function trending_scripts($hook) {	
		if('toplevel_page_trending_post_settings' != $hook) { return; }

		wp_enqueue_media();
		wp_enqueue_script('trending_post_js', THEME_DIR . '/js/trending_post.js', array('jquery'));
	}	

	function trending_post_settings() {
		isset($_GET['page']) ? $page = $_GET['page'] : $page = 'null';
		switch($page) {
			case "trending_post_settings":
			include_once(get_stylesheet_directory() . '/admin/trending_post_settings.php');
			break;
		}
	}



	add_action('admin_menu', 'now_feed_admin_menu', 9);
	function now_feed_admin_menu() {
		$page_title = "Now Feed Dashboard";
		$menu_title = "Now Feed Dashboard";
		$capability = "manage_options";
		$menu_slug = "now_feed_dashboard";
		$function = "now_feed_admin";
		$icon_url = 'dashicons-share';
		$position = 10;

		add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
	}

	add_action('admin_enqueue_scripts', 'now_feed_scripts');
	function now_feed_scripts($hook) {	
		if('toplevel_page_now_feed_dashboard' != $hook) { return; }

		wp_enqueue_style('bootstrap_css_admin', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css');
		
	//	wp_enqueue_media();
	//	wp_enqueue_script('trending_post_js', THEME_DIR . '/js/trending_post.js', array('jquery'));
	}	

	function now_feed_admin() {
		isset($_GET['page']) ? $page = $_GET['page'] : $page = 'null';
		switch($page) {
			case "now_feed_dashboard":
			include_once(get_stylesheet_directory() . '/admin/now_feed_dashboard.php');
			break;
		}
	}





	?>