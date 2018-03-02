<?php 


final class Emtheme_Customizer {
	private static $instance = null;

	public static function get_instance($active = true) {

		if (self::$instance === null)
			self::$instance = new self();

		return self::$instance;
	}

	private function __construct($active = true) {
		if (! $active)
			return;

		$this->wp_hooks();
	}


	private function wp_hooks() {
		add_action('customize_register', array($this, 'site_identity'));
		add_action('customize_register', array($this, 'footer_info'));
	}

	public function site_identity($wp_customize) {

		// add a setting for the site logo
		$wp_customize->add_setting('emtheme_logo', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_setting('emtheme_logo_mobile', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_setting('emtheme_title_mobile', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));

		// desktop logo
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'emtheme_logo',
			array(
				'label' => 'Upload Logo',
				'section' => 'title_tagline',
				'settings' => 'emtheme_logo',
		) ) );

		// mobile logo
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'emtheme_logo_mobile',
			array(
				'label' => 'Upload Mobile Logo',
				'section' => 'title_tagline',
				'settings' => 'emtheme_logo_mobile',
		) ) );

		// mobile site title
		$wp_customize->add_control('emtheme_title_mobile', 
			array(
				'label' => 'Mobile Title',
				'section' => 'title_tagline',
				'settings' => 'emtheme_title_mobile'
			)
		);
	}

	public function footer_info($wp_customize) {

		$wp_customize->add_panel('emtheme_footer_panel',
			array(
				'title' => 'Footer Info'
			)
		);

		$wp_customize->add_section('emtheme_footer_contact', 
			array(
				'title' => 'Contact Info',
				'panel' => 'emtheme_footer_panel'
			)
		);

		$wp_customize->add_setting('emtheme_footer_contact_active', 
			array(
				'type' => 'option'
			)
		);
		$wp_customize->add_control('emtheme_control_contact_active',
			array(
				'label' => 'Aktiver',
				'section' => 'emtheme_footer_contact',
				'settings' => 'emtheme_footer_contact_active',
				'type' => 'checkbox'
			)
		);

		$wp_customize->add_setting('emtheme_footer_email', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_email',
			array(
				'label' => 'Email',
				'section' => 'emtheme_footer_contact',
				'settings' => 'emtheme_footer_email'
			)
		);
		
		$wp_customize->add_setting('emtheme_footer_avdeling', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_avdeling',
			array(
				'label' => 'Avdeling',
				'section' => 'emtheme_footer_contact',
				'settings' => 'emtheme_footer_avdeling'
			)
		);

		$wp_customize->add_setting('emtheme_footer_selskap', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_selskap',
			array(
				'label' => 'Selskap',
				'section' => 'emtheme_footer_contact',
				'settings' => 'emtheme_footer_selskap'
			)
		);

		$wp_customize->add_setting('emtheme_footer_poststed', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_poststed',
			array(
				'label' => 'Poststed',
				'section' => 'emtheme_footer_contact',
				'settings' => 'emtheme_footer_poststed'
			)
		);

		$wp_customize->add_setting('emtheme_footer_postnr', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_postnr',
			array(
				'label' => 'Post Nr',
				'section' => 'emtheme_footer_contact',
				'settings' => 'emtheme_footer_postnr'
			)
		);

		$wp_customize->add_setting('emtheme_footer_veiadr', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_veiadr',
			array(
				'label' => 'Vei Addresse',
				'section' => 'emtheme_footer_contact',
				'settings' => 'emtheme_footer_veiadr'
			)
		);

		$wp_customize->add_setting('emtheme_footer_land', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_land',
			array(
				'label' => 'Land',
				'section' => 'emtheme_footer_contact',
				'settings' => 'emtheme_footer_land'
			)
		);


		/* SOCIAL SECTION */
		$wp_customize->add_section('emtheme_footer_social',
			array(
				'title' => 'Social Media',
				'description' => 'Social Media URLs',
				'panel' => 'emtheme_footer_panel'
			)
		);

		$wp_customize->add_setting('emtheme_footer_social_active', array(
			'type' => 'option'
		));
		$wp_customize->add_control('emtheme_control_social_active',
			array(
				'label' => 'Aktiver',
				'section' => 'emtheme_footer_social',
				'settings' => 'emtheme_footer_social_active',
				'type' => 'checkbox'
			)
		);

		$wp_customize->add_setting('emtheme_footer_twitter', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_twitter',
			array(
				'label' => 'Twitter',
				'section' => 'emtheme_footer_social',
				'settings' => 'emtheme_footer_twitter'
			)
		);

		$wp_customize->add_setting('emtheme_footer_facebook', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_facebook',
			array(
				'label' => 'Facebook',
				'section' => 'emtheme_footer_social',
				'settings' => 'emtheme_footer_facebook'
			)
		);

		$wp_customize->add_setting('emtheme_footer_google', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_google',
			array(
				'label' => 'Google+',
				'section' => 'emtheme_footer_social',
				'settings' => 'emtheme_footer_google'
			)
		);

		$wp_customize->add_setting('emtheme_footer_youtube', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_youtube',
			array(
				'label' => 'Youtube',
				'section' => 'emtheme_footer_social',
				'settings' => 'emtheme_footer_youtube'
			)
		);


		/* ABOUT US SECTION */
		$wp_customize->add_section('emtheme_footer_aboutus',
			array(
				'title' => 'About us',
				'panel' => 'emtheme_footer_panel'
			)
		);

		// ACTIVATE
		$wp_customize->add_setting('emtheme_footer_aboutus_active', array(
			'type' => 'option'
		));
		$wp_customize->add_control('emtheme_control_aboutus_active',
			array(
				'label' => 'Aktiver',
				'section' => 'emtheme_footer_aboutus',
				'settings' => 'emtheme_footer_aboutus_active',
				'type' => 'checkbox'
			)
		);

		// ABOUT US TEXTAREA
		$wp_customize->add_setting('emtheme_footer_about', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_about',
			array(
				'label' => 'Om Oss',
				'section' => 'emtheme_footer_aboutus',
				'settings' => 'emtheme_footer_about',
				'type' => 'textarea'
			)
		);

		/* EMTHEME CSS */
		$wp_customize->add_panel('emtheme_css_panel', array(
			'title' => 'Emtheme CSS'
		));

		$wp_customize->add_section('emtheme_css_section', array(
			'title' => 'Colors',
			'panel' => 'emtheme_css_panel'
		));


		$wp_customize->add_setting('emtheme_css_emtop', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control('emtheme_css_emtop_c', array(
			'label' => 'Logo/Title/Tagline Background Color',
			'description' => 'Hexcode',
			'settings' => 'emtheme_css_emtop',
			'section' => 'emtheme_css_section'
		));

		$wp_customize->add_setting('emtheme_css_emtop_font', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control('emtheme_css_emtop_font_c', array(
			'label' => 'Logo/Title/Tagline Font Color',
			'description' => 'Hexcode',
			'settings' => 'emtheme_css_emtop_font',
			'section' => 'emtheme_css_section'
		));
		
		$wp_customize->add_setting('emtheme_css_navfont', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control('emtheme_css_navfont_c', array(
			'label' => 'Navbar Font Color',
			'description' => 'Hexcode',
			'settings' => 'emtheme_css_navfont',
			'section' => 'emtheme_css_section'
		));

		$wp_customize->add_setting('emtheme_css_navbg', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control('emtheme_css_navbg_c', array(
			'label' => 'Navbar background',
			'description' => 'Hexcode',
			'settings' => 'emtheme_css_navbg',
			'section' => 'emtheme_css_section'
		));

		$wp_customize->add_setting('emtheme_css_navbg_hover', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control('emtheme_css_navbg_c_hover', array(
			'label' => 'Navbar HOVER background',
			'description' => 'Hexcode',
			'settings' => 'emtheme_css_navbg_hover',
			'section' => 'emtheme_css_section'
		));

		$wp_customize->add_setting('emtheme_css_navsub_font', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control('emtheme_css_navsub_font_c', array(
			'label' => 'Navbar submenu font color',
			'description' => 'Hexcode',
			'settings' => 'emtheme_css_navsub_font',
			'section' => 'emtheme_css_section'
		));

		$wp_customize->add_setting('emtheme_css_navsub_bg', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control('emtheme_css_navsub_bg_c', array(
			'label' => 'Navbar submenu background color',
			'description' => 'Hexcode',
			'settings' => 'emtheme_css_navsub_bg',
			'section' => 'emtheme_css_section'
		));

		$wp_customize->add_setting('emtheme_css_navsub_bg_hover', array(
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control('emtheme_css_navsub_bg_hover_c', array(
			'label' => 'Navbar submenu HOVER background color',
			'description' => 'Hexcode',
			'settings' => 'emtheme_css_navsub_bg_hover',
			'section' => 'emtheme_css_section'
		));
	
	

	}
}