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
		// if (!isset($atts['name']) || !isset($atts['url'])) return 'need both a name and url set in redirect shortcode';
		
		$time = 120;
		if (isset($atts['time'])) $time = $atts['time'];

		$bgc = '#fff';
		$fc = '#000';

		$colors = get_option('emtheme_color');

		if (isset($atts['background-color'])) 	$bgc = sanitize_hex_color($atts['background-color']);
		elseif (isset($colors['emtop_bg'])) 	$bgc = sanitize_hex_color($colors['emtop_bg']);
 
		if (isset($atts['font-color']))	$fc = sanitize_hex_color($atts['font-color']);
		elseif (isset($colors['emtop_font']))	$fc = sanitize_hex_color($colors['emtop_font']);


		$html = '<div class="emtheme-redirect-container" style="background-color: '.$bgc.'; color: '.$fc.';">';
		// $html = '<div class="emtheme-redirect-container" style="background-color: '.$bgc.'; color: '.$fc.';"><i class="material-icons emtheme-lock">lock_outline</i>';

		if ($content) $html .= do_shortcode($content).'<br>';

		if (isset($atts['url'])) $html .= '<p><a style="font-size: 1.6rem" href="'.esc_url($atts['url']).'">Gå videre med en gang</a></p>';

		$html .= '</div>';

		if (isset($atts['url'])) $html .= '<script>(function() { var e = document.createElement("meta"); e.setAttribute("http-equiv", "refresh"); e.setAttribute("content", "'.$time.'; url=\"'.esc_url($atts['url']).'\""); document.querySelector("head").appendChild(e) } )();</script>';
		
		return $html;
	}
}