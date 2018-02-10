<?php 

final class EmWidget {
	/* SINGLETON */
	private static $instance = null;
	public static function get_instance($activate = true) {

		if (self::$instance === null)
			self::$instance = new self($activate);

		return self::$instance;
	}

	private function __construct($activate = true) {
		if ( (! $activate) )
			return;
		$this->wp_hooks();
	}

	private function wp_hooks() {
		add_action( 'widgets_init', array($this, 'register_widget') );
	}

	/* THEME WIDGETS REGISTRATION */
	public function register_widget() {
		// Widget for front-end top logo
		register_sidebar(array(
			'name' => 'logo',
			'id' => 'emtheme-logo'
		));

		// Widget for front-end top logo - but for mobile
		register_sidebar(array(
			'name' => 'logo mobile',
			'id' => 'emtheme-logo-mobile'
		));
	}
}