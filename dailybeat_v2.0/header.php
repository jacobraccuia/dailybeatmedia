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

<?php if(!is_singular('premiere')) { ?>

<div class="navbar_wrapper">
	<div class="navbar navbar-default navbar-static-top sticky_waypoint" id="navbar" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo home_url(); ?>"><img src="<?php echo THEME_DIR; ?>/images/logo.png" width="182" height="25" alt="logo" /></a>
			</div>
			<div class="collapse navbar-collapse navbar-ex1-collapse">
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
				<?php $ph = "GOLD ALL IN MY SEARCH"; ?>
				<form action="<?php echo home_url(); ?>" id="search_form" class="search_wrapper navbar-form navbar-right" method="get">
					<label class="sr-only" for="s">Search</label>
					<div class="form-group">
						<input type="text" class="search_input" id="s" name="s" autocomplete="off" 
							value="<?php echo $ph; ?>"
							onfocus="if(this.value=='<?php echo $ph; ?>')this.value='';"
							onblur="if(this.value=='')this.value='<?php echo $ph; ?>'"
							placeholder="<?php echo $ph; ?>"
						/>
					</div>
					<input type="submit" value="" id="search_submit" class="search_submit button normal" />
				</form>
			</div><!--/.nav-collapse -->
		</div>
	</div>
</div>



<?php } else { // premiere page<br>

	global $post;
	$id = $post->ID;
	$db_soundcloud_color = get_post_meta($id, 'db_soundcloud_color', true);

	$permalink = get_permalink($id);
	$title = get_the_title($id);



?>

	<div class="navbar_wrapper">
		<div class="navbar navbar-default navbar-static-top sticky_waypoint" id="navbar" role="navigation" style="border-bottom-color:<?php echo $db_soundcloud_color; ?>!important;">
			<div class="navbar-header">
				<?php $logo = '<img src="' . THEME_DIR . '/images/db_first_transparent.png" width="139" height="39" alt="logo" style="background-color:' . $db_soundcloud_color . '!important;"/>'; ?>
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
<?php } ?>


<?php /*
<div class="sticky_header navbar navbar-default navbar-static-top" id="navbar-affix">
<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand small_logo" href="<?php echo home_url(); ?>"></a>
		</div>
		<div class="collapse navbar-collapse navbar-ex1-collapse">
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
			<?php $ph = "GOLD ALL IN MY SEARCH"; ?>
			<form action="<?php echo home_url(); ?>" id="search_form" class="search_wrapper navbar-form navbar-right" method="get">
				<label class="sr-only" for="s">Search</label>
				<div class="form-group">
					<input type="text" class="search_input" id="s" name="s" autocomplete="off" 
						value="<?php echo $ph; ?>"
						onfocus="if(this.value=='<?php echo $ph; ?>')this.value='';"
						onblur="if(this.value=='')this.value='<?php echo $ph; ?>'"
						placeholder="<?php echo $ph; ?>"
					/>
				</div>
				<input type="submit" value="" id="search_submit" class="search_submit button normal" />
			</form>
		</div><!--/.nav-collapse -->
	</div>
</div> */ ?>

		<!-- Place in head part widget:dlbt002 -->
<script type="text/javascript">

var sbElementInterval = setInterval(function(){sbElementCheck()}, 50);

function sbElementCheck() {

var targetedElement = document.getElementById('ingageunit');
if(targetedElement) {
clearInterval(sbElementInterval);
(function(d) {
var js, s = d.getElementsByTagName('script')[0];
js = d.createElement('script');
js.async = true;
js.onload = function(e) {
SbInGageWidget.init({
partnerId : 4047,
widgetId : 'dlbt002',
cmsPath : 'http://cms.springboardplatform.com'
});
}
js.src = "http://cdn.springboardplatform.com/storage/js/ingage/apingage.min.js";
s.parentNode.insertBefore(js, s);
})(window.document);
}
}
</script>
<!-- Place in head part -->
