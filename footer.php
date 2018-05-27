<?php 


if (is_customize_preview()) {
	echo Emtheme_footer_customize::get_instance();
}
else {
	$footer = Emtheme_footer::get_instance();
	$footer->init();
	echo $footer;
}


class Emtheme_footer {
	private static $instance = null;
	protected $footer;
	protected $html = '';

	protected $social = ['twitter', 'facebook', 'google', 'youtube'];
	protected $contact = ['email', 'avdeling', 'selskap', 'postnr', 'poststed', 'veiadr', 'land'];

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	protected function __construct() {
		$this->footer = get_option('emtheme_footer');

		// $this->init();
	}

	public function __toString() {
		return $this->html;
	}

	public function init() {
		if ($this->check('social_active') || $this->check('contact_active') || $this->check('aboutus_active')) {
			$this->html .= '<div class="em-footer"><div class="em-inner-footer">';

			if ($this->check('social_active')) {
				$this->html .= '<div class="em-socialmedia-container"><ul class="em-footer-ul">';

				foreach($this->social as $item)
					$this->html .= $this->getLink($item);

				$this->html .= '</ul>';

				if (isset($this->footer['custom_links'])) $this->html .= wp_kses_post($this->footer['custom_links']);

				$this->html .= '</div>';
			}

			if ($this->check('contact_active')) {
				$this->html .= '<div class="em-contact-container"><ul class="em-footer-ul">';

				foreach($this->contact as $item)
					$this->html .= $this->getLi($item);

				$this->html .= '</ul></div>';
			}

			if  ($this->check('aboutus_active') && isset($this->footer['aboutus'])) {
				$about = wp_kses_post($this->footer['aboutus']);

				// $about = str_replace('[p]', '<p>', $about);
				// $about = str_replace('[/p]', '</p>', $about);

				$this->html .= '<div class="em-aboutus-container">'.$about.'</div>';
			}
		
			$this->html .= '</div></div>';
		}
	}

	private function check($value) {
		if (! isset($this->footer[$value]) || $this->footer[$value] == '')
			return false;

		return true;
	}

	private function getLi($value) {
		if ($this->check($value))
			return '<li class="em-footer-listitem">'.esc_html($this->footer[$value]).'</li>';

		return '';
	}

	private function getLink($value) {
		if ($this->check($value))
			return '<li class="em-footer-listitem"><a class="em-footer-link" href="'.esc_url($this->footer[$value]).'">'.esc_html($value).'</a></li>';

		return '';
	}

}

class Emtheme_footer_customize extends Emtheme_footer {
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null)
			self::$instance = new self();

		return self::$instance;
	}
	
	protected function __construct() {
		parent::__construct();
		$this->init();
	}

	public function init() {
		$this->html .= '<div class="em-footer"><div class="em-inner-footer">';

		$this->html .= '<div class="em-socialmedia-container"><ul class="em-footer-ul">';

		foreach($this->social as $item)
			$this->html .= $this->getLink($item);

		$this->html .= '</ul></div>';

		$this->html .= '<div class="em-contact-container"><ul class="em-footer-ul">';

		foreach($this->contact as $item)
			$this->html .= $this->getLi($item);

		$this->html .= '</ul></div>';


		$v = isset($this->footer['aboutus']) ? $this->footer['aboutus'] : '';
		
		$this->html .= '<div class="em-aboutus-container">'.wp_kses_post($v).'</div>';
	
		$this->html .= '</div></div>';
	}

	private function getLi($value) {
		$v = isset($this->footer[$value]) ? $this->footer[$value] : '';

		return '<li class="em-footer-listitem em-footer-'.$value.'">'.esc_html($v).'</li>';
	}

	private function getLink($value) {
		$v = isset($this->footer[$value]) ? $this->footer[$value] : '';

		return '<li class="em-footer-listitem em-footer-'.$value.'"><a class="em-footer-link" href="'.esc_url($v).'">'.esc_html($value).'</a></li>';

	}
	public function __toString() {
		return $this->html;
	}
}

// google scripts
if (! get_option('em_google_disable')) {

	$nr = get_option('em_google_tagmanager');
	if (isset($nr) && $nr != '') {
		$nr = sanitize_text_field($nr);
		echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='.$nr.'" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';
		echo '<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({"gtm.start":new Date().getTime(),event:"gtm.js"});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!="dataLayer"?"&l="+l:"";j.async=true;j.src="https://www.googletagmanager.com/gtm.js?id="+i+dl;f.parentNode.insertBefore(j,f);})(window,document,"script", "dataLayer", "'.$nr.'");</script>';
	}


	$nr = get_option('em_google_analytics');
	if (isset($nr) && $nr != '') {
		$google = '<script async src="https://www.googletagmanager.com/gtag/js?id='.sanitize_text_field($nr).'"></script>';
		$google .= '<script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag("js", new Date()); gtag("config", "'.sanitize_text_field($nr).'");</script>';
		echo $google;
	}
}



wp_footer(); 
?>
</body></html>