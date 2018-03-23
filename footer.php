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

		if (self::$instance === null)
			self::$instance = new self();

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

				$this->html .= '</ul></div>';
			}

			if ($this->check('contact_active')) {
				$this->html .= '<div class="em-contact-container"><ul class="em-footer-ul">';

				foreach($this->contact as $item)
					$this->html .= $this->getLi($item);

				$this->html .= '</ul></div>';
			}


			if  ($this->check('aboutus_active') && isset($this->footer['aboutus'])) 
				$this->html .= '<div class="em-aboutus-container">'.esc_html($this->footer['aboutus']).'</div>';
		
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
		$this->html .= '<div class="em-aboutus-container">'.esc_html($v).'</div>';
	
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


wp_footer(); 
?>
</body></html>