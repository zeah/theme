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

		register_sidebar([
			'name' => 'Shortcode 1',
			'id' => 'shortcode-widget-01',
			'description' => 'activated by [widget number=1] or [widget]'
		]);

		register_sidebar([
			'name' => 'Shortcode 2',
			'id' => 'shortcode-widget-02',
			'description' => 'activated by [widget number=2]'
		]);

		register_sidebar([
			'name' => 'Shortcode 3',
			'id' => 'shortcode-widget-03',
			'description' => 'activated by [widget number=3]'
		]);

		register_sidebar([
			'name' => 'Shortcode 4',
			'id' => 'shortcode-widget-04',
			'description' => 'activated by [widget number=4]'
		]);
	}

}