jQuery(document).ready(function($) {

	window.homepage_scripts = function(reload) {

		$('.category-picker li').on('click', function() {
			if($(this).hasClass('active')) { return; }

			var category_id = $(this).data('id');

			$('.category-picker li').removeClass('active');
			$(this).addClass('active');

			$('.category-post-wrapper .post-wrapper:not([data-section-id="' + category_id + '"])').fadeOut(600);
			$('.category-post-wrapper [data-section-id="' + category_id + '"]').delay(700).fadeIn(600);

		});
	}

	if($('body').hasClass('home')) {
		// call the scripts!
		homepage_scripts();
	}

});
