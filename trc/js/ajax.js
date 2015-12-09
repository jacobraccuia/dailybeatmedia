jQuery(document).ready(function($) {

	x = 0;
	if($('#sidebar').height() < $('.sidebar-height').height()) {
		adjust_heights(); // run on load
		x = 1;
	}
	
	$(window).load(function() {
		adjust_heights();

		if(x == 1) { // $('#sidebar').height() < $('.sidebar-height').height()) {
			
			sticky = $('.container').stickem({
				item: '#sidebar .custom-ad.sidebar',
				container: '#sidebar',
				offset: $('#navbar').height() + $('#wpadminbar').height() + 20,
				stickClass: 'sticky',
				endStickClass: 'sticky-bottom', 
				start: $('#sidebar .custom-ad.sidebar').offset().top - $('#sidebar').offset().top - 3
			});	
		}
	});
	
	running = 0;
	$('.load-posts').click(function() {
		
		// abort old AJAX request if it's still running
		if(running == 1) { ajax_request.abort(); }
		running = 1;
				
		var load_more = $(this),
			category = load_more.data('category') || 0,
			author = load_more.data('author') || 0,
			exclude = load_more.data('exclude'),
			page = load_more.data('page'),
			search_str = load_more.data('search_str') || '',
			target = $(load_more.data('target'));
			
			load_more.find('i').css('display','inline-block');
		
		ajax_request = $.post(MyAjax.ajaxurl,
			{
				action : 'load_posts',
				postCommentNonce : MyAjax.postCommentNonce,
				type : 'load_more',
				category : category,
				exclude : exclude,
				search_str : search_str,
				author : author,
				page : page
			},
			function(response) {
				
				if(response.results.indexOf('That is all!') > -1) {
					$('.load-posts').hide();	
					$('.load-posts').after('<div style="text-align:center; margin-top:15px;">That is all!</div>');
				} else {
					
					target.append(response.results); // put results on page
	
					load_more.data('exclude', response.exclude);
					load_more.find('i').hide();
				}
				
				
				
			// $('#category-picker').removeClass('affix-bottom').addClass('affix').removeAttr('style');

				adjust_heights();
				running = 0;
				
			}, 'json'
		); 
	});
	
	
	function adjust_heights() {
		if($('#sidebar').height() < $('.sidebar-height').outerHeight()) {
			var height = $('.sidebar-height').outerHeight();
			$('#sidebar').height(height);
		}
	}
	
	
	var resizeTimer;
	$(window).resize(function() {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function() {
			adjust_heights();
		}, 100);
	});
	
	
	
	
	
});