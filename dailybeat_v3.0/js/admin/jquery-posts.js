jQuery(document).ready(function($) {

	// create artist search data object to use for ... you guessed it ... searching. !
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

	if($('#db_feat_artist').length) {
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
	}


	$('#new_section').on('click', function() {
		var section_number = $(this).data('section');
		var post_id = $(this).data('post_id');

		$('#new_section').data('section', section_number);

		$.post(
			DB_Ajax_Call.ajaxurl, {
				action : 'load_section',
				counter : section_number,
				post_id : post_id,
				postCommentNonce : DB_Ajax_Call.postCommentNonce,
			},
			function(response) {

				$('#new_section').before(response.results);


			}, 'json');


	});




});
