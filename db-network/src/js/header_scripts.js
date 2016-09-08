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
