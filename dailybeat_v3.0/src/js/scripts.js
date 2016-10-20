jQuery(document).ready(function($) {

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
