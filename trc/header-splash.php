<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<!-- meta tags -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta charset="<?php bloginfo('charset'); ?>" />
	<!--<meta name="author" content="Name">-->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	
	<!-- links -->
	<link rel="shortcut icon" href="<?php echo THEME_DIR; ?>/favicon.ico" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS2 Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<meta name="google-site-verification" content="YZf8f0PRHZfL62L9Ja-_Nx_G7T4tv2j6JxIjRqyvTR0" />

	<title>
		<?php
			if(is_front_page()) {
				echo get_bloginfo('name') . get_bloginfo('description');
			} else {
				wp_title($sep = ' to rep');
			}
		?>
	</title>
    
	<?php wp_head(); ?> 

</head>

<body <?php body_class(); ?> id="splash">