<?php 

$emsoc = Emtheme_footer::get_instance();

echo $emsoc->get_footer();

final class Emtheme_footer {
	private static $instance = null;

	public static function get_instance($active = true) {

		if (self::$instance === null)
			self::$instance = new self();

		return self::$instance;
	}

	private function __construct($active = true) {
		if (! $active)
			return;

	}

	public function get_footer() {
		$html = '';
		$social = get_option('emtheme_footer_social_active');
		$contact = get_option('emtheme_footer_contact_active');
		$aboutus = get_option('emtheme_footer_aboutus_active');


		if ($social || $contact || $aboutus) {
	 		$html = '<div class="em-footer"><div class="em-inner-footer">';
	
			if ($social)		
				$html .= $this->get_footer_social();

			if ($contact)
				$html .= $this->get_footer_contact();

			if ($aboutus)
				$html .= $this->get_footer_about();

			$html .= '</div></div>';
		}

		return $html;
	}

	private function get_footer_social() {
		$html = '';
		$twitter = get_option('emtheme_footer_twitter');
		$facebook = get_option('emtheme_footer_facebook');
		$google = get_option('emtheme_footer_google');
		$youtube = get_option('emtheme_footer_youtube');

		if ($twitter || $facebook || $google || $youtube) {
			$html .= '<div class="em-socialmedia-container"><ul class="em-footer-ul">';

			if ($twitter)
				$html .= '<li class="em-footer-listitem"><a class="em-footer-link" href="'.esc_url($twitter).'">Twitter</a></li>';

			if ($facebook)
				$html .= '<li class="em-footer-listitem"><a class="em-footer-link" href="'.esc_url($facebook).'">Facebook</a></li>';

			if ($google)
				$html .= '<li class="em-footer-listitem"><a class="em-footer-link" href="'.esc_url($google).'">Google+</a></li>';

			if ($youtube)
				$html .= '<li class="em-footer-listitem"><a class="em-footer-link" href="'.esc_url($youtube).'">Youtube</a></li>';

			$html .= '</ul></div>';
		}

		return $html;
	}

	private function get_footer_contact() {
		$html = '';
		$email = get_option('emtheme_footer_email');
		$avdeling = get_option('emtheme_footer_avdeling');
		$selskap = get_option('emtheme_footer_selskap');
		$poststed = get_option('emtheme_footer_poststed');
		$postnr = get_option('emtheme_footer_postnr');
		$veiadr = get_option('emtheme_footer_veiadr');
		$land = get_option('emtheme_footer_land');


		if ($email || $avdeling || $selskap || $poststed || $postnr || $veiadr || $land) {
			$html .= '<div class="em-contact-container"><ul class="em-footer-ul">';

			if ($email)
				$html .= '<li class="em-footer-listitem em-footer-epost">'.esc_html($email).'</li>';

			if ($avdeling)
				$html .= '<li class="em-footer-listitem">'.esc_html($avdeling).'</li>';

			if ($selskap)
				$html .= '<li class="em-footer-listitem">'.esc_html($selskap).'</li>';

			if ($poststed)
				$html .= '<li class="em-footer-listitem em-footer-post">'.esc_html($poststed).'</li>';

			if ($postnr)
				$html .= '<li class="em-footer-listitem em-footer-post">'.esc_html($postnr).'</li>';

			if ($veiadr)
				$html .= '<li class="em-footer-listitem">'.esc_html($veiadr).'</li>';

			if ($land)
				$html .= '<li class="em-footer-listitem">'.esc_html($land).'</li>';

			$html .= '</ul></div>';
		}

		return $html;
	}

	private function get_footer_about() {
		$aboutus = get_option('emtheme_footer_about');
		if ($aboutus)
			return '<div class="em-aboutus-container">'.preg_replace('/\[p\]/', '<p>', esc_html($aboutus)).'</div>';

		return '';
	}
}

wp_footer(); ?>
</body></html>