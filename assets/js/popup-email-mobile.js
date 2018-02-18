jQuery(document).ready(function($) {

	let makediv = (...c) => {
		let e = document.createElement('div');

		for (cl of c)
			e.classList.add(cl);

		return e;
	}

	let textInput = (...c) => {
		let e = document.createElement('input');
		e.setAttribute('type', 'text');

		for (cl of c)
			e.classList.add(cl);

		return e;
	}

	let container = makediv('em-m-popup-container');
	
	let title = makediv('em-m-popup-title');
	title.appendChild(document.createTextNode(emmail.data['title_mobile']));

	let info = makediv('em-m-popup-info');
	info.appendChild(document.createTextNode(emmail.data['info_mobile']));

	container.appendChild(title);
	container.appendChild(info);
	// let info = makediv('em-m-popup-info');
	
	let inputContainer = makediv('em-m-popup-input-container');

	let nameContainer = makediv('em-m-popup-input-name');
	nameContainer.appendChild(document.createTextNode(emmail.data['name_text']));

	let nameInput = textInput('em-m-popup-input');



	let emailContainer = makediv('em-m-popup-input-email');
	emailContainer.appendChild(document.createTextNode(emmail.data['email_text']));
	let emailInput = textInput('em-m-popup-input');


	inputContainer.appendChild(nameContainer);
	inputContainer.appendChild(nameInput);

	inputContainer.appendChild(emailContainer);
	inputContainer.appendChild(emailInput);

	container.appendChild(inputContainer);

	let gobutton = document.createElement('button');
	gobutton.setAttribute('type', 'button');
	gobutton.classList.add('em-m-popup-button');

	jQuery(gobutton).on('click', function() {

		function validateEmail(mail) {
			if (/^\w+([\+\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
				return true;

			return false;
		}

		function validateName(name) {
			if (name == '')
				return true;

			if (/^[A-åa-å\s]+$/.test(name))
				return true;

			return false;
		}

		if (! validateEmail(emailInput.value)) {
			jQuery(emailInput).animate({
				'borderWidth': 4
			}, 300, function() {
				jQuery(emailInput).animate({
					'borderWidth': 0
				}, 300)
			});
			return;
		}

		if (! validateName(nameInput.value)) {
			jQuery(nameInput).animate({
				'borderWidth': 4
			}, 300, function() {
				jQuery(nameInput).animate({
					'borderWidth': 0
				}, 300)
			});
			return;
		}
		jQuery(gobutton).off('click');

		jQuery.ajax({
			url : emmail.ajax_url,
			type : 'post',
			data : {
				action : 'emmail_action',
				emmail : emailInput.value,
				emmailsrc: 'mobile_bottom',
				emname: nameInput.value,
				security: emmail['nonce']
			},
			success : function( response ) {
				// console.dir(response);
			}
		});

		jQuery(container).animate({
			maxHeight: 0
		}, 500, function() {
			jQuery(container).remove();
		})
	});

	gobutton.appendChild(document.createTextNode(emmail.data['gobutton_text_mobile']));

	container.appendChild(gobutton);

	document.body.appendChild(container);

	function activateAni() {
		jQuery(title).one('click', function() {
			jQuery(container).animate({
				maxHeight: 300
			}, 500, function() {
				jQuery(title).one('click', function() {
					jQuery(container).animate({
						maxHeight: 40
					}, 500, function() {
						activateAni();
					});
				});
			});
		});
	}
	activateAni();

	console.dir(emmail);

});