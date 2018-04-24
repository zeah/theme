<?php 


final class Emtheme_Widget {
	/*singleton*/
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self;

		return self::$instance;
	}

	private function __construct() {
		$this->wp_hooks();
	}

	private function wp_hooks() {
		add_action('widgets_init', array($this, 'register_widget'));
	}


	public function register_widget() {
		register_sidebar(array( 
			'name' => '404 Sidebar',
			'id' => 'em-notfound-widget'
		));
	}

}