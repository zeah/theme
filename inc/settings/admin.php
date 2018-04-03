<?php 


final class Emtheme_Options {
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		if (! is_admin()) return;

		$this->wp_hooks();
	}

	private function wp_hooks() {
		add_action('admin_menu', array($this, 'add_emtheme_menu'));

		add_action( 'admin_init', array($this, 'initAdminside') );
		add_action( 'admin_init', array($this, 'registerSettings') );
	
		Emtheme_Frontpage::get_instance();
		// Emtheme_Contact::get_instance();
		Emtheme_Logger::get_instance();

		Emtheme_External::get_instance();

		Emtheme_Google::get_instance();
	}

	public function add_emtheme_menu() {
		add_menu_page('EmTheme Options', 'EM Theme', 'manage_options', 'em-options-page', array($this, 'emtheme_callback'), '', 61);
	}

	public function emtheme_callback() {
		echo '<div><h1>EM Theme Options</h1></div>';
		echo '<form action="options.php" method="POST">';
		settings_fields('em_options_admin');
		do_settings_sections('em-admin-page');
		// do_settings_sections('em-admin-pagetwo');
		submit_button('save');
		echo '</form>';
	}

	public function san_callback($input) {
		return sanitize_text_field( $input );
	}

	public function registerSettings() {
		$args = [ 'sanitize_callback' => array($this, 'san_callback') ];

		register_setting('em_options_admin', 'em_admin_maint', $args);
		register_setting('em_options_admin', 'emtheme_shownav', $args);
		register_setting('em_options_admin', 'emtheme_styling', $args);
		register_setting('em_options_admin', 'emtheme_css', $args);
	}

	public function initAdminside() {
		add_settings_section( 'em_settings_maint', 'Maintenance mode', array($this, 'maint_text_callback'), 'em-admin-page' );
		add_settings_field( 'em-admin-active', 'Maintenance Aktiv', array($this, 'maint_callback'), 'em-admin-page', 'em_settings_maint' );

		add_settings_section( 'em_settings_disnav', 'Automatic Menu', array($this, 'disnav_text_callback'), 'em-admin-page' );
		add_settings_field( 'emtheme-shownav', 'Disable Automatic Menu', array($this, 'shownav_callback'), 'em-admin-page', 'em_settings_disnav' );
	
		add_settings_section( 'em_settings_styling', 'Styling', array($this, 'styling_text_callback'), 'em-admin-page' );
		add_settings_field( 'emtheme-styling-one', 'Styling one', array($this, 'styling_one_callback'), 'em-admin-page', 'em_settings_styling' );
		add_settings_field( 'emtheme-styling-two', 'Styling two', array($this, 'styling_two_callback'), 'em-admin-page', 'em_settings_styling' );
		add_settings_field( 'emtheme-styling-three', 'Styling three', array($this, 'styling_three_callback'), 'em-admin-page', 'em_settings_styling' );
		add_settings_field( 'emtheme-styling-four', 'Styling four', array($this, 'styling_four_callback'), 'em-admin-page', 'em_settings_styling' );

		add_settings_section('em_external_resources', 'External Resources', array($this, 'external_callback'), 'em-admin-page');
		add_settings_field('emtheme-ext-css', 'Desktop CSS', array($this, 'ext_css_callback'), 'em-admin-page', 'em_external_resources');

	}

	public function maint_text_callback() {
		echo 'Aktiver og forside vil vise maintenance mode';
	}

	public function maint_callback() {
		echo '<input type="checkbox" name="em_admin_maint"'.(get_option('em_admin_maint') ? ' checked' : '').'>';
	}

	public function disnav_text_callback() {
		echo 'To show or not to show the automatic generated navigation menu <strong> for non-logged in users</strong>';
	}

	public function shownav_callback() {
		echo '<input type="checkbox" name="emtheme_shownav"'.(get_option('emtheme_shownav') ? ' checked' : '').'>';
	}

	public function styling_text_callback() {
		echo 'choose your style (desktop only)';
	}

	public function styling_one_callback() {
		echo '<input type="radio" name="emtheme_styling" value="one"'.$this->styling_help('one').'>';
	}

	public function styling_two_callback() {
		echo '<input type="radio" name="emtheme_styling" value="two"'.$this->styling_help('two').'>';
	}

	public function styling_three_callback() {
		echo '<input type="radio" name="emtheme_styling" value="three"'.$this->styling_help('three').'>';
	}

	public function styling_four_callback() {
		echo '<input type="radio" name="emtheme_styling" value="four"'.$this->styling_help('four').'>';
	}

	public function external_callback() {
		echo 'Set external resources to use instead of internal resources.';
	}

	public function ext_css_callback() {
		echo '<input type="url" name="emtheme_css" value="'.(get_option('emtheme_css') ? esc_attr(get_option('emtheme_css')) : '').'">';
	}

	private function styling_help($v) {
		if (get_option('emtheme_styling') == $v) 	return ' checked';
		else 										return '';
	}
}