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
