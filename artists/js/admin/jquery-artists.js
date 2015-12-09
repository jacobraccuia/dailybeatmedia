jQuery(document).ready(function($) {

	// blah but it works!

	$('.songkick-button').on('click', function(e) {
		e.preventDefault();

		var 
		artist_name = $('#artist_name').val() || '',
		artist_id = $('#artist_id').val() || '',
		api_key = '4sT0rY7JO9H5KnnE',
		url = '',	
		call = $(this).data('call'),
		errors = 0,
		response = '';

		if(call == 'search') {
			if(artist_name == '') {
				response = 'artist name cannot be blank for this to work!'; 
				errors++; 
			}
			url = 'http://api.songkick.com/api/3.0/search/artists.json?query=' + artist_name + '&';
		}

		if(call == 'update') {
			if(artist_id == '') {
				response = 'artist id cannot be blank for this to work!'; 
				return false; 
			}
			url = 'http://api.songkick.com/api/3.0/artists/' + artist_id + '/calendar.json?';
		} 

		if(url == '') {
			response = 'contact ya boy jacob, something went wrong.'; 
			errors++; 
		}

		// make sure url ends in &
		url = url + 'apikey=' + api_key + '&jsoncallback=?'; 

		if(errors > 0) {
			$('#songkick_results').html(response);
		}

		$.getJSON(url, function(data) {
			if(call == 'update') {

				// parse and pretty result for user to pick
				var str = JSON.stringify(data, null, 2);
				$('#artist_tour_dates').text(str);

				response = '<div>Success! Don\'t forget to save! JSON result below for those curious: </div><pre>' + syntaxHighlight(str) + '</pre>';
			}

			if(call == 'search') {
				// if artist id is not set and there is one result, enter into field
				if(data.resultsPage.totalEntries == 1) {
					$('#artist_id').val(data.resultsPage.results.artist[0].id);
					response = '<div>Success! Updated Artist ID to ' + data.resultsPage.results.artist[0].id + '. Don\'t forget to save!';
				} else {
					// parse and pretty result for user to pick
					var str = JSON.stringify(data, null, 2);
					response = '<div>Success! Find proper ID below.</div><pre>' + syntaxHighlight(str) + '</pre>';
				}
			}

			$('#songkick_results').html(response);
			return;
		});
	});


});

function syntaxHighlight(json) {
	if (typeof json != 'string') {
		json = JSON.stringify(json, undefined, 2);
	}
	json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
		var cls = 'number';
		if (/^"/.test(match)) {
			if (/:$/.test(match)) {
				cls = 'key';
			} else {
				cls = 'string';
			}
		} else if (/true|false/.test(match)) {
			cls = 'boolean';
		} else if (/null/.test(match)) {
			cls = 'null';
		}
		return '<span class="' + cls + '">' + match + '</span>';
	});
}