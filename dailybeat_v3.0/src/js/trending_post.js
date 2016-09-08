/*	jQuery(document).ready(function($){
		var _custom_media = true,
		_orig_send_attachment = wp.media.editor.send.attachment;

		$('.upload_image_button').click(function(e) {
			var send_attachment_bkp = wp.media.editor.send.attachment;
			var button = $(this);
								var target = $(this).data('target');

			_custom_media = true;
			wp.media.editor.send.attachment = function(props, attachment){
				if ( _custom_media ) {
					console.log(target);
					$('#' + target).val(attachment.url);
				} else {
					return _orig_send_attachment.apply( this, [props, attachment] );
				};
			}

			wp.media.editor.open(button);
			return false;
		});

		$('.add_media').on('click', function(){
			_custom_media = false;
		});
	});
*/