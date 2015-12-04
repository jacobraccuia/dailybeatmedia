<?php 
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
	<title>DAILY BEAT: HELLO PHASE TWO</title>

    <link href='http://fonts.googleapis.com/css?family=Oswald:400,700' rel='stylesheet' type='text/css'>
    <script src="http://code.jquery.com/jquery-latest.min.js"
        type="text/javascript"></script>
	<style>
		html { font-family: 'Wire One', sans-serif;
			background: url('<?php bloginfo('template_url'); ?>/images/new_site_bg.jpg') no-repeat center center fixed; 
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
	  	}
		
		#logo { text-align:center; margin:-24px auto 0px; width:260px; background:rgba(0,0,0,.7); padding:20px 12px 5px;  border-radius:10px; -moz-border-radius:10px; -webkit-border-radius:10px;  }
		#logo img { margin:0px; padding:0px; }
		h2 { margin:20px 0px; color:#00E2CF; padding:10px 20px 0px; font-family:'Oswald'; width:400px; display:block; margin:0px auto; text-align:center; font-size:40px; }
		h3 { margin:20px 0px; color:#00E2CF; padding:10px 20px 0px; font-family:'Oswald'; width:400px; display:block; margin:0px auto; text-align:center; font-size:26px; }
		div.form-c { position:relative; overflow:hidden; width:550px; text-align:center; position:absolute; top:35%; left:50%; margin-left:-275px; border-radius:40px; -moz-border-radius:40px; -webkit-border-radius:40px;  border-radius:20px; -moz-border-radius:20px; -webkit-border-radius:20px;  background:rgba(0,0,0,.7); padding:5px 0px 15px; }
		.subscribe_input {  font-family: 'Oswald', sans-serif; width:350px; height:40px; padding:10px 120px 10px 20px; border-radius:20px; -moz-border-radius:20px; -webkit-border-radius:20px; border:0px; outline:0px; font-size:24px; font-weight:300; color:#333; }
		.submit-button { position:absolute; right:35px; top:5px; background:#00E2CF; color:#333; border:0px; outline:0px; width:113px; height:49px; box-sizing:border-box;  font-family: 'Oswald', sans-serif; font-size:24px; border-radius:20px; -moz-border-radius:20px; -webkit-border-radius:20px; }
		.submit-button:hover { background-color:#333; color:#00E2CF; }
		
		a { margin:0px auto; display:block; width:100%; color:#00E2CF; font-family:'Oswald'; font-size:16px; text-decoration:none; text-transform:uppercase; } 
		a:hover { color:white; text-decoration:none; }
		
		@media screen and (max-width: 480px) {
			h2 { font-size:24px; width:240px; padding:10px 0px;  }
			h3 { font-size:16px; width:240px; padding:10px 0px; }
			div.form-c { width:240px; margin:0px auto; left:50%; padding:10px 20px; margin-left:-140px; }
    		.subscribe_input { font-size:16px; text-align:center; height:30px; width:90%; padding:15px; display:block; margin:0px auto; }
			.submit-button { display:block; position:relative; top:inherit; right:inherit; font-size:20px; text-align:center; margin:0px auto 10px; }
		}
	</style>
    <script>
		jQuery(document).ready(function($) {
			
			
			$('.submit-button').on('click', function(e) {
	//			e.preventDefault();
	//			window.location.replace('http://daily-beat.com');
			});
		});
    </script>
</head>
<body>

<div id="logo">
<img src="<?php bloginfo('template_url'); ?>/images/logo_250.png" />
<br/>
</div>

<script type="text/javascript">var submitted=false;</script>
    <iframe name="hidden_iframe" id="hidden_iframe" style="display:none;" onload="if(submitted) { $('h3').css({'font-size':'24px', 'line-height':'30px', 'margin-bottom':'15px'}).html('Thank you for submitting your e-mail.<br/>We will notify you when PHASE TWO of Daily-Beat.com is LIVE!'); $('.hide-on-success').hide(); }"></iframe>
<form action="https://docs.google.com/forms/d/1sRfwyCI7FQ1xwvXLXETRKPc8w0FyhHr4SiIdlKPJl7k/formResponse" method="POST" id="ss-form" target="hidden_iframe" onsubmit="submitted=true;">
<div class="form-c">
<h2 class="hide-on-success">HELLO PHASE TWO</h2>
<h3>COMING DECEMBER 2013</h3>
<div style="width:100%; position:relative;" class="hide-on-success">
<p class="wysija-paragraph">
  
<?php $ph = "ENTER YOUR EMAIL FOR UPDATES"; ?>
<input type="text" name="entry.2144135102" value="" class="ss-q-short subscribe_input" id="entry_2144135102" dir="auto" aria-label="Untitled Question  "
		value="<?php echo $ph; ?>"
		onfocus="if(this.value=='<?php echo $ph; ?>')this.value='';"
		onblur="if(this.value=='')this.value='<?php echo $ph; ?>'"
		placeholder="<?php echo $ph; ?>"/>
    
</p>
<input class="submit-button" name="submit" type="submit" value="NOTIFY" />
</div>
<a href="http://daily-beat.com/blog">Continue to daily-beat.com</a>
</div>

 </form></div>





</body>
</html>