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
