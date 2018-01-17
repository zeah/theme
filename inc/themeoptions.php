<?php 

final class EmOptions {
	public static function init() {
		add_action('admin_menu', array('EmOptions', 'add_options_menu'));
		add_action( 'admin_init', array('EmOptions', 'initSetting') );
	}

	public static function add_options_menu() {
		add_menu_page('EmTheme Options', 'EM Options', 'manage_options', 'em-theme-options', array('EmOptions', 'em_options_callback'), '', 61);
	}

	public static function initSetting() {
		register_setting('em_options', 'em_name');
		register_setting('em_options', 'em_adr');

		add_settings_section( 'em_theme_settings', 'EM Settings', array('EmOptions', 'em_settings_callback'), 'em-theme-options' );
	
		add_settings_field( 'em-name', 'Navn', array('EmOptions', 'navn_callback'), 'em-theme-options', 'em_theme_settings' );
		add_settings_field( 'em-adr', 'Adresse', array('EmOptions', 'adr_callback'), 'em-theme-options', 'em_theme_settings' );


	}

	public static function em_settings_callback() {
		echo 'settings side bla bla bla';
	}

	public static function navn_callback() {
		echo '<input name="em_name" placeholder="name" value="'.get_option('em_name').'">';
	}

	public static function adr_callback() {
		echo '<input name="em_adr" placeholder="name" value="'.get_option('em_adr').'">';
	}

	public static function em_options_callback() {
		echo '<form action="options.php" method="POST">';
		settings_fields('em_options');
		do_settings_sections('em-theme-options');
		submit_button('save');
		echo '</form>';
		echo 'blah';
	}
}