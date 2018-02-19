<?php 


final class EmoptionAdmin {
	private static $instance = null;

	public static function get_instance($active = true) {

		if (self::$instance === null)
			self::$instance = new self($active);

		return self::$instance;
	}

	private function __construct($active = true) {
		if ( (! $active) || (! is_admin()) )
			return;

		$this->wp_hooks();
	}

	private function wp_hooks() {
		add_action( 'admin_init', array($this, 'initAdminside') );
		add_action( 'admin_init', array($this, 'registerSettings') );
	}

	public function san_callback($input) {
		return sanitize_text_field( $input );
	}

	public function registerSettings() {
		$args = [ 'sanitize_callback' => array($this, 'san_callback') ];

		register_setting('em_options_admin', 'em_admin_maint', $args);
	}

	public function initAdminside() {
		add_settings_section( 'em_admin_settings', 'Maintenance mode', array($this, 'maint_text_callback'), 'em-admin-page' );
		add_settings_field( 'em-admin-active', 'Maintenance Aktiv', array($this, 'maint_callback'), 'em-admin-page', 'em_admin_settings' );
	
	}

	public function maint_text_callback() {
		echo 'Aktiver og forside vil vise maintenance mode';
	}

	public function maint_callback() {
		echo '<input type="checkbox" name="em_admin_maint"'.(get_option('em_admin_maint') ? ' checked' : '').'>';
	}
}