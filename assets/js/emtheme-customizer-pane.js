((api, $) => {
	wp.customize.bind('ready', () => {
		// console.log('hiyua');
		let addSC = (setting, section, choices, label, priority) => {
			api.control.add(
				new api.Control(setting+'_c', {
					setting: setting,
					section: section,
					type: 'select',
					priority: priority,
					label: label,
					choices: choices
				})
			);
		}

		let getVariants = (font) => {
			if (!font)
				return;

			let types = [];
			for (let f of gfont)
				if (font == f.family) {
					types = f.variants;
					break;
				}

			let object = {};

			for (let t of types)
				object[t] = t;

			return object;
		}

		let mp = 'emtheme_';
		let mf = '_c';

		let map = new Map([
				['.emtop-font-color', mp+'color[emtop_font]'+mf],
				['.emtop-bg-color', mp+'color[emtop_bg]'+mf],

				['.navbar-mfont-color', mp+'color[nav_font]'+mf],
				['.navbar-mbg-color', mp+'color[nav_bg]'+mf],
				['.navbar-mbgh-color', mp+'color[nav_bg_hover]'+mf],

				['.navbar-sfont-color', mp+'color[navsub_font]'+mf],
				['.navbar-sbg-color', mp+'color[navsub_bg]'+mf],
				['.navbar-sbgh-color', mp+'color[navsub_bg_hover]'+mf],

				['.navbar-active-color', mp+'color[active]'+mf],
				['.navbar-activeh-color', mp+'color[active_hover]'+mf],

				['.emtheme-font-d-fam', mp+'font[standard]'+mf],
				['.emtheme-font-d-weight', mp+'font[standard_weight]'+mf],
				['.emtheme-font-d-size', mp+'font[standard_size]'+mf],
				['.emtheme-font-d-lheight', mp+'font[standard_lineheight]'+mf],

				['.emtheme-font-t-fam', mp+'font[title]'+mf],
				['.emtheme-font-t-weight', mp+'font[title_weight]'+mf],
				['.emtheme-font-t-size', mp+'font[title_size]'+mf],

				['.emtheme-font-n-fam', mp+'font[nav]'+mf],
				['.emtheme-font-n-weight', mp+'font[nav_weight]'+mf],
				['.emtheme-font-n-size', mp+'font[nav_size]'+mf],
			]);

		for (let [key, value] of map)
			$(key).click(() => api.control(value).focus());
		// $('.emtop-font-color').click(() => $('#customize-control-emtheme_color-emtop_font_c button').focus());

		/*
			Emtheme CSS
			FONTS
		*/
		let fonts = {};
		for (let font of gfont)
			fonts[font.family] = font.family;

		// default font: adding google fonts
		addSC('emtheme_font[standard]', 'emtheme_css_font', fonts, 'Google Fonts', 101);
		// default font: font weight
		addSC('emtheme_font[standard_weight]', 'emtheme_css_font', getVariants(api.instance('emtheme_font[standard]').get()), 'Weight', 102);

		// default font: google font event
		api('emtheme_font[standard]', function(std) {
			std.bind(function(value) {
				api.control.remove('emtheme_font[standard_weight]_c');
				$('#customize-control-emtheme_font-standard_weight_c').remove(); // should be something better than this?

				// remaking font weight
				addSC('emtheme_font[standard_weight]', 'emtheme_css_font', getVariants(api.instance('emtheme_font[standard]').get()), 'Weight', 102);
			});
		});


		// title font: adding google fonts
		addSC('emtheme_font[title]', 'emtheme_css_font', fonts, 'Google Fonts', 111);
		addSC('emtheme_font[title_weight]', 'emtheme_css_font', getVariants(api.instance('emtheme_font[title]').get()), 'Weight', 112);

		api('emtheme_font[title]', (value) => {
			value.bind((nv) => {
				api.control.remove('emtheme_font[title_weight]_c');
				$('#customize-control-emtheme_font-title_weight_c').remove(); // should be something better than this?
				
				addSC('emtheme_font[title_weight]', 'emtheme_css_font', getVariants(api.instance('emtheme_font[title]').get()), 'Weight', 112);
			});
		});

		// navbar font: adding google fonts
		addSC('emtheme_font[nav]', 'emtheme_css_font', fonts, 'Google Fonts', 121);
		addSC('emtheme_font[nav_weight]', 'emtheme_css_font', getVariants(api.instance('emtheme_font[nav]').get()), 'Weight', 122);

		api('emtheme_font[nav]', (value) => {
			value.bind((nv) => {
				api.control.remove('emtheme_font[nav_weight]_c');
				$('#customize-control-emtheme_font-nav_weight_c').remove(); // should be something better than this?
	
				addSC('emtheme_font[nav_weight]', 'emtheme_css_font', getVariants(api.instance('emtheme_font[nav]').get()), 'Weight', 122);
			});
		});

	});
})(wp.customize, jQuery);