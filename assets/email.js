// (() => {
	// console.log('heya');

	jQuery( document ).ready(function() {
	// var post_id = jQuery(this).data('id');
		jQuery.ajax({
			url : emmail.ajax_url,
			type : 'post',
			data : {
				action : 'emmail_action',
				emmail : 'testdser'
			},
			success : function( response ) {
				// console.log(response);
			}
		});
	});


// })();