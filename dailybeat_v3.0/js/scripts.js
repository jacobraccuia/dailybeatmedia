jQuery(document).ready(function($) {

	$('#navbar').affix({
		offset: { top: 0 }
	});
	/*
	if($('.custom-ad.sidebar').length) { // && $('#sidebar').height() > $('.sidebar-height').height()) {
		sticky = $('.container').stickem({
			 item: '.custom-ad.sidebar',
			 container: '#sidebar',
			 offset: $('#navbar').height() + $('#wpadminbar').height() + 20,
			 stickClass: 'sticky',
			 endStickClass: 'sticky-bottom', 
			 start: $('.custom-ad.sidebar').offset().top - $('#sidebar').offset().top - 3
		});
	}
	
	//*/
	

	$('.handle').on('click', function(e) {
		e.stopPropagation();
		if($('.nav-slide').hasClass('open')) { 
			$('.nav-slide').removeClass('open');
			return;
		}
		
		$('.nav-slide').addClass('open');

	});

	
	$('.toggle-menu').jPushMenu();

	/* truncate meeeee */
	$('.post-wrapper .post-info h3').dotdotdot({
		watch: "window"
	});



	/* music playaaa */
	$('#player_button').on('click', function() {
		if($(this).hasClass('open')) {
			$(this).removeClass('open');
			$('#player').slideUp();
			return false;
		}

		$(this).addClass('open');
		$('#player').slideDown(function() {

			var waveform_url = $('#player').data('waveform_url');
			if(waveform_url) {
				var track = { waveform_url: waveform_url };
				initialize_waveform(track);
			}

			updatePlayerHeight();

	     });
	});



	function initialize_player() {
		jQuery('.sc-player').scPlayer();
	}

	$(window).load(function() {
		initialize_player();
	});

	initialize = false;
	$(document).bind('onPlayerInit.scPlayer', function(event) {

		initialize = true;
		$('#player').addClass('loaded');

        updatePlayerHeight();

	});


	// when clicking tracks on the site
	var loaded_tracks = [];
	$('[data-play]').on('click', function() {

		if(!initialize) {
			return false;
		}

		var player = $('#player .sc-player'),
			track = $(this).data(),
			track_url = $(this).data('track-url'),
			playing = false;

		if($(this).hasClass('playing')) {
			playing = true;
		}

		$('[data-play]').each(function() {
			$(this).removeClass('playing');
		});

		if(!playing) {
			$(this).addClass('playing');
		}

		track_exists = false;
		for(var i = 0; i < loaded_tracks.length; i++) {
		    if(loaded_tracks[i] === track_url) {
		    	track_exists = true;
		    }
		}

		if(track_url != '') {
			var song = player.find('.sc-trackslist a[href="' + track_url + '"]');

			// if song does not exist, set loaded to true.
			if(!song.length && $(this).data('loaded') !== 'true') {
				$.scPlayer.loadTrackUrlAndPlay(player, track);
				$(this).data('loaded', 'true');
			} else {
				song.parent().click();
			}
		}
	});
	
	$(document).on('click','.navbar-player .fa-stack', function(event) {
    	var tracklist = $('#player ol.sc-trackslist');
    	tracklist.find('li.active').click();
    });	


	

	if($('body').hasClass('home')) {

		/* <<<<<<<<<<<<<< NOW FEED >>>>>>>>>>>>>> **/
		/***  update now feed every 3.3 minutes  ***/ 

		function now_feed_ajax() {
			setTimeout(function() {

				var bottom_widget_count = 8; 
				if($('.bottom-home-nowfeed .widget').length > 4) {
					bottom_widget_count = $('.bottom-home-nowfeed .widget').length;
				}

				$.post(
					DB_Ajax_Call.ajaxurl, {
						action : 'update_now_feed',
						bottom_count: bottom_widget_count,
						postCommentNonce : DB_Ajax_Call.postCommentNonce,
					},
					function(response) {

						var top = response.top;
						var bottom = response.bottom

						var top_now_feed = $('.top-home-nowfeed');
						var bottom_now_feed = $('.bottom-home-nowfeed');

						destroy = $('section.news').stickem().destroy();

						top_now_feed.html(top);
						bottom_now_feed.html(bottom);

						now_feed(sticky_now_feed);

					}, 'json');
			}, 3100 * 60); // every 3.3 minutes // so it doesn't update before cron
		}


		var bottom_feed = $('.bottom-home-nowfeed');
		var news_height = $('section.news_continued .home-center-content').height();

		var offset = $('.top-home-nowfeed .widget').length + $('.bottom-home-nowfeed .widget').length;

		// this function either adds a new widget to the bottom or removes one if the height is too much
		// once the bottom feed height is greater than the news height, direction is set to up and it won't get new items again.
		var i = 0;
		function check_bottom_feed_height(direction, offset) {
			if(i > 10) { return; } // just in case all hell breaks loose..

			var bottom_feed_height = bottom_feed.prop('scrollHeight');

			// remove last item
			if(bottom_feed_height > news_height) {
				bottom_feed.children().last().detach(); // remove last item from dom
				check_bottom_feed_height('up');
				return;
			}

			// add new item
			if(bottom_feed_height < news_height && direction != 'up') {
			
				get_next_now_feed(offset, function(widget) {
					bottom_feed.append(widget); // append item to the second feed		

					i++;
					check_bottom_feed_height('down', offset + 3); // ooooooo recursion
				});
			}
		}

		function now_feed(callback) {
			var top_now_feed = $('.top-home-nowfeed');
			var bottom_now_feed = $('.bottom-home-nowfeed');

			var news_height = $('section.news').height();

			var i = 0;	
			function check_feed_height() {
			if(i > 10) { return; } // just in case all hell breaks loose..

			var now_feed_height = top_now_feed.prop('scrollHeight');

				if(now_feed_height > news_height) {
					var last_child = top_now_feed.children().last().detach(); // remove last item from dom
					bottom_now_feed.prepend(last_child); // prepend item to the second feed

					i++;
					check_feed_height(); // ooooooo recursion
				}

			top_now_feed.height(news_height);
			}

			check_feed_height();

			var offset = $('.top-home-nowfeed .widget').length + $('.bottom-home-nowfeed .widget').length;
			check_bottom_feed_height('middle', offset);
			
			featherlight_now_feed();

			now_feed_ajax();
			callback();
		}	

	
	// calls the ajax function that gets the next widget in the now feed
	function get_next_now_feed(offset, callback) {
		$.post(
			DB_Ajax_Call.ajaxurl, {
				action : 'get_next_now_feed',
				offset: offset,
				postCommentNonce : DB_Ajax_Call.postCommentNonce,
			},
			function(response) {
				return callback(response.widget);
			}, 'json');
	}

	function sticky_now_feed() {
		if($('.top-home-nowfeed').length < 1) { return; }
		
		var news_height = $('section.news .home-center-content').height();
		var col_header_height = $('.top-home-nowfeed').siblings('.col-header').height();

		$('.top-home-nowfeed').height(news_height - col_header_height);

		sticky = $('section.news').stickem({
			item: $('.top-home-nowfeed .widget').last(),
			container: '.top-home-nowfeed',
			offset: $('#navbar').height() + $('#wpadminbar').height() + 20,
			bottomOffset: -29, // if the item ends too soon or early, add this offset.. hacked stickem file..
			stickClass: 'sticky',
			endStickClass: 'sticky-bottom',
			start: $('.top-home-nowfeed .widget:nth-last-child(2)').offset().top - $('.news').offset().top + $('.top-home-nowfeed .widget:nth-last-child(2)').height() + 2
		});

		$(window).scroll(); // trigger scroll to keep element in place.
	}

	function featherlight_now_feed() {

		$('.featherlight-now-feed').featherlight({
			targetAttr: 'href',
			openSpeed: 800,
			closeSpeed: 800,
			beforeOpen: function(event) {
				$('html').addClass('no-scroll');
			},
			beforeClose: function(event) {
				$('html').removeClass('no-scroll');
			},
			afterOpen: function(event){
				var old_html = $.parseHTML(this.$content.html());

				var parent = $(document.createElement('div')); // create wrapper div for manipulating inside of it

				parent.html(old_html);
				
				var image_url = parent.find('.featured-image').data('image-url');
				
				var img = $('<img />', { 
					src: image_url,
					class: 'media'
				});

				parent.find('.featherlight-now-feed').after(img).remove();
				var html = parent.html()

				this.$content.html(html);
				this.$content.find('.sticky').removeClass('.sticky');

			}
		});

	}

	//call the now feed, sticky it when the images are loaded
	now_feed(sticky_now_feed);

	$(window).load(function() {
		sticky_now_feed();
		sticky_fnt();
		check_bottom_feed_height('middle', offset);
	});

	$(window).resize(function() {
		sticky_now_feed();
		sticky_fnt();
	});


	function sticky_fnt() {

		if($('.fnt-sticky-wrapper').length < 1) { return false; }
		var col_header_height = $('.top-home-fnt').siblings('.col-header').height();

		var news_height = $('section.news .home-center-content').height();
		$('.top-home-fnt').height(news_height - col_header_height);

		sticky = $('section.news').stickem({
			item: $('.fnt-sticky-wrapper'),
			container: '.top-home-fnt',
			offset: $('#navbar').height() + $('#wpadminbar').height() + 20,
			bottomOffset: 0, // if the item ends too soon or early, add this offset.. hacked stickem file..
			stickClass: 'sticky',
			endStickClass: 'sticky-bottom',
			start: $('.fnt-sticky-wrapper').offset().top - $('.news').offset().top - $('.col-header-fnt').height()
		});

		$(window).scroll(); // trigger scroll to keep element in place.
	}

} // end home conditional


/* <<<<<<<<<<<<<< UPDATE POSTS >>>>>>>>>>>>>> **/
/***  triggered on button click  ***/ 

$('form .load-posts').on('click', function() {
	$('#load-more').submit();
});

running = 0;
$('#load-more').on('submit', function(e) {
	e.preventDefault();

		// abort old AJAX request if it's still running
		if(running == 1) { ajax_request.abort(); }
		running = 1;

		var load_more = $(this),
		target = $(load_more.data('target'));

		load_more.find('i').css('display','inline-block');


		if(!exclude_posts) { exclude_posts; }

		ajax_request = $.post(DB_Ajax_Call.ajaxurl,
		{
			action : 'load_posts',
			postCommentNonce : DB_Ajax_Call.postCommentNonce,
			exclude_posts : exclude_posts,
			options: load_more.serialize(),
		},
		function(response) {

			if(response.results.indexOf('That is all!') > -1) {
				$('.load-posts').hide();	
				$('.load-posts').after('<div style="text-align:center; margin-top:15px;">That is all!</div>');
			} else {

				target.append(response.results);
				load_more.find('i').hide();

				exclude_posts = response.exclude_posts;
			}

			running = 0;

		}, 'json'); 
	});


	// twitter API
	!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="http://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
	

});