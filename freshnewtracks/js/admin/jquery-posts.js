jQuery(document).ready(function($) {


//live search functions

	// create artist users search data object to use for ... you guessed it ... searching. !
	var search_data = [];
	var i = 0;
	$('#artist_database > li').each(function() {
		var id = $(this).data('id');
		var name = $(this).data('name');
		
		search_data[i] = {};
		search_data[i]['label'] = name;
		search_data[i]['value'] = name;
		search_data[i]['id'] = id;

		i++;
	});

	$('#db_feat_artist').autocomplete({
		source: search_data,
		focus: function(event, ui) {
			return false;
		},
		select: function(event, ui) {
			$('#db_feat_artist').val(ui.item.label);
			$('#db_featured_artist_id').val(ui.item.id);
			return false;
		}
	}).data('ui-autocomplete')._renderItem = function(ul, item) {
		return $('<li>').data('ui-autocomplete-item', item ).append(item.label + ' [' + item.id + ']').appendTo(ul);
	};


	$('.track_url').on('change, focusout, blur', function(e) {
		e.preventDefault();

		var track = $('.track_url').val();
	
		SC.get('/resolve?url=' + track, function(tracks, err) {

			if(tracks !== null && tracks.errors == 1 || err && err.message == '404 - Not Found' || err.message == "HTTP Error: 403") {
				$('.private_track_error').html('<span style="color:red;">This track is private and cannot play.  Please don\'t publish me!!</span>');
			} else {
				$('.private_track_error').html('<span style="color:green;">This track should play, but you should check anyways :)</span>');	
			}

	//			var waveform_url = tracks.waveform_url;

//			JSONP.get("http://www.waveformjs.org/w", { url: waveform_url.replace('https', 'http') }, function(data) {
//				$('.track_waveform').val(data);
//			});
		});


	});	

	SC.initialize({
	    client_id: "9f690b3117f0c43767528e2b60bc70ce"
	});

	JSONP = (function() {
		var config, counter, encode, head, jsonp, key, load, query, setDefaults, window;
		load = function(url) {
			var done, head, script;
			script = document.createElement("script");
			done = false;
			script.src = url;
			script.async = true;
			script.onload = script.onreadystatechange = function() {
				if (!done && (!this.readyState || this.readyState === "loaded" || this.readyState === "complete")) {
					done = true;
					script.onload = script.onreadystatechange = null;
					if (script && script.parentNode) {
						return script.parentNode.removeChild(script);
					}
				}
			};
			if (!head) {
				head = document.getElementsByTagName("head")[0];
			}
			return head.appendChild(script);
		};
		encode = function(str) {
			return encodeURIComponent(str);
		};
		jsonp = function(url, params, callback, callbackName) {
			var key, query, uniqueName;
			query = ((url || "").indexOf("?") === -1 ? "?" : "&");
			callbackName = callbackName || config["callbackName"] || "callback";
			uniqueName = callbackName + "_json" + (++counter);
			params = params || {};
			for (key in params) {
				if (params.hasOwnProperty(key)) {
					query += encode(key) + "=" + encode(params[key]) + "&";
				}
			}
			window[uniqueName] = function(data) {
				callback(data);
				try {
					delete window[uniqueName];
				} catch (_error) {}
				return window[uniqueName] = null;
			};
			load(url + query + callbackName + "=" + uniqueName);
			return uniqueName;
		};
		setDefaults = function(obj) {
			var config;
			return config = obj;
		};
		counter = 0;
		head = void 0;
		query = void 0;
		key = void 0;
		window = this;
		config = {};
		return {
			get: jsonp,
			init: setDefaults
		};
	})();


});