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

/*	jQuery(document).ready(function($){
		var _custom_media = true,
		_orig_send_attachment = wp.media.editor.send.attachment;

		$('.upload_image_button').click(function(e) {
			var send_attachment_bkp = wp.media.editor.send.attachment;
			var button = $(this);
								var target = $(this).data('target');

			_custom_media = true;
			wp.media.editor.send.attachment = function(props, attachment){
				if ( _custom_media ) {
					console.log(target);
					$('#' + target).val(attachment.url);
				} else {
					return _orig_send_attachment.apply( this, [props, attachment] );
				};
			}

			wp.media.editor.open(button);
			return false;
		});

		$('.add_media').on('click', function(){
			_custom_media = false;
		});
	});
*/