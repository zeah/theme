<?php 


// 
 
// $emsoc = Emtheme_footer::get_instance();

// echo $emsoc->get_footer();

// final class Emtheme_footer {
// 	private static $instance = null;
// 	private $footer = [];
// 	private $cust = false;

// 	public static function get_instance() {

// 		if (self::$instance === null)
// 			self::$instance = new self();

// 		return self::$instance;
// 	}

// 	private function __construct() {
// 		$this->footer = get_option('emtheme_footer');
// 		$this->cust = is_customize_preview();
// 	}

// 	public function get_footer() {
// 		$html = '';
// 		if ($this->get('social_active') || $this->get('contact_active') || $this->get('aboutus_active') || $this->cust) {
// 	 		$html = '<div class="em-footer"><div class="em-inner-footer">';
	
// 			if ($this->get('social_active') || $this->cust)		
// 				$html .= $this->get_footer_social();

// 			if ($this->get('contact_active') || $this->cust)
// 				$html .= $this->get_footer_contact();

// 			if ($this->get('aboutus_active') || $this->cust)
// 				$html .= $this->get_footer_about();

// 			$html .= '</div></div>';
// 		}

// 		return $html;
// 	}

// 	private function get_footer_social() {
// 		$html = '';
// 		$twitter = $this->get('twitter');
// 		$facebook = $this->get('facebook');
// 		$google = $this->get('google');
// 		$youtube = $this->get('youtube');

// 		if ($twitter || $facebook || $google || $youtube) {
// 			$html = '<div class="em-socialmedia-container"><ul class="em-footer-ul">';

// 			if ($twitter)
// 				$html .= '<li class="em-footer-listitem"><a class="em-footer-link" href="'.esc_url($twitter).'">Twitter</a></li>';

// 			if ($facebook)
// 				$html .= '<li class="em-footer-listitem"><a class="em-footer-link" href="'.esc_url($facebook).'">Facebook</a></li>';

// 			if ($google)
// 				$html .= '<li class="em-footer-listitem"><a class="em-footer-link" href="'.esc_url($google).'">Google+</a></li>';

// 			if ($youtube)
// 				$html .= '<li class="em-footer-listitem"><a class="em-footer-link" href="'.esc_url($youtube).'">Youtube</a></li>';

// 			$html .= '</ul></div>';
// 		}

// 		return $html;
// 	}

// 	private function get_footer_contact() {
// 		$html = '';
// 		$email = $this->get('email');
// 		$avdeling = $this->get('avdeling');
// 		$selskap = $this->get('selskap');
// 		$poststed = $this->get('poststed');
// 		$postnr = $this->get('postnr');
// 		$veiadr = $this->get('veiadr');
// 		$land = $this->get('land');

// 		if ($email || $avdeling || $selskap || $poststed || $postnr || $veiadr || $land) {
// 			$html = '<div class="em-contact-container"><ul class="em-footer-ul">';

// 			if ($email)
// 				$html .= '<li class="em-footer-listitem em-footer-epost">'.esc_html($email).'</li>';

// 			if ($avdeling)
// 				$html .= '<li class="em-footer-listitem">'.esc_html($avdeling).'</li>';

// 			if ($selskap)
// 				$html .= '<li class="em-footer-listitem">'.esc_html($selskap).'</li>';

// 			if ($poststed)
// 				$html .= '<li class="em-footer-listitem em-footer-post">'.esc_html($poststed).'</li>';

// 			if ($postnr)
// 				$html .= '<li class="em-footer-listitem em-footer-post">'.esc_html($postnr).'</li>';

// 			if ($veiadr)
// 				$html .= '<li class="em-footer-listitem">'.esc_html($veiadr).'</li>';

// 			if ($land)
// 				$html .= '<li class="em-footer-listitem">'.esc_html($land).'</li>';

// 			$html .= '</ul></div>';
// 		}

// 		return $html;
// 	}

// 	private function get_footer_about() {
// 		$aboutus = $this->get('aboutus');
// 		if ($aboutus)
// 			return '<div class="em-aboutus-container">'.preg_replace('/\[p\]/', '<p>', esc_html($aboutus)).'</div>';

// 		return '';
// 	}

// 	private function get($value) {
// 		if ($this->cust) {
// 			// echo 'hi '.$this->footer[$value];
// 			return isset($this->footer[$value]) ? ($this->footer[$value] ? $this->footer[$value] : ' ') : ' ';
// 		}

// 		if (!isset($this->footer[$value]))
// 			return false;

// 		return $this->footer[$value];
// 	}

// 	private function getLi($text, $href = null) {
// 		$c = $this->cust;
// 		if ($href)
// 			return '<li class="em-footer-listitem'.($c ? ' em-c-footer' : '').'"><a class="em-footer-link" href="'.esc_url($href).'">'.esc_html($text).'</a></li>';
			
// 		return '<li class="em-footer-listitem'.($c ? ' em-c-footer' : '').'">'.esc_html($text).'</li>';
// 	}
// }

// $test = Emtheme_footer_two::get_instance();
// // echo $test;

// final class Emtheme_footer_two {
// 	// singleton
// 	private static $instance = null;

// 	private $customizer;
// 	private $footer;

// 	private $html = '';

// 	public static function get_instance() {

// 		if (self::$instance === null)
// 			self::$instance = new self();

// 		return self::$instance;
// 	}

// 	private function __construct() {
// 		$this->customizer = is_customize_preview();
// 		$this->footer = get_option('emtheme_footer');

// 		$this->init();
// 	}

// 	private function init() {
// 		$f = $this->footer;

// 		if ($this->check('social_active') || $this->check('contact_active') || $this->check('aboutus_active')) {
// 			$this->add('<div class="em-footer"><div class="em-footer-inner">');

// 			if ($this->check('social_active')) {
// 				$this->add('<div class="em-social-container"><ul class="em-footer-ul>');

// 				$this->add($this->getLink('Twitter', $this->footer['twitter']));
// 				$this->add($this->getLink('Facebook', $this->footer['facebook']));
// 				$this->add($this->getLink('Google+', $this->footer['google']));
// 				$this->add($this->getLink('Youtube', $this->footer['youtube']));

// 				$this->add('</ul></div>');
// 			}

// 			if ($this->check('contact_active')) {
// 				$this->add('<div class="em-contact-container"><ul class="em-footer-ul>');

// 				$this->add($this->getLi('epost'));
// 				$this->add($this->getLi('avdeling'));
// 				$this->add($this->getLi('selskap'));
// 				$this->add($this->getLi('poststed'));
// 				$this->add($this->getLi('postnr'));
// 				$this->add($this->getLi('veiadr'));
// 				$this->add($this->getLi('land'));

// 				$this->add('</ul></div>');
// 			}

// 			if ($this->check('about_us')) {
// 				$this->add('<div class="em-aboutus-container">');

// 				$this->add(str_replace('[/p]', '</p>', str_replace('[p]', '<p>', esc_html($this->footer['aboutus']))));

// 				$this->add('</div>');
// 			}

// 			$this->add('</div></div>');
// 		}
// 	}

// 	private function check($value) {
// 		if ($this->customizer)
// 			return true;

// 		if (isset($this->footer[$value]) && $this->footer[$value] != '')
// 			return true;

// 		return false;
// 	}

// 	private function add($value) {
// 		$this->html .= $value;
// 	}

// 	private function getLi($value) {
// 		// if (isset($this->footer[$value]))
// 		if ($this->check($value))
// 			return '<li class="em-footer-listitem">'.esc_html($this->footer[$value]).'</li>';
// 	}

// 	private function getLink($value, $link) {
// 		// if (isset($this->footer[$link]))
// 		if ($this->check($value))
// 			return '<li class="em-footer-listitem"><a class="em-footer-link" href="'.esc_url($this->footer[$link]).'">'.esc_html($value).'</a></li>';
// 	}

// 	public function __toString() {
// 		return $this->html;
// 	}
// } 

wp_footer(); ?>
</body></html>