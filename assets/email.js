jQuery( document ).ready(function() {

	// document.onmousemove = mouse_callback;

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
	// popup.classList.add('em-popup-inner-show');

	var top = makediv('em-popup-top');
	// var topleft = makediv('em-popup-top-left');
	// var topright = makediv('em-popup-top-right');

	var cross = makediv('em-popup-kryss');
	cross.addEventListener('click', function() {
		jQuery(popup_container).fadeOut(1500, function() {
			jQuery(this).remove();
		});
		// document.body.removeChild(popup_container);
	});

	// top.appendChild(topleft);
	top.appendChild(cross);
	// top.appendChild(topright);
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

	gobutton.addEventListener('click', function() {
		console.log('hi');
		jQuery(popup).animate({
			height: 0,
			paddingTop: 0
		}, 500, function() {
			jQuery(popup).remove();
			jQuery(top).fadeOut(500, function() {
				jQuery(popup_container).remove();
			});
		});
		// popup.classList.add('em-popup-inner-show');
	});

	go.appendChild(gobutton);

	inputs.appendChild(go);

	popup.appendChild(inputs);


	var textcontainer = makediv('em-popup-text-container');
	var texttitle = makediv('em-popup-text-title');
	texttitle.appendChild(document.createTextNode(emmail.data['title']));

	textcontainer.appendChild(texttitle);
	// console.log(Object.keys(emmail.data['info']).length);

	// for (var key in emmail.data['info']) {

	// }

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
	// name input email input button

	// title

	// text text text


	// var popup = document.createElement('div');


	// popup.classList.add('em-popup');


	// // popup.appendChild(document.createTextNode('hi hello'));

	// var tekst = document.createElement('div');
	// tekst.classList.add('em-popup-tekst');
	// tekst.appendChild(document.createTextNode(emmail.tekst));

	// popup.appendChild(tekst);

	// var email = document.createElement('input');
	// email.setAttribute('type', 'text');
	// email.setAttribute('placeholder', 'din epost her');
	// email.classList.add('em-popup-email');

	// popup.appendChild(email);

	// var kryss = document.createElement('div');
	// kryss.classList.add('em-popup-kryss');
	// kryss.addEventListener('click', function() {
	// 	document.body.removeChild(popup);
	// });

	// popup.appendChild(kryss);

	// var bcont = document.createElement('div');
	// bcont.classList.add('em-popup-bcont');

	// popup.appendChild(bcont);

	// // var nei = document.createElement('button');
	// // nei.setAttribute('type', 'button');
	// // nei.classList.add('em-popup-nei');
	// // nei.classList.add('em-popup-button');
	// // nei.appendChild(document.createTextNode('Nei takk'));

	// // bcont.appendChild(nei);

	// var ja = document.createElement('button');
	// ja.setAttribute('type', 'button');
	// ja.classList.add('em-popup-ja');
	// ja.classList.add('em-popup-button');
	// ja.appendChild(document.createTextNode('FÅ TILBUD'));

	// bcont.appendChild(ja);



	document.addEventListener('mousemove', emmouse);
	var aktivert = false;
	function emmouse(e) {

		if (!aktivert && e.clientY > 100) 
			aktivert = true;

		else if (aktivert && e.clientY < 40) {
			document.removeEventListener('mousemove', emmouse);
			document.body.appendChild(popup_container);

			// jquery animation here instead of css3 animation
			// jQuery(top).fadeIn('fast', function() {
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


	console.dir(emmail);

	function sendAjax() {
		jQuery.ajax({
			url : emmail.ajax_url,
			type : 'post',
			data : {
				action : 'emmail_action',
				emmail : 'testdser'
			},
			success : function( response ) {
			}
		});
	}
});
