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

		add_shortcode('redirect', array($this, 'redirect_callback'));
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

	public function redirect_callback($atts, $content = null) {
		if (!isset($atts['name']) || !isset($atts['url'])) return 'need both a name and url set in redirect shortcode';
		
		$time = 2;
		if (isset($atts['time'])) $time = $atts['time'];

		$html = '<div class="emtheme-redirect-container">';

		if (get_option('emtheme_logo')) $html .= '<img src="'.esc_url(get_option('emtheme_logo')).'"><br>';

		$html .= 'Du vil nå bli videresendt til '.esc_html($atts['name']).'<br><a style="font-size: 1.6rem" href="'.esc_url($atts['url']).'">Link</a>';

		$html .= '</div>';

		$html .= '<script>(function() { var e = document.createElement("meta"); e.setAttribute("http-equiv", "refresh"); e.setAttribute("content", "'.$time.'; url=\"'.esc_url($atts['url']).'\""); document.querySelector("head").appendChild(e) } )();</script>';
		
		return $html;
	}
}