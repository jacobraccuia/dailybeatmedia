/*
*   SoundCloud Custom Player jQuery Plugin
*   Author: Matas Petrikas, matas@soundcloud.com
*   Copyright (c) 2009  SoundCloud Ltd.
*   Licensed under the MIT license:
*   http://www.opensource.org/licenses/mit-license.php
*
*   Usage:
*   <a href="http://soundcloud.com/matas/hobnotropic" class="sc-player">My new dub track</a>
*   The link will be automatically replaced by the HTML based player
*/
(function($) {
  // Convert milliseconds into Hours (h), Minutes (m), and Seconds (s)
  var timecode = function(ms) {
    var hms = function(ms) {
      return {
        h: Math.floor(ms/(60*60*1000)),
        m: Math.floor((ms/60000) % 60),
        s: Math.floor((ms/1000) % 60)
      };
    }(ms),
        tc = []; // Timecode array to be joined with '.'

        if (hms.h > 0) {
          tc.push(hms.h);
        }

        tc.push((hms.m < 10 && hms.h > 0 ? "0" + hms.m : hms.m));
        tc.push((hms.s < 10  ? "0" + hms.s : hms.s));

        return tc.join('.');
      };
  // shuffle the array
  var shuffle = function(arr) {
    arr.sort(function() { return 1 - Math.floor(Math.random() * 3); } );
    return arr;
  };

  var debug = true,
  useSandBox = false,
  $doc = $(document),
  log = function(args) {
    try {
      if(debug && window.console && window.console.log){
        window.console.log.apply(window.console, arguments);
      }
    } catch (e) {
          // no console available
        }
      },
      domain = useSandBox ? 'sandbox-soundcloud.com' : 'soundcloud.com',
      secureDocument = (document.location.protocol === 'https:'),
      // convert a SoundCloud resource URL to an API URL
      scApiUrl = function(url, apiKey) {
        var resolver = ( secureDocument || (/^https/i).test(url) ? 'https' : 'http') + '://api.' + domain + '/resolve?url=',
        params = 'format=json&consumer_key=' + apiKey +'&callback=?';

        // force the secure url in the secure environment
        if( secureDocument ) {
          url = url.replace(/^http:/, 'https:');
        }

        // check if it's already a resolved api url
        if ( (/api\./).test(url) ) {
          return url + '?' + params;
        } else {
          return resolver + url + '&' + params;
        }
      };

  // TODO Expose the audio engine, so it can be unit-tested
  var audioEngine = function() {
    var html5AudioAvailable = function() {
      var state = false;
      try{
        var a = new Audio();
        state = a.canPlayType && (/maybe|probably/).test(a.canPlayType('audio/mpeg'));
          // uncomment the following line, if you want to enable the html5 audio only on mobile devices
          // state = state && (/iPad|iphone|mobile|pre\//i).test(navigator.userAgent);
        }catch(e){
          // there's no audio support here sadly
        }

        return state;
      }(),
      callbacks = {
        onReady: function() {
          $doc.trigger('scPlayer:onAudioReady');
        },
        onPlay: function() {
          $doc.trigger('scPlayer:onMediaPlay');
        },
        onPause: function() {
          $doc.trigger('scPlayer:onMediaPause');
        },
        onEnd: function() {
          $doc.trigger('scPlayer:onMediaEnd');
        },
        onBuffer: function(percent) {
          $doc.trigger({type: 'scPlayer:onMediaBuffering', percent: percent});
        }
      };

      var html5Driver = function() {
        var player = new Audio(),
        onTimeUpdate = function(event){
          var obj = event.target,
          buffer = ((obj.buffered.length && obj.buffered.end(0)) / obj.duration) * 100;
            // ipad has no progress events implemented yet
            callbacks.onBuffer(buffer);
            // anounce if it's finished for the clients without 'ended' events implementation
            if (obj.currentTime === obj.duration) { callbacks.onEnd(); }
          },
          onProgress = function(event) {
            var obj = event.target,
            buffer = ((obj.buffered.length && obj.buffered.end(0)) / obj.duration) * 100;
            callbacks.onBuffer(buffer);
          };

          $('<div class="sc-player-engine-container"></div>').appendTo(document.body).append(player);

      // prepare the listeners
      player.addEventListener('play', callbacks.onPlay, false);
      player.addEventListener('pause', callbacks.onPause, false);
      // handled in the onTimeUpdate for now untill all the browsers support 'ended' event
      // player.addEventListener('ended', callbacks.onEnd, false);
      player.addEventListener('timeupdate', onTimeUpdate, false);
      player.addEventListener('progress', onProgress, false);

      return {
        load: function(track, apiKey) {
          player.pause();
          player.src = track.stream_url + (/\?/.test(track.stream_url) ? '&' : '?') + 'consumer_key=' + apiKey;
          player.load();
          player.play();
        },
        play: function() {
          player.play();
        },
        pause: function() {
          player.pause();
        },
        stop: function(){
          if (player.currentTime) {
            player.currentTime = 0;
            player.pause();
          }
        },
        seek: function(relative){
          player.currentTime = player.duration * relative;
          player.play();
        },
        getDuration: function() {
          return player.duration * 1000;
        },
        getPosition: function() {
          return player.currentTime * 1000;
        },
        setVolume: function(val) {
          player.volume = val / 100;
        }
      };
    };

    var flashDriver = function() {
      var engineId = 'scPlayerEngine',
      player,
      flashHtml = function(url) {
        var swf = (secureDocument ? 'https' : 'http') + '://player.' + domain +'/player.swf?url=' + url +'&amp;enable_api=true&amp;player_type=engine&amp;object_id=' + engineId;
        if ($.browser.msie) {
          return '<object height="100%" width="100%" id="' + engineId + '" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" data="' + swf + '">'+
          '<param name="movie" value="' + swf + '" />'+
          '<param name="allowscriptaccess" value="always" />'+
          '</object>';
        } else {
          return '<object height="100%" width="100%" id="' + engineId + '">'+
          '<embed allowscriptaccess="always" height="100%" width="100%" src="' + swf + '" type="application/x-shockwave-flash" name="' + engineId + '" />'+
          '</object>';
        }
      };

      // listen to audio engine events
      // when the loaded track is ready to play
      soundcloud.addEventListener('onPlayerReady', function(flashId, data) {
        player = soundcloud.getPlayer(engineId);
        callbacks.onReady();
      });

      // when the loaded track finished playing
      soundcloud.addEventListener('onMediaEnd', callbacks.onEnd);

      // when the loaded track is still buffering
      soundcloud.addEventListener('onMediaBuffering', function(flashId, data) {
        callbacks.onBuffer(data.percent);
      });

      // when the loaded track started to play
      soundcloud.addEventListener('onMediaPlay', callbacks.onPlay);

      // when the loaded track is was paused
      soundcloud.addEventListener('onMediaPause', callbacks.onPause);

      return {
        load: function(track) {

          var url = track.uri;
          if(player){
            player.api_load(url);
          }else{
            // create a container for the flash engine (IE needs this to operate properly)
            $('<div class="sc-player-engine-container"></div>').appendTo(document.body).html(flashHtml(url));
          }
        },
        play: function() {
          player && player.api_play();
        },
        pause: function() {
          player && player.api_pause();
        },
        stop: function(){
          player && player.api_stop();
        },
        seek: function(relative){
          player && player.api_seekTo((player.api_getTrackDuration() * relative));
        },
        getDuration: function() {
          return player && player.api_getTrackDuration && player.api_getTrackDuration() * 1000;
        },
        getPosition: function() {
          return player && player.api_getTrackPosition && player.api_getTrackPosition() * 1000;
        },
        setVolume: function(val) {
          if(player && player.api_setVolume){
            player.api_setVolume(val);
          }
        }

      };
    };

    return html5AudioAvailable? html5Driver() : flashDriver();

  }();



  var apiKey,
  didAutoPlay = false,
  players = [],
  updates = {},
  currentUrl,
  loadTracksData = function($player, links, key) {
    var index = 0,
    playerObj = { node: $player, tracks: [], meta: [] },
    loadUrl = function(link) {
      var apiUrl = scApiUrl(link.url, apiKey);
      $.getJSON(apiUrl, function(data) {
                // log('data loaded', link.url, data);
                index += 1;

                if(data.tracks){
                  // log('data.tracks', data.tracks);
                  playerObj.tracks = playerObj.tracks.concat(data.tracks);
                }else if(data.duration){
                  // a secret link fix, till the SC API returns permalink with secret on secret response
                  data.permalink_url = link.url;
                  // if track, add to player

                  data.meta = link;
                  playerObj.tracks.push(data);
                }else if(data.creator){
                  // it's a group!
                  links.push({url:data.uri + '/tracks'});
                }else if(data.username){
                  // if user, get his tracks or favorites
                  if(/favorites/.test(link.url)){
                    links.push({url:data.uri + '/favorites'});
                  }else{
                    links.push({url:data.uri + '/tracks'});
                  }
                }else if($.isArray(data)){
                  playerObj.tracks = playerObj.tracks.concat(data);
                }

                if(links[index]){
                  // if there are more track to load, get them from the api
                  loadUrl(links[index]);
                }else{
                  // if loading finishes, anounce it to the GUI
                  playerObj.node.trigger({type:'onTrackDataLoaded', playerObj: playerObj, url: apiUrl});
                }
              });
};
        // update current API key
        apiKey = key;
        // update the players queue
        players.push(playerObj);
        // load first tracks
        loadUrl(links[index]);
      },
      artworkImage = function(track, usePlaceholder) {
        if(usePlaceholder){
          return '<div class="sc-loading-artwork">Loading Artwork</div>';
        }else if (track.artwork_url) {
          return '<img src="' + track.artwork_url.replace('-large', '-t300x300') + '"/>';
        }else{
          return '<div class="sc-no-artwork"></div>';
        }
      },

      updateTrackInfo = function($player, track, callback) {
        // update the current track info in the player
        // log('updateTrackInfo', track);
        $('.sc-info', $player).each(function(index) {
          $('h3', this).html('<a href="' + track.permalink_url +'">' + track.meta.title + '</a>');
         // $('h4', this).html('by <a href="' + track.user.permalink_url +'">' + track.user.username + '</a>');
          //$('p', this).html(track.description || 'no Description');
        });

        $('.navbar-player h3').html(track.meta.title);
        $('.navbar-player h4').html(track.meta.artist);

        // update bio
        $('.sc-artist-info', $player).each(function(index) {
          if(track.meta.thumb_url != '' && track.meta.thumb_url != null) {    
            $(this).html('<div class="artist-pic"><img src="' + track.meta.thumb_url + '" /></div><div class="artist-meta"><h4>' + track.meta.artist + '</h4><ul class="artist-social"></ul></div>');
            if(track.meta.artist_twitter != '') {
              $(this).find('ul').append('<li><a href="http://twitter.com/' + track.meta.artist_twitter + '" target="_blank"><i class="fa fa-fw fa-twitter"></i></a></li>');
            }
            if(track.meta.artist_instagram != '') {
              $(this).find('ul').append('<li><a href="http://instagram.com/' + track.meta.artist_instagram + '" target="_blank"><i class="fa fa-fw fa-instagram"></i></a></li>');
            }
            if(track.meta.artist_soundcloud != '') {
              $(this).find('ul').append('<li><a href="http://soundcloud.com/' + track.meta.artist_soundcloud   + '" target="_blank"><i class="fa fa-fw fa-soundcloud"></i></a></li>');
            }

            $('.artist-list-divider').show();
            $(this).slideDown(400, function() {
                  updatePlayerHeight();
            });
          } else { 
            $('.artist-list-divider').hide();
            $(this).slideUp(400, function() {
                  updatePlayerHeight();
            });
          }

        });

        // update the artwork
        $('.sc-artwork-list li', $player).each(function(index) {
          var $item = $(this),
          itemTrack = $item.data('sc-track');

          if (itemTrack === track) {
            // show track artwork
            $item
            .addClass('active')
            .find('.sc-loading-artwork')
            .each(function(index) {
                  // if the image isn't loaded yet, do it now
                  $(this).removeClass('sc-loading-artwork').html(artworkImage(track, false));
                });
          
          } else {
            // reset other artworks
            $item.removeClass('active');
          }

          $('.navbar-player .art').html(artworkImage(track, false));

        });
        // update the track duration in the progress bar
        $('.sc-duration', $player).html(timecode(track.duration));
        // put the waveform into the progress bar
       // $('.sc-waveform-container', $player).html('<img src="' + track.waveform_url +'" />');

       $player.trigger('onPlayerTrackSwitch.scPlayer', [track]);
       if(callback) {
        callback();
       }
     },

     play = function(track) {
      var url = track.permalink_url;

      if(currentUrl === url){
          // log('will play');
          audioEngine.play();
        }else{
          currentUrl = url;
          // log('will load', url);
          audioEngine.load(track, apiKey);
        }			
      },
      getPlayerData = function(node) {
        return players[$(node).data('sc-player').id];
      },
      updatePlayStatus = function(player, status) {
        if(status){
          // reset all other players playing status
          $('div.sc-player.playing').removeClass('playing');
        }
        $(player)
        .toggleClass('playing', status)
        .trigger((status ? 'onPlayerPlay' : 'onPlayerPause'));
      },
      onPlay = function(player, id) {
        var track = getPlayerData(player).tracks[id || 0];

        updateTrackInfo(player, track);
        // cache the references to most updated DOM nodes in the progress bar
        updates = {
          $buffer: $('.sc-buffer', player),
          $played: $('.sc-played', player),
          position:  $('.sc-position', player)[0]
        };
        updatePlayStatus(player, true);
        play(track);

      },
      onPause = function(player) {
        updatePlayStatus(player, false);
        audioEngine.pause();
      },
      onFinish = function() {
        var $player = updates.$played.closest('.sc-player'),
        $nextItem;
        // update the scrubber width
        updates.$played.css('width', '0%');
        // show the position in the track position counter
        updates.position.innerHTML = timecode(0);
        // reset the player state
        updatePlayStatus($player, false);
        // stop the audio
        audioEngine.stop();
        $player.trigger('onPlayerTrackFinish');
      },
      onSeek = function(player, relative) {
        audioEngine.seek(relative);
        $(player).trigger('onPlayerSeek');
      },
      onSkip = function(player) {
        var $player = $(player);
        // continue playing through all players
        log('track finished get the next one');
        $nextItem = $('.sc-trackslist li.active', $player).nextAll('li').not('.divider').first();

        if($nextItem.length < 1) {
          $nextItem = $('.sc-trackslist li.active', $player).parent('.sc-trackslist').find('li').first(); 
        }

        // try to find the next track in other player
        if(!$nextItem.length){
          $nextItem = $player.nextAll('div.sc-player:first').find('.sc-trackslist li.active');
        }
        $nextItem.click();
      },
      soundVolume = function() {
        var vol = 60,
        cooks = document.cookie.split(';'),
        volRx = new RegExp('scPlayer_volume=(\\d+)');
        for(var i in cooks){
          if(volRx.test(cooks[i])){
            vol = parseInt(cooks[i].match(volRx)[1], 10);
            break;
          }
        }
        return vol;
      }(),
      onVolume = function(volume) {
        var vol = Math.floor(volume);
        // save the volume in the cookie
        var date = new Date();
        date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
        soundVolume = vol;
        document.cookie = ['scPlayer_volume=', vol, '; expires=', date.toUTCString(), '; path="/"'].join('');
        // update the volume in the engine
        audioEngine.setVolume(soundVolume);
      },
      positionPoll;

    // listen to audio engine events
    $doc
    .bind('scPlayer:onAudioReady', function(event) {
      log('onPlayerReady: audio engine is ready');
      audioEngine.play();
        // set initial volume
        onVolume(soundVolume);
      })
      // when the loaded track started to play
      .bind('scPlayer:onMediaPlay', function(event) {
        clearInterval(positionPoll);
        positionPoll = setInterval(function() {
          var duration = audioEngine.getDuration(),
          position = audioEngine.getPosition(),
          relative = (position / duration);

          big_waveform(relative);

          // update the scrubber width
          updates.$played.css('width', (100 * relative) + '%');
          // show the position in the track position counter
          updates.position.innerHTML = timecode(position);
          // announce the track position to the DOM

          $doc.trigger({
            type: 'onMediaTimeUpdate.scPlayer',
            duration: duration,
            position: position,
            relative: relative
          });
        }, 500);
      })
      // when the loaded track is was paused (lol @ this comment)
      .bind('scPlayer:onMediaPause', function(event) {
        clearInterval(positionPoll);
        positionPoll = null;
      })
      // change the volume
      .bind('scPlayer:onVolumeChange', function(event) {
        onVolume(event.volume);
      })
      .bind('scPlayer:onMediaEnd', function(event) {
        onFinish();
      })
      .bind('scPlayer:onMediaBuffering', function(event) {
        updates.$buffer.css('width', event.percent + '%');
      });


  // Generate custom skinnable HTML/CSS/JavaScript based SoundCloud players from links to SoundCloud resources
  $.scPlayer = function(options, node) {
    var opts = $.extend({}, $.scPlayer.defaults, options),
    playerId = players.length,
    $source = node && $(node),
    sourceClasses = $source[0].className.replace('sc-player', ''),
    links = opts.links || $.map($('a', $source).add($source.filter('a')), function(val) {
      return {
        url: val.href,
        title: val.innerHTML,
        artist: val.getAttribute('data-artist') || '',
        track: val.getAttribute('data-track') || '',
        thumb_url: val.getAttribute('data-thumb-url') || '',
        artist_instagram: val.getAttribute('data-artist-instagram') || '',
        artist_twitter: val.getAttribute('data-artist-twitter') || '',
        artist_soundcloud: val.getAttribute('data-artist-soundcloud') || '',
      }; 
    }),
    $player = $('<div class="sc-player loading"></div>').data('sc-player', {id: playerId}),
    $artworks = $('<ol class="sc-artwork-list"></ol>').appendTo($player),
    $info = $('<div id="large-waveform"></div><div class="sc-info"><h3 data-marquee="0"></h3><a href="#" class="sc-info-close">X</a></div>').appendTo($player),
    $controls = $('<div class="list-divider"></div><div class="sc-controls"></div><div class="list-divider"></div><div class="sc-artist-info"></div><div class="list-divider artist-list-divider"></div><h4 class="playlist-header">Keep Listening</h4><div class="list-divider"></div></div>').appendTo($player),
    $list = $('<ol class="sc-trackslist"></ol>').appendTo($player);
    $('<div class="powered-by">powered by soundcloud</div>').appendTo($player);
        // add the classes of the source node to the player itself
        // the players can be indvidually styled this way
        if(sourceClasses || opts.customClass){
          $player.addClass(sourceClasses).addClass(opts.customClass);
        }


        // adding controls to the player
        $player
        .find('.sc-controls')
       // .append('')
     //   .append('<a href="' + links[0].url + '/download" class="sc-download" target="_blank"><span class="glyphicon glyphicon-download-alt"></span></a>')
		//	.append('<div class="sc-volume-slider"><span class="sc-volume-status" style="width:' + soundVolume +'%"></span></div>')
   //.append('<a href="#" class="sc-volume-off"><span class="glyphicon glyphicon-volume-off"></span></a><a href="#" class="sc-volume-on"><span class="glyphicon glyphicon-volume-up"></span></a>')
   .append('<a href="#previous" class="sc-previous"><i class="fa fa-fw fa-backward"></i></a><a href="#play" class="sc-play"><i class="fa fa-fw fa-play"></i></a> <a href="#pause" class="sc-pause hidden"><span class="fa fa-fw fa-pause"></a><a href="#next" class="sc-next"><i class="fa fa-fw fa-forward"></i></a>')
   .end()
         // .append('<a href="#info" class="sc-info-toggle">Info</a>')
         .find('.sc-controls')
         .append('<div class="sc-scrubber"></div>')
         .find('.sc-scrubber')
         .append('<div class="sc-time-span"><div class="sc-waveform-container"></div><div class="sc-buffer"></div><div class="sc-played"></div></div>')
         .append('<div class="sc-time-indicators"><span class="sc-position"></span> | <span class="sc-duration"></span></div>');

        // load and parse the track data from SoundCloud API
       // console.log(links);
       loadTracksData($player, links, opts.apiKey);
        // init the player GUI, when the tracks data was laoded
        $player.bind('onTrackDataLoaded.scPlayer', function(event) {
          // log('onTrackDataLoaded.scPlayer', event.playerObj, playerId, event.target);
          var tracks = event.playerObj.tracks;

          var meta = event.playerObj.meta;
          if (opts.randomize) {
            tracks = shuffle(tracks);
          }
          // create the playlist


          $.each(tracks, function(index, track) {
            var active = index === 0;



			// create an item in the playlist
      $('<li><a href="' + track.permalink_url +'"><div class="track-name">' + track.meta.title + '</div><div class="artist-name">' + track.meta.artist + '</div></a><span class="sc-track-duration">' + timecode(track.duration) + '</span></li><li class="divider"></div>').data('sc-track', {id:index}).toggleClass('active', active).appendTo($list);
            // create an item in the artwork list
            $('<li></li>')
            .append(artworkImage(track, index >= opts.loadArtworks))
            .appendTo($artworks)
            .toggleClass('active', active)
            .data('sc-track', track);
          });
          // update the element before rendering it in the DOM
          $player.each(function() {
            if($.isFunction(opts.beforeRender)){
              opts.beforeRender.call(this, tracks);
            }
          });
          // set the first track's duration
          $('.sc-duration', $player)[0].innerHTML = timecode(tracks[0].duration);
          $('.sc-position', $player)[0].innerHTML = timecode(0);
          // set up the first track info
          updateTrackInfo($player, tracks[0]);


          // if continous play enabled always skip to the next track after one finishes
          if (opts.continuePlayback) {
            $player.bind('onPlayerTrackFinish', function(event) {
              onSkip($player);
            });
          }

          // announce the succesful initialization
          $player
          .removeClass('loading')
          .trigger('onPlayerInit');
          

          // if auto play is enabled and it's the first player, start playing
          if(opts.autoPlay && !didAutoPlay){
            onPlay($player);
            didAutoPlay = true;
          }
        });


    // replace the DOM source (if there's one)
    $source.each(function(index) {
      $(this).replaceWith($player);
    });

    return $player;
  };

  // stop all players, might be useful, before replacing the player dynamically
  $.scPlayer.stopAll = function() {
    $('.sc-player.playing a.sc-pause').click();
  };

  // destroy all the players and audio engine, usefull when reloading part of the page and audio has to stop
  $.scPlayer.destroy = function() {
    $('.sc-player, .sc-player-engine-container').remove();
  };

  // plugin wrapper
  $.fn.scPlayer = function(options) {
    // reset the auto play
    didAutoPlay = false;
    // create the players
    this.each(function() {
      $.scPlayer(options, this);
    });
    return this;
  };

  // default plugin options
  $.scPlayer.defaults = $.fn.scPlayer.defaults = {
    customClass: null,
    // do something with the dom object before you render it, add nodes, get more data from the services etc.
    beforeRender  :   function(tracksData) {
      var $player = $(this);
    },
    // initialization, when dom is ready
    onDomReady  : function() {
      $('a.sc-player, div.sc-player').scPlayer();
    },
    autoPlay: false,
    continuePlayback: true,
    randomize: false,
    loadArtworks: 5,
    // the default Api key should be replaced by your own one
    // get it here http://soundcloud.com/you/apps/new
    apiKey: '9f690b3117f0c43767528e2b60bc70ce'
  };


  // the GUI event bindings
  //--------------------------------------------------------

  var current_title = $(document).attr('title');

  // toggling play/pause
  $(document).on('click','a.sc-play, a.sc-pause', function(event) {
    var $list = track_name = $(this).closest('.sc-player').find('ol.sc-trackslist');
    
    // simulate the click in the tracklist
    if(!track_name.length) { 
      track_name = $('.full-width-soundcloud').find('ol.sc-trackslist');
    }

    $list.find('li.active').click();
    return false;
  });

  // previous track
  $(document).on('click', 'a.sc-previous', function(event) {
    event.preventDefault();
    var $list = track_name = $(this).closest('.sc-player').find('ol.sc-trackslist');

    var track = $list.find('li.active').prevAll('li').not('.divider').first();
    if(track.length < 1) {
      track = $list.find('li:not(.divider)').last(); 
    }

    track.click();

    return false;
  });

  // next track
  $(document).on('click', 'a.sc-next', function(event) {
    event.preventDefault();
    var $list = track_name = $(this).closest('.sc-player').find('ol.sc-trackslist');

    var track = $list.find('li.active').nextAll('li').not('.divider').first();
    if(track.length < 1) {
      track = $list.find('li:not(.divider)').first(); 
    }

    track.click();

    return false;
  });


  // displaying the info panel in the player
  $(document).on('click','a.sc-info-toggle, a.sc-info-close', function(event) {
    var $link = $(this);
    $link.closest('.sc-player')
    .find('.sc-info').toggleClass('active').end()
    .find('a.sc-info-toggle').toggleClass('active');
    return false;
  });

  // selecting tracks in the playlist
  $(document).on('click','.sc-trackslist li:not(.divider)', function(event) {

    var $track = $(this),
    $player = $track.closest('.sc-player'),
    trackId = $track.data('sc-track').id,
    trackPlaying = $player.is(':not(.playing)') || $track.is(':not(.active)');

    var song = $(this).find('a').attr('href');
    $('.track [data-play]').removeClass('playing');

    var all_tracks = $('[data-track-url="' + song + '"]');
    var marquee = $player.find('.sc-info h3');

    var current_track = $('#player .sc-trackslist').find('li.active a').text();

    if($(this).hasClass('sc-play')) {
      $(document).attr('title', '▶ ' + current_track);
    } else {
      $(document).attr('title', current_title);
    }

    // if track is playing
    if(trackPlaying) {
      onPlay($player, trackId);

      all_tracks.addClass('playing');
      if(marquee.find('a').height() > 25) {
		    marquee.marquee({
					speed: 12000,
					gap: 50,
					delayBeforeStart: 0,
					direction: 'left',
					duplicated: true
				});
      } else {
        marquee.marquee('pause');
      }

      $('.navbar-player').addClass('playing').addClass('active');
      $('#player_button').addClass('active');

    } else {
      onPause($player);
      all_tracks.removeClass('playing');
      $('.navbar-player').removeClass('playing');

    }

    $track.addClass('active').siblings('li').removeClass('active');
    $('.artworks li', $player).each(function(index) {
      $(this).toggleClass('active', index === trackId);
    });

  prependTrackToFront();


  return false;
});

var scrub = function(node, xPos) {
  var $scrubber = $(node).closest('.sc-time-span'),
  $buffer = $scrubber.find('.sc-buffer'),
  $available = $scrubber.find('.sc-waveform-container img'),
  $player = $scrubber.closest('.sc-player'),
  relative = Math.min($buffer.width(), (xPos  - $available.offset().left)) / $available.width();
  onSeek($player, relative);
};

var onTouchMove = function(ev) {
  if (ev.targetTouches.length === 1) {
    scrub(ev.target, ev.targetTouches && ev.targetTouches.length && ev.targetTouches[0].clientX);
    ev.preventDefault();
  }
};


  // seeking in the loaded track buffer
  $(document)
  .on('click','.sc-time-span', function(event) {
    scrub(this, event.pageX);
    return false;
  })
  .on('touchstart','.sc-time-span', function(event) {
    this.addEventListener('touchmove', onTouchMove, false);
    event.originalEvent.preventDefault();
  })
  .on('touchend','.sc-time-span', function(event) {
    this.removeEventListener('touchmove', onTouchMove, false);
    event.originalEvent.preventDefault();
  });


  // changing volume in the player
  var startVolumeTracking = function(sound) {
  //  var $node = $(node),
      //  originX = $node.offset().top,
     //   originHeight = $node.height(),
     //   getVolume = function(x) {
  	//		console.log(originHeight - originX);
      //    return Math.floor((1 - ((x - originX)/originHeight))*100);
      //  },
      var update = function(vol) {
        $doc.trigger({type: 'scPlayer:onVolumeChange', volume: vol });
      };

      if(sound == "on") {

        update(50);  

      } else {


        update(0);
  //  $node.bind('mousemove.sc-player', update);
 //   update(startEvent);
}
};

$(document).on('click', '.sc-volume-on', function(e) {
 e.preventDefault();
 startVolumeTracking('on');
 $(this).parents('.sc-player').find('.sc-volume-off').show();
 $(this).hide();
});
$(document).on('click', '.sc-volume-off', function(e) {
 e.preventDefault();
 startVolumeTracking('off');
 $(this).parents('.sc-player').find('.sc-volume-on').show();
 $(this).hide();
});


/*
  $doc.bind('scPlayer:onVolumeChange', function(event) {
    $('span.sc-volume-status').css({height: event.volume + '%'});
  });

*/


$(document).on('click', '#large-waveform canvas', function(event) {

  var cursor_position = get_cursor_position($(this), event);
  var x = cursor_position[0];

  var left_offset = $(this).offset().left;
  var distance_from_left = x - left_offset;
  
  $player = $('#player .sc-player');

  if($player.is(':not(.playing)')) {
   $('.sc-controls .sc-play').click();  
  }

  //relative = Math.min(distance_from_left / $(this).width());
  relative = Math.min(x / $(this).width());


  setTimeout(function() { onSeek($player, relative); }, 100);

});

$(document).on('mousemove', '#large-waveform canvas', function(event) {

  if($('#player .sc-player.playing').length) {

    var cursor_position = get_cursor_position($(this), event);
    var x = cursor_position[0];

    window.mouse_position_x = mouse_position = x / $(this).width();

    var duration = audioEngine.getDuration(),
    position = audioEngine.getPosition(),
    relative = (position / duration);

    big_waveform(relative, mouse_position);
  }
});

$(document).on('mouseleave', '#large-waveform canvas', function(event) {
  window.mouse_position_x = 0;

  var duration = audioEngine.getDuration(),
  position = audioEngine.getPosition(),
  relative = (position / duration);

  big_waveform(relative, mouse_position_x);
});



$.scPlayer.loadTrackInfoAndPlay = function($elem, track, meta) {
  var playerObj = players[$elem.data('sc-player').id];
  track.meta = meta; // add meta to track data
  playerObj.tracks.push(track);
  console.log(track);
  var index = playerObj.tracks.length-1;
  var $list = $(playerObj.node).find('.sc-trackslist');
  var $artworks = $(playerObj.node).find(".sc-artwork-list");

        // add to playlist
        var $li = $('<li><a href="' + track.permalink_url +'"><div class="track-name">' + track.meta.track + '</div><div class="artist-name">' + meta.artist + '</div></a><span class="sc-track-duration">' + timecode(track.duration) + '</span></li><li class="divider"></div>')
        .data('sc-track', {id:index})
        .prependTo($list);



        // add to artwork list
        $('<li></li>')
        .append(artworkImage(track, true))
        .appendTo($artworks)
        .data('sc-track', track);
        $li.click();
      }

      $.scPlayer.loadTrackUrlAndPlay = function($elem, track){
        var url = track.trackUrl;

        var apiUrl = scApiUrl(url, apiKey);
        $.getJSON(apiUrl, function(data) {
          if(data.duration){
            data.permalink_url = url;
            $.scPlayer.loadTrackInfoAndPlay($elem, data, track);
          }
        });
      }


  // ------------------------------------------------------------------- \\
  // the default Auto-Initialization
  $(function() {
    if($.isFunction($.scPlayer.defaults.onDomReady)){
      $.scPlayer.defaults.onDomReady = null;

    }
  });
})(jQuery);


  jQuery(document).bind('onPlayerTrackSwitch.scPlayer', function(event, track) {

    jQuery('#player').data('waveform_url', track.waveform_url);

    // if player isn't loaded or active, remove canvas
    if(jQuery('#player').hasClass('loaded')) {
      initialize_waveform(track);
    } 

    updatePlayerHeight();

  });

function updatePlayerHeight() { 
    var player_height = jQuery('#player').height();

    var total_height = 0;
    jQuery('#player > .sc-player > *').each(function() {
      if(jQuery(this).hasClass('sc-trackslist') || !jQuery(this).is(':visible')) { return; }
      total_height += jQuery(this).outerHeight();
    });

    var track_height = 150; //jQuery('#player .sc-trackslist').height();

    total_height = total_height;
    if(total_height < player_height) {
      // add remaining height to trackslist
      jQuery('#player .sc-trackslist').height((player_height - total_height) - 2);
    }

    prependTrackToFront();
  }

function prependTrackToFront() {
    if(jQuery('.sc-trackslist').find('li.active').index() > 0) {


      jQuery('.sc-trackslist').css('overflow-y', 'hidden');

      jQuery('.sc-trackslist').find('li.active').nextAll().andSelf().prependTo('.sc-trackslist');

      // stupid bug fix
      jQuery('.sc-trackslist').scrollTop(0);
      jQuery('.sc-trackslist').css('overflow-y', 'scroll');
    }
}

x = 0;
function initialize_waveform(track) {
  console.log(x);
  x++;
  var trk = jQuery('#large-waveform').data('track');

 /* // CHECK IF CANVAS HAS SHIT IN IT
  // remove canvas if it exists and is not the currently playing track..
  if(trk != track.permalink || trk == null) { 
    jQuery('#large-waveform canvas').remove();
    console.log('remove')
  }

  // if large waveform div exists, canvas does NOT exist, and a track is provided
  if(jQuery('#large-waveform').length && !jQuery('#large-waveform canvas').length && track != "") {
    jQuery('#large-waveform').data('track', track.permalink);
    waveform = new Waveform({
      container: document.getElementById('large-waveform'),
      innerColor: 'rgba(255, 255, 255, 0.3)',
      height: 200
    });
    
    waveform.dataFromSoundCloudTrack(track);
  }
*/


  // if large waveform div exists, canvas does NOT exist, and a track is provided
  if(!jQuery('#large-waveform canvas').length) {

    jQuery('#large-waveform').data('track', track.permalink);
    waveform = new Waveform({
      container: document.getElementById('large-waveform'),
      innerColor: 'rgba(255, 255, 255, 0.3)',
      height: 200
    });
   
   }
 
    waveform.dataFromSoundCloudTrack(track);
}


function big_waveform(track_position, mouse_position) { 

  if(typeof mouse_position == 'undefined') { mouse_position = window.mouse_position_x; }

  var color = '00E2CF';
  color = hex_to_rgb(color);
  var peaks = 1 / window.sc_data.length;

  if(jQuery('#large-waveform').length) {
    var waveform = new Waveform({
      canvas: jQuery('#large-waveform canvas')[0],
      height: 200,
      data: window.sc_data,
      innerColor: function(waveform_position, y) { // waveform_position is where the current waveform is in the drawing process, from 0 - 1
        // if mouse is ahead of song
        if(mouse_position > waveform_position) {
          if(track_position > waveform_position) {
              return 'rgba(' + getGradientColor('00E2CF', '5784E0', track_position) + ', .8)';
          } else if(track_position < mouse_position) { 
              return 'rgba(' + getGradientColor('00E2CF', '5784E0', track_position) + ', .6)';
          }
        } else {
          // if mouse is not on the waveform 
          if(mouse_position == 0) {
            if(track_position > waveform_position) {
              
              return 'rgba(' + getGradientColor('00E2CF', '5784E0', track_position) + ', .8)';
             // return 'rgba(' + color + ', .9)';
            } else if(track_position + peaks > waveform_position) { // super transparent one bar ahead of song
              return 'rgba(' + getGradientColor('00E2CF', '5784E0', track_position) + ', .4)';
            } else {
              return 'unplayed'; //rgba(255, 255, 255, 0.3)'; // unplayed
            }
          }
          // if mouse is stationary
          else if(track_position > waveform_position) {
              return 'rgba(' + getGradientColor('00E2CF', '5784E0', track_position) + ', .6)';
          } else if(track_position + peaks > waveform_position) { // super transparent one bar ahead of song
              return 'rgba(' + getGradientColor('00E2CF', '5784E0', track_position) + ', .4)';
          } else {
            return 'unplayed'; //rgba(255, 255, 255, 0.3)'; // unplayed
          }

          return '';
        } 
      }
    }); waveform.redraw();
  }
}

function get_cursor_position(canvas, event) {
  var x, y;
  
  canoffset = jQuery(canvas).offset();
  x = event.clientX + document.body.scrollLeft + document.documentElement.scrollLeft - Math.floor(canoffset.left);
  y = event.clientY + document.body.scrollTop + document.documentElement.scrollTop - Math.floor(canoffset.top) + 1;
  
  return [x,y];
}


 getGradientColor = function(start_color, end_color, percent) {
   // strip the leading # if it's there
   start_color = start_color.replace(/^\s*#|\s*$/g, '');
   end_color = end_color.replace(/^\s*#|\s*$/g, '');

   // convert 3 char codes --> 6, e.g. `E0F` --> `EE00FF`
   if(start_color.length == 3){
     start_color = start_color.replace(/(.)/g, '$1$1');
   }

   if(end_color.length == 3){
     end_color = end_color.replace(/(.)/g, '$1$1');
   }

   // get colors
   var start_red = parseInt(start_color.substr(0, 2), 16),
       start_green = parseInt(start_color.substr(2, 2), 16),
       start_blue = parseInt(start_color.substr(4, 2), 16);

   var end_red = parseInt(end_color.substr(0, 2), 16),
       end_green = parseInt(end_color.substr(2, 2), 16),
       end_blue = parseInt(end_color.substr(4, 2), 16);

   // calculate new color
   var diff_red = end_red - start_red;
   var diff_green = end_green - start_green;
   var diff_blue = end_blue - start_blue;

   diff_red = ( (diff_red * percent) + start_red ).toString(16).split('.')[0];
   diff_green = ( (diff_green * percent) + start_green ).toString(16).split('.')[0];
   diff_blue = ( (diff_blue * percent) + start_blue ).toString(16).split('.')[0];

   // ensure 2 digits by color
   if( diff_red.length == 1 )
     diff_red = '0' + diff_red

   if( diff_green.length == 1 )
     diff_green = '0' + diff_green

   if( diff_blue.length == 1 )
     diff_blue = '0' + diff_blue

   return hex_to_rgb('#' + diff_red + diff_green + diff_blue);
 };



function hex_to_rgb(hex) {

  hex = hex.replace(/[^0-9A-F]/gi, '');
  
  var bigint = parseInt(hex, 16);
  var r = (bigint >> 16) & 255;
  var g = (bigint >> 8) & 255;
  var b = bigint & 255;

  return r + "," + g + "," + b;
}



function c(x) {
	console.log(x);	
}