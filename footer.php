<?php 

$emsoc = Emsoc::get_instance();

echo $emsoc->get_footer();

final class Emsoc {
	private static $instance = null;
	private $data = null;

	public static function get_instance($active = true) {

		if (self::$instance === null)
			self::$instance = new self();

		return self::$instance;
	}

	private function __construct($active = true) {
		if (! $active)
			return;

		$this->data = get_option('em_contact_data');

	}

	public function get_footer() {
		$html = '<div class="em-footer"><div class="em-inner-footer">';
		
		if (get_option('em_social_active'))
			$html .= $this->get_footer_social();

		if (get_option('em_contact_active'))
			$html .= $this->get_footer_contact();

		if (get_option('em_omoss_active'))
			$html .= $this->get_footer_about();

		$html .= '</div></div>';
		return $html;
	}

	private function get_footer_social() {
		if (! isset($this->data['social']))
			return;


		$html = '<div class="em-socialmedia-container"><ul class="em-footer-ul">';

		$soc = $this->data['social'];
		foreach ($soc as $key => $s)
			$html .= '<li class="em-footer-listitem"><a class="em-footer-link" href="'.esc_url($s).'">'.esc_html($key).'</a></li>';

		$html .= '</ul></div>';
		return $html;
	}

	private function get_footer_contact() {
		if (! isset($this->data['contact']))
			return;

		$html = '<div class="em-contact-container"><ul class="em-footer-ul">';

		$con = $this->data['contact'];
		foreach ($con as $key => $c)
			$html .= '<li class="em-footer-listitem'.(($key == 'epost') ? ' em-footer-epost' : '').(($key == 'poststed' || $key == 'postnr') ? ' em-footer-post': '').'">'.esc_html($c).'</li>';

		$html .= '</ul></div>';
		return $html;
	}

	private function get_footer_about() {
		if (! isset($this->data['omoss']))
			return;

		return '<div class="em-aboutus-container">'.preg_replace('/\[p\]/', '<p>', esc_html($this->data['omoss'])).'</div>';
	}
}

wp_footer(); ?>
</body></html>