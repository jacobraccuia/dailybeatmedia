jQuery(document).ready(function($) {

	// scripts that need to be called on every page load
	window.global_scripts = function(reload) {

		$('#navbar').affix({
			offset: { top: 0 }
		});

		bLazy = new Blazy({
		    selector: '.featured-image, .blazy', // all images
		    offset: 100
		});


		$('.handle').on('click', function(e) {
			e.stopPropagation();
			if($('.nav-slide').hasClass('open')) { 
				$('.nav-slide').removeClass('open');
				return;
			}
			
			$('.nav-slide').addClass('open');

		});

		/* truncate meeeee */
		$('.post-wrapper .post-info dotdotdot').dotdotdot({
			watch: 'window'
		});

	}

	global_scripts();

	setInterval(function() {
		// refresh images every 5 min
		bLazy.revalidate();
	}, 5 * 60 * 1000);


	// set up cabinet	
	$('.toggle-menu').jPushMenu();

	/* music playaaa */
	// don't load player in reload scripts, as we don't want to refresh player when changing pages

	$('#player_button').on('click', function() {
		if($(this).hasClass('open')) {
			$(this).removeClass('open');
			$('#player').slideUp();
			return false;
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
		initialize_player();
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

		updatePlayerHeight();

	});

	// when clicking tracks on the site
	var loaded_tracks = [];
	$('[data-play]').on('click', function() {

		if(!initialize) {
			return false;
		}

		var player = $('#player .sc-player'),
		track = $(this).data(),
		track_url = $(this).data('track-url'),
		playing = false;

		if($(this).hasClass('playing')) {
			playing = true;
		}

		$('[data-play]').each(function() {
			$(this).removeClass('playing');
		});

		if(!playing) {
			$(this).addClass('playing');
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
			if(!song.length && $(this).data('loaded') !== 'true') {
				$.scPlayer.loadTrackUrlAndPlay(player, track);
				$(this).data('loaded', 'true');
			} else {
				song.parent().click();
			}
		}
	});
	
	$(document).on('click','.navbar-player .fa-stack', function(event) {
		var tracklist = $('#player ol.sc-trackslist');
		tracklist.find('li.active').click();
	});	





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