(function ($) {
	wp.customize.bind('ready', function(){

	wp.customize.control.add(
		new wp.customize.Control('emtheme_logo_c', {
			type: 'image',
			label: 'Upload logo',
			section: 'title_tagline',
			settings: 'emtheme_logo'
		})
	);

	//	create control from added data in gfont (gfont[current] = get_option('emtheme_font_weight'))

	let fonts = {};

	for (let font of gfont)
		fonts[font.family] = font.family;

	wp.customize.control.add(
		new wp.customize.Control('emtheme_font_standard_c', {
			setting: 'emtheme_font_standard',
			section: 'emtheme_css_font',
			type: 'select',
			priority: 99,
			label: 'Google Fonts',
			choices: fonts
		})
	);


	let sfont = wp.customize.instance('emtheme_font_standard').get();
	let types = '';
	for (let font of gfont)
		if (sfont == font.family) {
			types = font.variants;
			break;
		}

	let variants = {};
	if (types != '') 
		for (let t of types)
			variants[t] = t


	wp.customize.control.add(
		new wp.customize.Control('emtheme_font_test_c', {
			setting: 'emtheme_font_test',
			section: 'emtheme_css_font',
			type: 'select',
			priority: 100,
			label: 'Weight',
			choices: variants
		})
	);

	wp.customize('emtheme_font_standard', function(std) {
		std.bind(function(value) {

			// $('head').append('<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">');
			// $('html').css('font-family', '"Roboto", sans-serif');

			wp.customize.control.remove('emtheme_font_test_c');
			$('#customize-control-emtheme_font_test_c').remove();

			let sfont = std.get();
			let types = '';
			for (let font of gfont)
				if (sfont == font.family) {
					types = font.variants;
					break;
				}

			let variants = {};
			if (types != '') 
				for (let t of types)
					variants[t] = t


			wp.customize.control.add(
				new wp.customize.Control('emtheme_font_test_c', {
					setting: 'emtheme_font_test',
					section: 'emtheme_css_font',
					type: 'select',
					priority: 100,
					label: 'Weight',
					choices: variants
				})
			);



		});
	});

	// });

	// wp.customize('emtheme_font_standard', function(std) {
	// 	std.bind(function(value) {
	// 		// console.log(test.get('choices'));
	// 		// test.choices = {test: ['test']};
	// 		// console.log('hi'+std.get());
	// 		// std.set(['hi', 'ya', 'no']);

	// 		wp.customize.control.remove('emtheme_font_test_c');
	// 		$('#customize-control-emtheme_font_test_c').remove();
	// 		// if (wp.customize.control.instance('emtheme_font_test_c'))
	// 		// let types = gfont[std.get()].variants;
	// 		console.log(types);


	// 		let obj = {};
	// 		for (let val in types)
	// 			obj[types[val]] = types[val];

	// 		console.dir(obj);

	// 		// 	console.log('nay');
	// 		// wp.customize.control.add(
	// 		// 			new wp.customize.Control('emtheme_font_test_c', {
	// 		// 				setting: 'emtheme_font_test',
	// 		// 				section: 'emtheme_css_font',
	// 		// 				type: 'select',
	// 		// 				priority: 89,
	// 		// 				label: 'tett',
	// 		// 				choices: obj
	// 		// 			})
	// 		// 		);
	// 		});
			// wp.customize.control.add(
			// 	new wp.customize.Control('emtheme_font_test_c', {
			// 		setting: 'emtheme_font_test',
			// 		section: 'emtheme_css_font',
			// 		type: 'select',
			// 		label: 'tett',
			// 		priority: 100,
			// 		choices: ['test', 'test2']
			// 	})
			// );

			// if (wp.customize.control.instance('emtheme_font_test_c'))
			// 	console.log('yay');
			// else
			// 	console.log('nay');


			// wp.customize.control.remove('emtheme_font_test_c');
			// if (wp.customize.control.instance('emtheme_font_test_c'))
			// 	console.log('yay');
			// else
			// 	console.log('nay');
			// wp.customize('emtheme_font_test').set('tester');
			// wp.customize.control.instance('emtheme_font_test_c').setting.set('djfdkfhk');
			// console.log(wp.customize.control.instance('emtheme_font_test_c').params.choices);
			// wp.customize.control.params.choices = ['jdfds', 'dfdkhf'];
			// wp.customize.control.instance('emtheme_font_test_c').params.choices = ['ja'];
			// console.log(wp.customize.control.instance('emtheme_font_test_c'));

			// wp.customize.control.add(
			// 	new wp.customize.Control('emtheme_font_test_c', {
			// 		setting: 'emtheme_font_test',
			// 		section: 'emtheme_font_css',
			// 		type: 'checkbox',
			// 		label: 'tett'
			// 		// choices: []
			// 	})
			// );
		
	// 	});
	});
})(jQuery);