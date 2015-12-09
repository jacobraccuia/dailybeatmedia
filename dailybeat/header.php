<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<!--Shuffler-3f632d6a863c313a6aef6e96f36d1091-->
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />


<title>
<?php if ( is_front_page() ) { ?><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>
<?php } else { ?><?php wp_title($sep = ''); ?>
<?php } ?>
</title>

<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url'); ?>" title="<?php printf( __( '%s latest posts', 'your-theme' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'your-theme' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />


<!-- Attached CSS Stylesheets -->
<link rel="stylesheet" href="<?php echo get_bloginfo('template_directory');?>/style.css" />
<link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:700,400' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,700' rel='stylesheet' type='text/css'>
<?php wp_enqueue_script('jquery'); ?>
<?php wp_head(); ?>

<script>
jQuery(document).ready(function($){


			$('.sat').waypoint(function() {
			  if ($(".nav2").is(":hidden")) {
				$(".nav2").fadeIn(200);
			  } else {
					$(".nav2").fadeOut(200);
				}
			});
			
			$('.ratingbar').waypoint({
				
				offset: 120,
				handler: function(direction) {
				$('.sharethisbar').toggleClass('stuck');
			}
			});
			
			$('.sharethisbar').waypoint('sticky', {
				offset: 100 // Apply "stuck" when element 30px from top

			});
				
		

			
			
			
		jQuery('.nav ul li').hover(function() { hoveron(); }, function() { hoveroff(); });
		
		function hoveron() {
			$('#headerlogo').css("background", "url('<?php bloginfo('template_url'); ?>/images/dblogohover.jpg') repeat scroll 0 0 transparent");
		}
		function hoveroff() {
			$('#headerlogo').css("background", "url('<?php bloginfo('template_url'); ?>/images/dblogo.jpg') repeat scroll 0 0 transparent");
		}
		
		$('iframe').each( function() {
    var url = $(this).attr("src")
    $(this).attr({
        "src" : url.replace('?rel=0', '')+"?wmode=transparent",
        "wmode" : "Opaque"
    })
});
	}); 
</script>

</head>
<body>

<div id="wrapper">
<div id="contentwrapper">

<div id="header">

	<div id="headerlogo"><a href="http://daily-beat.com"><div class="logoclick"></div></a><div class="logohover"><img src="<?php bloginfo('template_url'); ?>/images/dblogohover.jpg"></div></div>
    
  <!--  <div class="headerlinks">
    <div style="height:75px; position:absolute; border-left:1px dashed #bf0000; width:1px;"></div>
    </div>
    -->
    <div class="nav sat">
        <ul>
           <li class="a" id="<?php if(in_category('News')) echo "n1a"; ?>"><a href="http://www.daily-beat.com/category/news/">News</a>
           <ul>
                	<li><a href="http://www.daily-beat.com/category/celebrity-gossip/">Celebrity Gossip</a></li>
                	<li><a href="http://www.daily-beat.com/category/hollywood/">Hollywood</a></li>
                	
             </ul>   	
            </li>
            <li class="b" id="<?php if(in_category('Electronic')) echo "n1a"; ?>"><a href="http://www.daily-beat.com/category/electronic/">Electronic</a>
            	<ul>
                	<li><a href="http://www.daily-beat.com/category/dance">Dance</a></li>
                	<li><a href="http://www.daily-beat.com/category/disco">Disco</a></li>
                	<li><a href="http://www.daily-beat.com/category/house">House</a></li>
                	<li><a href="http://www.daily-beat.com/category/electro">Electro</a></li>
                	<li><a href="http://www.daily-beat.com/category/dubstep-2">Dubstep</a></li>
                	<li><a href="http://www.daily-beat.com/category/trance">Trance</a></li>
            	</ul></li>
            <li class="c" id="<?php if(in_category('Hip-hop')) echo "n1a"; ?>"><a href="http://www.daily-beat.com/category/hip-hop/">Hip-Hop</a>
            	<ul class="left2">
                	<li><a href="http://www.daily-beat.com/category/rap">Rap</a></li>
                	<li><a href="http://www.daily-beat.com/category/underground">Underground</a></li>
                	<li><a href="http://www.daily-beat.com/category/mixtape">Mixtapes</a></li>
                	<li><a href="http://www.daily-beat.com/category/old-school">Old School</a></li>
            	</ul></li>
        </ul> 
       <ul class="right">
	        <li class="d" id="<?php if(in_category('Alternative')) echo "n1a"; ?>"><a href="http://www.daily-beat.com/category/alternative/">Alternative</a>
            	<ul>
    			 	<li><a href="http://daily-beat.com/category/indie-pop/">Indie Pop</a></li>
					<li><a href="http://daily-beat.com/category/rock/">Rock</a></li>
					<li><a href="http://daily-beat.com/category/chill-out/">Chill Out</a></li>
            	</ul></li>	
            <li class="e" id="<?php if(in_category('Lifestyle')) echo "n1a"; ?>"><a href="http://www.daily-beat.com/category/lifestyle/">Lifestyle</a>
            	<ul class="left2">
                	<li><a href="http://daily-beat.com/category/mens-fashion">Men's Fashion</a></li>
					<li><a href="http://daily-beat.com/category/womens-fashion">Women's Fashion</a></li>
					<li><a href="http://daily-beat.com/category/giveaway/">Giveaway</a></li>
					<li><a href="http://daily-beat.com/category/video/">Video</a></li>
					<li><a href="http://daily-beat.com/category/festivals/">Festivals</a></li>
					<li><a href="http://daily-beat.com/category/events/">Events</a></li>
            	</ul></li>
            <li class="f" id="<?php if(in_category('Interviews')) echo "n1a"; ?>"><a href="http://www.daily-beat.com/category/interviews/">Interviews</a></li>
		</ul>
		
	
    </div>

	<div class="nav nav2"> <!-- hidden nav for scroll -->
		<ul>
		   <li class="a" id="<?php if(in_category('News')) echo "n1a"; ?>"><a href="http://www.daily-beat.com/category/news/">News</a></li>
			<li class="b" id="<?php if(in_category('Electronic')) echo "n1a"; ?>"><a href="http://www.daily-beat.com/category/electronic/">Electronic</a></li>
			<li class="c" id="<?php if(in_category('Hip-hop')) echo "n1a"; ?>"><a href="http://www.daily-beat.com/category/hip-hop/">Hip-Hop</a></li>
		</ul> 
	   <ul class="right">
			<li class="d" id="<?php if(in_category('Alternative')) echo "n1a"; ?>"><a href="http://www.daily-beat.com/category/alternative/">Alternative</a></li>	
			<li class="e" id="<?php if(in_category('Lifestyle')) echo "n1a"; ?>"><a href="http://www.daily-beat.com/category/lifestyle/">Lifestyle</a></li>
			<li class="f" id="<?php if(in_category('Interviews')) echo "n1a"; ?>"><a href="http://www.daily-beat.com/category/interviews/">Interviews</a></li>
		</ul>
	</div>

    <div class="ticker">
		<div id="innerwrap">
		<span class='tickeroverlay-left'><strong>Latest</strong></span><div class='lefttriangle'></div>
			<div class='tickercontainer'>
                <strong>Stay Connected</strong>
                <a href="http://facebook.com/beacondailybeat"><span class="fbook"></span>BeaconDailyBeat</a>
                <a href="http://twitter.com/beacondailybeat"><span class="twitter"></span>@BeaconDailyBeat</a>
                <a href="mailto:dailybeat@beaconrecords.com"><span class="mail"></span>DailyBeat@BeaconRecords.com</a>
			</div>
		</div>
	</div>
    
</div>