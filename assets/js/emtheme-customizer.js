(function($) {

	// wp.customize.state.bind( function( newDevice ) {
	// });
	// wp.customize.previewedDevice.bind(function(new_device) {
 //    	console.log('Reponsive view has been changed to: ' + new_device);
	// });

	// wp.customize('blogname', 'blogdescription', function(blog, desc) {
	// 	blog.bind(function(value){
	// 		desc.set(value);
	// 	});
	// });

	// wp.customize.control.add(
	// 			new wp.customize.Control('emtheme_font_test_c', {
	// 				setting: 'emtheme_font_test',
	// 				section: 'emtheme_font_css',
	// 				type: 'checkbox',
	// 				label: 'tett'
	// 			})
	// 		);

	// site identity
	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {

			// wp.customize.setting('blogdescription').value(newval);
			// console.log(wp.customize('blogdescription').set('test'));

			$( '.emtheme-title' ).html( newval );
		} );
	} );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '.emtheme-tagline' ).html( newval );
		} );
	} );

	wp.customize( 'emtheme_title_mobile', function( value ) {
		value.bind( function( newval ) {
			$( '.emtheme-mobile-title > a' ).html( newval );
		} );
	} );

	wp.customize( 'emtheme_logo', function( value ) {
		value.bind( function( newval ) {
			$( '.emtheme-logo' ).attr('src', newval );
		} );
	} );

	wp.customize( 'emtheme_logo_mobile', function( value ) {
		value.bind( function( newval ) {
			$( '.emtheme-logo-mobile' ).attr('src', newval );
		} );
	} );


	// emtheme colors
	var activeHover = $('.em-nav-item').css('background-color');;

	wp.customize( 'emtheme_css_emtop', function( value ) {
		value.bind( function( newval ) {
			$('.emtop').css( 'background-color', newval );
		});
	});


	wp.customize( 'emtheme_css_emtop_font', function( value ) {
		value.bind( function( newval ) {
			$('.emtheme-top-link').css( 'color', newval );
		});
	});

	wp.customize( 'emtheme_css_navfont', function( value ) {
		value.bind( function( newval ) {
			$('.em-nav-lenke').css( 'color', newval );
		});
	});
	
	wp.customize( 'emtheme_css_navbg', function( value ) {
		value.bind( function( newval ) {
			$('.nav-container').css( 'background-color', newval );
			if (screen.width < 961)
				$('.emtop').css('background-color', newval);
		});
	});

	wp.customize( 'emtheme_css_navbg_hover', function( value ) {
		value.bind( function( newval ) {
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

	wp.customize( 'emtheme_css_navsub_font', function( value ) {
		value.bind( function( newval ) {
			$('.em-nav-sublenke').css( 'color', newval );
		});
	});

	wp.customize( 'emtheme_css_navsub_bg', function( value ) {
		value.bind( function( newval ) {
			$('.em-nav-sub-container').css( 'background-color', newval );
			$('.em-nav-subitem').css( 'background-color', newval );
		});
	});

	wp.customize( 'emtheme_css_navsub_bg_hover', function( value ) {
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

	wp.customize( 'emtheme_css_active', function( value ) {
		value.bind( function( newval ) {
			activeHover = newval;
			$('.em-nav-current').css( 'background-color', newval );
		});
	});

	wp.customize( 'emtheme_css_active_hover', function( value ) {
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


	wp.customize('emtheme_font_standard', function(std) {
		std.bind(function(newvalue) {
			// console.log('hi '+newvalue);
			$('head').append('<link href="https://fonts.googleapis.com/css?family='+newvalue+'" rel="stylesheet">');
			$('html').css('font-family', newvalue);

		});
	});
	// wp.customize( 'emtheme_footer_', function( value ) {
	// 	value.bind( function( newval ) {
	// 		$('').html(newval);
	// 	});
	// });








})(jQuery);