jQuery(document).ready(function($) {

	var mediaUploader;

	$('#em-popup-logo-button').on('click', function(e) {
		e.preventDefault();


		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose logo',
			button: {
				text: 'Choose image'
			},
			multiple: false
		});

		mediaUploader.on('select', function() {
			attachment = mediaUploader.state().get('selection').first().toJSON();
			$('#em-popup-logo').val(attachment.url);

			$('#em-popup-logo-image').attr('src', attachment.url);

			return;
		});

		if (mediaUploader) {
			mediaUploader.open();
			return;
		}


	});

	$('#em-popup-remove-button').on('click', function() {
		$('#em-popup-logo').val('');
		$('#em-popup-logo-image').attr('src', '');
	});

});