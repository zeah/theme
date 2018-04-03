<?php 


final class Emtheme_External {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_menu', array($this, 'add_page') );


	}

	public function add_page() {
		add_submenu_page( 'em-options-page', 'External Resources', 'Resources', 'manage_options', 'em-external-page', array($this, 'external_callback') );
	}

	public function external_callback() {

	}
}