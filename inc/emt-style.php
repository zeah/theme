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

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->colors = get_option('emtheme_color');
		$this->fonts = get_option('emtheme_font');
	}

	public function get_css() {
		$style = '<style>';

		$style .= $this->general();
		$style .= $this->general_desktop();
		$style .= $this->general_mobile();

		if (!has_nav_menu('header-menu')) {
			$style .= $this->page_desktop();
			$style .= $this->page_mobile();
		}
		else {
			$style .= $this->custom_desktop();
			$style .= $this->custom_mobile();
		}

		$style .= '</style>';

		return $style;
	}

	private function general() {
		$fonts = $this->fonts;
		$colors = $this->colors;

		$style = '/* NAVBAR CSS */
.emtheme-title { font-family: '.(isset($fonts['title']) ? $fonts['title'] : 'verdana').'; font-weight: '.((isset($fonts['title_weight']) && $fonts['title_weight'] != 'regular') ? (esc_html(str_replace('italic', '', $fonts['title_weight']))) : '400').'; font-size: '.(isset($fonts['title_size']) ? $fonts['title_size'].'rem' : '4.6rem').'; }
.emtheme-top-link, .emtheme-top-link:visited { color: '.(isset($colors['emtop_font']) ? esc_html($colors['emtop_font']) : Emtheme_style::$colors['top']['font']).'; }
.emtheme-tagline, .main { font-family: '.(isset($fonts['standard']) ? $fonts['standard'] : 'arial').'; font-weight: '.((isset($fonts['standard_weight']) && $fonts['standard_weight'] != 'regular') ? (esc_html(str_replace('italic', '', $fonts['standard_weight']))) : '400').'; font-size: '.(isset($fonts['standard_size']) ? $fonts['standard_size'].'rem' : '1.6rem').'; }
.content > p { line-height: '.(isset($fonts['standard_lineheight']) ? esc_html($fonts['standard_lineheight']) : '1.6').'; }
.menu-container { font-family: '.(isset($fonts['nav']) ? $fonts['nav'] : 'arial').'; font-weight: '.((isset($fonts['nav_weight']) && $fonts['nav_weight'] != 'regular') ? (esc_html(str_replace('italic', '', $fonts['nav_weight']))) : '400').'; font-size: '.(isset($fonts['nav_size']) ? $fonts['nav_size'].'rem' : '1.8rem').'; }
.menu ul, ul.menu { padding: 0; margin: 0 auto; }
.menu li { list-style: none;}';

		return $style;
	}

	private function general_desktop() {
		// $fonts = $this->fonts;
		$colors = $this->colors;

		$style = '
@media screen and (min-width: 1024px) {
	.emtop { background-color: '.(isset($colors['emtop_bg']) ? esc_html($colors['emtop_bg']) : Emtheme_style::$colors['top']['bg']).'; }
	.menu-container { background-color: '.(isset($colors['nav_bg']) ? esc_html($colors['nav_bg']) : Emtheme_style::$colors['nav']['bg']).'; }
	.menu { width: 112rem; margin: auto; }
	.emtheme-mobile-icon { display: none; }
}';

		return $style;
	}

	private function general_mobile() {
		// $fonts = $this->fonts;
		$colors = $this->colors;

		$style = '
@media screen and (max-width: 1023px) {
	.emtop { background-color: '.(isset($colors['nav_bg']) ? esc_html($colors['nav_bg']) : Emtheme_style::$colors['nav']['bg']).'; }
	.emtheme-mobile-icon { color: white; font-size: 4.6rem !important; }
	.menu-container { position: relative; }
	.menu { display: none; position: absolute; width: 100vw; top: 4.6rem; right: 0; z-index: 999; background-color: '.(isset($colors['nav_bg']) ? esc_html($colors['nav_bg']) : Emtheme_style::$colors['nav']['bg']).'; }
	.nav-show { display: block !important; }
	.emtheme-mob-arrow { float: right; display: inline; color: white; margin-right: 3rem; }
	.emtheme-mob-arrow:after { content: " \25bc"; }
}';

		return $style;
	}


	private function page_desktop() {
		// $fonts = $this->fonts;
		$colors = $this->colors;

		$style = '
@media screen and (min-width: 1024px) {
	.menu > ul { display: flex; }
	.page_item > a { display: block; min-width: 12rem; padding: 1rem; border-right: solid 1px rgba(255,255,255,.5); color: '.(isset($colors['nav_font']) ? esc_html($colors['nav_font']) : Emtheme_style::$colors['nav']['font']).'; text-align: center; text-decoration: none; white-space: nowrap; }
	.page_item:last-child > a { border-right: none; }
	.page_item > a:hover { background-color: '.(isset($colors['nav_bg_hover']) ? esc_html($colors['nav_bg_hover']) : Emtheme_style::$colors['nav']['hover']).'; }
	.children { display: none; position: absolute; z-index: 99; background-color: '.(isset($colors['navsub_bg']) ? esc_html($colors['navsub_bg']) : Emtheme_style::$colors['sub']['bg']).'; }
	.page_item_has_children > a:after { content: " \25bc"; font-size: 1.4rem; }
	.page_item_has_children:hover > .children { display: block; }
	.children > .page_item > a { padding: 0.5rem 1rem; color: '.(isset($colors['navsub_font']) ? esc_html($colors['navsub_font']) : Emtheme_style::$colors['sub']['font']).'; }
	.children > .page_item > a:hover { background-color: '.(isset($colors['navsub_bg_hover']) ? esc_html($colors['navsub_bg_hover']) : Emtheme_style::$colors['sub']['hover']).'; }
	.current_page_item > a { background-color: '.(isset($colors['active']) ? esc_html($colors['active']) : Emtheme_style::$colors['active']['bg']).'; }
	.current_page_item > a:hover { background-color: '.(isset($colors['active_hover']) ? esc_html($colors['active_hover']) : Emtheme_style::$colors['active']['hover']).'; }
}';

		return $style;
	}
	
	private function page_mobile() {
		// $fonts = $this->fonts;
		$colors = $this->colors;

		$style = '
@media screen and (max-width: 1023px) {
	.page_item { display: inline-block; width: 100%; margin: 2rem 0; }
	.page_item > a { min-width: 12rem; padding: 0.5rem 3rem; color: '.(isset($colors['nav_font']) ? esc_html($colors['nav_font']) : Emtheme_style::$colors['nav']['font']).'; text-decoration: none; text-align: left; white-space: nowrap; }
	.children { display: none; z-index: 99; background-color: '.(isset($colors['navsub_bg']) ? esc_html($colors['navsub_bg']) : Emtheme_style::$colors['sub']['bg']).'; }
	.children > .page_item > a { padding: 0.5rem 1rem; color: '.(isset($colors['navsub_font']) ? esc_html($colors['navsub_font']) : Emtheme_style::$colors['sub']['font']).'; }
	.children > .page_item > a:hover { background-color: '.(isset($colors['navsub_bg_hover']) ? esc_html($colors['navsub_bg_hover']) : Emtheme_style::$colors['sub']['hover']).'; }
	.current_page_item > a { background-color: '.(isset($colors['active']) ? esc_html($colors['active']) : Emtheme_style::$colors['active']['bg']).'; }
	.current_page_item > a:hover { background-color: '.(isset($colors['active_hover']) ? esc_html($colors['active_hover']) : Emtheme_style::$colors['active']['hover']).' !important; }
}';

		return $style;
	}

	private function custom_desktop() {
		// $fonts = $this->fonts;
		$colors = $this->colors;

		$style = '
@media screen and (min-width: 1024px) {
	.menu { display: flex; padding: 0; }
	.menu-item > a { display: block; min-width: 12rem; padding: 1rem; border-right: solid 1px rgba(255,255,255,.5); color: '.(isset($colors['nav_font']) ? esc_html($colors['nav_font']) : Emtheme_style::$colors['nav']['font']).'; text-decoration: none; text-align: center; white-space: nowrap; }
	.menu-item:last-child > a { border-right: none; }
	.menu-item > a:hover { background-color: '.(isset($colors['nav_bg_hover']) ? esc_html($colors['nav_bg_hover']) : Emtheme_style::$colors['nav']['hover']).'; }
	.sub-menu { display: none; position: absolute; z-index: 99; background-color: '.(isset($colors['navsub_bg']) ? esc_html($colors['navsub_bg']) : Emtheme_style::$colors['sub']['bg']).'; }
	.menu-item-has-children > a:after { content: " \25bc"; font-size: 1.4rem; }
	.menu-item-has-children:hover > .sub-menu { display: block; }
	.sub-menu > .menu-item > a { padding: 0.5rem 1rem; color: '.(isset($colors['navsub_font']) ? esc_html($colors['navsub_font']) : Emtheme_style::$colors['sub']['font']).'; }
	.sub-menu > .menu-item > a:hover { background-color: '.(isset($colors['navsub_bg_hover']) ? esc_html($colors['navsub_bg_hover']) : Emtheme_style::$colors['sub']['hover']).'; }
	.current-menu-item > a { background-color: '.(isset($colors['active']) ? esc_html($colors['active']) : Emtheme_style::$colors['active']['bg']).'; }
	.current-menu-item > a:hover { background-color: '.(isset($colors['active_hover']) ? esc_html($colors['active_hover']) : Emtheme_style::$colors['active']['hover']).' !important; }
}';

		return $style;
	}
	
	private function custom_mobile() {
		// $fonts = $this->fonts;
		$colors = $this->colors;

		$style = '
@media screen and (max-width: 1023px) {
	.menu-item > a { display: block; min-width: 12rem; padding: 1rem; border-right: solid 1px rgba(255,255,255,.5); color: '.(isset($colors['nav_font']) ? esc_html($colors['nav_font']) : Emtheme_style::$colors['nav']['font']).'; text-decoration: none; text-align: left; white-space: nowrap; }
	.menu-item-has-children > a:after { content: " \25bc"; font-size: 1.4rem; }
	.sub-menu { display: none; z-index: 99; background-color: '.(isset($colors['navsub_bg']) ? esc_html($colors['navsub_bg']) : Emtheme_style::$colors['sub']['bg']).'; }
	.sub-menu > .menu-item > a { padding: 1rem; color: '.(isset($colors['navsub_font']) ? esc_html($colors['navsub_font']) : Emtheme_style::$colors['sub']['font']).'; }
	.sub-menu > .menu-item > a:hover { background-color: '.(isset($colors['navsub_bg_hover']) ? esc_html($colors['navsub_bg_hover']) : Emtheme_style::$colors['sub']['hover']).'; }
	.current-menu-item > a { background-color: '.(isset($colors['active']) ? esc_html($colors['active']) : Emtheme_style::$colors['active']['bg']).'; }
	.current-menu-item > a:hover { background-color: '.(isset($colors['active_hover']) ? esc_html($colors['active_hover']) : Emtheme_style::$colors['active']['hover']).' !important; }
}';

		return $style;
	}



}