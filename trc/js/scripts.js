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
	
	$('.jumbotron .info').scrollSpy();
	
	$('.jumbotron .info').on('scrollSpy:enter', function() {
		$('.single-header-title').fadeOut();
	});
	
	$('.jumbotron .info').on('scrollSpy:exit', function() {
		$('.single-header-title').fadeIn();
	});
	
	
	$('.handle').on('click', function(e) {
		e.stopPropagation();
		if($('.nav-slide').hasClass('open')) { 
			$('.nav-slide').removeClass('open');
			return;
		}
		
		$('.nav-slide').addClass('open');
	
	
	});
	
	
	// twitter API
	!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="http://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
	
});