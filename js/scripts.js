jQuery(document).ready(function($) {
	

	$('.navbar-wrapper').height($("#navbar").height()); // IMPORTANT - this prevents jumping of page on scroll	
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


	/* <<<<<<<<<<<<<< NOW FEED >>>>>>>>>>>>>> **/
	/***  update now feed every 3.3 minutes  ***/ 

	// call the now feed, sticky it when the images are loaded
	now_feed(sticky_now_feed);

	$(window).load(function() {
		sticky_now_feed();
	});

	$(window).resize(function() {
		sticky_now_feed();
	});

	function now_feed_ajax() {
		setTimeout(function() {
			$.post(
				DB_Ajax_Call.ajaxurl, {
					action : 'update_now_feed',
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
		featherlight_now_feed();

		now_feed_ajax();
		callback();
	}	


	function sticky_now_feed() {
		if($('.top-home-nowfeed').length < 1) { return; }
		
		var news_height = $('section.news').height();
		$('.top-home-nowfeed').height(news_height);

		sticky = $('section.news').stickem({
			item: $('.top-home-nowfeed .widget').last(),
			container: '.top-home-nowfeed',
			offset: $('#navbar').height() + $('#wpadminbar').height() + 20,
			bottomOffset: -28, // if the item ends too soon or early, add this offset.. hacked stickem file..
			stickClass: 'sticky',
			endStickClass: 'sticky-bottom',
			start: $('.top-home-nowfeed .widget:nth-last-child(2)').offset().top - $('.news').offset().top + $('.top-home-nowfeed .widget:nth-last-child(2)').outerHeight(true)
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