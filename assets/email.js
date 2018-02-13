jQuery( document ).ready(function() {

	// document.onmousemove = mouse_callback;

	var aktivert = false;
	var popup = document.createElement('div');
	popup.classList.add('em-popup');
	// popup.appendChild(document.createTextNode('hi hello'));

	var tekst = document.createElement('div');
	tekst.classList.add('em-popup-tekst');
	tekst.appendChild(document.createTextNode(emmail.tekst));

	popup.appendChild(tekst);

	var email = document.createElement('input');
	email.setAttribute('type', 'text');
	email.setAttribute('placeholder', 'din epost her');
	email.classList.add('em-popup-email');

	popup.appendChild(email);

	var kryss = document.createElement('div');
	kryss.classList.add('em-popup-kryss');
	kryss.addEventListener('click', function() {
		document.body.removeChild(popup);
	});

	popup.appendChild(kryss);

	var bcont = document.createElement('div');
	bcont.classList.add('em-popup-bcont');

	popup.appendChild(bcont);

	// var nei = document.createElement('button');
	// nei.setAttribute('type', 'button');
	// nei.classList.add('em-popup-nei');
	// nei.classList.add('em-popup-button');
	// nei.appendChild(document.createTextNode('Nei takk'));

	// bcont.appendChild(nei);

	var ja = document.createElement('button');
	ja.setAttribute('type', 'button');
	ja.classList.add('em-popup-ja');
	ja.classList.add('em-popup-button');
	ja.appendChild(document.createTextNode('FÅ TILBUD'));

	bcont.appendChild(ja);



	document.addEventListener('mousemove', emmouse);

	function emmouse(e) {

		if (!aktivert && e.clientY > 100) 
			aktivert = true;

		else if (aktivert && e.clientY < 20) {
			document.body.appendChild(popup);
			document.removeEventListener('mousemove', emmouse);
		}
	}

	// function mouse_callback(e) {
		console.log('bleh');
	// }

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
