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
	<link rel="shortcut icon" href="<?php echo THEME_DIR; ?>/favicon.ico" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS2 Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<meta name="google-site-verification" content="YZf8f0PRHZfL62L9Ja-_Nx_G7T4tv2j6JxIjRqyvTR0" />



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

		<div class="navbar-wrapper">
			<nav class="navbar navbar-default navbar-static-top" id="navbar">
				<div id="page-progress"></div>
				<div class="nav-border"></div>

				<div class="navbar-header">
					<div id="veggie" class="toggle-menu menu-left push-body">
						<i class="veggieburger"></i>
					</div>
					<a class="navbar-brand" href="<?php echo home_url(); ?>">
						<img src="<?php echo THEME_DIR; ?>/images/svg/db_logo.svg" />
					</a>

					<ul class="navbar-extras">
            	<?php /* <li class="site-dropdown">
            		<div data-toggle="channels">
            			Sites <span class="sites-open">+</span><span class="sites-close">-</span>
            		</div>
            		<?php db_channel_guide(); ?>
            	</li> */ ?>
            	<li class="navbar-mobile search-wrapper">
            		<ul>
            			<li class="header-search">
            				<?php get_search_form(); ?>
            			</li>
            			<li class="h-icon search" data-toggle="search-slide">
            				<i class="fa fa-times fa-fw"></i>
            				<i class="fa fa-search fa-fw"></i>
            			</li>
            		</ul>
            	</li>
            	<li class="h-icon divider">&nbsp;</li>
            	<li class="h-icon facebook"><a href="https://www.facebook.com/groups/torontoravecommunity/" target="_blank"><i class="fa fa-fw fa-facebook"></i></li>
            	<li class="h-icon twitter"><a href="https://twitter.com/torontorc" target="_blank"><i class="fa fa-fw fa-twitter"></i></a></li>
            	<li class="h-icon instagram"><a href="http://instagram.com/torontoravecommunity" target="_blank"><i class="fa fa-fw fa-instagram"></i></a></li>
            	<li class="h-icon soundcloud"><a href="http://instagram.com/torontoravecommunity" target="_blank"><i class="fa fa-fw fa-soundcloud"></i></a></li>
            	<li class="h-icon youtube"><a href="http://instagram.com/torontoravecommunity" target="_blank"><i class="fa fa-fw fa-youtube-play"></i></a></li>
            	<li class="h-icon divider">&nbsp;</li>
            	<li class="h-icon beatsxtra"><i class="svg svg-beatsxtra"></i></li>
            </ul>
        </div>

    </nav>
</div>

<nav id="cabinet" class="cbp-spmenu-left">
	<h2><span>Channels</span></h2>
	<ul class="sites">
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>
	<h2><span>Corporate</span</h2>

</nav>
<div class="cabinet-overlay"></div>
