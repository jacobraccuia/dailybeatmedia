jQuery(document).ready(function($) {

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

 		var target_href = this.href;

 		// if url not going to another dailybeat page


		var matches = target_href.match(/^https?\:\/\/([^\/?#]+)(?:[\/?#]|$)/i);
		var domain = matches && matches[1];  // domain will be null if no match is found

		// if current page and target page are on the same domain
		if(domain != document.location.hostname || target_href.indexOf('/wp-admin') > -1) { 
			return;
		}

		event.preventDefault();		

		$('#page-progress').css('width', 0).show().animate({
			'width' : '20%'
		}, {
			'easing' : 'linear',
			'complete' : function() {
				$('#page-progress').animate({'width' : '60%'}, 3500, 'linear');
			}
		});

		$.ajax({
		    type: 'POST',
		    url: target_href,
		    success: function(data){

				$('#page-progress').stop().animate({
					'width' : '100%',
				}, {
					duration: 50,
					queue: false,
					complete: function() {
   						$('#page-progress').fadeOut();
					}
				});

				var new_page_content = $(data).siblings('#content').html();

			    var matches = data.match(/<title>(.*?)<\/title>/);
			    var page_title = matches[1];

				History.pushState({ html: new_page_content }, page_title, target_href);
		    }
		});

 		
		/*
		$.get(href, function(data) {
			
			// the ajax returns variable data, which contains an entire HTML page.
			// $('#content') is a SIBLING of the main page, not a child, since it's the highest level.
			// by taking the html of $('#content') we get the content we want to swap out.
			
			var new_data = $(data).siblings('#content').html();
			
	//		c(1);
			
			// apparently we don't update the page here, happens in state change bind
			//update_content(new_data);
			
			// add an item to the history log and tell the adapter what content to get
			History.pushState({ html: new_data }, event.target.textContent, event.target.href);
			
		});
		*/
	});



// this function updates page content with ajax and cool effects!
var updateRunning = 0;
function update_content(data) {
	// return an already resolved promise
	if(data == null) { return $().promise(); }
	
	++updateRunning;
	
	var id = $(data).prop('id'),
		content = "#" + id,
		header = 0, p1, p2;


		$('#content').invisible().html(data);
		$('#content').visible();
		

		return $.when(p1, p2).always(function() {
			--updateRunning;
		});
		
	}
	
	
	// this wrapper function queues up and lets all fadeIn/fadeOut happen before executing again
	var queue = [];
	function update_content_wrapper(data) {
		if(updateRunning !== 0) {
			// just queue the data if update already running
			queue.push(data);
		} else {
			update_content(data).always(function() {
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

	// store the initial content so we can revisit it later
//	History.replaceState({ html : current_data }, document.title, document.location.href);

	// revert to a previously saved state
	History.Adapter.bind(window, 'statechange', function() { // note: We are using statechange instead of popstate
        var event_state = History.getState(); // note: We are using History.getState() instead of event.state
		update_content_wrapper(event_state.data.html);	
	});


});



function randomIntFromInterval(min,max) {
	return Math.floor(Math.random()*(max-min+1)+min);
}

jQuery.fn.visible = function() {
    return this.css('visibility', 'visible');
};

jQuery.fn.invisible = function() {
    return this.css('visibility', 'hidden');
};
