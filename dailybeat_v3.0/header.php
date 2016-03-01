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

		<div class="navbar-wrapper">

			<nav class="navbar navbar-default navbar-fixed-top" id="navbar">
				<div id="page-progress"></div>
				<div class="nav-border"></div>

				<div class="navbar-header">
					<div id="veggie" class="toggle-menu menu-left push">
						<div class="veggieburger">	
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</div>
					</div>
					<a class="navbar-brand" href="<?php echo home_url(); ?>">
						<img src="<?php echo THEME_DIR; ?>/images/svg/db_logo.svg" />
					</a>

					<ul class="navbar-extras">
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
						<li class="social-header-icons">
							<ul>
								<li class="h-icon facebook"><a href="https://www.facebook.com/groups/torontoravecommunity/" target="_blank"><i class="fa fa-fw fa-facebook"></i></li>
								<li class="h-icon twitter"><a href="https://twitter.com/torontorc" target="_blank"><i class="fa fa-fw fa-twitter"></i></a></li>
								<li class="h-icon instagram"><a href="http://instagram.com/torontoravecommunity" target="_blank"><i class="fa fa-fw fa-instagram"></i></a></li>
								<li class="h-icon soundcloud"><a href="http://instagram.com/torontoravecommunity" target="_blank"><i class="fa fa-fw fa-soundcloud"></i></a></li>
								<li class="h-icon youtube"><a href="http://instagram.com/torontoravecommunity" target="_blank"><i class="fa fa-fw fa-youtube-play"></i></a></li>
								<li class="h-icon divider">&nbsp;</li>
							</ul>
						</li>
						<li class="navbar-player">
							<ul>
								<li>
									<div class="album">
										<div class="art"></div>
										<div class="play-overlay">
											<span class="fa-stack">
												<i class="fa fa-circle fa-stack-2x"></i>
												<i class="fa fa-play-circle fa-stack-1x"></i>
												<i class="fa fa-pause-circle fa-stack-1x"></i>
											</span>
										</div>
									</div>
								</li>
								<li class="track-info">
									<h3></h3>
									<h4></h4>
								</li>
							</ul>
						</li>
						<li class="h-icon beatsxtra" id="player_button"><i class="svg svg-beatsxtra"></i><i class="svg svg-beatsxtra-icon"></i></li>
					</ul>
				</div>

			</nav>
		</div>

		<nav id="cabinet" class="cbp-spmenu-left">
			<?php

			$channels = array(
				'0' => array('attack', 'Attack', 'http://daily-beat.com'),
				'1' => array('beatmersive', 'Beatmersive', 'http://beatmersive.com'),
				'3' => array('headliners', 'Headliners Tribune', 'http://dailybeatmedia.com'),
				'2' => array('fnt', 'Fresh New Tracks', 'http://freshnewtracks.com'),
				'4' => array('rr', 'Raver Rafting', 'http://raverrafting.daily-beat.com'),
				'5' => array('trc', 'Toronto Rave Community', 'http://trc.daily-beat.com')
				);		

			$corporate = array(
				'1' => array('dbm', 'Daily Beat Media', 'http://dailybeatmedia.com')
				);

				?>	

				<h2><span>Channels</span></h2>
				<ul class="sites">
					<?php foreach($channels as $key => $logo) {
						echo '<li><a href="' . $logo[2] .'" title="' . $logo[1] . '">';
						echo '<span class="svg svg-icon svg-' . $logo[0] . '-icon"></span><span class="svg svg-logo svg-' . $logo[0] . '-grey"></span>';		
						echo '</a></li>';
						echo '<li class="divider"></li>';
					}
					?>
				</ul>

				<h2><span>Corporate</span></h2>
				<ul class="sites">
					<?php foreach($corporate as $key => $logo) {
						echo '<li><a href="' . $logo[2] .'" title="' . $logo[1] . '">';
						echo '<span class="svg svg-icon svg-' . $logo[0] . '-icon"></span><span class="svg svg-logo svg-' . $logo[0] . '-grey"></span>';		
						echo '</a></li>';
						echo '<li class="divider"></li>';
					}
					?>
				</ul>
			</nav>
			<div id="cabinet-overlay"></div>


			<div id="player">
				<div class="loading"><i class="fa fa-spinner fa-pulse"></i></div>
				<div class="sc-player">
					<?php get_fnt_for_player(); ?>
				</div>
				<div class="offpage-fa-fix"><i class="fa fa-pause-circle"></i></div>
			</div>
