$(() => {
(function($, api) {
	// wp.customize.previewer.bind('ready', () => {
	// 	console.log('hiya');
	// });
	// 	console.log('hiya');
	// $().ready(() => {
	// 	console.log(api('emtheme_footer[social_active]').get());
	// });

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

	// api.state.bind( function( newDevice ) {
	// });
	// api.previewedDevice.bind(function(new_device) {
 //    	console.log('Reponsive view has been changed to: ' + new_device);
	// });

	// api('blogname', 'blogdescription', function(blog, desc) {
	// 	blog.bind(function(value){
	// 		desc.set(value);
	// 	});
	// });

	// api.control.add(
	// 			new api.Control('emtheme_font_test_c', {
	// 				setting: 'emtheme_font_test',
	// 				section: 'emtheme_font_css',
	// 				type: 'checkbox',
	// 				label: 'tett'
	// 			})
	// 		);

	// site identity
	api( 'blogname', function( value ) {
		value.bind( function( newval ) {

			// api.setting('blogdescription').value(newval);
			// console.log(api('blogdescription').set('test'));

			$( '.emtheme-title' ).html( newval );
		} );
	} );

	api( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '.emtheme-tagline' ).html( newval );
		} );
	} );

	api( 'emtheme_title_mobile', function( value ) {
		value.bind( function( newval ) {
			$( '.emtheme-mobile-title > a' ).html( newval );
		} );
	} );

	api( 'emtheme_logo', function( value ) {
		value.bind( function( newval ) {
			$( '.emtheme-logo' ).attr('src', newval );
		} );
	} );

	api( 'emtheme_logo_mobile', function( value ) {
		value.bind( function( newval ) {
			$( '.emtheme-logo-mobile' ).attr('src', newval );
		} );
	} );


	// emtheme colors
	var activeHover = $('.em-nav-item').css('background-color');;

	api( 'emtheme_color[emtop_bg]', function( value ) {
		value.bind( function( newval ) {
			$('.emtop').css( 'background-color', newval );
		});
	});


	api( 'emtheme_color[emtop_font]', function( value ) {
		value.bind( function( newval ) {
			$('.emtheme-top-link').css( 'color', newval );
		});
	});

	api( 'emtheme_color[nav_font]', function( value ) {
		value.bind( function( newval ) {
			$('.em-nav-lenke').css( 'color', newval );
		});
	});
	
	api( 'emtheme_color[nav_bg]', function( value ) {
		value.bind( function( newval ) {
			$('.nav-container').css( 'background-color', newval );
			if (screen.width < 961)
				$('.emtop').css('background-color', newval);
		});
	});

	api( 'emtheme_color[nav_bg_hover]', function( value ) {
		value.bind( function( newval ) {
			// special hover case
			$('.em-nav-item').hover(
				function() {
  					$(this).css('background-color', newval);
				},
				function() {
					$(this).css('background-color', 'transparent');
				}
			);
		});
	});

	api( 'emtheme_color[navsub_font]', function( value ) {
		value.bind( function( newval ) {
			$('.em-nav-sublenke').css( 'color', newval );
		});
	});

	api( 'emtheme_color[navsub_bg]', function( value ) {
		value.bind( function( newval ) {
			console.log('test');
			$('.em-nav-sub-container').css( 'background-color', newval );
			$('.em-nav-subitem').css( 'background-color', newval );
		});
	});

	api( 'emtheme_color[navsub_bg_hover]', function( value ) {
		value.bind( function( newval ) {
			$('.em-nav-subitem').hover(
				function() {
  					$(this).css('background-color', newval);
				},
				function() {
					$(this).css('background-color', 'transparent');
				}
			);
		});
	});

	api( 'emtheme_color[active]', function( value ) {
		value.bind( function( newval ) {
			activeHover = newval;
			$('.em-nav-current').css( 'background-color', newval );
		});
	});

	api( 'emtheme_color[active_hover]', function( value ) {
		value.bind( function( newval ) {
			$('.em-nav-current').hover(
				function() {
  					$(this).css('background-color', newval);
				},
				function() {
					$(this).css('background-color', activeHover);
				}
			);
		});
	});


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


	api('emtheme_font[nav]', (value) => {
		value.bind((newvalue) => {
			let weight = api.instance('emtheme_font[nav_weight]').get();
			let nav = $('.nav');

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
			let nav = $('.nav');

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
			$('.nav').css('font-size', newvalue+'rem');
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

	api('emtheme_footer[social_active]', (value) => {
		value.bind((newvalue) => {
			fshow(newvalue);
			// if (newvalue === true)
			// 	$('.em-footer').show();
			// else
			// 	$('.em-footer').hide();
			// console.log(newvalue);
		});
	});
	
	api('emtheme_footer[contact_active]', (value) => {
		value.bind((newvalue) => {
			fshow(newvalue);
			// console.log(newvalue);
			// if (newvalue === true)
			// 	$('.em-footer').show();
			// else
			// 	$('.em-footer').hide();
		});
	});
	
	api('emtheme_footer[aboutus_active]', (value) => {
		value.bind((newvalue) => {
			fshow(newvalue);
			// console.log(newvalue);
			// if (newvalue === true)
			// 	$('.em-footer').show();
			// else
			// 	$('.em-footer').hide();
		});
	});
	// wp.customize.bind('ready', () => {
	// console.log(api.instance('emtheme_footer[twitter]').get());
		// console.log('hi '+api.control('emtheme_footer[social_active]_c').get());
	// });
	// $('.em-footer').hide();

})(jQuery, wp.customize);
});
// }))(jQuery, wp.customize);
