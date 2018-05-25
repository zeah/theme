<?php 

defined( 'ABSPATH' ) or die( 'Blank Space' );

final class Emtheme_CSS {
	/* SINGLETON */
	private static $instance = null;
	private $fonts;
	private $colors;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$col = get_option('emtheme_color');
		$fon = get_option('emtheme_font');
		
		// wp_die('<xmp>'.print_r($col, true).'</xmp>');
		
		// COLORS
		$colors = [];

		// page background color (body tag)
		$colors['background'] = isset($col['background']) ? sanitize_hex_color($col['background']) : '#fff'; 

		// main background color 
		$colors['main_background'] = isset($col['main_background']) ? sanitize_hex_color($col['main_background']) : '#fff';

		if (isset($col['main_shadow'])) {
			// $css = '0 0 5px';

			if ($col['main_shadow'] == '') $css = 'none';
			else $css = '0 0 5px '.sanitize_hex_color($col['main_shadow']);

			$colors['main_shadow'] = $css;  
		}
		else $colors['main_shadow'] = 'none';

		// header font
		$colors['header_font'] = isset($col['emtop_font']) ? sanitize_hex_color($col['emtop_font']) : '#000';

		// header background
		$colors['header_background'] = isset($col['emtop_bg']) ? sanitize_hex_color($col['emtop_bg']) : '#fff';

		// $colors['header_image'] = isset($col['emtop_bg_image']) ? esc_url($col['emtop_bg_image']) : false;

		// $colors['header_image_opacity'] = isset($col['emtop_bg_image_opacity']) ? esc_html($col['emtop_bg_image_opacity']) : '0.5';

		// search
		$colors['search'] = isset($col['search']) ? sanitize_hex_color($col['search']) : '#000';
		
		// bg image
		$colors['header_background_image'] = isset($col['emtop_bg_image']) ? esc_url($col['emtop_bg_image']) : '';
		
		// bg image opacity
		$colors['header_background_image_opacity'] = isset($col['emtop_bg_image_opacity']) ? esc_html($col['emtop_bg_image_opacity']) : '0.5';

		// navbar font
		$colors['navbar_font'] = isset($col['nav_font']) ? sanitize_hex_color($col['nav_font']) : '#fff';
		
		// navbar bg
		if (isset($col['nav_bg_top'])) {
			$colors['navbar_background'] = 'background-color: '.sanitize_hex_color($col['nav_bg_top']).';';
		
			if (isset($col['nav_bg_middle']) && $col['nav_bg_middle'] != '' && isset($col['nav_bg_bottom']) && $col['nav_bg_bottom'] != '') 
				$colors['navbar_background'] = "background: linear-gradient(to top, $col[nav_bg_bottom] 0%, $col[nav_bg_middle] 50%, $col[nav_bg_top] 100%)";

			elseif (isset($col['nav_bg_middle']) && $col['nav_bg_middle'] != '')
				$colors['navbar_background'] = "background: linear-gradient(to top, $col[nav_bg_middle] 50%, $col[nav_bg_top] 100%)";
			
			elseif (isset($col['nav_bg_bottom']) && $col['nav_bg_bottom'] != '')
				$colors['navbar_background'] = "background: linear-gradient(to top, $col[nav_bg_bottom] 0%, $col[nav_bg_top] 100%)";

		}
		else $colors['navbar_background'] = 'background-color: #000;';
		
		// navbar hover
		if (isset($col['nav_bg_hover_top'])) {
			$colors['navbar_hover'] = 'background-color: '.sanitize_hex_color($col['nav_bg_hover_top']).';';
		
			if (isset($col['nav_bg_hover_middle']) && $col['nav_bg_hover_middle'] != '' && isset($col['nav_bg_hover_bottom']) && $col['nav_bg_hover_bottom'] != '') 
				$colors['navbar_hover'] = "background: linear-gradient(to top, $col[nav_bg_hover_bottom] 0%, $col[nav_bg_hover_middle] 50%, $col[nav_bg_hover_top] 100%)";

			elseif (isset($col['nav_bg_hover_middle']) && $col['nav_bg_hover_middle'] != '')
				$colors['navbar_hover'] = "background: linear-gradient(to top, $col[nav_bg_hover_middle] 50%, $col[nav_bg_hover_top] 100%)";
			
			elseif (isset($col['nav_bg_hover_bottom']) && $col['nav_bg_hover_bottom'] != '')
				$colors['navbar_hover'] = "background: linear-gradient(to top, $col[nav_bg_hover_bottom] 0%, $col[nav_bg_hover_top] 100%)";

		}
		else $colors['navbar_hover'] = 'background-color: #000;';

		// submenu font
		$colors['submenu_font'] = isset($col['navsub_font']) ? sanitize_hex_color($col['navsub_font']) : '#000';
		
		// submenu bg
		$colors['submenu_background'] = isset($col['navsub_bg']) ? sanitize_hex_color($col['navsub_bg']) : '#ccc';
						
		// submenu hover
		$colors['submenu_hover'] = isset($col['navsub_bg_hover']) ? sanitize_hex_color($col['navsub_bg_hover']) : '#aaa';
		
		$this->colors = $colors;

		// FONTS
		$fonts = [];

		// content font family
		$fonts['content_family'] = isset($fon['standard']) ? esc_html($fon['standard']) : 'arial';

		// content weight
		$fonts = array_merge($fonts, $this->check_weight((isset($fon['standard_weight']) ? $fon['standard_weight'] : false), 'content'));

		// content font size
		$fonts['content_size'] = isset($fon['standard_size']) ? esc_html($fon['standard_size']) : '1.6';

		// content lineheight
		$fonts['content_lineheight'] = isset($fon['standard_lineheight']) ? esc_html($fon['standard_lineheight']) : 1;

		// title font family
		$fonts['title_family'] = isset($fon['title']) ? esc_html($fon['title']) : 'verdana';

		// title weight
		$fonts = array_merge($fonts, $this->check_weight((isset($fon['title_weight']) ? $fon['title_weight'] : false), 'title'));

		// title font size
		$fonts['title_size'] = isset($fon['title_size']) ? esc_html($fon['title_size']) : '2.6';

		// navbar font family
		$fonts['navbar_family'] = isset($fon['nav']) ? esc_html($fon['nav']) : 'arial';

		// navbar weight
		$fonts = array_merge($fonts, $this->check_weight((isset($fon['nav_weight']) ? $fon['nav_weight'] : false), 'navbar'));

		// navbar font-size
		$fonts['navbar_size'] = isset($fon['nav_size']) ? esc_html($fon['nav_size']) : '2';

		$this->fonts = $fonts;
	}

	public function get_css() {
		$style = get_option('emtheme_nav_layout');

		if (!$style) $style = 'default';

		$version = null;
		switch ($style) {
			case 'default': $version = Emtheme_CSS_Def::get_instance($this->fonts, $this->colors); break;
			case 'one': $version = Emtheme_CSS_One::get_instance($this->fonts, $this->colors); break;
			// case 'two': $version = Emtheme_CSS_One::get_instance($this->fonts, $this->colors); break;
			default: return;
		}

		return $version->get_css();
	}

	/*
		splitting up google weight into css weight + font style
	*/
	private function check_weight($weight, $prefix) {
		$data = [];

		if ($weight) {

			if (strpos($weight, 'italic')) 	$data[$prefix.'_style'] = 'italic';
			else 							$data[$prefix.'_style'] = 'normal';
			
			$weight = str_replace('regular', 'normal', $weight);
			$data[$prefix.'_weight'] = str_replace('italic', '', esc_html($weight));
		}
		else {
			$data[$prefix.'_weight'] = 'normal';
			$data[$prefix.'_style'] = 'normal';
		}

		return $data;
	}

}



















final class Emtheme_CSS_Def {
	/* SINGLETON */
	private static $instance = null;
	private $fonts;
	private $colors;

	public static function get_instance($fonts, $colors) {
		if (self::$instance === null) self::$instance = new self($fonts, $colors);

		// self::$instance->fonts = $fonts;
		// self::$instance->colors = $colors;

		return self::$instance;
	}

	private function __construct($fonts, $colors) {
		$this->fonts = $fonts;
		$this->colors = $colors;
	}

	public function get_css() {
		$col = $this->colors;
		$fon = $this->fonts;
		// wp_die('<xmp>'.print_r($col, true).'</xmp>');
		

		$css = "<style>";
		$css .= "\nbody { font-family: $fon[content_family]; font-size: $fon[content_size]rem; }";
		// $css .= "\n.content, .emtheme-tagline, .em-footer { font-family: $fon[content_family]; font-size: $fon[content_size]rem; }";
		$css .= "\n.content-title-text { font-family: $fon[title_family]; font-size: $fon[title_size]rem; }";
		$css .= "\n.main { background-color: $col[main_background]; box-shadow: $col[main_shadow]; }";

		// desktop
		$css .= "\n@media screen and (min-width: 1024px) {";

		$css .= "\nbody { background-color: $col[background]; }";
		
		$width = get_option('emtheme_main_width') ? intval(get_option('emtheme_main_width')) / 10 : '112';
		$css .= "\n".'.main { width: '.$width.'rem; }';

		$css .= $this->gen_desktop();

		if (!has_nav_menu('header-menu')) 	$css .= $this->page_desktop();
		else 								$css .= $this->custom_desktop();

		$css .= "\n}";


		// mobile
		$css .= "\n@media screen and (max-width: 1023px) {";

		$css .= $this->gen_mobile();
		
		if (!has_nav_menu('header-menu')) 	$css .= $this->page_mobile();
		else 								$css .= $this->custom_mobile();

		$css .= "\n}\n</style>";

		return preg_replace('/\h+/', ' ', $css);
	}

	private function gen_desktop() {
		$col = $this->colors;
		$fon = $this->fonts;

		$css  = "\n.emtheme-header-container { position: relative; background-color: $col[header_background]; z-index: 99; padding-top: 1rem;}";

		if ($col['header_background_image'])
			$css .= "\n.emtheme-header-container:after { content: ''; position: absolute; top: 0; bottom: 0; left: 0; right: 0; background-image: url('$col[header_background_image]'); background-repeat: repeat; z-index: -3; opacity: $col[header_background_image_opacity];}";

		$css .= "\n.emtheme-header { width: 112rem; margin: auto; display: flex; align-items: center; }";
		$css .= "\n.emtheme-identity { display: flex; align-items: center; margin-right: auto; color: $col[header_font]; text-decoration: none; }";
		$css .= "\n.emtheme-logo { max-height: 20rem; }"; 

		// wp_die('<xmp>'.print_r($col, true).'</xmp>');
		
		
		$css .= "\n.emtheme-title-tagline { margin-left: 3rem; }"; 
		
		$css .= "\n.emtheme-title { display: block; font-family: $fon[title_family]; font-size: $fon[title_size]rem; font-weight: $fon[title_weight]; font-style: $fon[title_style]; }"; 
		$css .= "\n.emtheme-search-icon, .emtheme-search-input { color: $col[search]; }"; 
		$css .= "\n.emtheme-search-input { border-bottom: solid 2px $col[search] !important; }"; 
		// wp_die('<xmp>'.print_r($fon, true).'</xmp>');		

		$css .= "\n.menu-container { $col[navbar_background];}";
		$css .= "\n.menu-container ul { padding: 0; margin: 0}";
		$css .= "\n.menu-container li { list-style: none; }";

		return $css;
	}


	private function page_desktop() {
		$col = $this->colors;
		$fon = $this->fonts;

		$css  = "\n.menu { position: relative; right: 2rem; width: 112rem; margin: auto; }";
		$css .= "\n.menu > ul {  display: flex; flex-wrap: wrap; }"; 
		$css .= "\n.menu > ul > .page_item > a { padding: 0.5rem 2rem; }"; 
		  
		$css .= "\n.page_item_has_children { position: relative; }";
		$css .= "\n.page_item_has_children > a:after { content: ' \\25bc'}";
		$css .= "\n.page_item_has_children:hover > .children { display: block; }";
		$css .= "\n.page_item > a { display: block; font-family: $fon[navbar_family]; font-size: $fon[navbar_size]rem; color: $col[navbar_font]; text-decoration: none; }"; 
		$css .= "\n.page_item > a:hover { $col[navbar_hover]; }";
		
		$css .= "\n.children { display: none; position: absolute; z-index: 100; background-color: $col[submenu_background]; }";
		$css .= "\n.children > li { white-space: nowrap; }";
		$css .= "\n.children > li > a { display: block; padding: 1rem; color: $col[submenu_font]; border-bottom: solid rgba(0, 0, 0, .5) 1px; }";
		$css .= "\n.children > li:last-child > a { border-bottom: none; }";
		$css .= "\n.children > li > a:hover { background-color: $col[submenu_hover]; }";
		


		return $css; 
	}


	private function custom_desktop() {
		$col = $this->colors;
		$fon = $this->fonts;

		$css  = "\n.menu { position: relative; right: 2rem; width: 112rem; margin: auto !important; }";
		$css .= "\n.menu {  display: flex; flex-wrap: wrap; }"; 
		$css .= "\n.menu > .menu-item > a { padding: 0.5rem 2rem; }"; 
		  
		$css .= "\n.menu-item-has-children { position: relative; }";
		$css .= "\n.menu-item-has-children > a:after { content: ' \\25bc'}";
		$css .= "\n.menu-item-has-children:hover > .sub-menu { display: block; }";
		$css .= "\n.menu-item > a { display: block; font-family: $fon[navbar_family]; font-size: $fon[navbar_size]rem; color: $col[navbar_font]; text-decoration: none; }"; 
		$css .= "\n.menu-item > a:hover { $col[navbar_hover]; }";
		
		$css .= "\n.sub-menu { display: none; position: absolute; z-index: 100; background-color: $col[submenu_background]; }";
		$css .= "\n.sub-menu > li { white-space: nowrap; }";
		$css .= "\n.sub-menu > li > a { display: block; padding: 1rem; color: $col[submenu_font]; border-bottom: solid rgba(0, 0, 0, .5) 1px; }";
		$css .= "\n.sub-menu > li:last-child > a { border-bottom: none; }";
		$css .= "\n.sub-menu > li > a:hover { background-color: $col[submenu_hover]; }";
		return $css;		
	}

	private function gen_mobile() {
		$col = $this->colors;
		$fon = $this->fonts;

		$css  = "\n.emtheme-header-container { display: flex; $col[navbar_background]; height: 4rem;}";
		$css .= "\n.emtheme-header { margin-right: auto; }";
		$css .= "\n.emtheme-identity { display: flex; align-items: center; text-decoration: none; }"; 
		$css .= "\n.emtheme-title { font-size: 3.2rem; margin-left: 2rem; font-family: $fon[title_family]; color: $col[navbar_font]; }"; 
		
		$css .= "\n.emtheme-logo-mobile { height: 4rem; }";
		$css .= "\n.emtheme-mobile-icon { color: white; margin-right: 1rem; font-size: 4rem !important; }";
		$css .= "\n.emtheme-mob-arrow { color: $col[submenu_font]; font-size: 2.6rem; margin-right: 3rem; }";
		$css .= "\n.emtheme-mob-arrow:after { content: ' \\25bc'; }";
		$css .= "\n.nav-show { display: block !important; }";

		return $css;
	}


	private function page_mobile() {
		$col = $this->colors;
		$fon = $this->fonts;
		$css  = "\n.menu-container { position: relative; }";
		$css .= "\n.menu { display: none; position: absolute; top: 40px; right: -2px; z-index: 99; background-color: $col[submenu_background]; }";
		$css .= "\n.menu ul { padding: 0; margin: 0; }";
		$css .= "\n.menu ul > li { list-style: none; }";
		$css .= "\n.menu > ul > .page_item { border-bottom: solid 1px $col[submenu_font]; }";
		$css .= "\n.menu > ul > .page_item:last-child { border-bottom: none; }";
		$css .= "\n.page_item > a { display: block; margin: 2rem 0; margin-right: auto; padding: 0 2rem; color: $col[submenu_font]; font-size: 2.6rem; white-space: nowrap; text-decoration: none; }";
		$css .= "\n.page_item_has_children { display: flex; align-items: center; }";
		$css .= "\n.page_item { display: flex; flex-wrap: wrap;}";
		$css .= "\n.children { display: none; width: 100%; background-color: $col[submenu_font]25; }";
		$css .= "\n.children > .page_item { padding-left: 2rem; border-bottom: dashed 1px $col[submenu_font]; }";
		$css .= "\n.children > .page_item:last-child { border: none; }";
		return $css;
	}


	private function custom_mobile() {
		$col = $this->colors;
		$fon = $this->fonts;

		$css  = "\n.menu-container { position: relative; }";
		$css .= "\n.menu { display: none; position: absolute; top: 40px; right: 0; z-index: 99; background-color: $col[submenu_background]; }";
		$css .= "\n.menu-container ul { padding: 0; margin: 0; }";
		$css .= "\n.menu > li { list-style: none; }";
		$css .= "\n.menu > .menu-item { border-bottom: solid 1px $col[submenu_font]; }";
		$css .= "\n.menu > .menu-item:last-child { border-bottom: none; }";
		$css .= "\n.menu-item > a { display: block; margin: 2rem 0; margin-right: auto; padding: 0 2rem; color: $col[submenu_font]; font-size: 2.6rem; white-space: nowrap; text-decoration: none; }";
		$css .= "\n.menu-item-has-children { display: flex; align-items: center; }";
		$css .= "\n.menu-item { display: flex; flex-wrap: wrap; min-width: 30rem;}";
		$css .= "\n.sub-menu { display: none; width: 100%; background-color: $col[submenu_font]25; }";
		$css .= "\n.sub-menu > .menu-item { padding-left: 2rem; border-bottom: dashed 1px $col[submenu_font]; }";
		$css .= "\n.sub-menu > .menu-item:last-child { border: none; }";
	
		return $css;
	}



	// public function get_css() {
	// 	// desktop
	// 	$css = "<style>\n@media screen and (min-width: 1024px) {";

	// 	$css .= "\n.emtop { color: #fff }";

	// 	$css .= "\n}";

	// 	// mobile
	// 	$css .= "\n@media screen and (max-width: 1023px) {";
	// 	$css .= "\n}\n</style>";
	// 	return preg_replace('/\h+/', ' ', $css);
	// }

	// private function gen_desktop() {
		
	// }


	// private function page_desktop() {
		
	// }


	// private function custom_desktop() {
		
	// }


	// private function gen_mobile() {
		
	// }


	// private function page_mobile() {
		
	// }


	// private function custom_mobile() {
		
	// }


}






















final class Emtheme_CSS_One {
	/* SINGLETON */
	private static $instance = null;
	private $fonts;
	private $colors;

	public static function get_instance($fonts, $colors) {
		if (self::$instance === null) self::$instance = new self($fonts, $colors);
		return self::$instance;
	}

	private function __construct($fonts, $colors) {
		$this->fonts = $fonts;
		$this->colors = $colors;
	}


	public function get_css() {
		$col = $this->colors;
		$fon = $this->fonts;

		// desktop
		$css = "<style>";

		$css .= "\nbody { font-family: $fon[content_family]; font-size: $fon[content_size]rem; }";
		// $css .= "\n.content, .tagline, .em-footer { font-family: $fon[content_family]; font-size: $fon[content_size]rem; }";
		$css .= "\n.content-title-text { font-family: $fon[title_family]; font-size: $fon[title_size]rem; }";
		// $css .= "\n.main { background-color: $col[main_background]; }";
		$css .= "\n.main { background-color: $col[main_background]; box-shadow: $col[main_shadow]; line-height: $fon[content_lineheight]; }";

		$css .= "\n@media screen and (min-width: 1120px) {";
		$css .= "\nbody { background-color: $col[background]; }";

		$width = get_option('emtheme_main_width') ? intval(get_option('emtheme_main_width')) / 10 : '112';
		$css .= "\n".'.main { width: '.$width.'rem; }';

		$css .= $this->gen_desktop();
		if (!has_nav_menu('header-menu')) 	$css .= $this->page_desktop();
		else 								$css .= $this->custom_desktop();


		$css .= "\n}";

		// mobile
		$css .= "\n@media screen and (max-width: 1119px) {";
		$css .= $this->gen_mobile();
		
		if (!has_nav_menu('header-menu')) 	$css .= $this->page_mobile();
		else 								$css .= $this->custom_mobile();

		$css .= "\n}\n</style>";
		return preg_replace('/\h+/', ' ', $css);
	}

	private function gen_desktop() {
		$col = $this->colors;
		$fon = $this->fonts;

		$css  = "\n.emtheme-header-container { bottom-margin: 4rem; $col[navbar_background];}";
		$css .= "\n.emtheme-header { display: flex; align-items: center; width: 112rem; margin: auto; box-sizing: border-box; font-family: $fon[navbar_family]; font-size: $fon[navbar_size]rem; color: $col[navbar_font]; }";
		$css .= "\n.emtheme-header-link { display: flex; margin-right: auto; height: 3rem; align-items: center; text-decoration: none; color: $col[navbar_font]; }";
		$css .= "\n.emtheme-logo { height: 100%; margin-left: 1rem; }";

		$css .= "\n.menu-container { position: relative; left: 1rem; }";
		$css .= "\n.menu-container ul { padding: 0; margin: 0}";
		$css .= "\n.menu-container li { list-style: none; }";

		return $css;
	}


	private function page_desktop() {
		$col = $this->colors;
		$fon = $this->fonts;
		
		$css  = "\n.menu > ul { display: flex; align-items: center; }";
		$css .= "\n.menu > ul > .page_item:last-child { margin-right: 0; }";
		$css .= "\n.menu > ul > .page_item { height: 3.2rem; line-height: 3.2rem; }";
		$css .= "\n.page_item > a { display: block; padding: 0 1rem; color: $col[navbar_font]; text-decoration: none; }";
		$css .= "\n.page_item > a:hover { $col[navbar_hover]; }";
		$css .= "\n.page_item_has_children { position: relative; }";
		$css .= "\n.page_item_has_children > a:after { content: ' \\25bc'}";
		$css .= "\n.page_item_has_children:hover > .children { display: block; }";
		$css .= "\n.children { display: none; position: absolute; z-index: 100; background-color: $col[submenu_background]; }";
		$css .= "\n.children > li { white-space: nowrap; }";
		$css .= "\n.children > li > a { display: block; padding: 1rem; color: $col[submenu_font]; border-bottom: solid rgba(0, 0, 0, .5) 1px; }";
		$css .= "\n.children > li:last-child > a { border-bottom: none; }";
		$css .= "\n.children > li > a:hover { background-color: $col[submenu_hover]; }";
		return $css;
	}


	private function custom_desktop() {
		$col = $this->colors;
		$fon = $this->fonts;
		
		$css  = "\n.menu { display: flex; align-items: center; }";
		$css .= "\n.menu > .menu-item:last-child { margin-right: 0; }";
		$css .= "\n.menu > .menu-item { height: 3.2rem; line-height: 3.2rem; }";
		$css .= "\n.menu-item > a { display: block; padding: 0 1rem; color: $col[navbar_font]; text-decoration: none; }";
		$css .= "\n.menu-item > a:hover { $col[navbar_hover]; height: 100% }";
		$css .= "\n.menu-item-has-children { position: relative; }";
		$css .= "\n.menu-item-has-children > a:after { content: ' \\25bc'}";
		$css .= "\n.menu-item-has-children:hover > .sub-menu { display: block; }";
		$css .= "\n.sub-menu { display: none; position: absolute; z-index: 100; background-color: $col[submenu_background]; }";
		$css .= "\n.sub-menu > li { white-space: nowrap; }";
		$css .= "\n.sub-menu > li > a { display: block; padding: 1rem; color: $col[submenu_font]; border-bottom: solid rgba(0, 0, 0, .5) 1px; }";
		$css .= "\n.sub-menu > li:last-child > a { border-bottom: none; }";
		$css .= "\n.sub-menu > li > a:hover { background-color: $col[submenu_hover]; }";
		return $css;
	}




	private function gen_mobile() {
		$col = $this->colors;
		$fon = $this->fonts;

		$css  = "\n.emtheme-header { display: flex; $col[navbar_background]; height: 4rem; }";
		$css .= "\n.emtheme-header-link { display: flex; align-items: center; margin-right: auto; text-decoration: none; }";
		$css .= "\n.emtheme-logo { height: 4rem; }";
		$css .= "\n.emtheme-title { margin-left: 2rem; font-size: 3.2rem; color: $col[navbar_font]; font-family: $fon[navbar_family]; }"; 
		
		$css .= "\n.emtheme-mobile-icon { color: white; margin-right: 1rem; font-size: 4rem !important; }";
		$css .= "\n.emtheme-mob-arrow { color: $col[submenu_font]; font-size: 2.6rem; margin-right: 3rem; }";
		$css .= "\n.emtheme-mob-arrow:after { content: ' \\25bc'; }";
		$css .= "\n.nav-show { display: block !important; }";

		return $css;
	}


	private function page_mobile() {
		$col = $this->colors;
		$fon = $this->fonts;
		$css  = "\n.menu-container { position: relative; }";
		$css .= "\n.menu { display: none; position: absolute; top: 40px; right: -2px; z-index: 99; background-color: $col[submenu_background]; }";
		$css .= "\n.menu ul { padding: 0; margin: 0; }";
		$css .= "\n.menu ul > li { list-style: none; }";
		$css .= "\n.menu > ul > .page_item { border-bottom: solid 1px $col[submenu_font]; }";
		$css .= "\n.menu > ul > .page_item:last-child { border-bottom: none; }";
		$css .= "\n.page_item > a { display: block; margin: 2rem 0; margin-right: auto; padding: 0 2rem; color: $col[submenu_font]; font-size: 2.6rem; white-space: nowrap; text-decoration: none; }";
		$css .= "\n.page_item_has_children { display: flex; align-items: center; }";
		$css .= "\n.page_item { display: flex; flex-wrap: wrap;}";
		$css .= "\n.children { display: none; width: 100%; background-color: $col[submenu_font]25; }";
		$css .= "\n.children > .page_item { padding-left: 2rem; border-bottom: dashed 1px $col[submenu_font]; }";
		$css .= "\n.children > .page_item:last-child { border: none; }";
		return $css;
	}


	private function custom_mobile() {
		$col = $this->colors;
		$fon = $this->fonts;

		$css  = "\n.menu-container { position: relative; }";
		$css .= "\n.menu { display: none; position: absolute; top: 40px; right: 0; z-index: 99; background-color: $col[submenu_background]; }";
		$css .= "\n.menu-container ul { padding: 0; margin: 0; }";
		$css .= "\n.menu > li { list-style: none; }";
		$css .= "\n.menu > .menu-item { border-bottom: solid 1px $col[submenu_font]; }";
		$css .= "\n.menu > .menu-item:last-child { border-bottom: none; }";
		$css .= "\n.menu-item > a { display: block; margin: 2rem 0; margin-right: auto; padding: 0 2rem; color: $col[submenu_font]; font-size: 2.6rem; white-space: nowrap; text-decoration: none; }";
		$css .= "\n.menu-item-has-children { display: flex; align-items: center; }";
		$css .= "\n.menu-item { display: flex; flex-wrap: wrap; white-space: nowrap; min-width: 30rem; }";
		$css .= "\n.sub-menu { display: none; width: 100%; background-color: $col[submenu_font]25; }";
		$css .= "\n.sub-menu > .menu-item { padding-left: 2rem; border-bottom: dashed 1px $col[submenu_font]; }";
		$css .= "\n.sub-menu > .menu-item:last-child { border: none; }";
	
		return $css;
	}


}