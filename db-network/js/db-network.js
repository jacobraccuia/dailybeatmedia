jQuery(document).ready(function($) {

	LinkClicked = false;

	$(document).on('click', 'a', function(event) {

		// if browser doesn't support pushState, treat links as normal!
		// if command click new tab
		if(
			!history.pushState ||
			event.ctrlKey || 
			event.shiftKey || 
		    event.metaKey || // apple
		    (event.button && event.button == 1) // middle click, >IE9 + everyone else
		    ) {
			return;
		}

		if(this.getAttribute('href').charAt(0) === '#')
			return;

		LinkClicked = true;
			    
		var target_href = this.href;

 		// if url not going to another dailybeat page

 		var matches = target_href.match(/^https?\:\/\/([^\/?#]+)(?:[\/?#]|$)/i);
		var domain = matches && matches[1];  // domain will be null if no match is found

		// if current page and target page are on the same domain
		if(domain != document.location.hostname || target_href.indexOf('/wp-admin') > -1) { 
			return;
		}

		event.preventDefault();		

		page_progress_bar('start');

		$.ajax({
			type: 'POST',
			url: target_href,
			success: function(data) {

				page_progress_bar('stop');

				var parser = new DOMParser();
				doc = parser.parseFromString(data, "text/html");
				
				var body_classes = doc.body.getAttribute('class');
				var new_page_content = $(doc.body).find('#content').html();

				var matches = data.match(/<title>(.*?)<\/title>/);
				var page_title = matches[1];

			    // garbage collector
			    parser = doc = null;
			
				History.pushState({ html: new_page_content, bodyClass: body_classes, scrollPos : 0 }, page_title, target_href);
			}
		});

	});


// this function updates page content with ajax and cool effects!
// data is an object that stores page html, body classes, and more
var updateRunning = 0;
function update_content(data, source) {
	// return an already resolved promise
	if(data == null) { return $().promise(); }

	if(source == 'buttons') {
		page_progress_bar('start', 400);
	}

	++updateRunning;

	var pageContent = data.html;
	var body_classes = data.bodyClass;
	var scrollPos = data.scrollPosition || 0;

	// replace body class with classes loaded
	$('body').removeClass().addClass(body_classes);

	var id = $(pageContent).prop('id'),
	content = "#" + id,
	header = 0, p1, p2;

	$('#content').invisible().html(pageContent);
	$('#content').visible();

	window.scrollTo(scrollPos, 0);

	if(body_classes.indexOf('single') > -1) {
		single_scripts(true);
	}

	if(body_classes.indexOf('home') > -1) {
		homepage_scripts(true);
	}

	global_scripts();


	if(source == 'buttons') {
		page_progress_bar('stop');
	}

	LinkClicked = false;

	return $.when(p1, p2).always(function() {
		--updateRunning;
	});
	
}


	// this wrapper function queues up and lets all fadeIn/fadeOut happen before executing again
	var queue = [];
	function update_content_wrapper(data, source) {
		if(updateRunning !== 0) {
			// just queue the data if update already running
			queue.push(data);
		} else {
			update_content(data, source).always(function() {
				// if items in the queue, get the oldest one
				if(queue.length) {
					update_content_wrapper(queue.shift());
				}
			});
		}
	}
	
	
	//var event_state = History.getState(); // Note: We are using History.getState() instead of event.state
	//update_content(event_state.data.html);	
	

	// current_data stores the current page data into history
	var current_data = $('#content').html();
	var body_classes = $('body').attr('class');
	var current_scroll_position = $(window).scrollTop();

	// store the initial content so we can revisit it later
	History.replaceState({ html : current_data, bodyClass: body_classes, scrollPos: current_scroll_position }, document.title, document.location.href);

	// revert to a previously saved state
	History.Adapter.bind(window, 'statechange', function() { // note: We are using statechange instead of popstate
        var event_state = History.getState(); // note: We are using History.getState() instead of event.state

        var button = 'link';
        if(LinkClicked === false) {
        	button = 'buttons';
        }

        update_content_wrapper(event_state.data, button);	

    });


});


function page_progress_bar(time, duration) {
	duration = duration || 3500;
	if(time == 'start') {

		jQuery('#page-progress').css('width', 0).show().animate({
			'width' : '20%',
			'duration': 200,
		}, {
			easing : 'linear',
			queue : false,
			complete : function() {
				jQuery('#page-progress').animate({'width' : '60%'}, duration, 'linear');
			}
		});
	}

	if(time == 'stop') {
		
		jQuery('#page-progress').stop(true).animate({
			'width' : '100%',
		}, {
			duration: 50,
			queue: false,
			complete: function() {
				jQuery('#page-progress').fadeOut();
			}
		});
	}
}


function randomIntFromInterval(min,max) {
	return Math.floor(Math.random()*(max-min+1)+min);
}

jQuery.fn.visible = function() {
	return this.css('visibility', 'visible');
};

jQuery.fn.invisible = function() {
	return this.css('visibility', 'hidden');
};

jQuery(document).ready(function($) {

	// set up cabinet	
	$('.toggle-menu').jPushMenu();


	/**/////////////**//
	/* music playaaa */
	/**////////////**//

	// don't load player in reload scripts, as we don't want to refresh player when changing pages


	$('#player_button').on('click', function() {
		if($(this).hasClass('open')) {
			$(this).removeClass('open');
			$('#player').slideUp();
			return false;
		}

		if(!$('#player').hasClass('loaded')) {
			initialize_player();
		}

		$(this).addClass('open');
		$('#player').slideDown(function() {

			var waveform_url = $('#player').data('waveform_url');
			if(waveform_url) {
				var track = { waveform_url: waveform_url };
				initialize_waveform(track);
			}

			updatePlayerHeight();

		});
	});

	function initialize_player() {
		jQuery('.sc-player').scPlayer();
	}

	$(window).load(function() {
		// maybe it should load 5 seconds after site?
		setTimeout(function () {
			
	//$('#player_button').click();
		//	initialize_player();
		}, 1000);
	});

	initialize = false;
	$(document).bind('onPlayerInit.scPlayer', function(event) {

		initialize = true;
		$('#player').addClass('loaded');

		var waveform_url = $('#player').data('waveform_url');
		if(waveform_url) {
			var track = { waveform_url: waveform_url };
			initialize_waveform(track);
		}

		//updatePlayerHeight();
		
		queue_tracks();
	});

	// load all other tracks after initialization
	// this is to save time with the loading of the player
	function queue_tracks() {
		
		$('.queue_track').each(function() {
			var track = $(this);
			loadTrack($(this), true, function() {
				track.remove();
			});
		});
	}

	// when clicking tracks on the site
	var loaded_tracks = [];
	var loading_track = false;
	$('[data-play]').on('click', function() {

		if(loading_track) { return; }
		loading_track = true;

		if(!initialize && !$('#player_button').hasClass('open')) {
			$('#player_button').click();
		}

		loadTrack($(this), false, function() {
			loading_track = false;
		});

	});

	// initalLoad is for the loading of the page. it does two things
	//  - appends track to bottom of list 
	//  - does not click track
	// set it to true ONLY if you don't want the track you're loading to "play"
	function loadTrack(this_track, initialLoad, callback) {
		if(this_track == null) { return; }

		var player = $('#player .sc-player'),
		track = this_track.data(),
		track_url = this_track.data('track-url'),
		playing = false;

		if(track == null) { return; }

		if(this_track.hasClass('playing')) {
			playing = true;
		}

		$('[data-play]').each(function() {
			this_track.removeClass('playing');
		});

		if(!playing) {
			this_track.addClass('playing');
		}

		track_exists = false;
		for(var i = 0; i < loaded_tracks.length; i++) {
			if(loaded_tracks[i] === track_url) {
				track_exists = true;
			}
		}

		if(track_url != '') {
			var song = player.find('.sc-trackslist a[href="' + track_url + '"]');

			// if song does not exist, set loaded to true.
			if(!song.length && this_track.data('loaded') !== 'true') {
				$.scPlayer.loadTrackUrlAndPlay(player, track, initialLoad);
				this_track.data('loaded', 'true');
			} else {
				song.parent().trigger('click',  [{ fntTrack : true }]);
			}
		}

		if(callback != null) {
			callback();
		}
	}
	
	$(document).on('click','.navbar-player .fa-stack', function(event) {
		var tracklist = $('#player ol.sc-trackslist');
		tracklist.find('li.active').click();
	});	





});

jQuery(document).ready(function($) {

	setTimeout(function() {
		$.post(
			DB_Ajax.ajaxurl, {
				action : 'db_popular_post_aggregator',
				postCommentNonce : DB_Ajax.postCommentNonce,
			},
			function(response) { // success here console
			}); return false;
	}, 10000);
	
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