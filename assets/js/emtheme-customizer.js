$(() => {
(function($, api) {

	let setItalic = (weight, element) => {
		if (weight.includes('italic'))
			element.css('font-style', 'italic');
		else
			element.css('font-style', 'normal');
	}

	let setWeight = (weight, element) => {
		let w = weight.replace('italic', '');

		if (weight == 'regular' || w == '') {
			w = '';
			element.css('font-weight', 'normal'); // set to normal?
		}
		else 
			element.css('font-weight', w);

		setItalic(weight, element);

		return w;
	}



	let checkVariant = (font, weight) => {
		for (let f of gfont)
			if (font == f.family)
				for (let v of f.variants)
					if (weight == v)
						return true;
		return false;
	}


	// site identity
	api('blogname', (value) => value.bind((newval) => $('.emtheme-title').html(newval)));

	api('blogdescription', (value) => value.bind((newval) => $('.emtheme-tagline').html(newval)));

	api('emtheme_title_mobile', (value) => value.bind((newval) => $('.emtheme-mobile-title > a').html(newval)));

	api('emtheme_logo', (value) => value.bind((newval) => $('.emtheme-logo').attr('src', newval)));

	api('emtheme_logo_mobile', (value) => value.bind((newval) => $('.emtheme-logo-mobile').attr('src', newval)));


	// emtheme colors
	var activeHover = $('.current_page_item > a').css('background-color') || $('.current-menu-item > a').css('background-color');

	api('emtheme_color[emtop_bg]', (value) => value.bind((newval) => $('.emtop').css('background-color', newval)));

	api('emtheme_color[emtop_font]', (value) => value.bind((newval) => $('.emtheme-top-link').css('color', newval)));

	api('emtheme_color[emtop_bg_image]', (value) => value.bind((newval) => {
		if (!newval) 	newval = 'none';
		else 			newval = 'url("'+newval+'")';

		let opacity = api.instance('emtheme_color[emtop_bg_image_opacity]').get();

		// console.log('hi '+opacity);
		$('.emtheme-bg-op').remove();
		$('head').append('<style class="emtheme-bg-op"> .emtop-bg:after { content: ""; background: '+newval+'; opacity: '+opacity+'; top: 0; left: 0; bottom: 0; right: 0; position: absolute; z-index: 2;} </style>');
	}));

	api('emtheme_color[emtop_bg_image_opacity]', (value) => value.bind((newval) => {

		let url = api.instance('emtheme_color[emtop_bg_image]').get();
		if (!url) return;

		url = 'url("'+url+'")';


		$('.emtheme-bg-op').remove();
		$('head').append('<style class="emtheme-bg-op"> .emtop-bg:after { content: ""; background: '+url+'; opacity: '+newval+'; top: 0; left: 0; bottom: 0; right: 0; position: absolute; z-index: 2;} </style>');
	}));

	// navbar colors
	api('emtheme_color[nav_font]', (value) => value.bind((newval) => $('.page_item > a, .menu-item > a').not('.children > li > a, .sub-menu > li > a').css('color', newval)));

	api('emtheme_color[nav_bg]', (value) => value.bind((newval) => {
		$('.menu-container').css('background-color', newval)
		if (screen.width < 1024) $('.emtop').css('background-color', newval);
	}));	

	api('emtheme_color[nav_bg_hover]', (value) => value.bind((newval) => {
			$('.page_item > a, .menu-item > a').not('.children > li > a, .sub-menu > li > a, .current_page_item > a, .current-menu-item > a').hover(
				function() { $(this).css('background-color', newval); },
				function() { $(this).css('background-color', 'transparent'); }
			);
	}));

	api('emtheme_color[navsub_font]', (value) => value.bind((newval) => $('.children > li > a, .sub-menu > li > a').css('color', newval)));


	api('emtheme_color[navsub_bg]', (value) => value.bind((newval) => $('.children, .sub-menu').css('background-color', newval)));


	api('emtheme_color[navsub_bg_hover]', (value) => value.bind((newval) => {
			$('.children > li > a, .sub-menu > li > a').hover(
				function() { $(this).css('background-color', newval); },
				function() { $(this).css('background-color', 'transparent'); }
			);
		}));

	api('emtheme_color[active]', (value) => value.bind((newval) => {
		activeHover = newval;
		$('.current_page_item > a, .current-menu-item > a').css( 'background-color', newval );
	}));

	api('emtheme_color[active_hover]', (value) => value.bind((newval) => {
		console.log(newval);
		$('.current_page_item > a, .current-menu-item > a').hover(
			function() { $(this).css('background-color', newval); },
			function() { $(this).css('background-color', activeHover); }
		);
	}));


	/*
		FONTS
	*/
	api('emtheme_font[standard]', (value) => { 
		value.bind((newvalue) => {
			let weight = api.instance('emtheme_font[standard_weight]').get();
			let content = $('.content');

			content.css('font-family', newvalue);

			// if weight is not empty
			if (weight) {

				// if weight is an anctual weight
				if (checkVariant(newvalue, weight)) {
					weight = setWeight(weight, content);
					newvalue += weight ? ':'+weight.replace('italic', 'i') : '';
				} 

				// set weight to empty and css to normal (removing bold/italic)
				else 
					setWeight('regular', content);
			}

			// newvalue = newvalue.replace(/ /g, '+');

			$('#emtheme-standard-link-google').remove();
			$('head').append('<link id="emtheme-standard-link-google" href="https://fonts.googleapis.com/css?family='+newvalue.replace(/ /g, '+')+'" rel="stylesheet">');
		});
	});

	api('emtheme_font[standard_weight]', (value) => {
		value.bind((newvalue) => {
			let font = api.instance('emtheme_font[standard]').get();
			let content = $('.content');

			// dont do anything if no font is selected
			if (!font) {
				console.log('doing nothing b/c no font found');
				return;
			}

			// sets boldness and italic
			setWeight(newvalue, content);

			// creates string for google
			font += ':'+newvalue.replace('italic', 'i'); 


			$('#emtheme-content-link-google').remove();
			$('head').append('<link id="emtheme-content-link-google" href="https://fonts.googleapis.com/css?family='+font.replace(/ /g, '+')+'" rel="stylesheet">');
			// $('.content').css('font-family', font);
		});
	});

	api('emtheme_font[standard_size]', (value) => {
		value.bind((newvalue) => {
			$('.content').css('font-size', newvalue+'rem');
		});
	})

	api('emtheme_font[standard_lineheight]', (value) => {
		value.bind((newvalue) => {
			$('.content > p').css('line-height', newvalue);
		});
	})


	/* TITLE */
	api('emtheme_font[title]', (value) => { value.bind((newvalue) => {
			let weight = api.instance('emtheme_font[title_weight]').get();
			let title = $('.emtheme-title');

			title.css('font-family', newvalue);

			// if weight is not empty
			if (weight) {

				// if weight is an anctual weight
				if (checkVariant(newvalue, weight)) {
					weight = setWeight(weight, title);
					newvalue += weight ? ':'+weight.replace('italic', 'i') : '';
				} 

				// set weight to empty and css to normal (removing bold/italic)
				else 
					setWeight('regular', title);
			}

			// newvalue = newvalue.replace(/ /g, '+');

			$('#emtheme-title-link-google').remove();
			$('head').append('<link id="emtheme-title-link-google" href="https://fonts.googleapis.com/css?family='+newvalue.replace(/ /g, '+')+'" rel="stylesheet">');
		});
	});

	api('emtheme_font[title_weight]', (value) => {
		value.bind((newvalue) => {
			let font = api.instance('emtheme_font[title]').get();
			let content = $('.emtheme-title');

			// dont do anything if no font is selected
			if (!font) {
				console.log('doing nothing b/c no font found');
				return;
			}

			// sets boldness and italic
			setWeight(newvalue, content);

			// creates string for google
			font += ':'+newvalue.replace('italic', 'i'); 


			$('#emtheme-title-link-google').remove();
			$('head').append('<link id="emtheme-title-link-google" href="https://fonts.googleapis.com/css?family='+font.replace(/ /g, '+')+'" rel="stylesheet">');
			// $('.emtheme-title').css('font-family', font);
		});
	});

	api('emtheme_font[title_size]', (value) => {
		value.bind((newvalue) => {
			$('.emtheme-title').css('font-size', newvalue+'rem');
		});
	});

	/* NAV FONTS */
	api('emtheme_font[nav]', (value) => {
		value.bind((newvalue) => {
			let weight = api.instance('emtheme_font[nav_weight]').get();
			let nav = $('.menu-container');

			nav.css('font-family', newvalue);

			// if weight is not empty
			if (weight) {

				// if weight is an anctual weight
				if (checkVariant(newvalue, weight)) {
					weight = setWeight(weight, nav);
					newvalue += weight ? ':'+weight.replace('italic', 'i') : '';
				} 

				// set weight to empty and css to normal (removing bold/italic)
				else 
					setWeight('regular', nav);
			}

			// newvalue = newvalue.replace(/ /g, '+');

			$('#emtheme-nav-link-google').remove();
			$('head').append('<link id="emtheme-nav-link-google" href="https://fonts.googleapis.com/css?family='+newvalue.replace(/ /g, '+')+'" rel="stylesheet">');
		});
	});

	api('emtheme_font[nav_weight]', (value) => {
		value.bind((newvalue) => {
			let font = api.instance('emtheme_font[nav]').get();
			let nav = $('.menu-container');

			// dont do anything if no font is selected
			if (!font) {
				console.log('doing nothing b/c no font found');
				return;
			}

			// sets boldness and italic
			setWeight(newvalue, nav);

			// creates string for google
			font += ':'+newvalue.replace('italic', 'i'); 


			$('#emtheme-title-link-google').remove();
			$('head').append('<link id="emtheme-title-link-google" href="https://fonts.googleapis.com/css?family='+font.replace(/ /g, '+')+'" rel="stylesheet">');
			// $('.emtheme-title').css('font-family', font);
		});


	});

	api('emtheme_font[nav_size]', (value) => {
		value.bind((newvalue) => {
			$('.menu-container').css('font-size', newvalue+'rem');
		});
	});


	/*
		FOOTER
	*/


	let fshow = (v) => {
		if (v) {
			$('.em-footer').show();
			window.scrollTo(0,document.body.scrollHeight);
		}
		else {
			let soc = api('emtheme_footer[social_active]').get();
			let con = api('emtheme_footer[contact_active]').get();
			let abu = api('emtheme_footer[aboutus_active]').get();

			if (!soc && !con && !abu)
				$('.em-footer').hide();
		}
	}

	// check on init whether to show footer or not
	fshow(false);

	if (!api('emtheme_footer[social_active]').get())
		$('.em-socialmedia-container').hide();

	if (!api('emtheme_footer[contact_active]').get())
		$('.em-contact-container').hide();

	if (!api('emtheme_footer[aboutus_active]').get())
		$('.em-aboutus-container').hide();

	api('emtheme_footer[social_active]', (value) => {
		value.bind((newvalue) => {
			fshow(newvalue);

			if (newvalue) 
				$('.em-socialmedia-container').show();
			else
				$('.em-socialmedia-container').hide();
			// console.log(newvalue);
		});
	});
	
	api('emtheme_footer[contact_active]', (value) => {
		value.bind((newvalue) => {
			fshow(newvalue);

			if (newvalue) 
				$('.em-contact-container').show();
			else
				$('.em-contact-container').hide();
		});
	});
	
	api('emtheme_footer[aboutus_active]', (value) => {
		value.bind((newvalue) => {
			fshow(newvalue);

			if (newvalue) 
				$('.em-aboutus-container').show();
			else
				$('.em-aboutus-container').hide();
		});
	});
	api('emtheme_footer[aboutus]', (value) => {
		value.bind((newvalue) => {
			$('.em-aboutus-container').html(newvalue.replace(/\[p\]/, '<p>'));
		});
	});


	let socList = ['twitter', 'facebook', 'google', 'youtube'];
	let colist = ['email', 'avdeling', 'selskap', 'poststed', 'postnr', 'veiadr', 'land'];
	let sclist = socList.concat(colist);

	for (let v of sclist) {
		if (api('emtheme_footer['+v+']').get() == '')
			$('.em-footer-'+v).hide(); 
	
		api('emtheme_footer['+v+']', (value) => {
			value.bind((newvalue) => {
				console.log(newvalue);

				if (socList.includes(v))
					$('.em-footer-'+v+' > a').attr('href', newvalue);
				else
					$('.em-footer-'+v).text(newvalue);

				if (newvalue.length == 0)
					$('.em-footer-'+v).hide();
				else if (newvalue.length == 1)
					$('.em-footer-'+v).show();
			});
		});
	}


})(jQuery, wp.customize);
});
