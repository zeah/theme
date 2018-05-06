<?php 


final class Emtheme_Customizer {
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->wp_hooks();
	}


	private function wp_hooks() {
		add_action('customize_register', array($this, 'site_identity'));
		add_action('customize_register', array($this, 'footer_info'));
		add_action('customize_register', array($this, 'emtheme_css'));
		add_action('customize_register', array($this, 'emtheme_css_font'));
		add_action('customize_register', array($this, 'add_nav_layout'));

		add_action('customize_preview_init', array($this, 'cd_customizer'), 9999);

		add_action('customize_controls_enqueue_scripts', array($this, 'cd_customizer_pane'));
	}

	public function cd_customizer() {
		$fontFile = get_stylesheet_directory() . '/assets/cache/google-web-fonts.txt';
        $content = json_decode(file_get_contents($fontFile));

		wp_enqueue_script('cd_customizer', get_theme_file_uri() . '/assets/js/emtheme-customizer.js', array( 'jquery','customize-preview' ), '', true);
	    wp_localize_script('cd_customizer', 'gfont', $content->items);

	    wp_enqueue_style( 'emtheme-customizer-css', get_theme_file_uri() . '/assets/css/emtheme-customizer.css', false );
	}

	public function cd_customizer_pane() {
		$fontFile = get_stylesheet_directory() . '/assets/cache/google-web-fonts.txt';
        $content = json_decode(file_get_contents($fontFile));

		wp_enqueue_script('cd_customizer_pane', get_theme_file_uri() . '/assets/js/emtheme-customizer-pane.js', array( 'jquery','customize-preview' ), '', true);
	    wp_localize_script('cd_customizer_pane', 'gfont', $content->items);
	
	    wp_enqueue_style( 'emtheme-customizer-pane-css', get_theme_file_uri() . '/assets/css/emtheme-customizer-pane.css', false );
	}

	private function add_settings($wp_customize, $array, $args, $name = null) {
		if ( (!is_array($array)) || (!is_array($args)) )
			return;

		foreach($array as $value)
			if ($name !== null)
				$wp_customize->add_setting($name.'['.$value.']', $args);
			else
				$wp_customize->add_setting($value, $args);
	}

	public function site_identity($wp_customize) {

		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		$array_text = [
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		];

		$settings = ['emtheme_logo', 'emtheme_logo_mobile', 'emtheme_title_mobile'];

		$this->add_settings($wp_customize, $settings, $array_text);
		// add a setting for the site logo
		// $wp_customize->add_setting('emtheme_logo', $array_text);
		// $wp_customize->add_setting('emtheme_logo_mobile', $array_text);
		// $wp_customize->add_setting('emtheme_title_mobile', $array_text);

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

		$this->add_settings($wp_customize, ['emtheme_mobile_color'], ['type' => 'option', 'sanitize' => 'sanitize_hex_color']);
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_mobile_color_c', array(
				'label' => 'Mobile Theme Color',
				'description' => 'The color of the web adresse area.<br>Only visible on mobile devices.',
				'settings' => 'emtheme_mobile_color',
				'section' => 'title_tagline'
		)));
	}

	public function footer_info($wp_customize) {

		$wp_customize->add_panel('emtheme_footer_panel',
			array(
				'title' => 'Footer Info',
				'priority' => 600
			)
		);

		$args = [
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		];

		$settings = [
			'contact_active', 'email', 'avdeling', 'selskap', 'poststed', 'postnr', 'veiadr', 'land',
			'social_active', 'twitter', 'facebook', 'google', 'youtube',
			'aboutus_active', 'aboutus'
		];

		$this->add_settings($wp_customize, $settings, $args, 'emtheme_footer');

		$wp_customize->add_section('emtheme_footer_contact', array(
			'title' => 'Contact Info',
			'panel' => 'emtheme_footer_panel'
		));

		$wp_customize->add_control('emtheme_footer[contact_active]_c', array(
			'label' => 'Aktiver',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer[contact_active]',
			'type' => 'checkbox'
		));

		$wp_customize->add_control('emtheme_footer[contact]_c',array(
			'label' => 'Email',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer[email]'
		));
		
		$wp_customize->add_control('emtheme_footer[avdeling]_c', array(
			'label' => 'Avdeling',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer[avdeling]'
		));

		$wp_customize->add_control('emtheme_footer[selskap]_c', array(
			'label' => 'Selskap',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer[selskap]'
		));

		$wp_customize->add_control('emtheme_footer[poststed]_c', array(
			'label' => 'Poststed',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer[poststed]'
		));

		$wp_customize->add_control('emtheme_footer[postnr]_c', array(
			'label' => 'Post Nr',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer[postnr]'
		));

		$wp_customize->add_control('emtheme_footer[veiadr]_c', array(
			'label' => 'Vei Addresse',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer[veiadr]'
		));

		$wp_customize->add_control('emtheme_footer[land]_c', array(
			'label' => 'Land',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer[land]'
		));


		/* SOCIAL SECTION  AND LINKS */
		$wp_customize->add_section('emtheme_footer_social', array(
			'title' => 'Social Media & Links',
			'description' => 'Social Media URLs',
			'panel' => 'emtheme_footer_panel'
		));

		$wp_customize->add_control('emtheme_footer[social_active]_c', array(
			'label' => 'Aktiver',
			'section' => 'emtheme_footer_social',
			'settings' => 'emtheme_footer[social_active]',
			'type' => 'checkbox'
		));

		$wp_customize->add_control('emtheme_footer[twitter]_c', array(
			'label' => 'Twitter',
			'section' => 'emtheme_footer_social',
			'settings' => 'emtheme_footer[twitter]'
		));

		$wp_customize->add_control('emtheme_footer[facebook]_c', array(
			'label' => 'Facebook',
			'section' => 'emtheme_footer_social',
			'settings' => 'emtheme_footer[facebook]'
		));

		$wp_customize->add_control('emtheme_footer[google]_c', array(
			'label' => 'Google+',
			'section' => 'emtheme_footer_social',
			'settings' => 'emtheme_footer[google]'
		));

		$wp_customize->add_control('emtheme_footer[youtube]_c', array(
			'label' => 'Youtube',
			'section' => 'emtheme_footer_social',
			'settings' => 'emtheme_footer[youtube]'
		));


		$this->add_settings($wp_customize, ['custom_links'], ['type' => 'option', 'transport' => 'postMessage', 'sanitize' => 'wp_kses_post'], 'emtheme_footer');
		$wp_customize->add_control('emtheme_footer[custom_links]_c', array(
			'label' => 'Custom HTML',
			'description' => '<strong>Customizer will not show a preview of this box.</strong><br>Use class em-footer-link to style anchor elements and with styling there is no need to add line breaks between anchors.<br>eks.: <xmp><a class="em-footer-link"</xmp><xmp>href="https://www.nrk.no">NRK</a></xmp>',
			'type' => 'textarea',
			'section' => 'emtheme_footer_social',
			'settings' => 'emtheme_footer[custom_links]'
		));


		/* ABOUT US SECTION */
		$wp_customize->add_section('emtheme_footer_aboutus', array(
			'title' => 'About us',
			'panel' => 'emtheme_footer_panel'
		));

		// ACTIVATE
		$wp_customize->add_control('emtheme_footer[aboutus_active]_c', array(
			'label' => 'Aktiver',
			'section' => 'emtheme_footer_aboutus',
			'settings' => 'emtheme_footer[aboutus_active]',
			'type' => 'checkbox'
		));

		// ABOUT US TEXTAREA
		$wp_customize->add_control('emtheme_footer[aboutus]_c', array(
			'label' => 'Om Oss',
			'description' => '[p] is new paragraph.',
			'section' => 'emtheme_footer_aboutus',
			'settings' => 'emtheme_footer[aboutus]',
			'type' => 'textarea'
		));
	}

	public function emtheme_css($wp_customize) {

		$args = array(
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		);

		/* PANEL */
		// $wp_customize->add_panel('emtheme_css_panel', array(
		// 	'title' => 'Emtheme CSS',
		// 	'priority' => 21
		// ));

		/* COLORS SECTION */
		$wp_customize->add_section('emtheme_css_color', array(
			'title' => 'Colors',
			'description' => '<ul><strong>Top</strong>
								<li><a class="emtop-font-color" style="cursor:pointer;">font</a></li>
								<li><a class="emtop-bg-color" style="cursor:pointer;">background</a></li>
							  </ul><br>
							  
							  <ul><strong>Navbar</strong>
							  <li><a class="navbar-mfont-color" style="cursor:pointer;">top menu font</a></li>
							  <li><a class="navbar-mbg-color" style="cursor:pointer;">top menu background</a></li>
							  <li><a class="navbar-mbgh-color" style="cursor:pointer;">top menu background hover</a></li><br>
							  <li><a class="navbar-sfont-color" style="cursor:pointer;">sub menu font</a></li>
							  <li><a class="navbar-sbg-color" style="cursor:pointer;">sub menu background</a></li>
							  <li><a class="navbar-sbgh-color" style="cursor:pointer;">sub menu background hover</a></li><br>
							  <li><a class="navbar-active-color" style="cursor:pointer;">active page background</a></li>
							  <li><a class="navbar-activeh-color" style="cursor:pointer;">active page background hover</a></li>
							  </ul>',
			'priority' => 500
			// 'panel' => 'emtheme_css_panel'
		));


		$settings = ['emtop_font', 'emtop_bg', 'search', 'nav_font', 'nav_bg_top', 'nav_bg_middle', 'nav_bg_bottom', 'nav_bg_hover_top', 
					 'nav_bg_hover_middle', 'nav_bg_hover_bottom', 'navsub_font', 'navsub_bg', 'navsub_bg_hover', 'active', 'active_hover'];

		$this->add_settings($wp_customize, $settings, $args, 'emtheme_color');
		$this->add_settings($wp_customize, ['emtop_bg_image', 'emtop_bg_image_opacity'], ['type' => 'option', 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field'], 'emtheme_color');

		/* FONT COLOR TOP (LOGO, TITLE, TAGLINE )*/
		$wp_customize->get_setting('emtheme_color[emtop_font]')->default = Emtheme_style::$colors['top']['font']; 
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_color[emtop_font]_c', array(
				'label' => 'Header Font-color',
				'description' => 'Site title and tagline.',
				'settings' => 'emtheme_color[emtop_font]',
				'section' => 'emtheme_css_color'
		)));

		/* BACKGROUND COLOR/IMAGE TOP (LOGO, TITLE, TAGLINE )*/
		$wp_customize->get_setting('emtheme_color[emtop_bg]')->default = Emtheme_style::$colors['top']['bg']; 
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_color[emtop_bg]_c', array(
				'label' => 'Header Background-color',
				'description' => 'Container for site logo, title and tagline.',
				'settings' => 'emtheme_color[emtop_bg]',
				'section' => 'emtheme_css_color'
		)));

		$wp_customize->get_setting('emtheme_color[search]')->default = Emtheme_style::$colors['search']['color']; 
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_color[search]_c', array(
				'label' => 'Search box/text',
				'description' => '',
				'settings' => 'emtheme_color[search]',
				'section' => 'emtheme_css_color'
		)));

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'emtheme_bg_image_c',
			array(
				'label' => 'Repeating background image for header',
				'settings' => 'emtheme_color[emtop_bg_image]',
				'section' => 'emtheme_css_color',
		) ) );

		$wp_customize->get_setting('emtheme_color[emtop_bg_image_opacity]')->default = 1;
		$wp_customize->add_control('emtheme_color[emtop_bg_image_opacity]_c', array(
			'type' => 'number',
			'label' => 'Background-image opacity',
			'description' => '',
			'section' => 'emtheme_css_color',
			'settings' => 'emtheme_color[emtop_bg_image_opacity]',
			// 'priority' => 103,
			'input_attrs' => array(
			    'min' => 0,
			    'max' => 1,
				'step' => 0.05,
			),
		));

		/* NAVBAR FONT COLOR */
		$wp_customize->get_setting('emtheme_color[nav_font]')->default = Emtheme_style::$colors['nav']['font']; 
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_color[nav_font]_c', array(
				'label' => 'Navbar Font-color',
				'settings' => 'emtheme_color[nav_font]',
				'section' => 'emtheme_css_color'
		)));

		/* NAVBAR BACKGROUND COLOR */
		$wp_customize->get_setting('emtheme_color[nav_bg_top]')->default = Emtheme_style::$colors['nav']['bg']; 
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_color[nav_bg_top]_c', array(
				'label' => 'Navbar Background-color TOP/FULL',
				'description' => 'If this is only <strong>one</strong> set, then background color will only be this one.',
				'settings' => 'emtheme_color[nav_bg_top]',
				'section' => 'emtheme_css_color'
		)));
		// $wp_customize->get_setting('emtheme_color[nav_bg_middle]')->default = Emtheme_style::$colors['nav']['bg']; 
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_color[nav_bg_middle]_c', array(
				'label' => 'Navbar Background-color MIDDLE',
				'description' => 'A third color placed in the center.<br>Bottom color must be set before this one.',
				'settings' => 'emtheme_color[nav_bg_middle]',
				'section' => 'emtheme_css_color'
		)));
		// $wp_customize->get_setting('emtheme_color[nav_bg_bottom]')->default = Emtheme_style::$colors['nav']['bg']; 
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_color[nav_bg_bottom]_c', array(
				'label' => 'Navbar Background-color BOTTOM',
				'settings' => 'emtheme_color[nav_bg_bottom]',
				'section' => 'emtheme_css_color'
		)));

		/* NAVBAR BACKGROUND COLOR HOVER */
		$wp_customize->get_setting('emtheme_color[nav_bg_hover_top]')->default = Emtheme_style::$colors['nav']['hover']; 
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_color[nav_bg_hover_top]_c', array(
				'label' => 'Navbar Hover Background-color TOP/FULL',
				'settings' => 'emtheme_color[nav_bg_hover_top]',
				'section' => 'emtheme_css_color'
		)));

		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_color[nav_bg_hover_middle]_c', array(
				'label' => 'Navbar Hover Background-color MIDDLE',
				'settings' => 'emtheme_color[nav_bg_hover_middle]',
				'section' => 'emtheme_css_color'
		)));

		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_color[nav_bg_hover_bottom]_c', array(
				'label' => 'Navbar Hover Background-color BOTTOM',
				'settings' => 'emtheme_color[nav_bg_hover_bottom]',
				'section' => 'emtheme_css_color'
		)));

		/* SUBMENU FONT COLOR */
		$wp_customize->get_setting('emtheme_color[navsub_font]')->default = Emtheme_style::$colors['sub']['font']; 
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_color[navsub_font]_c', array(
				'label' => 'Submenu Font-color',
				'settings' => 'emtheme_color[navsub_font]',
				'section' => 'emtheme_css_color'
		)));

		/* SUBMENU BACKGROUND COLOR */
		$wp_customize->get_setting('emtheme_color[navsub_bg]')->default = Emtheme_style::$colors['sub']['bg']; 
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_color[navsub_bg]_c', array(
				'label' => 'Submenu Background-color',
				'settings' => 'emtheme_color[navsub_bg]',
				'section' => 'emtheme_css_color'
		)));
		
		/* SUBMENU BACKGROUND COLOR HOVER */
		$wp_customize->get_setting('emtheme_color[navsub_bg_hover]')->default = Emtheme_style::$colors['sub']['hover']; 
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_color[navsub_bg_hover]_c', array(
				'label' => 'Submenu Hover Background-color',
				'settings' => 'emtheme_color[navsub_bg_hover]',
				'section' => 'emtheme_css_color'
		)));

		/* ACTIVE PAGE MARKER - BACKGROUND COLOR */
		// $wp_customize->get_setting('emtheme_color[active]')->default = Emtheme_style::$colors['active']['bg']; 
		// $wp_customize->add_control(new WP_Customize_Color_Control( 
		// 	$wp_customize, 'emtheme_color[active]_c', array(
		// 		'label' => 'Background-color Marker',
		// 		'description' => 'Shows which page you currently are on.',
		// 		'settings' => 'emtheme_color[active]',
		// 		'section' => 'emtheme_css_color' 
		// )));

		/* ACTIVE PAGE MARKER - BACKGROUND COLOR HOVER */
		// $wp_customize->get_setting('emtheme_color[active_hover]')->default = Emtheme_style::$colors['active']['hover']; 
		// $wp_customize->add_control(new WP_Customize_Color_Control( 
		// 	$wp_customize, 'emtheme_color[active_hover]_c', array(
		// 		'label' => 'Background-color Hover Marker',
		// 		'description' => 'Shows which page you currently are on.',
		// 		'settings' => 'emtheme_color[active_hover]',
		// 		'section' => 'emtheme_css_color' 
		// )));
	}

	public function emtheme_css_font($wp_customize) {	

		/* EMTHEME FONT SECTION */
		$wp_customize->add_section('emtheme_css_font', array(
			'title' => 'Fonts',
			'description' => '<ul><strong>Default Font</strong>
								<li><a class="emtheme-font-d-fam">Font Family</a></li>
								<li><a class="emtheme-font-d-weight">Font Weight</a></li>
								<li><a class="emtheme-font-d-size">Font Size</a></li>
								<li><a class="emtheme-font-d-lheight">Line-height</a></li>
							  </ul>
							  <ul><strong>Title Font</strong>
 							 	<li><a class="emtheme-font-t-fam">Font Family</a></li>
								<li><a class="emtheme-font-t-weight">Font Weight</a></li>
								<li><a class="emtheme-font-t-size">Font Size</a></li>
							  </ul>
							  <ul><strong>Navbar Font</strong>
 							 	<li><a class="emtheme-font-n-fam">Font Family</a></li>
								<li><a class="emtheme-font-n-weight">Font Weight</a></li>
								<li><a class="emtheme-font-n-size">Font Size</a></li>
							  </ul>
							  ',
			'priority' => 501
			// 'panel' => 'emtheme_css_panel'
		));

		$args = [
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		];

		$settings = ['standard', 'standard_weight', 'standard_size', 'standard_lineheight',
		             'title', 'title_weight', 'title_size',
		             'nav', 'nav_weight', 'nav_size'];

		$this->add_settings($wp_customize, $settings, $args, 'emtheme_font');


		/*
			DEFAULT FONT SETTINGS
		*/

		/*
			text control
			header for default font "section"
		*/
		$wp_customize->add_control(
			new Text_Control($wp_customize, 'default_font_control', array(
				'label' => 'Default Font',
				// 'text' => '<p><p>test text for header',
				'priority' => 100,
				'section' => 'emtheme_css_font',
				'settings' => array(),
			))
		);

		// control for font family is made by javascript

		// control for font weight is made by javascript

		// font size
		$wp_customize->get_setting('emtheme_font[standard_size]')->default = 1.6;
		$wp_customize->add_control('emtheme_font[standard_size]_c', array(
			'type' => 'number',
			'label' => 'Font size',
			'description' => 'Font size is given in rem. 1rem is 10px, 1.6rem is 16px.<br>(Lower than 16px gets bad seo on mobile.)',
			'section' => 'emtheme_css_font',
			'settings' => 'emtheme_font[standard_size]',
			'priority' => 103,
			'input_attrs' => array(
			    'min' => 0.8,
				'step' => 0.2,
			),
		));

		// line height
		$wp_customize->get_setting('emtheme_font[standard_lineheight]')->default = 1.6;
		$wp_customize->add_control('emtheme_font[standard_lineheight]_c', array(
			'type' => 'number',
			'label' => 'Line Height for Post Content',
			'description' => 'Raw number. Multiplier of font-size.<br>Example: font size is 1.6 (16px) and line-height is 1.5 then one line has the height of 16px*1.5 = 24px',
			'section' => 'emtheme_css_font',
			'settings' => 'emtheme_font[standard_lineheight]',
			'priority' => 104,
			'input_attrs' => array(
			    'min' => 0.5,
				'step' => 0.1,
			),
		));

		// seperator
		$wp_customize->add_control(
			new Sep_Control($wp_customize, 'emtheme-stdfont-sep', array(
				'section' => 'emtheme_css_font',
				'priority' => 109,
				'settings' => array()
			))
		);


		// TITLE

		/*
		text control
		header for default font "section"
		*/
		$wp_customize->add_control(
			new Text_Control($wp_customize, 'title_font_control', array(
				'label' => 'Title Font',
				// 'text' => '<p><p>test text for header',
				'priority' => 110,
				'section' => 'emtheme_css_font',
				'settings' => array(),
			))
		);


		// font family from javascript

		// font weight from javascript

		// font size
		$wp_customize->get_setting('emtheme_font[title_size]')->default = 4.6;
		$wp_customize->add_control('emtheme_font[title_size]_c', array(
			'type' => 'number',
			'label' => 'Font size',
			'description' => 'Font size is given in rem. 1rem is 10px, 1.6rem is 16px.',
			'section' => 'emtheme_css_font',
			'settings' => 'emtheme_font[title_size]',
			'priority' => 113,
			'input_attrs' => array(
			    'min' => 0.8,
				'step' => 0.2,
			),
		));


		// seperator
		$wp_customize->add_control(
			new Sep_Control($wp_customize, 'emtheme-titlefont-sep', array(
				'section' => 'emtheme_css_font',
				'priority' => 119,
				'settings' => array()
			))
		);



		// NAV FONT
		$wp_customize->add_control(
			new Text_Control($wp_customize, 'nav_font_control', array(
				'label' => 'Navbar Font',
				// 'text' => '<p><p>test text for header',
				'priority' => 120,
				'section' => 'emtheme_css_font',
				'settings' => array(),
			))
		);

		// font family from javascript

		// font weight from javascript

		$wp_customize->get_setting('emtheme_font[nav_size]')->default = 2;
		$wp_customize->add_control('emtheme_font[nav_size]_c', array(
			'type' => 'number',
			'label' => 'Font size',
			'description' => 'Font size is given in rem. 1rem is 10px, 1.6rem is 16px.',
			'section' => 'emtheme_css_font',
			'settings' => 'emtheme_font[nav_size]',
			'priority' => 123,
			'input_attrs' => array(
			    'min' => 0.8,
				'step' => 0.2,
			),
		));

		// seperator
		$wp_customize->add_control(
			new Sep_Control($wp_customize, 'emtheme-navfont-sep', array(
				'section' => 'emtheme_css_font',
				'priority' => 129,
				'settings' => array()
			))
		);
	}




	public function add_nav_layout($wp_customize) {
		$array_text = [
			'type' => 'option',
			'transport' => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'default'
		];

		$settings = ['emtheme_nav_layout'];

		$this->add_settings($wp_customize, $settings, $array_text);


		// $wp_customize->add_setting('emtheme_nav_layout_setting')
		// $wp_customize->add_panel('emtheme_nav_layout_panel',
		// 	array(
		// 		'title' => 'Nav layout',
		// 		'priority' => 23
		// 	)
		// );


		$wp_customize->add_section('emtheme_nav_layout_section', [
			'title' => 'Nav Layout',
			'capability' => 'edit_theme_options',
			'priority' => 510
		]);

		$wp_customize->add_control('emtheme_nav_layout_c', [
			'type' => 'select',
			'label' => 'nav layout',
			'section' => 'emtheme_nav_layout_section',
			'settings' => 'emtheme_nav_layout',
			'choices' => [
				'default' => 'Default',
				'one' => 'One'
			]

		]);



	}
}























if (class_exists('WP_Customize_Control')) {
	class Text_Control extends WP_Customize_Control {
		public $type = 'customtext';
		public $text = '';

		public function render_content() {
			echo '<h2>'.esc_html($this->label).'</h2><div>'
			.wp_kses($this->text, array('a' => array(), 'br' => array(), 'p' => array(), 'ul' => array(), 'li' => array(), 'strong' => array())).'</div>';
		}
	}

	class Sep_Control extends WP_Customize_Control {
		public $type = 'seperator';

		public function render_content() {
			echo '<div class="emtheme-customizer-sep"></div>';
		}
	}
}

/**
 * A class to create a dropdown for all google fonts
 */
if (class_exists('WP_Customize_Control')) {
 class Google_Font_Dropdown_Custom_Control extends WP_Customize_Control
 {
    private $fonts = false;
    public function __construct($manager, $id, $args = array(), $options = array())
    {
        $this->fonts = $this->get_fonts();
        parent::__construct( $manager, $id, $args );
    }
    /**
     * Render the content of the category dropdown
     *
     * @return HTML
     */
    public function render_content()
    {
        if(!empty($this->fonts))
        {
            ?>
                <label>
                    <span class="customize-category-select-control"><?php echo esc_html( $this->label ); ?></span>
                    <select <?php $this->link(); ?>>
                        <?php
                            foreach ( $this->fonts as $k => $v )
                            {
                                printf('<option value="%s" %s>%s</option>', $k, selected($this->value(), $k, false), $v->family);
                            }
                        ?>
                    </select>
                </label>
            <?php
        }
    }
    /**
     * Get the google fonts from the API or in the cache
     *
     * @param  integer $amount
     *
     * @return String
     */
    public function get_fonts( $amount = 30 )
    {
        // $selectDirectory = get_stylesheet_directory().'/wordpress-theme-customizer-custom-controls/select/';
        // $selectDirectoryInc = get_stylesheet_directory().'/inc/wordpress-theme-customizer-custom-controls/select/';
        // $finalselectDirectory = '';
        // if(is_dir($selectDirectory))
        // {
        //     $finalselectDirectory = $selectDirectory;
        // }
        // if(is_dir($selectDirectoryInc))
        // {
        //     $finalselectDirectory = $selectDirectoryInc;
        // }
        $fontFile = get_stylesheet_directory() . '/assets/cache/google-web-fonts.txt';
        //Total time the file will be cached in seconds, set to a week
        $cachetime = 86400 * 7;
        if(file_exists($fontFile) && $cachetime < filemtime($fontFile))
        {
            $content = json_decode(file_get_contents($fontFile));
        } else {
            $googleApi = 'https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&key=AIzaSyB0sb865wT8n1ZdWK9KhdknRLzyJxr0Qiw';
            $fontContent = wp_remote_get( $googleApi, array('sslverify'   => false) );
            $fp = fopen($fontFile, 'w');
            fwrite($fp, $fontContent['body']);
            fclose($fp);
            $content = json_decode($fontContent['body']);
        }
        if($amount == 'all')
        {
            return $content->items;
        } else {
            return array_slice($content->items, 0, $amount);
        }
    }
 }
}