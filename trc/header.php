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

<body <?php body_class(); ?>>
<a href="https://plus.google.com/114713860257080729502" rel="publisher" style="display:inline;"></a>
<?php

	$class = "";
	if(is_single() || is_archive()) {
		global $post;
		$id = $post->ID;
		$category = get_primary_category($id);		
		$class = $category->slug;
	}
?>
<div class="navbar-wrapper <?php echo $class; ?>">
	<div class="navbar navbar-default navbar-static-top cat-border" id="navbar" role="navigation">
		<div class="navbar-header">
			<?php $logo = '<img src="' . THEME_DIR . '/images/trc.png"  alt="logo"/>'; ?>
			<a class="navbar-brand" href="<?php echo home_url(); ?>"><?php echo $logo; ?></a>
		</div>
		<div class="collapse navbar-collapse navbar-ex1-collapse">
			
            <?php
				$q_class = $h_class = "";	
				if(is_single()) { $q_class = 'nav-slide'; $h_class = 'show-me'; }
			?>
            
            <div class="cat-links <?php echo $q_class; ?>">
  	        	<div class="handle <?php echo $q_class; ?>">
                <button type="button" class="navbar-toggle">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
				</button>
               </div>

				<?php if(is_single()) { ?>
                    <div class="single-header-title"><div class="single-header-cat cat-background"><?php echo $category->name; ?></div><?php the_title(); ?></div>
                <?php } ?>
            

				<?php 
                    wp_nav_menu( array(
                        'theme_location'    => 'primary_menu',
                        'depth'             => 2,
                        'container'         => false,
                        'menu_class'        => 'nav navbar-nav',
                        'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                        'walker'            => new wp_bootstrap_navwalker())
                        );
                ?>
			</div>				
			<ul class="header-icons">
				<li class="subscribe dd" data-toggle="navbar-dropdown" data-target="subscribe"><i class="fa fa-fw fa-envelope"></i></a></li>
				<li class="social facebook"><a href="https://www.facebook.com/groups/torontoravecommunity/" target="_blank"><i class="fa fa-fw fa-facebook"></i></a></li>
				<li class="social twitter"><a href="https://twitter.com/torontorc" target="_blank"><i class="fa fa-fw fa-twitter"></i></a></li>
				<li class="social instagram"><a href="http://instagram.com/torontoravecommunity" target="_blank"><i class="fa fa-fw fa-instagram"></i></a></li>
				<li class="search dd" data-toggle="navbar-dropdown" data-target="search"><i class="fa fa-search fw"></i></li>
				<li class="channel-list dd" data-toggle="channels"><div class="leaf"></div>
					<?php db_channel_guide(); ?>
				</li>
			</ul>
		</div><!--/.nav-collapse -->
		<div id="navbar-dropdown">
			<div class="container">
				<?php db_search(); ?>	
			</div>
            
			<div class="container">
				<div id="subscribe-dropdown" class="dropdown-content">
					<div class="row">
							<div class="col-lg-1 hidden-md hidden-sm hidden-xs"></div>
							<div class="col-lg-5 col-md-6">
								<h2 class="section-title">Subscribe to our Mailing List</h2>
                                <p style="margin-top:10px;">Don’t miss out on all the biggest news in TRC!<br/><br/>
                                    Be the first to know about major TRC news, announcements, giveaways, and more right to your inbox.
                                    <br/><br/>
                                    Sign up here!
								</p>
							</div>
							<div class="col-lg-5 col-md-6">
							
								<!-- Begin MailChimp Signup Form -->
								<div id="mc_embed_signup">
									<form action="//beaconrecords.us8.list-manage.com/subscribe/post?u=90eeba7b59fa4c32b1d5dab94&amp;id=a49f487d2d" method="post" id="mc-embedded-subscribe-form" name=				"mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
										
										<div id="mc_embed_signup_scroll">
											<div class="mc-field-group">
												<label for="mce-EMAIL">Email Address </label>
												<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
											</div>
											<div class="names ">
												
												<div class="side side-first">
													<label for="mce-FNAME">First Name </label>
													<input type="text" value="" name="FNAME" class="first-name" id="mce-FNAME">
												</div>
												<div class="side">
													<label for="mce-LNAME">Last Name </label>
													<input type="text" value="" name="LNAME" class="last-name" id="mce-LNAME">
												</div>
											</div>
											<div id="mce-responses" class="clear">
												<div class="response" id="mce-error-response" style="display:none"></div>
												<div class="response" id="mce-success-response" style="display:none"></div>
											</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
											<div style="position: absolute; left: -5000px;"><input type="text" name="b_90eeba7b59fa4c32b1d5dab94_a49f487d2d" tabindex="-1" value=""></div>
											<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
										</div>
									</form>
								</div>
								
<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
								<!--End mc_embed_signup-->
							
							</div>
							<div class="col-sm-1 hidden-md hidden-sm hidden-xs"></div>
						</div>
                </div>
			</div>
		</div>
	</div>
</div>


