jQuery(document).ready(function($) {

	// iframe set up

	$fluidEl = $('.single-content');
	$allVideos = $('.single-body iframe'),

	// remove hardcoded with and height from each iframe
	$allVideos.each(function() {
		$(this).data('aspectRatio', this.height / this.width).removeAttr('height').removeAttr('width');
	});


		resize_media(sticky_social_feed);

	$(window).load(function() {
		resize_media(sticky_social_feed);
	});

	$(window).resize(function() {
		resize_media(sticky_social_feed);
	});


	function sticky_social_feed() {

		var height = $('.single-body .col-offset-center').height();
//		var right_col_height = $('.single-body .col-fixed-right').outerHeight();

//		if(right_col_height > height) {
//			height = right_col_height;
//		}

		// set left column to max height for sticky 
		$('.col-fixed-left').height(height);

		sticky = $('.single-body').stickem({
			item: $('#sticky_sharing'),
			container: '.col-fixed',
			offset: $('#navbar').height() + $('#wpadminbar').height() + 20,
			bottomOffset: 25, // if the item ends too soon or early, add this offset.. hacked stickem file..
			stickClass: 'sticky',
			endStickClass: 'sticky-bottom',
			start: $('#sticky_sharing').offset().top - $('.single-body').offset().top,
		});

		$(window).scroll(); // trigger scroll to keep element in place.
	}

	function resize_media(callback) {
		resize_iframes();
		resize_images();
		callback();
	}

	function resize_iframes() {
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
		$('.single-content .alignleft, .single-content .alignright').each(function() {
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



});