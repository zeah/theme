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
		if (!shortcode_exists('col')) add_shortcode('col', array($this, 'col_callback'));

		if (!shortcode_exists('redirect')) add_shortcode('redirect', array($this, 'redirect_callback'));

		if (!shortcode_exists('box')) add_shortcode('box', array($this, 'box_shortcode'));
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

	/* check wheter $value is either left, right or center - returns nothing if not */
	private function helper_col($value) {
		$v = ['left', 'right', 'center'];

		return in_array($value, $v) ? ' class="em-'.$value.'"' : '';
	}

	public function redirect_callback($atts, $content = null) {
		// if (!isset($atts['name']) || !isset($atts['url'])) return 'need both a name and url set in redirect shortcode';
		
		$time = 2;
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

	public function box_shortcode($atts, $content = null) {
		
		if (!isset($atts['name'])) return;

		$type = 'page';
		$text = 'Les mer >>>';
		$color = '#ffffff';
		$float = false;
		$boxsize = '25';
		$fontsize = '3.2';
		$columns = 2;

		if (isset($atts['type'])) $type = explode(',', preg_replace('/ /', '', sanitize_text_field($atts['type'])));  
		if (isset($atts['text'])) $text = esc_html($atts['text']);
		if (isset($atts['color'])) $color = sanitize_hex_color($atts['color']);
		if (isset($atts['float'])) {
			if ($atts['float'] == 'left') $float = 'left';
			if ($atts['float'] == 'right') $float = 'right';
		}
		if (isset($atts['fontsize'])) {
			$tempsize = floatval($atts['fontsize']);
			
			if ($tempsize > 6) $size = ($tempsize / 10);
			else $fontsize = $tempsize;
		}

		if (isset($atts['boxsize'])) {
			$tempsize = floatval($atts['boxsize']);
			
			if ($tempsize > 60) $boxsize = ($tempsize / 10);
			else $boxsize = $tempsize;
		}

		if (isset($atts['columns']) && intval($atts['columns'] < 5)) $columns = intval($atts['columns']);

		switch ($columns) {
			case (4): $columns = '25%'; break;
			case (3): $columns = '33%'; break;
			case (2): $columns = '50%'; break;
			case (1): $columns = '100%'; break;
		}


		$args = [
			'post_type' => $type,
			'post_name__in' => explode(',', preg_replace('/ /', '', sanitize_text_field($atts['name']))),
			'posts_per_page' => -1

		];

		$posts = get_posts($args);

		$html = '<div class="emtheme-boxes'.($float ? '' : ' emtheme-boxes-center').'" style="color: '
				.$color.'; font-size: '.esc_html($fontsize).'rem;'.($float ? ' float: '.esc_html($float).';' : '').'">';
		
		foreach($posts as $post) {

			$thumbnail = 'url(\''.esc_url(get_the_post_thumbnail_url($post,'full')).'\')';

			$html .= '<div class="emtheme-box-inner" style="width: '.esc_html($columns).'"><div class="emtheme-box" onclick="location.assign(\''.esc_url(get_permalink($post)).'\')" style="background-image: '.$thumbnail.'; width: '.esc_html($boxsize).'rem; height: '.esc_html($boxsize).'rem;">';

			$html .= '<div class="emtheme-box-title">'.esc_html($post->post_title).'</div>';

			$html .= '<div class="emtheme-box-text"><div class="emtheme-box-text-bottom">'.esc_html($text).'</div></div>';

			$html .= '</div></div>';

		}
		$html .= '</div>';

		return $html;
	}
}