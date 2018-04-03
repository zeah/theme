<?php 


final class Emtheme_google {
	/*singleton*/
	private static $instance = null; 

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		add_action('admin_menu', array($this, 'add_page'));

		add_action('admin_init', array($this, 'init_page'));
	}

	public function add_page() {
		add_submenu_page('em-options-page', 'Google', 'Google', 'manage_options', 'em_google', array($this, 'google_callback'));
	}

	public function init_page() {
		register_setting('em-google-options', 'em_google_disable', ['sanitize_callback' => 'sanitize_text_field']);
		register_setting('em-google-options', 'em_google_analytics', ['sanitize_callback' => 'sanitize_text_field']);
		register_setting('em-google-options', 'em_google_tagmanager', ['sanitize_callback' => 'sanitize_text_field']);

		add_settings_section('em-google-settings', 'Google Scripts', array($this, 'google_settings_callback'), 'em_google');
		add_settings_field('em-google-disable', 'Disable Scripts', array($this, 'google_disable_callback'), 'em_google', 'em-google-settings');
		add_settings_field('em-google-analytics-id', 'Google Analytics ID', array($this, 'google_analytics_callback'), 'em_google', 'em-google-settings');
		add_settings_field('em-google-tagmanager-id', 'Google Tagmanager ID', array($this, 'google_tagmanager_callback'), 'em_google', 'em-google-settings');
	}

	public function google_callback() {
		echo '<form action="options.php" method="POST">';
		settings_fields('em-google-options');
		do_settings_sections('em_google');
		submit_button('save');
		echo '</form>';
	}

	public function google_settings_callback() {
		echo 'Scripts to be added to footer. (always add to footer.)';
	}

	public function google_disable_callback() {
		$check = get_option('em_google_disable');

		if (isset($check) && $check) 	$check = ' checked';
		else 							$check = '';

		echo '<input name="em_google_disable" type="checkbox"'.$check.'>';
	}

	public function google_analytics_callback() {
		$value = get_option('em_google_analytics');

		if (isset($value))  $value = esc_attr($value);
		else 				$value = '';

		echo '<input type="text" name="em_google_analytics" value="'.$value.'">';
	}

	public function google_tagmanager_callback() {
		$value = get_option('em_google_tagmanager');

		if (isset($value))  $value = esc_attr($value);
		else 				$value = '';

		echo '<input type="text" name="em_google_tagmanager" value="'.$value.'">';
	}

}