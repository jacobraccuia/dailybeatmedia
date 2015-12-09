jQuery(document).ready(function($) {
	
	$('#featured_slider').responsiveSlides({
		auto: true,             // Boolean: Animate automatically, true or false
		speed: 500,            // Integer: Speed of the transition, in milliseconds
		timeout: 7000,          // Integer: Time between slide transitions, in milliseconds
		pager: false,           // Boolean: Show pager, true or false
		nav: false,             // Boolean: Show navigation, true or false
		random: false,          // Boolean: Randomize the order of the slides, true or false
		pause: true,           // Boolean: Pause on hover, true or false
		pauseControls: true,    // Boolean: Pause when hovering controls, true or false
		prevText: "<",   // String: Text for the "previous" button
		nextText: ">",       // String: Text for the "next" button
		maxwidth: "1140",           // Integer: Max-width of the slideshow, in pixels
		navContainer: "",       // Selector: Where controls should be appended to, default is after the 'ul'
		manualControls: "",     // Selector: Declare custom pager navigation
		namespace: "rslides",   // String: Change the default namespace used
		before: function(){},   // Function: Before callback
		after: function(){}     // Function: After callback
	});
	
	
	// if single meta bar expands to two lines, remove last category
	if($('.single .meta').height() > 30) {
		var cat = $('.single .meta .category').html();
		
		// split to array, pop last one off, join, boom done!
		cat = cat.split(', ');
		if(cat.length > 1) {
			cat.pop();
			cat = cat.join(', ');
		}
		
		$('.single .meta .category').html(cat);
		
	}
	
	if($('p.no-results').length) { $('.load_posts').hide(); }
	
	// NOTE: post image resize takes place in ajax.js
	
	/*
	$('.navbar.sticky_waypoint').waypoint(function() {
		if($(".sticky_header").is(":hidden")) {
			$(".sticky_header").fadeIn(200);
		} else {
			$(".sticky_header").fadeOut(200);
		}
	});
	
	*/
	
	$('#navbar').affix({
    	offset: { top: 0 } //$('#navbar').offset().top } // you only need this if it's not at top...
	});
	
	$('.navbar_wrapper').height($("#navbar").height()); // IMPORTANT - this prevents jumping of page on scroll	

	
	/*
	if($(window).height() < 720 ) {
		
		$(window).off('.affix');
		$(".twitter")
			.removeClass("affix affix-top affix-bottom")
			.removeData("bs.affix");
	
	}*/
	
	
		
		
	$('.single h1.title.sticky_waypoint').waypoint(function() {
		if($(".sticky_title").is(":hidden")) {
			$(".sticky_title").fadeIn(200);
		} else {
			$(".sticky_title").fadeOut(200);
		}
	});
	
	$('.sticky_search_waypoint').waypoint(function() {
		if($(".sticky_search").is(":hidden")) {
			$(".sticky_search").fadeIn(200);
		} else {
			$(".sticky_search").fadeOut(200);
		}
	});
	
	
	$('<a href="http://origin.www.zuus.com/music-videos/daily_beat" target="_blank" class="edc_background_link"></a>').appendTo($('body:not(.postid-29160, .postid-29268, .postid-29320, .postid-29437)'));	
	$('<a href="http://www.millioneiress.com/" target="_blank" class="background_link"></a>').appendTo($('.postid-29160, .postid-29268, .postid-29320, .postid-29437'));	
	
	
	
	// navigation hovering
	$('.dropdown-menu li').mouseenter(function() {
		
		
		$('.dropdown-menu li').removeClass('active');
		$(this).addClass('active');
		
		var id = $(this).prop('id');
		//id.substr(10, id.length);
		
		var parent = $(this).closest('.dropdown-menu');
		parent.find('.dropdown').hide();
		$('.dropdown.' + id).show();
	});
	

	// to remove the dumb pink bar
	$('#navbar li.menu-item').mouseenter(function() {
		$('#navbar').css('z-index', 1002);
		$('.search_dropdown').removeClass('search_bottom_border');
	});
	
	$('#navbar li.menu-item').mouseleave(function() {
		$('#navbar').css('z-index', 1000);
		$('.search_dropdown').addClass('search_bottom_border');
	});
	
//	$('.dropdown h4.title').ellipsis();
	
	$('.navbar-toggle').on('click', function() {
		$('.search_dropdown').removeClass('open').hide();
	});
	
	$('.search_icon').on('click', function() {
		// if search bar is open, close dropdown
		if($('.search_dropdown').hasClass('open')) {
			$('.search_dropdown').removeClass('open').hide();
			return;
		}
			
		/* close the navbar! */	
		$('.navbar-collapse').addClass('no-transition').stop().css({'height': '1px'}).removeClass('in').addClass('collapse');
		$('.navbar-toggle').stop().removeClass('collapsed');
		$('.navbar-collapse').removeClass('no-transition');
		
		$('.search_dropdown').addClass('open').show();
		$('.search_input').focus();
		
	});
	
		
	$(document).on('scroll', function() {
		if($('.search_dropdown').hasClass('open')) {
			$('.search_dropdown').removeClass('open').slideUp();
			return;
		}
	});
	
	// fade in player for zuus
		$('.zuus_player').on('click', function() {
			
			$('#navbar').css('z-index', 2000);
				
			html = $('#zuus-widget').html();
			if(html == "") {
				
			$('.zuus_content').append("<iframe src='http://www.zuus.com/players/simple?lr_publisher_id=112058&width=640&height=360&mute=false&mute_start=true&auto_play=true&context=hulk_3rdparty&partner_id=dailybeat&simple=false&uuid=32ca' scrolling='no' style='width: 640px; height: 360px; border: 0px; overflow: hidden;'></iframe>");

				
		//		$.getScript("http://player.zuus.com/assets/js/zuusWidget.js");	
			}
						
			$('.zuus_overlay').fadeIn(500, function() {
				$('.zuus_overlay').animate({ height: 100 + '%' }, 1000, function() {
					$('.zuus_content').show();
			
					$('.zuus_overlay .close').show();
					$('body').addClass('stop_scrolling');
				});
			});
		});
   	
	   	$('.close, .zuus_overlay').on('click', function() {
			$('.zuus_overlay').fadeOut();
			$('.zuus_overlay .close').fadeOut();
			$('body').removeClass('stop_scrolling');
			$('.zuus_content').animate({height:500}, 1000, function() { $(this).fadeOut(1000); });
		
			$('#navbar').css('z-index', 1000);
		});  
	
	
	/* play buttons */
	$('.premiere-title .sc-play').on('click', function() {
		$('#large-waveform').addClass('active');
		
		$(this).addClass('hidden');
		$(this).siblings('.sc-pause').removeClass('hidden');
		$(this).parents('.content-overlay').find('.full-width-soundcloud .sc-play').click();
	});
	
	
	$('.premiere-title .sc-pause').on('click', function() {
		$(this).addClass('hidden');
		$(this).siblings('.sc-play').removeClass('hidden');
		$(this).parents('.content-overlay').find('.full-width-soundcloud .sc-pause').click();
	});
	
	
		
	// twitter API
	!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="http://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");

	
});