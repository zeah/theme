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
		add_action('customize_register', array($this, 'test_cust'));
	}

	public function test_cust($wp_customize) {

		// $wp_customize->add_section('emtheme_settings',
		// 	array(
		// 		'title' => 'Hello',
		// 		// 'priority' => 35,
		// 		// 'capability' => 'edit_theme_options',
		// 		'descriptiong'  => 'Yep'
		// 	)
		// );

		// $wp_customize->add_setting('test_setting',
		// 	array(
		// 		'default' => 'Hello',
		// 		'type' => 'theme_mod',
		// 		// 'capability' => 'edit_theme_options',
		// 		'transport' => 'refresh'
		// 	)
		// );

		// $wp_customize->add_control('emtheme_control',
		// 	array(
		// 		'label' => 'text',
		// 		'type' => 'text',
		// 		'settings' => 'test_setting',
		// 		'section' => 'title_tagline',
		// 		// 'capability' => 'edit_theme_options'
		// 	)
		// );

		// $wp_customize->add_panel( 'em_menus', array(
		//   'title' => __( 'tetdfdsf' ),
		//   'description' => 'none', // Include html tags such as <p>.
		//   // 'priority' => 160, // Mixed with top-level-section hierarchy.
		// ) );

		// $wp_customize->add_section( 'test_section' , array(
		//   'title' => 'hiya',
		//   'panel' => 'em_menus',
		// ) );

		// // add a setting for the site logo
		// $wp_customize->add_setting('your_theme_logo');
		// // Add a control to upload the logo
		// $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'your_theme_logo',
		// 	array(
		// 	'label' => 'Upload Logo',
		// 	'section' => 'test_section',
		// 	'settings' => 'your_theme_logo',
		// ) ) );

     // 1. Define a new section (if desired) to the Theme Customizer
      // $wp_customize->add_section( 'mytheme_options', 
      //    array(
      //       'title'       => __( 'MyTheme Options', 'mytheme' ), //Visible title of section
      //       'priority'    => 35, //Determines what order this appears in
      //       'capability'  => 'edit_theme_options', //Capability needed to tweak
      //       'description' => __('Allows you to customize some example settings for MyTheme.', 'mytheme'), //Descriptive tooltip
      //    ) 
      // );
      
      // //2. Register new settings to the WP database...
      // $wp_customize->add_setting( 'link_textcolor', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
      //    array(
      //       'default'    => '#2BA6CB', //Default setting/value to save
      //       'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
      //       'capability' => 'edit_theme_options', //Optional. Special permissions for accessing this setting.
      //       'transport'  => 'postMessage', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
      //    ) 
      // );      
            
      // //3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
      // $wp_customize->add_control( new WP_Customize_Color_Control( //Instantiate the color control class
      //    $wp_customize, //Pass the $wp_customize object (required)
      //    'mytheme_link_textcolor', //Set a unique ID for the control
      //    array(
      //       'label'      => __( 'Link Color', 'mytheme' ), //Admin-visible name of the control
      //       'settings'   => 'link_textcolor', //Which setting to load and manipulate (serialized is okay)
      //       'priority'   => 10, //Determines the order this control appears in for the specified section
      //       'section'    => 'mytheme_options', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
      //    ) 
      // ) );








		// $wp_customize->add_section( 'footer' , array(
		//   'title' => __( 'Footer', 'themename' ),
		//   'priority' => 105, // Before Widgets.
		// ) );

	// 	$wp_customize->add_setting('emtheme-cust-test', array(
	// 		'type' => 'theme_mod',
	// 		'capability' => 'edit_theme_options',
	// 		'default' => '#ffffff',
	// 		'transport' => 'refresh',
	// 		'sanitize_callback' => 'sanitize_hex_color'
	// 	));

	// 	$wp_customize->add_control('emtheme-cust-control', array(
	// 		'type' => 'text',
	// 		'priority' => 10,
	// 		'section' => 'colors',
	// 		'label' => 'test farge',
	// 		'description' => 'tester',
	// 		'input_attrs' => array(
	// 		    'class' => 'my-custom-class-for-js',
	// 		    'style' => 'border: 1px solid #900',
	// 			'placeholder' => __( 'mm/dd/yyyy' )
	// 		),
	// 	));
	// 	$wp_customize->add_section( 'custom_css', array(
	// 	  'title' => __( 'Custom CSS' ),
	// 	  'description' => __( 'Add custom CSS here' ),
	// 	  'panel' => '', // Not typically needed.
	// 	  'priority' => 160,
	// 	  'capability' => 'edit_theme_options',
	// 	  'theme_supports' => '', // Rarely needed.
	// 	) );
	}
}