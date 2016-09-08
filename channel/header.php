<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-touch-fullscreen" content="yes">

	<?php if(is_single()) { ?>
		<?php if(has_post_thumbnail()) { 	
			$thumb_id = get_post_thumbnail_id();
			$thumb_url = wp_get_attachment_image_src($thumb_id, array(600,600), true);
			$thumb_url = $thumb_url[0];
		} ?>
		
		<meta property="og:title" content="<?php the_title(); ?>"/>
		<meta property="og:type" content="website"/>
		<meta property="og:url" content="<?php the_permalink(); ?>"/>
		<meta property="og:image" content="<?php echo $thumb_url; ?>"/>
		<meta property="og:description" content="<?php echo char_based_excerpt(80); ?> "/>		
		<?php } ?>
		
		<!-- links -->
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS2 Feed" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<meta name="google-site-verification" content="YZf8f0PRHZfL62L9Ja-_Nx_G7T4tv2j6JxIjRqyvTR0" />

		<!-- favi -->
		<link rel="apple-touch-icon" sizes="57x57" href="<?php echo THEME_DIR; ?>/favicon/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="<?php echo THEME_DIR; ?>/favicon/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo THEME_DIR; ?>/favicon/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php echo THEME_DIR; ?>/favicon/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo THEME_DIR; ?>/favicon/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php echo THEME_DIR; ?>/favicon/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo THEME_DIR; ?>/favicon/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo THEME_DIR; ?>/favicon/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo THEME_DIR; ?>/favicon/apple-touch-icon-180x180.png">
		<link rel="icon" type="image/png" href="<?php echo THEME_DIR; ?>/favicon/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="<?php echo THEME_DIR; ?>/favicon/favicon-194x194.png" sizes="194x194">
		<link rel="icon" type="image/png" href="<?php echo THEME_DIR; ?>/favicon/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="<?php echo THEME_DIR; ?>/favicon/android-chrome-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="<?php echo THEME_DIR; ?>/favicon/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="<?php echo THEME_DIR; ?>/favicon/manifest.json">
		<link rel="mask-icon" href="<?php echo THEME_DIR; ?>/favicon/safari-pinned-tab.svg" color="#5bbad5">
		<link rel="shortcut icon" href="<?php echo THEME_DIR; ?>/favicon/favicon.ico">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="msapplication-TileImage" content="<?php echo THEME_DIR; ?>/favicon/mstile-144x144.png">
		<meta name="msapplication-config" content="<?php echo THEME_DIR; ?>/favicon/browserconfig.xml">
		<meta name="theme-color" content="#ffffff">


		<title><?php
			if(is_front_page()) {
				echo get_bloginfo('name') . get_bloginfo('description');
			} else {
				wp_title($sep = ' ');
			}
			?></title>	


			<?php wp_head(); ?> 

		</head>

		<body <?php body_class(); ?>>

			<?php dbm_header(); ?>
