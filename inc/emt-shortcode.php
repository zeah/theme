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

		if (!shortcode_exists('widget')) add_shortcode('widget', array($this, 'widget_shortcode'));
	}

	public function col_callback($atts, $content = null) {
		if ($content === null) return;

		if (! isset($atts[0])) return '<div>'.$content.'</div>';

		$width = '25';
		if (isset($atts['width'])) $width = intval($atts['width']) / 10;
		$margin = '2';
		if (isset($atts['margin'])) $margin = intval($atts['margin']) / 10;

		switch ($atts[0]) {
			case 'right': return '<div class="em-right" style="width: '.$width.'rem; margin-left: '.$margin.'rem;">'.$content.'</div>'; break;
			case 'center': return '<div class="em-center">'.$content.'</div>'; break;
			case 'left': return '<div class="em-left" style="width: '.$width.'rem; margin-right: '.$margin.'rem;">'.$content.'</div>'; break;
			// case 'left': return '<div class="em-left">'.$content.'</div>'; break;
		}


	}

	public function col_callback2($atts, $content = null) {
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
		// wp_die(print_r($atts['name'], true));
		
		if (!isset($atts['name'])) return;

		$type = 'page';
		$text = 'Les mer >>>';
		$title = false;
		$havetitle = true;
		$color = '#ffffff';
		// $boxsize = '25';
		$height = '25';
		$width = '25';
		$fontsize = '3.2';
		$columns = 2;
		$margin = 2;
		$float = false;
		$grid = 'grid-template-columns: 25rem 25rem';


		// wp_die(print_r($atts['name'], true));
		if (isset($atts['name']) && !is_array($atts['name'])) $grid = '';

		if (isset($atts['type'])) $type = explode(',', preg_replace('/ /', '', sanitize_text_field($atts['type'])));  
		if (isset($atts['text'])) $text = esc_html($atts['text']);
		if (isset($atts['title'])) $title = esc_html($atts['title']);
		if (isset($atts['title']) && $atts['title'] == '') $havetitle = false;
		if (isset($atts['color'])) $color = sanitize_hex_color($atts['color']);
		if (isset($atts['height'])) $height = intval($atts['height']) / 10;
		if (isset($atts['width'])) $width = intval($atts['width']) / 10;
		if (isset($atts['fontsize'])) $fontsize = intval($atts['fontsize']) / 10; 
		if (isset($atts['margin'])) $margin = intval($atts['margin']) / 10;
		if (isset($atts['float'])) {
			switch ($atts['float']) {
				case 'left': $float = 'left'; break;
				case 'right': $float = 'right'; break;
			}
		}

		if (isset($atts['columns']) && !$float) {
			$cols = intval($atts['columns']);
			$grid = 'grid-template-columns:';

			if ($cols < 11 && $cols > 0) {

				for ($i = 0; $i < $cols; $i++) 
					$grid .= ' '.$width.'rem';
				
				$grid .= ';';

			}
			else
				$grid .= ' 25rem 25rem;';
		}

		if ($float) $grid = false;

		$args = [
			'post_type' => $type,
			'post_name__in' => explode(',', preg_replace('/ /', '', sanitize_text_field($atts['name']))),
			'posts_per_page' => -1

		];


		$posts = get_posts($args);
		$html = '<div class="emtheme-boxes" style="'.($float ? 'float: '.$float.'; ' : '').'color: '.$color.'; font-size: '.esc_html($fontsize).'rem; '.($grid ? $grid : '').'">';
		
		foreach($posts as $post) {

			if (!$title) $title_text = esc_html($post->post_title);
			else $title_text = $title;

			$thumbnail = 'url(\''.esc_url(get_the_post_thumbnail_url($post,'full')).'\')';

			$html .= '<a href="'.get_permalink($post).'" class="emtheme-box" style="color: '.$color.'; background-image: '.$thumbnail.'; width: '.$width.'rem; height: '.$height.'rem; margin-bottom: '.$margin.'rem;">';

			if ($havetitle) $html .= '<span class="emtheme-box-title">'.$title_text.'</span>';

			if ($text) $html .= '<span class="emtheme-box-text"><span class="emtheme-box-text-bottom">'.esc_html($text).'</span></span>';

			$html .= '</a>'; // emtheme-box

		}
		$html .= '</div>';

		return $html;
	}

	/**
	*	
	*/
	public function widget_shortcode($atts, $content = null) {
		$widget = 'shortcode-widget-01';
		if (isset($atts['number']) && intval($atts['number']) < 5) 	
			switch (intval($atts['number'])) {
				case '1': $widget = 'shortcode-widget-01'; break;
				case '2': $widget = 'shortcode-widget-02'; break;
				case '3': $widget = 'shortcode-widget-03'; break;
				case '4': $widget = 'shortcode-widget-04'; break;
			}
		

		$pos = '';
		if (isset($atts['pos'])) switch ($atts['pos']) {
									case 'left': $pos = ' style="float: left;"'; break;
									case 'right': $pos = ' style="float: right;"'; break;
									case 'center': $pos = ' style="text-align: center"'; break;
								 }

		if (is_active_sidebar($widget)) {
			$sidebar = '';
			ob_start();
			dynamic_sidebar($widget);
			$sidebar = ob_get_clean();

			return '<div class="emtheme-widget"'.$pos.'>'.$sidebar.'</div>';
		}
		return;
	}

	private function get_widget($name) {
		if (is_active_sidebar($name)) {
			$sidebar = '';
			ob_start();
			dynamic_sidebar($name);
			$sidebar = ob_get_clean();

			return $sidebar;
		}

		return;
	}

}