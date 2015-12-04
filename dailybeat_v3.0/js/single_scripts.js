jQuery(document).ready(function($) {

	// iframe set up

	$fluidEl = $('.single-content');
	$allVideos = $('.single-body iframe'),

	// remove hardcoded with and height from each iframe
	$allVideos.each(function() {
		$(this).data('aspectRatio', this.height / this.width).removeAttr('height').removeAttr('width');
	});


	function sticky_social_feed() {

//	  sticky.destroy();
	if(typeof sticky !== 'undefined') { sticky.destroy(); }

/*
		$('#sticky_sharing').sticky({
			topSpacing: $('#navbar').height() + $('#wpadminbar').height(),
			//bottomSpacing: $('#footer-wrapper').height(),
			className: 'sticky',
			parentContainer: $('.single-body .col-fixed-left'),
		});


	/*/	


	sticky = $('.single-body').stickem({
			item: $('#sticky_sharing'),
			container: '.col-fixed-left',
			offset: $('#navbar').height() + $('#wpadminbar').height() + 20,
			bottomOffset: 0, // if the item ends too soon or early, add this offset.. hacked stickem file..
			stickClass: 'sticky',
			endStickClass: 'sticky-bottom',
			start: $('#sticky_sharing').offset().top - $('.single-body').offset().top,
		});
/**/
		$(window).scroll().resize(); // trigger scroll to keep element in place.
	}

	
	//sticky_social_feed();

	$('.single-body').imagesLoaded(function() {
		resize_media(sticky_social_feed);
	});

	$(window).resize(function() {
		resize_iframes();

		var height = $('.single-content').height();
		$('.col-fixed-left').height(height);

//		sticky_social_feed();
	});



	function resize_media(callback) {
		resize_iframes();
		resize_images();


		var height = $('.single-content').height();
		$('.col-fixed-left').height(height);

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