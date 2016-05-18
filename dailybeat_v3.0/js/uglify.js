jQuery(document).ready(function($) {

	// scripts that need to be called on every page load
	window.global_scripts = function(reload) {

		$('#navbar').affix({
			offset: { top: 0 }
		});

		bLazy = new Blazy({
		    selector: '.featured-image, .blazy', // all images
		    offset: 100
		});


		$('.handle').on('click', function(e) {
			e.stopPropagation();
			if($('.nav-slide').hasClass('open')) { 
				$('.nav-slide').removeClass('open');
				return;
			}
			
			$('.nav-slide').addClass('open');

		});

		/* truncate meeeee */
		$('.post-wrapper .post-info dotdotdot').dotdotdot({
			watch: 'window'
		});

	}

	global_scripts();

	setInterval(function() {
		// refresh images every 5 min
		bLazy.revalidate();
	}, 5 * 60 * 1000);


	// set up cabinet	
	$('.toggle-menu').jPushMenu();

	/* music playaaa */
	// don't load player in reload scripts, as we don't want to refresh player when changing pages

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

		var waveform_url = $('#player').data('waveform_url');
		if(waveform_url) {
			var track = { waveform_url: waveform_url };
			initialize_waveform(track);
		}

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
jQuery(document).ready(function($) {

	// make it global
	window.single_scripts = function(reload) {

		// refresh scripts!
		if(reload) {
			resize_media(sticky_social_feed);	
		}

		// iframe set up

	//	if($('.single-content').length) {
		
	//	}


		$(window).load(function() {
			resize_media(sticky_social_feed);
		});

		$(window).resize(function() {
			resize_media(sticky_social_feed);
		});

		
		function sticky_social_feed() {

			if($('.single-body').length) {

				var height = $('.single-body .col-offset-center').height() - $('.single-body .related-posts').height();
		//		var right_col_height = $('.single-body .col-fixed-right').outerHeight();

		//		if(right_col_height > height) {
		//			height = right_col_height;
		//		}

				// set left column to max height for sticky 
				$('.single-body .col-fixed-left').height(height);

				sticky = $('.single-body').stickem({
					item: $('#sticky_sharing'),
					container: '.col-fixed-left',
					offset: $('#navbar').height() + $('#wpadminbar').height() + 20,
					bottomOffset: 0, // if the item ends too soon or early, add this offset.. hacked stickem file..
					stickClass: 'sticky',
					endStickClass: 'sticky-bottom',
					start: $('#sticky_sharing').offset().top - $('.single-body').offset().top,
				});

				$(window).scroll(); // trigger scroll to keep element in place.
			}
		}

		function resize_media(callback) {

			$allVideos = $('.content iframe');

			// remove hardcoded with and height from each iframe
			$allVideos.each(function() {
				$(this).data('aspectRatio', this.height / this.width).removeAttr('height').removeAttr('width');
			});

			resize_iframes();
			resize_images();
			callback();
		}

		function resize_iframes() {
			$fluidEl = $('.content');
			$allVideos = $('.single-body iframe');

			var newWidth = $fluidEl.width();
			
			// Resize all videos according to their own aspect ratio
			$allVideos.each(function() {
				var $el = $(this);
				$el
				.width(newWidth)
				.height(newWidth * $el.data('aspectRatio'));
			});
		}


		function resize_images() {
			// single image resize
			$('.content .alignleft, .content .alignright').each(function() {
		        var maxWidth = 450; // Max width for the image
		        var maxHeight = 400;    // Max height for the image
		        var ratio = 0;  // Used for aspect ratio
		        var width = $(this).find('img').width();    // Current image width
		        var height = $(this).find('img').height();  // Current image height

		        // Check if the current width is larger than the max
		        if(width > maxWidth){
		            ratio = maxWidth / width;   // get ratio for scaling image
		            $(this).css("width", maxWidth); // Set new width
		            $(this).css("height", height * ratio);  // Scale height based on ratio
		            height = height * ratio;    // Reset height to match scaled image
		            width = width * ratio;    // Reset width to match scaled image
		        }

		        // Check if current height is larger than max
		        if(height > maxHeight){
		            ratio = maxHeight / height; // get ratio for scaling image
		            $(this).css("height", maxHeight);   // Set new height
		            $(this).css("width", width * ratio);    // Scale width based on ratio
		            width = width * ratio;    // Reset width to match scaled image
		            height = height * ratio;    // Reset height to match scaled image
		        }
		    });
		}



		var $win = $(window);

		function percentageSeen($element) {
		    var viewportHeight = $(window).height(),
		        scrollTop = $win.scrollTop(),
		        elementOffsetTop = $element.offset().top,
		        elementHeight = $element.height();
		    
		    
		    if (scrollTop < elementOffsetTop) {
		        return 0;
		    } else if ((elementOffsetTop + elementHeight) < scrollTop) {
		        return 100;
		    } else {
		        var distance = scrollTop - elementOffsetTop;
		        var percentage = (distance * 100) / elementHeight;
		        percentage = Math.round(percentage);
		        return percentage;
		    }
		}

		$(window).scroll(function () {

			$('.exclusive-image .overlay').each(function() { 
				$(this).css('opacity', percentageSeen($(this)) / 100);				
			});

		});

	};


	if($('body').hasClass('single')) {
		// call the scripts!
		single_scripts();
	}

});

jQuery(document).ready(function($) {

	window.homepage_scripts = function(reload) {

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

						now_feed();
						//now_feed(sticky_now_feed);

					}, 'json');
			}, 3100 * 60); // every 3.3 minutes // so it doesn't update before cron
		}


		// this function either adds a new widget to the bottom or removes one if the height is too much
		// once the bottom feed height is greater than the news height, direction is set to up and it won't get new items again.
		var i = 0,
			bottom_feed = $('.bottom-home-nowfeed'),
			news_height = $('section.news_continued .home-center-content').height(),
			offset = $('.top-home-nowfeed .widget').length + $('.bottom-home-nowfeed .widget').length;

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
			function check_feed_height() { return;
				if(i > 10) { return; } // just in case all hell breaks loose..

				var now_feed_height = top_now_feed.prop('scrollHeight');
				console.log(now_feed_height);

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
			
			if(typeof bLazy != 'undefined') {
				bLazy.revalidate();
			}

			featherlight_now_feed();

			now_feed_ajax();
			if(typeof(callback) == 'function') {
				callback();
			}

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
/*
		function sticky_now_feed() {
			if($('.top-home-nowfeed').length < 1) { return; }
			
			var news_height = $('section.news .home-center-content').height();
			var col_header_height = $('.top-home-nowfeed').siblings('.col-header').height();

			$('.top-home-nowfeed').height(news_height - col_header_height);
			return;

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
*/
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
/*
		function sticky_fnt() {
			return; 
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
*/
		// call the now feed, sticky it when the images are loaded
		//now_feed(sticky_now_feed);
		now_feed();

		$(window).load(function() {
		//sticky_now_feed();
		//	sticky_fnt();
	//		check_bottom_feed_height('middle', offset);
		});

		$(window).resize(function() {
	//		sticky_now_feed();
	//		sticky_fnt();
		});


	}


	homepage_scripts();

	if($('body').hasClass('home')) {
		// call the scripts!
		homepage_scripts();
	}

});

jQuery(document).ready(function($) {

	LinkClicked = false;

	$(document).on('click', 'a', function(event) {

		// if browser doesn't support pushState, treat links as normal!
		// if command click new tab
		if(
			!history.pushState ||
			event.ctrlKey || 
			event.shiftKey || 
		    event.metaKey || // apple
		    (event.button && event.button == 1) // middle click, >IE9 + everyone else
		    ) {
			return;
		}

		if(this.getAttribute('href').charAt(0) === '#')
			return;

		LinkClicked = true;
			    
		var target_href = this.href;

 		// if url not going to another dailybeat page

 		var matches = target_href.match(/^https?\:\/\/([^\/?#]+)(?:[\/?#]|$)/i);
		var domain = matches && matches[1];  // domain will be null if no match is found

		// if current page and target page are on the same domain
		if(domain != document.location.hostname || target_href.indexOf('/wp-admin') > -1) { 
			return;
		}

		event.preventDefault();		

		page_progress_bar('start');

		$.ajax({
			type: 'POST',
			url: target_href,
			success: function(data) {

				page_progress_bar('stop');

				var parser = new DOMParser();
				doc = parser.parseFromString(data, "text/html");
				
				var body_classes = doc.body.getAttribute('class');
				var new_page_content = $(doc.body).find('#content').html();

				var matches = data.match(/<title>(.*?)<\/title>/);
				var page_title = matches[1];

			    // garbage collector
			    parser = doc = null;
			
				History.pushState({ html: new_page_content, bodyClass: body_classes, scrollPos : 0 }, page_title, target_href);
			}
		});

	});


// this function updates page content with ajax and cool effects!
// data is an object that stores page html, body classes, and more
var updateRunning = 0;
function update_content(data, source) {
	// return an already resolved promise
	if(data == null) { return $().promise(); }

	if(source == 'buttons') {
		page_progress_bar('start', 400);
	}

	++updateRunning;

	var pageContent = data.html;
	var body_classes = data.bodyClass;
	var scrollPos = data.scrollPosition || 0;

	// replace body class with classes loaded
	$('body').removeClass().addClass(body_classes);

	var id = $(pageContent).prop('id'),
	content = "#" + id,
	header = 0, p1, p2;

	$('#content').invisible().html(pageContent);
	$('#content').visible();

	window.scrollTo(scrollPos, 0);

	if(body_classes.indexOf('single') > -1) {
		single_scripts(true);
	}

	if(body_classes.indexOf('home') > -1) {
		homepage_scripts(true);
	}

	global_scripts();


	if(source == 'buttons') {
		page_progress_bar('stop');
	}

	LinkClicked = false;

	return $.when(p1, p2).always(function() {
		--updateRunning;
	});
	
}


	// this wrapper function queues up and lets all fadeIn/fadeOut happen before executing again
	var queue = [];
	function update_content_wrapper(data, source) {
		if(updateRunning !== 0) {
			// just queue the data if update already running
			queue.push(data);
		} else {
			update_content(data, source).always(function() {
				// if items in the queue, get the oldest one
				if(queue.length) {
					update_content_wrapper(queue.shift());
				}
			});
		}
	}
	
	
	//var event_state = History.getState(); // Note: We are using History.getState() instead of event.state
	//update_content(event_state.data.html);	
	

	// current_data stores the current page data into history
	var current_data = $('#content').html();
	var body_classes = $('body').attr('class');
	var current_scroll_position = $(window).scrollTop();

	// store the initial content so we can revisit it later
	History.replaceState({ html : current_data, bodyClass: body_classes, scrollPos: current_scroll_position }, document.title, document.location.href);

	// revert to a previously saved state
	History.Adapter.bind(window, 'statechange', function() { // note: We are using statechange instead of popstate
        var event_state = History.getState(); // note: We are using History.getState() instead of event.state

        var button = 'link';
        if(LinkClicked === false) {
        	button = 'buttons';
        }

        update_content_wrapper(event_state.data, button);	

    });


});


function page_progress_bar(time, duration) {
	duration = duration || 3500;
	if(time == 'start') {

		jQuery('#page-progress').css('width', 0).show().animate({
			'width' : '20%',
			'duration': 200,
		}, {
			easing : 'linear',
			queue : false,
			complete : function() {
				jQuery('#page-progress').animate({'width' : '60%'}, duration, 'linear');
			}
		});
	}

	if(time == 'stop') {
		
		jQuery('#page-progress').stop(true).animate({
			'width' : '100%',
		}, {
			duration: 50,
			queue: false,
			complete: function() {
				jQuery('#page-progress').fadeOut();
			}
		});
	}
}


function randomIntFromInterval(min,max) {
	return Math.floor(Math.random()*(max-min+1)+min);
}

jQuery.fn.visible = function() {
	return this.css('visibility', 'visible');
};

jQuery.fn.invisible = function() {
	return this.css('visibility', 'hidden');
};
