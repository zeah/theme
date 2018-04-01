<?php 

final class Emtheme_Shortcode {
	/* SINGLETON */
	private static $instance = null;
	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->wp_hooks();
	}

	private function wp_hooks() {
		add_shortcode('col', array($this, 'col_callback'));
	}

	public function col_callback($atts, $content = null) {
		if ($content === null) return;

		if (! isset($atts[0])) {
			$atts = array();
			$atts[0] = '';
		}

		$content = do_shortcode($content);

		return '<div'.$this->helper_col($atts[0]).'>'.$content.'</div>';
	}

	private function helper_col($value) {
		$v = ['left', 'right', 'center'];

		return in_array($value, $v) ? ' class="em-'.$value.'"' : '';
	}
}