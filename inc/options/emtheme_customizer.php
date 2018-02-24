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


		// add a setting for the site logo
		$wp_customize->add_setting('emtheme_logo');
		$wp_customize->add_setting('emtheme_logo_mobile');
		$wp_customize->add_setting('emtheme_title_mobile');

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
}