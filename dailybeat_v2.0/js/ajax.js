jQuery(document).ready(function($) {
	
	$(window).load(function() {
		
		if($('#sidebar').length) {	
		
			var mh = $('.container.main').outerHeight();
			var sh = $('#sidebar').height();
		
			$('#sidebar').attr('data-main-height', mh);
			$('#sidebar').attr('data-sidebar-height', sh);
		
			fix_sidebar_height();
		
		}
	
	});
	
	var clicks = 1;
	post_image_resize();
///	post_image_resize();
	
	
	$('.load_posts').click(function() {
		
		$('.load_posts').html("").spin({
			lines: 10, // The number of lines to draw
			length: 5, // The length of each line
			width: 3, // The line thickness
			radius: 5, // The radius of the inner circle
			corners: 1, // Corner roundness (0..1)
			rotate: 0, // The rotation offset
			direction: 1, // 1: clockwise, -1: counterclockwise
			speed: 1.3, // Rounds per second
			trail: 60, // Afterglow percentage
			shadow: false, // Whether to render a shadow
			hwaccel: false, // Whether to use hardware acceleration
			className: 'spinner', // The CSS class to assign to the spinner
			zIndex: 2e9, // The z-index (defaults to 2000000000)
			top: 'auto', // Top position relative to parent in px
			left: 'auto' // Left position relative to parent in px
		});
		
		var page = "";
		
		if($(this).hasClass('home')) {
			page = "home";	
		} else if($(this).hasClass('category')) {
			page = "category";	
		} else if($(this).hasClass('search')) {
			page = "search";	
		} else if($(this).hasClass('author')) {
			page = "author";
		}
		
		var category = 0;
		if($(this).attr('data-category')) {
			category = $(this).data('category');
		}
		
		var search_str = '';
		if($(this).attr('data-search')) {
			search_str = $(this).data('search');
		}
		
		var author_id = '';
		if($(this).attr('data-author')) {
			author_id = $(this).data('author');
		}
		
		
			$.post(
				MyAjax.ajaxurl,
					{
					action : 'load_posts',
					postCommentNonce : MyAjax.postCommentNonce,
					page : page,
					clicks : clicks,
					author_id : author_id,
					search_str : search_str,
					category : category
					},
				function(response) {
					clicks++;			
					$('.load_posts').before(response);
					post_image_resize();

					if($('p.no-results').length) { $('.load_posts').hide(); }

					if(response == "") {
						$('.load_posts').before('<p style="text-align:center;"><br/>That\'s all, folks.<br/><br/></p>').hide();
						
					}
					
					$('a.sc-player').scPlayer();

					
					$('.load_posts').html("LOAD MORE POSTS").stop();
					
					
					fix_sidebar_height();
					
						
					// re-adjust heights
									
					
		/*
					$('.twitter').affix({
						offset: {
						top: $('.twitter_sticky').offset().top - offs,
						bottom: function() {
								return (this.bottom = $('#footer_wrapper').outerHeight(true)) + 00
							  }
						}
				});
		*/

				}
			); return false;
		});
	
	var resizeTimer;
	$(window).resize(function() {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function() {
			
			if($('#sidebar').length) {	
	
					// if mh IS NOT SET
			if(isFinite(String(mh))) {
				var mh = $('.container.main').outerHeight();
				var sh = $('#sidebar').height();
			}
	
				$('#sidebar').attr('data-main-height', mh);
				$('#sidebar').attr('data-sidebar-height', sh);
		
				post_image_resize();
				
				fix_sidebar_height();
			}
			
		}, 100);
	});
	
	$(window).on('orientationchange', function() {
		
				post_image_resize();


	clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function() {

		post_image_resize();

		if($('#sidebar').length) {	
		
			// if mh IS NOT SET
			if(isFinite(String(mh))) {
				var mh = $('.container.main').outerHeight();
				var sh = $('#sidebar').height();
			}
		
			$('#sidebar').attr('data-main-height', mh);
			$('#sidebar').attr('data-sidebar-height', sh);
		
				post_image_resize();
				fix_sidebar_height();
		}
				
		}, 500);	
	});
	
	$(window).on('scroll', function() {
	clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function() {

		if($('#sidebar').length) {	
		
			// if mh IS NOT SET
			if(isFinite(String(mh))) {
				var mh = $('.container.main').outerHeight();
				var sh = $('#sidebar').height();
			}
		
			$('#sidebar').attr('data-main-height', mh);
			$('#sidebar').attr('data-sidebar-height', sh);
		
				fix_sidebar_height();
		}
				
		}, 2000);	
	});
	
	
	
		
	function fix_sidebar_height() {
		
		// initial sidebar height
		var sh = $('#sidebar').attr('data-sidebar-height');	
		
		// get the current height of the sidebar and of the non-sticky-widgets
		var h = $('#sidebar').outerHeight(); 
		var h2 = $('.non-sticky-widgets').outerHeight();
		
		// take the height of the left column
		var m2 = $('.container.main > .row > .col-md-8').outerHeight();
		
		// final is the height of the left column content minus the height of the non-sticky-widgets.  THIS is the height to use for the sticky wrapper, and should fill the rest
		var final = m2 - h2;	
		//console.log('h2  ' + h2);
		//console.log('m2  ' + m2);

	
		// if the left column is smaller than the sidebar, we DO NOT WANT to sticky.  don't overcomplicate things.	
		if(m2 < sh) {
				
		$(window).off('.twitter.affix');
		$(".twitter")
			.removeClass("affix affix-top affix-bottom")
			.removeData("bs.affix");
			
		} else {
		
			// set sidebar height to left column height
			$('#sidebar').height(m2);
			// set twitter wrapper to the remaining height ( see variables above ).
			$('.twitter_sticky').height(final);
		

			// load sticky	
			setTimeout(function() {	
				$('.widget .twitter')
				.affix({
					offset: {
						top: function() {
							var offs = 60;
							if($('.logged-in.single').length || $('.logged-in.search').length) { offs = 138; }
							else if($('.single').length || $('.search').length) { offs = 111; }
							else if($('.logged-in').length) { offs = 92; }
							else { offs = 64; }
							return $('.twitter_sticky').offset().top - offs
						},
						bottom: function() {
							//return 390; 
							return $('#footer_wrapper').outerHeight(true) + 80;
							}
						}
					});
				}, 100)
		}
	}
		
	function post_image_resize(load_more) {
		if($(window).width() > 569)  {
			// make all posts the height of the content
			$('.post.standard').each(function() {
				var featured_container = $(this).find('.featured');
				var height = $(this).find('.content').outerHeight(true);
				
				featured_container.height(height);
	
			});
	 
		} else {
			$('.post.standard').each(function() {
				var featured_container = $(this).find('.featured');	
				featured_container.removeAttr('style');		
			});
		}
	
	} // close function
	
});