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
