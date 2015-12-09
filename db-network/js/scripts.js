jQuery(document).ready(function($) {

	setTimeout(function() {
		$.post(
			DB_Ajax.ajaxurl, {
				action : 'db_popular_post_aggregator',
				postCommentNonce : DB_Ajax.postCommentNonce,
			},
			function(response) { // success here console
			}); return false;
	}, 5000);
	
	$('[data-toggle="search-slide"]').on('click', function(event) {
		
		var wrapper = $('.search-wrapper');

		if(wrapper.hasClass('active')) {
			wrapper.removeClass('active');
			return;
		}

		wrapper.addClass('active');
		$('.search-box').focus();		
	});
	
	// handles drop down of menu
	$('[data-toggle="navbar-dropdown"]').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		
		if($(this).hasClass('active')) {
			$('#navbar-dropdown').slideUp().removeClass('open');
			$(this).removeClass('active');
			return;
		}
		

		$('[data-toggle="navbar-dropdown"]').removeClass('active');
		$(this).addClass('active');
		
		// hide all content
		$('.dropdown-content').hide();
			
			var target = $(this).data('target')
			$('#' + target + '-dropdown').fadeIn();
			
			
			// show navbar
		$('#navbar-dropdown').slideDown().addClass('open');

	});


	var resizeTimer;	
	$(document).on('scroll click', function(event) {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function() {
			if(!$(event.target).closest('#navbar-dropdown').length && !$(event.target).closest('.channel-list').length) {
       			if($('#navbar-dropdown').hasClass('open')) {
					$('#navbar-dropdown').slideUp().removeClass('open');
					$('[data-toggle="navbar-dropdown"]').removeClass('active');
				return;			
				}
	    	}   
			
		}, 50);
	});
	
	
	
	$('[data-toggle="channels"]').on('click', function() {
		if($(this).hasClass('open')) {
			$('#channel-list').slideUp();
			$(this).removeClass('open');
			return;
		}
			$(this).addClass('open');
			$('#channel-list').slideDown();
				
	});

	$(document).click(function(event) { 
		if(!$(event.target).closest('[data-toggle="channels"]').length) {
			if($(this).hasClass('open')) {
				$('#channel-list').slideUp();
				$(this).removeClass('open');
				return;
			}
		}
	});

});

$