<?php 
// both page/custom and desktop/mobile
// ---- || ---- desktop
// ---- || ---- mobile

// page desktop
// page mobile

// custom desktop
// custom mobile


final class Emtheme_Styler {
	/*singleton*/
	private static $instance = null;

	private $colors;
	private $fonts;
	private $nav_bg;
	private $nav_bg_hover;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->colors = get_option('emtheme_color');
		$this->fonts = get_option('emtheme_font');

		$this->colors['nav_bg_top'] = isset($this->colors['nav_bg_top']) ? $this->colors['nav_bg_top'] : Emtheme_style::$colors['nav']['bg'];
		$this->colors['nav_bg_hover_top'] = isset($this->colors['nav_bg_hover_top']) ? $this->colors['nav_bg_hover_top'] : Emtheme_style::$colors['nav']['hover'];

		// sets background color to what background color TOP from customizer or black as default
		$this->nav_bg = 'background-color: '.(isset($this->colors['nav_bg_top']) ? $this->colors['nav_bg_top'] : '#000').';';

		// 2 color linear-gradient
		if (isset($this->colors['nav_bg_middle']) && isset($this->colors['nav_bg_bottom']) && $this->colors['nav_bg_middle'] != '' && $this->colors['nav_bg_bottom'] != '')
			$this->nav_bg = 'background: linear-gradient(to top, '.$this->colors['nav_bg_bottom'].' 0%, '.$this->colors['nav_bg_middle'].' 50%, '.$this->colors['nav_bg_top'].' 100%);';
		
		// 3 color linear gradient
		elseif (isset($this->colors['nav_bg_bottom']) && $this->colors['nav_bg_bottom'] != '')
			$this->nav_bg = 'background: linear-gradient(to top, '.$this->colors['nav_bg_bottom'].' 0%, '.$this->colors['nav_bg_top'].' 100%);';

		// hover background background color from customizer or black as default
		$this->nav_bg_hover = 'background-color: '.(isset($this->colors['nav_bg_hover_top']) ? $this->colors['nav_bg_hover_top'] : '#000').';';

		// 2 color hover linear-background
		if (isset($this->colors['nav_bg_hover_middle']) && isset($this->colors['nav_bg_hover_bottom']) && $this->colors['nav_bg_hover_middle'] != '' && $this->colors['nav_bg_hover_bottom'] != '')
			$this->nav_bg_hover = 'background: linear-gradient(to top, '.$this->colors['nav_bg_hover_bottom'].' 0%, '.$this->colors['nav_bg_hover_middle'].' 50%, '.$this->colors['nav_bg_hover_top'].' 100%);';
		
		// 3 color hover linear-background
		elseif (isset($this->colors['nav_bg_hover_bottom']) && $this->colors['nav_bg_hover_bottom'] != '')
			$this->nav_bg_hover = 'background: linear-gradient(to top, '.$this->colors['nav_bg_hover_bottom'].' 0%, '.$this->colors['nav_bg_hover_top'].' 100%);';
	}

	/*
		the function that is called from head.php
	*/
	public function get_css() {

		// $option = get_option('emtheme_styling');

		$style = '<style>';


		// wp_die(print_r($option, true));
		

		// if ($option == 'two') {

		// }

		$nav_layout = get_option('emtheme_nav_layout');

		if ($nav_layout == 'one') $style_opt = Emtheme_Styler_One::get_instance();
		else 						$style_opt = Emtheme_Styler_Def::get_instance();

		$style .= $style_opt->get_style();

		// mobile 
		$style .= $this->def_general_mobile();
		if (!has_nav_menu('header-menu')) 	$style .= $this->def_page_mobile();
		else 								$style .= $this->def_custom_mobile();

		$style .= '</style>';

		return $style;
	}

	private function def_general_mobile() {
		$colors = $this->colors;

		$style = '
@media screen and (max-width: 1023px) {
	.emtop { display: flex; height: 4.6rem; '.esc_html($this->nav_bg).' }
	.emtheme-top-link { margin-right: auto; display: flex; align-items: center; text-decoration: none; color: '.$colors['emtop_font'].' }
	.emtheme-title { margin: 0 1rem; font-size: 2.6rem}
	.emtheme-logo { display: none; }
	.emtheme-logo-mobile > img { position: relative; top: 1px; height: 4.6rem; width: auto;}
	.emtheme-mobile-icon { color: white; font-size: 4.6rem !important; }
	.menu-container { position: relative; font-size: 2rem; }
	.menu { display: none; position: absolute; width: 100vw; top: 4.6rem; right: 0; z-index: 999; background-color: '.(isset($colors['nav_bg_top']) ? esc_html($colors['nav_bg_top']) : Emtheme_style::$colors['nav']['bg']).'; }
	.nav-show { display: block !important; }
	.emtheme-mob-arrow { float: right; display: inline; color: white; margin-right: 3rem; }
	.emtheme-mob-arrow:after { content: " \25bc"; }
	.current_page_item > a { border: none !important; }
	ul { padding: 0; margin: 0;}
}';

		return $style;
	}


	
	private function def_page_mobile() {
		$colors = $this->colors;

		$style = '
@media screen and (max-width: 1023px) {
	.page_item { display: inline-block; width: 100%; margin: 2rem 0; }
	.page_item > a { min-width: 12rem; padding: 0.5rem 3rem; color: '.(isset($colors['nav_font']) ? esc_html($colors['nav_font']) : Emtheme_style::$colors['nav']['font']).'; text-decoration: none; text-align: left; white-space: nowrap; }
	.children { display: none; z-index: 99; background-color: '.(isset($colors['navsub_bg']) ? esc_html($colors['navsub_bg']) : Emtheme_style::$colors['sub']['bg']).'; }
	.children > .page_item > a { padding: 0.5rem 3rem; color: '.(isset($colors['navsub_font']) ? esc_html($colors['navsub_font']) : Emtheme_style::$colors['sub']['font']).'; }
	.children > .page_item > a:hover { background-color: '.(isset($colors['navsub_bg_hover']) ? esc_html($colors['navsub_bg_hover']) : Emtheme_style::$colors['sub']['hover']).'; }
}';

		return $style;
	}
	
	private function def_custom_mobile() {
		$colors = $this->colors;

		$style = '
@media screen and (max-width: 1023px) {
	.menu-item { padding: 1rem 0; }
	.menu-item > a { min-width: 12rem; padding: 0.5rem 3rem; color: '.(isset($colors['nav_font']) ? esc_html($colors['nav_font']) : Emtheme_style::$colors['nav']['font']).'; text-decoration: none; text-align: left; white-space: nowrap; }
	.sub-menu { display: none; z-index: 99; background-color: '.(isset($colors['navsub_bg']) ? esc_html($colors['navsub_bg']) : Emtheme_style::$colors['sub']['bg']).'; }
	.sub-menu > .menu-item > a { padding: 0.5rem 3rem; color: '.(isset($colors['navsub_font']) ? esc_html($colors['navsub_font']) : Emtheme_style::$colors['sub']['font']).'; }
	.sub-menu > .menu-item > a:hover { background-color: '.(isset($colors['navsub_bg_hover']) ? esc_html($colors['navsub_bg_hover']) : Emtheme_style::$colors['sub']['hover']).'; }
}';

		return $style;
	}



}

























final class Emtheme_Styler_Def {
	/* SINGLETON */
	private static $instance = null;
	private $colors;
	private $fonts;
	private $nav_bg;
	private $nav_bg_hover;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->colors = get_option('emtheme_color');
		$this->fonts = get_option('emtheme_font');

		$this->colors['nav_bg_top'] = isset($this->colors['nav_bg_top']) ? $this->colors['nav_bg_top'] : Emtheme_style::$colors['nav']['bg'];
		$this->colors['nav_bg_hover_top'] = isset($this->colors['nav_bg_hover_top']) ? $this->colors['nav_bg_hover_top'] : Emtheme_style::$colors['nav']['hover'];

		// sets background color to what background color TOP from customizer or black as default
		$this->nav_bg = 'background-color: '.(isset($this->colors['nav_bg_top']) ? $this->colors['nav_bg_top'] : '#000').';';

		// 2 color linear-gradient
		if (isset($this->colors['nav_bg_middle']) && isset($this->colors['nav_bg_bottom']) && $this->colors['nav_bg_middle'] != '' && $this->colors['nav_bg_bottom'] != '')
			$this->nav_bg = 'background: linear-gradient(to top, '.$this->colors['nav_bg_bottom'].' 0%, '.$this->colors['nav_bg_middle'].' 50%, '.$this->colors['nav_bg_top'].' 100%);';
		
		// 3 color linear gradient
		elseif (isset($this->colors['nav_bg_bottom']) && $this->colors['nav_bg_bottom'] != '')
			$this->nav_bg = 'background: linear-gradient(to top, '.$this->colors['nav_bg_bottom'].' 0%, '.$this->colors['nav_bg_top'].' 100%);';

		// hover background background color from customizer or black as default
		$this->nav_bg_hover = 'background-color: '.(isset($this->colors['nav_bg_hover_top']) ? $this->colors['nav_bg_hover_top'] : '#000').';';

		// 2 color hover linear-background
		if (isset($this->colors['nav_bg_hover_middle']) && isset($this->colors['nav_bg_hover_bottom']) && $this->colors['nav_bg_hover_middle'] != '' && $this->colors['nav_bg_hover_bottom'] != '')
			$this->nav_bg_hover = 'background: linear-gradient(to top, '.$this->colors['nav_bg_hover_bottom'].' 0%, '.$this->colors['nav_bg_hover_middle'].' 50%, '.$this->colors['nav_bg_hover_top'].' 100%);';
		
		// 3 color hover linear-background
		elseif (isset($this->colors['nav_bg_hover_bottom']) && $this->colors['nav_bg_hover_bottom'] != '')
			$this->nav_bg_hover = 'background: linear-gradient(to top, '.$this->colors['nav_bg_hover_bottom'].' 0%, '.$this->colors['nav_bg_hover_top'].' 100%);';
		// $this->wp_hooks();
	}

	public function get_style() {

		$style = $this->general();
		$style .= $this->general_desktop();
		if (!has_nav_menu('header-menu'))	$style .= $this->page();
		else 								$style .= $this->custom();

		return $style;
	}

	private function general() {
		$fonts = $this->fonts;
		$colors = $this->colors;

		$style = '/* NAVBAR CSS */
.emtop { position: relative; }
.emtheme-site-identity { position: relative; z-index: 10; }
.emtheme-title { font-family: '.(isset($fonts['title']) ? esc_html($fonts['title']) : 'verdana').'; font-weight: '.((isset($fonts['title_weight']) && $fonts['title_weight'] != 'regular') ? (esc_html(str_replace('italic', '', $fonts['title_weight']))) : '400').'; font-size: '.(isset($fonts['title_size']) ? $fonts['title_size'].'rem' : '4.6rem').'; }
.emtheme-top-link, .emtheme-top-link:visited { color: '.(isset($colors['emtop_font']) ? esc_html($colors['emtop_font']) : Emtheme_style::$colors['top']['font']).'; }
.emtheme-tagline, .main { font-family: '.(isset($fonts['standard']) ? $fonts['standard'] : 'arial').'; font-weight: '.((isset($fonts['standard_weight']) && $fonts['standard_weight'] != 'regular') ? (esc_html(str_replace('italic', '', $fonts['standard_weight']))) : '400').'; font-size: '.(isset($fonts['standard_size']) ? $fonts['standard_size'].'rem' : '1.6rem').'; }
.content > p { line-height: '.(isset($fonts['standard_lineheight']) ? esc_html($fonts['standard_lineheight']) : '1.6').'; }
.menu-container { z-index: 10; position: relative; font-family: '.(isset($fonts['nav']) ? $fonts['nav'] : 'arial').'; font-weight: '.((isset($fonts['nav_weight']) && $fonts['nav_weight'] != 'regular') ? (esc_html(str_replace('italic', '', $fonts['nav_weight']))) : '400').'; font-size: '.(isset($fonts['nav_size']) ? $fonts['nav_size'].'rem' : '1.8rem').'; }
.menu ul, ul.menu { padding: 0; margin: 0 auto; }
.menu li { list-style: none;}';

		if (isset($colors['emtop_bg_image']) && $colors['emtop_bg_image'] != '') {
			$opacity = isset($colors['emtop_bg_image_opacity']) ? esc_html($colors['emtop_bg_image_opacity']) : '1';
		 	$style .=  '.emtop-bg:after { content: ""; background: url("'.esc_url($colors['emtop_bg_image']).'"); opacity: '.$opacity.'; top: 0; left: 0; bottom: 0; right: 0; position: absolute; z-index: 2;}';
		}
		
		$search_color = isset($colors['search']) ? sanitize_hex_color($colors['search']) : Emtheme_style::$colors['search']['color'];

		$style .= '
.emtheme-search-box,
.emtheme-search-input,
.emtheme-search-icon { color: '.$search_color.';}
.emtheme-search-input { border: none; border-bottom: solid 2px '.$search_color.';}';

		return $style;
	}


	private function general_desktop() {
		$fonts = $this->fonts;
		$colors = $this->colors;

		$style = '
@media screen and (min-width: 1024px) {
	.emtop { background-color: '.(isset($colors['emtop_bg']) ? esc_html($colors['emtop_bg']) : Emtheme_style::$colors['top']['bg']).'; }
	.emtheme-title { font-size: '.(isset($fonts['title_size']) ? $fonts['title_size'].'rem' : '2.2rem').'}
	.menu-container {'.esc_html($this->nav_bg).'}
	.menu { width: 112rem; margin: auto; }
	.emtheme-mobile-icon { display: none !important; }
	.emtheme-mobile { display: none; }
	.emtheme-logo { height: 10rem; width: auto;}
}';

	// .current-menu-item > a, .current_page_item > a
	// .current-menu-item > a:hover, .current_page_item > a:hover
		return $style;
	}


	private function page() {
	// $fonts = $this->fonts;
		$colors = $this->colors;

		$style = '
@media screen and (min-width: 1024px) {
.menu > ul { display: flex; }
.page_item > a { display: block; min-width: 12rem; padding: 0.5rem 1rem; border-right: solid 1px rgba(255,255,255,.5); color: '.(isset($colors['nav_font']) ? esc_html($colors['nav_font']) : Emtheme_style::$colors['nav']['font']).'; text-align: center; text-decoration: none; white-space: nowrap; }
.page_item:last-child > a { border-right: none; }
.menu > ul > .page_item > a:hover { '.esc_html($this->nav_bg_hover).' }
.children { display: none; position: absolute; z-index: 99; background-color: '.(isset($colors['navsub_bg']) ? esc_html($colors['navsub_bg']) : Emtheme_style::$colors['sub']['bg']).'; }
.page_item_has_children > a:after { content: " \25bc"; font-size: 1.4rem; }
.page_item_has_children:hover > .children { display: block; }
.children > .page_item > a { text-align: left; padding: 0.5rem 1rem; color: '.(isset($colors['navsub_font']) ? esc_html($colors['navsub_font']) : Emtheme_style::$colors['sub']['font']).'; }
.children > .page_item > a:hover { background-color: '.(isset($colors['navsub_bg_hover']) ? esc_html($colors['navsub_bg_hover']) : Emtheme_style::$colors['sub']['hover']).'; }
}';

		return $style;
	}


	private function custom() {
		// $fonts = $this->fonts;
		$colors = $this->colors;

		$style = '
@media screen and (min-width: 1024px) {
	.menu { display: flex; padding: 0; }
	.menu-item > a { display: block; min-width: 12rem; padding: 0.5rem 1rem; border-right: solid 1px rgba(255,255,255,.5); color: '.(isset($colors['nav_font']) ? esc_html($colors['nav_font']) : Emtheme_style::$colors['nav']['font']).'; text-decoration: none; text-align: center; white-space: nowrap; }
	.menu-item:last-child > a { border-right: none; }
	.menu > .menu-item > a:hover { '.esc_html($this->nav_bg_hover).' }
	.sub-menu { display: none; position: absolute; z-index: 99; background-color: '.(isset($colors['navsub_bg']) ? esc_html($colors['navsub_bg']) : Emtheme_style::$colors['sub']['bg']).'; }
	.menu-item-has-children > a:after { content: " \25bc"; font-size: 1.4rem; }
	.menu-item-has-children:hover > .sub-menu { display: block; }
	.sub-menu > .menu-item > a { text-align: left; padding: 0.5rem 1rem; color: '.(isset($colors['navsub_font']) ? esc_html($colors['navsub_font']) : Emtheme_style::$colors['sub']['font']).'; }
	.sub-menu > .menu-item > a:hover { background-color: '.(isset($colors['navsub_bg_hover']) ? esc_html($colors['navsub_bg_hover']) : Emtheme_style::$colors['sub']['hover']).'; }
}';

		return $style;
	}

}



























final class Emtheme_Styler_One {
	/* SINGLETON */
	private static $instance = null;
	private $colors;
	private $fonts;
	private $nav_bg;
	private $nav_bg_hover;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->colors = get_option('emtheme_color');
		$this->fonts = get_option('emtheme_font');

		// ??
		$this->colors['nav_bg_top'] = isset($this->colors['nav_bg_top']) ? esc_html($this->colors['nav_bg_top']) : Emtheme_style::$colors['nav']['bg'];
		$this->colors['nav_bg_hover_top'] = isset($this->colors['nav_bg_hover_top']) ? esc_html($this->colors['nav_bg_hover_top']) : Emtheme_style::$colors['nav']['hover'];

		// sets background color to what background color TOP from customizer or black as default
		$this->nav_bg = 'background-color: '.(isset($this->colors['nav_bg_top']) ? esc_html($this->colors['nav_bg_top']) : '#000').';';

		// 3 color linear-gradient
		if (isset($this->colors['nav_bg_middle']) && isset($this->colors['nav_bg_bottom']) && $this->colors['nav_bg_middle'] != '' && $this->colors['nav_bg_bottom'] != '')
			$this->nav_bg = 'background: linear-gradient(to top, '.esc_html($this->colors['nav_bg_bottom']).' 0%, '.esc_html($this->colors['nav_bg_middle']).' 50%, '.esc_html($this->colors['nav_bg_top']).' 100%);';
		
		// 2 color linear gradient
		elseif (isset($this->colors['nav_bg_bottom']) && $this->colors['nav_bg_bottom'] != '')
			$this->nav_bg = 'background: linear-gradient(to top, '.esc_html($this->colors['nav_bg_bottom']).' 0%, '.esc_html($this->colors['nav_bg_top']).' 100%);';

		// hover background background color from customizer or black as default
		$this->nav_bg_hover = 'background-color: '.(isset($this->colors['nav_bg_hover_top']) ? esc_html($this->colors['nav_bg_hover_top']) : '#000').';';

		// 3 color hover linear-background
		if (isset($this->colors['nav_bg_hover_middle']) && isset($this->colors['nav_bg_hover_bottom']) && $this->colors['nav_bg_hover_middle'] != '' && $this->colors['nav_bg_hover_bottom'] != '')
			$this->nav_bg_hover = 'background: linear-gradient(to top, '.esc_html($this->colors['nav_bg_hover_bottom']).' 0%, '.esc_html($this->colors['nav_bg_hover_middle']).' 50%, '.esc_html($this->colors['nav_bg_hover_top']).' 100%);';
		
		// 2 color hover linear-background
		elseif (isset($this->colors['nav_bg_hover_bottom']) && $this->colors['nav_bg_hover_bottom'] != '')
			$this->nav_bg_hover = 'background: linear-gradient(to top, '.esc_html($this->colors['nav_bg_hover_bottom']).' 0%, '.esc_html($this->colors['nav_bg_hover_top']).' 100%);';
	}

	public function get_style() {
		$style = $this->general();
		$style .= $this->general_desktop();
		if (!has_nav_menu('header-menu'))	$style .= $this->page();
		else 								$style .= $this->custom();

		return $style;
	}

	private function general() {

		return '
@media screen and (min-width: 1024px) {
	.emtheme-top-container { position: sticky; top: 0px; '.esc_html($this->nav_bg).'; z-index: 100;}
	.emtheme-top-link { display: flex; padding: 0.2rem 0; align-items: center; }
	.emtheme-logo-mobile, .emtheme-tagline { display: none; }
	.emtheme-title { margin-right: 1rem; color: '.esc_html($this->colors['emtop_font']).'; font-family: '.esc_html($this->fonts['title']).'; font-size: '.esc_html($this->fonts['title_size']).'rem; }
	.emtheme-logo { margin-right: 2rem; }
	.emtheme-logo-image-fixedheight { position: relative; top: 0.1rem; height: '.esc_html($this->fonts['title_size']).'rem; width: auto; }
	.menu, .emtheme-box { font-family: '.esc_html($this->fonts['nav']).' }
	.main { font-family: '.(isset($this->fonts['standard']) ? esc_html($this->fonts['standard']) : 'verdana').'; font-weight: '.((isset($this->fonts['standard_weight']) && $this->fonts['standard_weight'] != 'regular') ? (esc_html(str_replace('italic', '', $this->fonts['standard_weight']))) : '400').'; font-size: '.(isset($this->fonts['standard_size']) ? esc_html($this->fonts['standard_size']).'rem' : '1.6rem').'; }
	.content > p { line-height: '.(isset($this->fonts['standard_lineheight']) ? esc_html($this->fonts['standard_lineheight']) : '1.6').'; }
	.emtop { display: flex; justify-content: flex-end;  margin: 0 auto; padding: 0.3rem 0; font-size: '.esc_html($this->fonts['nav_size']).'rem; }
}';
	}

	private function general_desktop() {
		return '
@media screen and (min-width: 1024px) {
	.emtop { width: 112rem; }
	.menu { height: 100%; }
	.emtheme-mobile-icon { display: none !important; }
}';
	}

	private function page() {
		return '
@media screen and (min-width: 1024px) {
	.page_item { list-style: none; white-space: nowrap; }
	.menu a { display: block; text-decoration: none; }
	.menu > ul { display: flex; height: 100%; padding: 0; margin: 0; align-items: center; }
	.menu > ul > .page_item { padding-right: 2rem; }
	.menu > ul > .page_item:last-child { padding-right: 0; }
	.menu > ul > .page_item > a { padding-top: 0.2rem; color: '.esc_html($this->colors['nav_font']).'; border-bottom: dotted 0.2rem '.esc_html($this->colors['nav_bg']).';}
	.menu > ul > .current_page_item > a { border-bottom: dotted 0.2rem '.esc_html($this->colors['nav_font']).'50; }
	.page_item_has_children { position: relative; }
	.children { display: none; position: absolute; background-color: '.esc_html($this->colors['navsub_bg']).'; z-index: 99; padding: 0; }
	.children .page_item > a { padding: 1rem; color: '.esc_html($this->colors['navsub_font']).'; border-bottom: solid 1px '.esc_html($this->colors['navsub_bg']).'; border-top: solid 1px '.esc_html($this->colors['navsub_bg']).';}
	.children .page_item > a:hover { background-color: '.esc_html($this->colors['navsub_bg_hover']).'; border-bottom: solid 1px '.esc_html($this->colors['navsub_font']).'; border-top: solid 1px '.esc_html($this->colors['navsub_font']).';}
	.page_item_has_children > a:after { content: " \25bc"; }
	.page_item_has_children:hover > .children { display: block; }
}';
	}

	private function custom() {
		return '
@media screen and (min-width: 1024px) {
	.menu-item { list-style: none; white-space: nowrap; }
	.menu a { display: block; text-decoration: none; }
	.menu { display: flex; padding: 0; margin: 0; align-items: center; }
	.menu > .menu-item { padding-right: 2rem; }
	.menu > .menu-item:last-child { padding-right: 0; }
	.menu > .menu-item > a { padding-top: 0.2rem; color: '.esc_html($this->colors['nav_font']).'; border-bottom: dotted 0.2rem '.esc_html($this->colors['nav_bg']).';}
	.menu > .current_page_item > a { border-bottom: dotted 0.2rem '.esc_html($this->colors['nav_font']).'50; }
	.menu-item-has-children { position: relative; }
	.sub-menu { display: none; position: absolute; background-color: '.esc_html($this->colors['navsub_bg']).'; z-index: 99; padding: 0; }
	.sub-menu .menu-item > a { padding: 1rem; color: '.esc_html($this->colors['navsub_font']).'; border-bottom: solid 1px '.esc_html($this->colors['navsub_bg']).'; border-top: solid 1px '.esc_html($this->colors['navsub_bg']).';}
	.sub-menu .menu-item > a:hover { background-color: '.esc_html($this->colors['navsub_bg_hover']).'; border-bottom: solid 1px '.esc_html($this->colors['navsub_font']).'; border-top: solid 1px '.esc_html($this->colors['navsub_font']).';}
	.menu-item-has-children > a:after { content: " \25bc"; }
	.menu-item-has-children:hover > .sub-menu { display: block; }
}';
	}



}