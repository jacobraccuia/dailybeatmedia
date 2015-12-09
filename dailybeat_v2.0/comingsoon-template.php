`<?php 
/**
 * Template Name: SPLASH - new site coming soon
 */
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<!-- meta tags -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="description" content="Keywords">
	<meta name="author" content="Name">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>Raw Tracks | Coming Soon</title>
	
    <link href='http://fonts.googleapis.com/css?family=Oswald:400,700' rel='stylesheet' type='text/css'>
    <script src="http://code.jquery.com/jquery-latest.min.js"
        type="text/javascript"></script>
	<style>
	
			/* http://meyerweb.com/eric/tools/css/reset/ 
   			v2.0 | 20110126
  			 License: none (public domain)
			*/

			html, body, div, span, applet, object, iframe,
			h1, h2, h3, h4, h5, h6, p, blockquote, pre,
			a, abbr, acronym, address, big, cite, code,
			del, dfn, em, img, ins, kbd, q, s, samp,
			small, strike, strong, sub, sup, tt, var,
			b, u, i, center,
			dl, dt, dd, ol, ul, li,
			fieldset, form, label, legend,
			table, caption, tbody, tfoot, thead, tr, th, td,
			article, aside, canvas, details, embed, 
			figure, figcaption, footer, header, hgroup, 
			menu, nav, output, ruby, section, summary,
				time, mark, audio, video {
					margin: 0;
					padding: 0;
					border: 0;
					font-size: 100%;
					font: inherit;
					vertical-align: baseline;
							}
				/* HTML5 display-role reset for older browsers */
				article, aside, details, figcaption, figure, 
				footer, header, hgroup, menu, nav, section {
					display: block;
				}
			body {
				line-height: 1;
			}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}
		html { font-family: 'Wire One', sans-serif;
			background: url('<?php bloginfo('template_url'); ?>/images/bg-diagonal.jpg') no-repeat center center fixed #000; 
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
	  	}
		#overlay { display: none; position: fixed; top: 30px; left: 0px; text-align: center; float: left; width: 100%; background: #000; height: 500px; margin: 0px auto; padding: 0px; }
		#overlaycontent { position:relative; padding-top: 5px; margin: 0px auto; width: 1140px; text-align:left; height:645px; display:none; }
		#overlaycontent iframe { display: block; margin-left: 215px; }
		
		#socialbar { display: block; position: absolute; width: 100%; height: 30px; top:0px; background-color: #181818; -webkit-box-shadow: 0 3px 3px rgba(0, 0, 0, 0.3); -moz-box-shadow: 0 3px 3px rgba(0, 0, 0, 0.3); box-shadow: 0 3px 3px rgba(0, 0, 0, 0.3); }
		#socialbannerwrapper { display: block; position: relative; height: 30px; width:1125px; margin:0px auto; text-align: left; color: #FFFFFF; font-family: "Gotham-Medium",Helvetica,sans-serif; font-size: 14px; line-height: 14px; }
		#socialbanner { display: inline-block; position: relative; text-align: left; height: 30px; vertical-align: middle; }
		#socialtextwrapper { display: inline-block; position: relative; height: 30px; top: 0px; color:#00adca; font-family:'Oswald'; font-size:16px; text-decoration:none; text-transform:uppercase; }
		#socialtext { display: table-cell; height: 30px; vertical-align: middle; }
		#socialwidgets { display: inline-block; position: relative; height: 30px; top: 5px; float: right; padding: 0px; }
		#footer {  display: block; position:absolute; width: 100%; bottom:0; text-align: center; height: 40px; margin:0px auto; padding:0px; background-color: #181818; -webkit-box-shadow: 0 3px 3px rgba(0, 0, 0, 0.3); -moz-box-shadow: 0 3px 3px rgba(0, 0, 0, 0.3); box-shadow: 0 -3px 3px rgba(0, 0, 0, 0.3); }
		#footercontent { text-align: center; width: 815px; margin:10px auto 0px; padding: 0; }
		h2 { margin:20px 0px; color:#00E2CF; padding:0px 20px 0px; font-family:'Oswald'; width:400px; display:block; margin:0px auto; text-align:center; font-size:40px; }
		h3 { margin:20px 0px; color:#00E2CF; padding:0px 20px 0px; font-family:'Oswald'; width:400px; display:block; margin:0px auto; text-align:center; font-size:26px; }
		div.form-c { position:relative; overflow:hidden; width:250px; text-align:center; position:absolute; margin-top:200px; margin-left:600px; border-radius:40px; -moz-border-radius:40px; -webkit-border-radius:40px;  border-radius:20px; -moz-border-radius:20px; -webkit-border-radius:20px;  background:rgba(0,0,0,.7); padding:5px 0px 15px; }
		div.live_stream {  width:100%; text-align:center; margin:0px auto; margin-top:-15px; }
		div.close { margin:0px auto; padding:0px; cursor:pointer; position:absolute; bottom:0px; }
		div.left_image { top: 0px; position: absolute; }
		div.days { width:1140px; margin:35px auto; position:relative; }
		div.wednesday {margin-left:330px; }
		div.thursday { margin-left:540px; }
		div.friday { margin-left:760px; }
		div.saturday { margin-left:980px; }
		.subscribe_input {  font-family: 'Oswald', sans-serif; width:350px; height:40px; padding:10px 120px 10px 20px; border-radius:20px; -moz-border-radius:20px; -webkit-border-radius:20px; border:0px; outline:0px; font-size:24px; font-weight:300; color:#333; }
		.submit-button { position:absolute; right:35px; top:5px; background:#00E2CF; color:#333; border:0px; outline:0px; width:113px; height:49px; box-sizing:border-box;  font-family: 'Oswald', sans-serif; font-size:24px; border-radius:20px; -moz-border-radius:20px; -webkit-border-radius:20px; }
		.submit-button:hover { background-color:#333; color:#00E2CF; }
		.day { position:relative; overflow:hidden; width:170px; text-align:center; position:absolute; margin-top:12px; border-radius:40px; -moz-border-radius:40px; -webkit-border-radius:40px;  border-radius:20px; -moz-border-radius:20px; -webkit-border-radius:20px;  background:rgba(0,0,0,.7); }
		.day a { padding:10px 0px; }
		.day:hover { cursor:pointer; }
		
		
		
		a span { font-size:110%; }
		a { margin:0px auto; display:block; width:100%; color:#e36486; font-family:'Oswald'; font-size:16px; text-decoration:none; text-transform:uppercase; } 
		a:hover { color:white; text-decoration:none; }
		
		@media screen and (max-width: 480px) {
			h2 { font-size:24px; width:240px; padding:10px 0px;  }
			h3 { font-size:16px; width:240px; padding:10px 0px; }
			div.form-c { width:240px; margin:0px auto; left:50%; padding:10px 20px; margin-left:-140px; }
    		.subscribe_input { font-size:16px; text-align:center; height:30px; width:90%; padding:15px; display:block; margin:0px auto; }
			.submit-button { display:block; position:relative; top:inherit; right:inherit; font-size:20px; text-align:center; margin:0px auto 10px; }
		}
	</style>


<script type="text/javascript">
<!--
if (screen.width <= 1140) {
document.location = "/blog";
}
//-->
</script>
    
   <script> 
	
   	$(document).ready(function() { 
		$('.day').on('click', function() {
			$('.vid_container').hide();
			
			var that = this;
			
			$('#overlay').fadeIn(1000, function() { $('#overlay').animate({ height: 100 + '%' }, 1000, function() {
				if($(that).hasClass('wednesday')) { $('.wednesday_vid').show(); }
				if($(that).hasClass('thursday')) { $('.thursday_vid').show(); }
				if($(that).hasClass('friday')) { $('.friday_vid').show(); }
				if($(that).hasClass('saturday')) { $('.saturday_vid').show(); } 
				
				$('#overlaycontent').show();
				
			});
			});
		});
   	
	   	$('.close').on('click', function() {
			$('#overlaycontent').fadeOut();
			$('#overlay').animate({height:500}, 1000, function() { $(this).fadeOut(1000); });
		});  
   	
   	}); 
   	
   </script>
    
</head>
<body>
<div id="socialbar">
	<div id="socialbarwrapper">
	<div id="socialbannerwrapper">
	<div id="socialtextwrapper"><span id="socialtext">DAILY BEAT LIVE STREAM 03.29.14</span></div>
	<div id="socialwidgets">
	<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2FBeaconDailyBeat&amp;width&amp;layout=button_count&amp;action=like&amp;height=21&amp;appId=341242722673656" scrolling="no" frameborder="0" style="border:none; width:85px; overflow:hidden; height:20px;" allowTransparency="true"></iframe>
	
	<a href="https://twitter.com/intent/tweet?button_hashtag=DailyBeatLive&text=I%20am%20currently%20watching%20the%20%40BeaconDailyBeat%20Live%20stream.%20Check%20it%20out%20on%20daily-beat.com%20" class="twitter-hashtag-button" data-related="BeaconDailyBeat">Tweet #DailyBeatLive</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
	
	</div>
	</div>
	</div>
</div>


<div class="days">
	<div class="live_stream"><img src="<?php echo THEME_DIR; ?>/images/DailyBeatDBBanner.png" /></div>
		<div class="wednesday day"><a>WATCH </br> <span>03.26 LIVE STREAM</span></a></div>
	<div class="thursday day"><a>WATCH </br> <span>03.27 LIVE STREAM</a></span></div>
	<div class="friday day"><a>WATCH </br> <span>03.28 LIVE STREAM</a></span></div>
	<div class="saturday day"><a>WATCH </br> <span>03.29 LIVE STREAM</a></span></div>
</div>



<div id="overlay">
	<div id="overlaycontent">
		<div class="wednesday_vid vid_container"><div class="left_image"><img src="<?php echo THEME_DIR; ?>/images/wednesday.png" /></div><iframe width="920" height="518" src="//www.youtube.com/embed/6N-B46lh6Uo" frameborder="0" allowfullscreen></iframe></div>
		
		<div class="thursday_vid vid_container"><div class="left_image"><img src="<?php echo THEME_DIR; ?>/images/thursday.png" /></div><iframe width="920" height="518" src="//www.youtube.com/embed/rVdXs2HhG14&t" frameborder="0" allowfullscreen></iframe></div>
		
		<div class="friday_vid vid_container"><div class="left_image"><img src="<?php echo THEME_DIR; ?>/images/friday.png" /></div><iframe width="920" height="518" src="//www.youtube.com/embed/8VbUjoWUwM8" frameborder="0" allowfullscreen></iframe></div>
		
		<div class="saturday_vid vid_container"><div class="left_image"><img src="<?php echo THEME_DIR; ?>/images/saturday.png" /></div><iframe width="920" height="518" src="//www.youtube.com/embed/tcnpRIckJHQ" frameborder="0" allowfullscreen></iframe></div>
		
		<div class="close">
			<img src="<?php echo THEME_DIR; ?>/images/sponsorbar.png" />
			<a><img src="<?php echo THEME_DIR; ?>/images/closebutton.png" /></a>
		</div>
	
		
</div>

</div>

<div id="footer"><div id="footercontent"><a href="http://daily-beat.com/blog">Continue to daily-beat.com</a></div></div>



	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40411404-1', 'daily-beat.com');
  ga('send', 'pageview');

</script>




</body>
</html>