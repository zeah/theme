jQuery( document ).ready(function() {

	var makediv = function(c) {
		var e = document.createElement('div');
		e.classList.add(c);
		return e;
	}

	var textInput = function(c) {
		var e = document.createElement('input');
		e.setAttribute('type', 'text');
		e.classList.add(c);

		return e;
	}

	var popup_container = makediv('em-popup');

	var popup = makediv('em-popup-inner');

	var top = makediv('em-popup-top');

	var cross = makediv('em-popup-kryss');
	cross.addEventListener('click', function() {
		jQuery(popup_container).fadeOut(1500, function() {
			jQuery(this).remove();
		});
	});

	top.appendChild(cross);
	popup_container.appendChild(top);

	var inputs = makediv('em-popup-inputs');

	var name = makediv('em-popup-name');
	name.appendChild(document.createTextNode(emmail.data['name_text']));
	var name_input = textInput('em-popup-input');
	name.appendChild(name_input);

	inputs.appendChild(name);

	var email = makediv('em-popup-email');
	email.appendChild(document.createTextNode(emmail.data['email_text']));

	var email_input = textInput('em-popup-input');
	email.appendChild(email_input);

	inputs.appendChild(email);

	var go = makediv('em-popup-go');
	var gobutton = document.createElement('button');
	gobutton.setAttribute('type', 'button');
	gobutton.classList.add('em-popup-gobutton');
	gobutton.appendChild(document.createTextNode(emmail.data['gobutton_text']))


	var goButtonCallback = function() {

		function validateEmail(mail) {
			if (/^\w+([\+\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
				return true;

			console.log('false');
			return false;
		}

		function validateName(name) {
			if (name == '')
				return true;

			if (/^[A-åa-å\s]+$/.test(name))
				return true;

			console.log('fake name');
			return false;
		}

		if (! validateEmail(email_input.value)) {
			jQuery(email_input).animate({
				'borderWidth': 4
			}, 300, function() {
				jQuery(email_input).animate({
					'borderWidth': 0
				}, 300)
			});
			return;
		}

		if (! validateName(name_input.value)) {
			jQuery(name_input).animate({
				'borderWidth': 4
			}, 300, function() {
				jQuery(name_input).animate({
					'borderWidth': 0
				}, 300)
			});
			return;
		}

		sendAjax(email_input.value, 'leave_popup', name_input.value);

		jQuery(popup).animate({
			height: 0,
			paddingTop: 0
		}, 500, function() {
			jQuery(popup).remove();
			jQuery(top).fadeOut(500, function() {
				jQuery(popup_container).remove();
			});
		});
	};

	gobutton.addEventListener('click', goButtonCallback);
	go.appendChild(gobutton);

	inputs.appendChild(go);

	popup.appendChild(inputs);

	var textcontainer = makediv('em-popup-text-container');
	var texttitle = makediv('em-popup-text-title');
	texttitle.appendChild(document.createTextNode(emmail.data['title']));

	textcontainer.appendChild(texttitle);

	var logocontainer = makediv('em-popup-text-logo-container');
	var logo = document.createElement('img');
	logo.classList.add('em-popup-logo-img');
	logo.setAttribute('src', emmail.data['logo']);
	logocontainer.appendChild(logo);

	textcontainer.appendChild(logocontainer);

	var infocontainer = makediv('em-popup-text-info-container');

	for (var key in emmail.data['info']) {
		var info = makediv('em-popup-text-info');
		info.appendChild(document.createTextNode(emmail.data['info'][key]));
		infocontainer.appendChild(info);
	}
	textcontainer.appendChild(infocontainer);

	popup.appendChild(textcontainer);
	popup_container.appendChild(popup);

	document.addEventListener('mousemove', emmouse);
	
	var aktivert = false;
	function emmouse(e) {
		if (!aktivert && e.clientY > 100) 
			aktivert = true;

		else if (aktivert && e.clientY < 40) {
			document.removeEventListener('mousemove', emmouse);
			document.body.appendChild(popup_container);

				jQuery(top).animate({
					opacity: 1
				}, 300, function() {
					jQuery(popup).css('opacity', '1');
					jQuery(popup).animate({
						maxHeight: '500px',
						paddingTop: '40px'
					}, 500, function() {

				});
			});
		}
	}
	// console.dir(emmail);

	function sendAjax(email, emailsrc, name) {
		jQuery.ajax({
			url : emmail.ajax_url,
			type : 'post',
			data : {
				action : 'emmail_action',
				emmail : email,
				emmailsrc: emailsrc,
				emname: name
			},
			success : function( response ) {
			}
		});
	}
});
