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
	

	<title>
		<?php
			if(is_front_page()) {
				echo get_bloginfo('name') . get_bloginfo('description');
			} else {
				wp_title($sep = '');
			}
		?>
	</title>	


	<?php wp_head(); ?> 

</head>
<script type='text/javascript'>
	var googletag = googletag || {};
	googletag.cmd = googletag.cmd || [];
	(function() {
	var gads = document.createElement('script');
	gads.async = true;
	gads.type = 'text/javascript';
	var useSSL = 'https:' == document.location.protocol;
	gads.src = (useSSL ? 'https:' : 'http:') + 
	'//www.googletagservices.com/tag/js/gpt.js';
	var node = document.getElementsByTagName('script')[0];
	node.parentNode.insertBefore(gads, node);
})();

	googletag.cmd.push(function() {
	googletag.defineSlot('/1103149/DailyBeat_160x600', [160, 600], 'div-gpt-ad-1395271462856-0').addService(googletag.pubads());
	googletag.defineSlot('/1103149/DailyBeat_300x250', [300, 250], 'div-gpt-ad-1395271462856-1').addService(googletag.pubads());
	googletag.defineSlot('/1103149/DailyBeat_300x600', [300, 600], 'div-gpt-ad-1395271462856-2').addService(googletag.pubads());
	googletag.defineSlot('/1103149/DailyBeat_728x90', [728, 90], 'div-gpt-ad-1395271462856-3').addService(googletag.pubads());
	googletag.defineSlot('/1103149/DailyBeat_Skin', [1, 1], 'div-gpt-ad-1395271462856-4').addService(googletag.pubads());
	googletag.pubads().enableSingleRequest();
	googletag.enableServices();
});
</script>

<body <?php body_class(); ?>>
<!-- JAVASCRIPT SDK CODE -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=341242722673656";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<?php


	global $post;
	$id = $post->ID;
	$db_soundcloud_color = '#00dbc9';

	$permalink = home_url();
	$title = 'The NEW Toronto Rave Community (Coming Winter 2014)';



?>

	<div class="navbar_wrapper">
		<div class="navbar navbar-default navbar-static-top sticky_waypoint" id="navbar" role="navigation" style="border-bottom-color:<?php echo $db_soundcloud_color; ?>!important;">
			<div class="navbar-header">
				<?php $logo = '<img src="' . THEME_DIR . '/images/trc.png"  alt="logo"/>'; ?>
				<a class="navbar-brand" href="<?php echo home_url(); ?>"><?php echo $logo; ?></a>
			</div>
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="header-icons">
					<li class="social facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $permalink; ?>" data-shared="<?php echo $title; ?>" data-social="Facebook" target="_blank"><i class="fa fa-fw fa-facebook"></i></a></li>
					<li class="social twitter"><a href="https://twitter.com/share?url=<?php echo $permalink; ?>" data-shared="<?php echo $title; ?>" data-social="Twitter" target="_blank"><i class="fa fa-fw fa-twitter"></i></a></li>
					<li class="social google"><a href="https://plus.google.com/share?url=<?php echo $permalink; ?>" data-shared="<?php echo $title; ?>" data-social="Google Plus" target="_blank"><i class="fa fa-fw fa-google-plus"></i></a></li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>
